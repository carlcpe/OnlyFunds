<?php

include('config.php');

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

    $currentBalance = getWalletBalance();
    $changeAmount = $newBalance - $currentBalance;

    $updateQuery = "UPDATE wallet SET balance = $newBalance WHERE user_id = '".$_SESSION['UID']."'";
    $updateResult = mysqli_query($con, $updateQuery);

    if (!$updateResult) {
        die("Update Failed: " . mysqli_error($con));
    }

    $insertQuery = "INSERT INTO wallet_history (user_id, balance, change_amount, date_updated)
                    VALUES ('".$_SESSION['UID']."', $newBalance, $changeAmount, NOW())";
    $insertResult = mysqli_query($con, $insertQuery);

    if (!$insertResult) {
        die("Insert Failed: " . mysqli_error($con));
    }
}

$msg = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['withdrawBtn'])) {
        $withdrawAmount = $_POST['withdrawAmount'];
        if (!empty($withdrawAmount)) {
            withdrawFromWallet($withdrawAmount);
            // Set success message and redirect to dashboard.php
            $_SESSION['msg'] = "Withdraw successful!";
            header("Location: withdraw.php");
            exit;
        } else {
            // Set error message and redirect back to withdraw.php
            $_SESSION['msg'] = "Please enter an amount to withdraw.";
            header("Location: withdraw.php");
            exit;
        }
    }
}

function withdrawFromWallet($amount) {
    global $con;

    $currentBalance = getWalletBalance();
    if ($amount > $currentBalance) {
        // Set error message and redirect back to withdraw.php
        $_SESSION['msg'] = "Insufficient balance!";
        header("Location: withdraw.php");
        exit;
    }

    $newBalance = $currentBalance - $amount;
    updateWalletBalance($newBalance);

    $checkQuery = "SELECT id FROM category WHERE name = 'withdraw'";
    $checkResult = mysqli_query($con, $checkQuery);

    if (mysqli_num_rows($checkResult) == 0) {
        $insertCategoryQuery = "INSERT INTO category (name) VALUES ('withdraw')";
        $insertCategoryResult = mysqli_query($con, $insertCategoryQuery);

        if (!$insertCategoryResult) {
            die("Insert Category Failed: " . mysqli_error($con));
        }
    }

    $categoryQuery = "SELECT id FROM category WHERE name = 'withdraw'";
    $categoryResult = mysqli_query($con, $categoryQuery);
    $categoryRow = mysqli_fetch_assoc($categoryResult);
    $categoryId = $categoryRow['id'];

    $insertExpenseQuery = "INSERT INTO expense (category_id, item, price, expense_date, added_on, added_by)
                         VALUES ($categoryId, 'withdrawal', $amount, NOW(), NOW(), '".$_SESSION['UID']."')";
    $insertExpenseResult = mysqli_query($con, $insertExpenseQuery);

    if (!$insertExpenseResult) {
        die("Insert Expense Failed: " . mysqli_error($con));
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Withdraw from Wallet</title>
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
                            <form action="" method="post" id="withdrawForm">
                                <div class="form-group">
                                    <label>Enter Amount to Withdraw:</label>
                                    <input type="number" class="form-control" name="withdrawAmount" required>
                                </div>
                                <button type="submit" class="btn btn-danger" name="withdrawBtn">Withdraw</button>
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
</body>
</html>

<?php include('footer.php'); ?>
