<?php
include('config.php');

$msg = "";

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['transferBtn'])) {
        $transferAmount = $_POST['transferAmount'];
        $recipientUserId = $_POST['recipientUserId']; // New input field for recipient's user_id

        if (!empty($transferAmount) && !empty($recipientUserId)) {
            transferMoney($transferAmount, $recipientUserId);
            // Set success message and redirect to transfer.php
            $_SESSION['msg'] = "Transfer successful!";
            header("Location: transfer.php");
            exit;
        } else {
            // Set error message and redirect back to transfer.php
            $_SESSION['msg'] = "Please enter both amount and recipient's User ID.";
            header("Location: transfer.php");
            exit;
        }
    }
}

function transferMoney($amount, $recipientUserId) {
    global $con;

    $currentBalance = getWalletBalance();

    // Check if the recipient's user ID exists
    $recipientQuery = "SELECT * FROM wallet WHERE user_id = '$recipientUserId'";
    $recipientResult = mysqli_query($con, $recipientQuery);

    if (!$recipientResult || mysqli_num_rows($recipientResult) == 0) {
        // Set error message and redirect back to transfer.php
        $_SESSION['msg'] = "Recipient user ID not found!";
        header("Location: transfer.php");
        exit;
    }

    $recipientRow = mysqli_fetch_assoc($recipientResult);
    $recipientBalance = $recipientRow['balance'];

    if ($amount > $currentBalance) {
        // Set error message and redirect back to transfer.php
        $_SESSION['msg'] = "Insufficient balance!";
        header("Location: transfer.php");
        exit;
    }

    // Calculate new balances
    $newBalanceSender = $currentBalance - $amount;
    $newBalanceReceiver = $recipientBalance + $amount;

    // Update sender's balance
    updateWalletBalance($newBalanceSender);

    // Update recipient's balance
    $updateRecipientQuery = "UPDATE wallet SET balance = $newBalanceReceiver WHERE user_id = '$recipientUserId'";
    $updateRecipientResult = mysqli_query($con, $updateRecipientQuery);

    if (!$updateRecipientResult) {
        die("Update Recipient Balance Failed: " . mysqli_error($con));
    }

    // Insert expense for sender (category 'transfer' as withdrawal)
    insertExpense($amount);

    // Insert income for recipient (category 'transfer' as deposit)
    insertIncome('transfer', $amount, $recipientUserId);

    // Redirect back to transfer.php after successful transfer
    $_SESSION['msg'] = "Transfer successful!";
    header("Location: transfer.php");
    exit;
}

function insertExpense($amount) {
    global $con;

    $checkQuery = "SELECT id FROM category WHERE name = 'withdraw'";
    $checkResult = mysqli_query($con, $checkQuery);

    if (mysqli_num_rows($checkResult) == 0) {
        $insertCategoryQuery = "INSERT INTO category (name) VALUES ('transfer')";
        $insertCategoryResult = mysqli_query($con, $insertCategoryQuery);

        if (!$insertCategoryResult) {
            die("Insert Category Failed: " . mysqli_error($con));
        }
    }

    $categoryQuery = "SELECT id FROM category WHERE name = 'transfer'";
    $categoryResult = mysqli_query($con, $categoryQuery);
    $categoryRow = mysqli_fetch_assoc($categoryResult);
    $categoryId = $categoryRow['id'];

    $insertExpenseQuery = "INSERT INTO expense (category_id, item, price, expense_date, added_on, added_by)
                         VALUES ($categoryId, 'transfer', $amount, NOW(), NOW(), '".$_SESSION['UID']."')";
    $insertExpenseResult = mysqli_query($con, $insertExpenseQuery);

    if (!$insertExpenseResult) {
        die("Insert Expense Failed: " . mysqli_error($con));
    }
}


function insertIncome($categoryName, $amount, $userId) {
    global $con;

    // Check if 'transfer' category exists, if not, create it
    $checkQuery = "SELECT id FROM income_categories WHERE name = 'transfer'";
    $checkResult = mysqli_query($con, $checkQuery);

    if (mysqli_num_rows($checkResult) == 0) {
        $insertCategoryQuery = "INSERT INTO income_categories (name) VALUES ('transfer')";
        $insertCategoryResult = mysqli_query($con, $insertCategoryQuery);

        if (!$insertCategoryResult) {
            die("Insert Category Failed: " . mysqli_error($con));
        }
    }

    // Get category ID for 'transfer'
    $categoryQuery = "SELECT id FROM income_categories WHERE name = 'transfer'";
    $categoryResult = mysqli_query($con, $categoryQuery);
    $categoryRow = mysqli_fetch_assoc($categoryResult);
    $categoryId = $categoryRow['id'];

    // Insert income record
    $insertIncomeQuery = "INSERT INTO income (category_id, description, amount, income_date, created_at, user_id)
                         VALUES ($categoryId, '$categoryName', $amount, NOW(), NOW(), '$userId')";
    $insertIncomeResult = mysqli_query($con, $insertIncomeQuery);

    if (!$insertIncomeResult) {
        die("Insert Income Failed: " . mysqli_error($con));
    }
}
function getWalletBalance() {
    global $con;
    $query = "SELECT balance FROM wallet WHERE user_id = '".$_SESSION['UID']."'";
    $result = mysqli_query($con, $query);

    if (!$result) {
        die("Query Failed: " . mysqli_error($con));
    }

    $row = mysqli_fetch_assoc($result);
    return $row['balance'] ?? 0; // Return 0 if null
}
function updateWalletBalance($newBalance) {
    global $con;

    // Check current balance before updating
    $currentBalance = getWalletBalance();
    if ($newBalance == $currentBalance) {
        // Balance hasn't changed, no need to update or log
        return;
    }

    // Calculate change amount
    $changeAmount = $newBalance - $currentBalance;

    // Update wallet balance
    $updateQuery = "UPDATE wallet SET balance = $newBalance WHERE user_id = '".$_SESSION['UID']."'";
    $updateResult = mysqli_query($con, $updateQuery);

    if (!$updateResult) {
        die("Update Failed: " . mysqli_error($con));
    }

    // Log the new balance and change amount into wallet_history
    $insertQuery = "INSERT INTO wallet_history (user_id, balance, change_amount, date_updated)
                    VALUES ('".$_SESSION['UID']."', $newBalance, $changeAmount, NOW())";
    $insertResult = mysqli_query($con, $insertQuery);

    if (!$insertResult) {
        die("Insert Failed: " . mysqli_error($con));
    }
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Transfer Money</title>
    <link href="vendor/bootstrap-4.1/bootstrap.min.css" rel="stylesheet" media="all">
    <link href="css/theme.css" rel="stylesheet" media="all">
    <style>
        .custom-select {
            width: 100%;
            height: 45px; 
            font-size: 12px; 
        }
        .center-button-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 20px;
        }
        .full-width-button {
            width: 100%;
        }
    </style>
</head>
<body class="animsition">
    <div class="page-wrapper">
        <div class="page-content--bge5">
            <div class="container">
                <div class="login-wrap">
                    <div class="login-content">
                        <div class="login-form">
                            <?php if (isset($_SESSION['msg'])): ?>
                                <div class="alert alert-info"><?php echo htmlspecialchars($_SESSION['msg']); ?></div>
                                <?php unset($_SESSION['msg']); ?>
                            <?php endif; ?>
                            <form action="" method="post" id="transferForm">
                                <div class="form-group">
                                    <label>Recipient's User ID:</label>
                                    <input type="text" class="form-control" name="recipientUserId" required>
                                </div>
                                <div class="form-group">
                                    <label>Enter Amount to Transfer:</label>
                                    <input type="number" class="form-control" name="transferAmount" required>
                                </div>
                                <button type="submit" class="btn btn-success" name="transferBtn">Transfer</button>
                            </form>
                            <div class="center-button-container">
                                <a href="wallet.php" class="btn btn-primary">Back</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="vendor/jquery-3.2.1.min.js"></script>
    <script src="vendor/bootstrap-4.1/popper.min.js"></script>
    <script src="vendor/bootstrap-4.1/bootstrap.min.js"></script>
    <script src="js/main.js"></script>
