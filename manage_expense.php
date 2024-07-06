<?php
include('config.php');
include('functions.php');

$msg = "";
if (isset($_POST['addExpense'])) {
    $category = get_safe_value($_POST['category']);
    $newCategory = get_safe_value($_POST['newCategory']);
    $item = get_safe_value($_POST['item']);
    $price = get_safe_value($_POST['price']);
    $expenseDate = get_safe_value($_POST['expenseDate']);

    if ($category == 'new' && !empty($newCategory)) {
        // Insert new category into the database
        $insertCategoryQuery = "INSERT INTO category (name) VALUES ('$newCategory')";
        if ($con->query($insertCategoryQuery) === TRUE) {
            // Get the last inserted category ID
            $category = $con->insert_id;
        } else {
            $msg = "Error: " . $con->error;
        }
    }

    if (!empty($category) && !empty($item) && !empty($price) && !empty($expenseDate)) {
        // Insert expense into the database
        $insertExpenseQuery = "INSERT INTO expense (category_id, item, price, expense_date, added_by)
                               VALUES ('$category', '$item', '$price', '$expenseDate', '".$_SESSION['UID']."')";
        if ($con->query($insertExpenseQuery) === TRUE) {
            $msg = "Expense added successfully!";
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
    <title>Add Expense</title>
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
                            <form action="" method="post" id="expenseForm">
                                <div class="form-group">
                                    <label>Category</label>
                                    <select class="au-input au-input--full custom-select" name="category" id="categorySelect" required>
                                        <option value="">Select Category</option>
                                        <?php
                                        $categories = mysqli_query($con, "SELECT * FROM category");
                                        while ($cat = mysqli_fetch_assoc($categories)) {
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
                                    <label>Item</label>
                                    <input class="au-input au-input--full" type="text" name="item" placeholder="Item" required>
                                </div>
                                <div class="form-group">
                                    <label>Price</label>
                                    <input class="au-input au-input--full" type="number" name="price" placeholder="Price" required>
                                </div>
                                <div class="form-group">
                                    <label>Expense Date</label>
                                    <input class="au-input au-input--full" type="date" name="expenseDate" required>
                                </div>
                                <button class="au-btn au-btn--block my-submit-button m-b-20" type="submit" name="addExpense">Add Expense</button>
                              </form>
                              <div id="msg"><?php echo $msg ?></di>
                              <div class="center-button-container">
                                <a href="expense.php" class="au-btn au-btn--blue ">Back to Expenses</a>
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
