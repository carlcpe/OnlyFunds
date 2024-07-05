<?php
// Include necessary files (config.php and functions.php)
include('config.php');
include('functions.php');

$msg = "";

// Process form submission to add new income
if (isset($_POST['addIncome'])) {
    $category = get_safe_value($_POST['category']);
    $newCategory = get_safe_value($_POST['newCategory']);
    $description = get_safe_value($_POST['description']);
    $amount = get_safe_value($_POST['amount']);
    $incomeDate = get_safe_value($_POST['incomeDate']);

    // Insert new category if it's a new one
    if ($category == 'new' && !empty($newCategory)) {
        $insertCategoryQuery = "INSERT INTO income_categories (name) VALUES ('$newCategory')";
        if ($con->query($insertCategoryQuery) === TRUE) {
            $category = $con->insert_id; // Get the last inserted category ID
        } else {
            $msg = "Error: " . $con->error;
        }
    }

    // Insert income record into the database
    if (!empty($category) && !empty($description) && !empty($amount) && !empty($incomeDate)) {
        $insertIncomeQuery = "INSERT INTO income (category_id, description, amount, income_date,user_id)
                              VALUES ('$category', '$description', '$amount', '$incomeDate','".$_SESSION['UID']."')";
        if ($con->query($insertIncomeQuery) === TRUE) {
            $msg = "Income added successfully!";
        } else {
            $msg = "Error: " . $con->error;
        }
    } else {
        $msg = "Please fill all fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Add Income</title>
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
    </style>
</head>
<body class="animsition">
    <div class="page-wrapper">
        <div class="page-content--bge5">
            <div class="container">
                <div class="login-wrap">
                    <div class="login-content">
                        <div class="login-form">
                            <form action="" method="post" id="incomeForm">
                                <div class="form-group">
                                    <label>Category</label>
                                    <select class="au-input au-input--full custom-select" name="category" id="categorySelect" required>
                                        <option value="">Select Category</option>
                                        <?php
                                        // Fetch income categories for dropdown
                                        $incomeCategories = mysqli_query($con, "SELECT * FROM income_categories");
                                        while ($cat = mysqli_fetch_assoc($incomeCategories)) {
                                            echo '<option value="' . $cat['id'] . '">' . $cat['name'] . '</option>';
                                        }
                                        ?>
                                        <option value="new">Add New Category</option>
                                    </select>
                                </div>
                                <div class="form-group" id="newCategoryDiv" style="display:none;">
                                    <label>New Category</label>
                                    <input class="au-input au-input--full" type="text" name="newCategory" placeholder="New Category">
                                </div>
                                <div class="form-group">
                                    <label>Description</label>
                                    <input class="au-input au-input--full" type="text" name="description" placeholder="Description" required>
                                </div>
                                <div class="form-group">
                                    <label>Amount</label>
                                    <input class="au-input au-input--full" type="number" name="amount" placeholder="Amount" required>
                                </div>
                                <div class="form-group">
                                    <label>Income Date</label>
                                    <input class="au-input au-input--full" type="date" name="incomeDate" required>
                                </div>
                                <button class="au-btn au-btn--block au-btn--green m-b-20 " type="submit" name="addIncome">Add Income</button>
                            </form>
                            <div id="msg"><?php echo $msg; ?></div>
                            <div class="center-button-container">
                                <a href="income.php" class="au-btn au-btn--blue">Back to Dashboard</a>
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
    <script>
        document.getElementById('categorySelect').addEventListener('change', function() {
            var newCategoryDiv = document.getElementById('newCategoryDiv');
            if (this.value === 'new') {
                newCategoryDiv.style.display = 'block';
            } else {
                newCategoryDiv.style.display = 'none';
            }
        });
    </script>
</body>
</html>
