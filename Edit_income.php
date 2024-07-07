<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('header.php'); // Include your header file
checkUser(); // Function to check if user is logged in
userArea(); // Function to display user-specific area

// Update logic
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $id = $_POST['id'];
    $description = $_POST['description'];
    $amount = $_POST['amount'];
    $income_date = $_POST['income_date'];

    // Validate and sanitize inputs as needed

    // Update query
    $query = "UPDATE income SET description=?, amount=?, income_date=? WHERE id=?";

    // Prepare statement
    $stmt = mysqli_prepare($con, $query);

    // Bind parameters
    mysqli_stmt_bind_param($stmt, 'sdsi', $description, $amount, $income_date, $id);

    // Execute the query
    if (mysqli_stmt_execute($stmt)) {
        echo "<br/>Data updated successfully.<br/>";
    } else {
        echo "Error updating record: " . mysqli_stmt_error($stmt);
    }

    mysqli_stmt_close($stmt);
}

// Fetching income details for editing
if (isset($_GET['id']) && $_GET['id'] > 0) {
    $id = $_GET['id'];
    $result = mysqli_query($con, "SELECT income.*, category.name 
                                  FROM income, category  
                                  WHERE income.category_id = category.id 
                                  AND income.id = $id
                                  AND income.user_id = '".$_SESSION['UID']."'");
    if ($result) {
        $row = mysqli_fetch_assoc($result);
    } else {
        echo "Error fetching record: " . mysqli_error($con);
        exit;
    }
} else {
    echo "Invalid income ID.";
    exit;
}
?>

<script>
   setTitle("Edit Income"); // Set the title (similar to setting "Income" in the previous code block)
   selectLink('income_link'); // Select the current link (similar to selecting "income_link" in the previous code block)
</script>

<div class="main-content">
   <div class="section__content section__content--p30">
      <div class="container-fluid">
         <div class="row">
            <div class="col-lg-12">
               <h2>Edit Income</h2>
               <a href="income.php">Back to Income List</a>
               <br/><br/>
               <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . '?id=' . $id; ?>">
                  <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                  <div class="form-group">
                     <label for="description">Description:</label>
                     <input type="text" class="form-control" id="description" name="description" value="<?php echo htmlspecialchars($row['description']); ?>">
                  </div>
                  <div class="form-group">
                     <label for="amount">Amount:</label>
                     <input type="number" class="form-control" id="amount" name="amount" value="<?php echo htmlspecialchars($row['amount']); ?>">
                  </div>
                  <div class="form-group">
                     <label for="income_date">Income Date:</label>
                     <input type="date" class="form-control" id="income_date" name="income_date" value="<?php echo htmlspecialchars($row['income_date']); ?>">
                  </div>
                  <button type="submit" class="btn btn-primary">Update Income</button>
               </form>
            </div>
         </div>
      </div>
   </div>
</div>

<?php
include('footer.php'); // Include your footer file
?>
