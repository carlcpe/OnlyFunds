<?php
include('config.php'); // Include your database connection script

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['depositBtn'])) {
        $depositAmount = $_POST['depositAmount'];
        if (!empty($depositAmount)) {
            depositToWallet($depositAmount);
            $_SESSION['msg'] = "Deposit successful!";
            header("Location: wallet.php");
            exit;
        } else {
            $_SESSION['msg'] = "Please enter an amount to deposit.";
            header("Location: deposit.php");
            exit;
        }
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

function depositToWallet($amount) {
    global $con;

    $currentBalance = getWalletBalance();
    $newBalance = $currentBalance + $amount;
    updateWalletBalance($newBalance);

    $checkQuery = "SELECT id FROM income_categories WHERE name = 'deposit'";
    $checkResult = mysqli_query($con, $checkQuery);

    if (mysqli_num_rows($checkResult) == 0) {
        $insertCategoryQuery = "INSERT INTO income_categories (name) VALUES ('deposit')";
        $insertCategoryResult = mysqli_query($con, $insertCategoryQuery);

        if (!$insertCategoryResult) {
            die("Insert Category Failed: " . mysqli_error($con));
        }
    }

    $categoryQuery = "SELECT id FROM income_categories WHERE name = 'deposit'";
    $categoryResult = mysqli_query($con, $categoryQuery);
    $categoryRow = mysqli_fetch_assoc($categoryResult);
    $categoryId = $categoryRow['id'];

    $insertIncomeQuery = "INSERT INTO income (category_id, amount, income_date, created_at, user_id)
                         VALUES ($categoryId, $amount, NOW(), NOW(), '".$_SESSION['UID']."')";
    $insertIncomeResult = mysqli_query($con, $insertIncomeQuery);

    if (!$insertIncomeResult) {
        die("Insert Income Failed: " . mysqli_error($con));
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Deposit to Wallet</title>
    <link href="vendor/bootstrap-4.1/bootstrap.min.css" rel="stylesheet" media="all">
    <link href="css/theme.css" rel="stylesheet" media="all">
    <style>
        .custom-select {
            width: 100%; /* Adjust the width as needed */
            height: 45px; /* Adjust the height as needed */
            font-size: 12px; /* Adjust the font size as needed */
        }
        .center-button-container {
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .au-input--full {
            width: 100%;
            height: 45px;
            border: 1px solid #e5e5e5;
            padding: 0 15px;
            font-size: 12px;
            color: #666;
            border-radius: 5px;
            background-color: #fff;
            margin-bottom: 20px;
        }
        .au-btn {
            font-size: 12px;
            padding: 10px 20px;
            border-radius: 5px;
            text-transform: uppercase;
            cursor: pointer;
            color: #fff;
            background-color: #333;
            border: none;
            width: 100%;
        }
        .au-btn:hover {
            background-color: #555;
        }
        .m-b-20 {
            margin-bottom: 20px;
        }
        .au-btn--block {
            display: block;
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
                            <div class="card">
                                <div class="card-header">
                                    <strong>Deposit to Wallet</strong>
                                </div>
                                <div class="card-body">
                                    <?php
                                    if (isset($_SESSION['msg'])) {
                                        echo '<div class="alert alert-info">' . $_SESSION['msg'] . '</div>';
                                        unset($_SESSION['msg']);
                                    }
                                    ?>
                                    <form action="deposit.php" method="post">
                                        <div class="form-group">
                                            <label for="depositAmount">Enter Amount to Deposit:</label>
                                            <input type="number" class="au-input au-input--full" id="depositAmount" name="depositAmount" required>
                                        </div>
                                        <button type="submit" class="au-btn au-btn--block au-btn--green m-b-20" name="depositBtn">Deposit</button>
                                    </form>
                                    <div class="center-button-container">
                                        <a href="wallet.php" class="au-btn au-btn--blue">Back to Wallet</a>
                                    </div>
                                </div>
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
