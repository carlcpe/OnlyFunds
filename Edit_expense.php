<?php
   include('header.php');
   checkUser();
   userArea();

   // Update logic
   if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $id = get_safe_value($_POST['id']);
      $item = get_safe_value($_POST['item']);
      $price = get_safe_value($_POST['price']);
      $details = get_safe_value($_POST['details']);
      $expense_date = get_safe_value($_POST['expense_date']);
      $added_on = get_safe_value($_POST['added_on']);

      // Update query
      $query = "UPDATE expense SET item=?, price=?, details=?, expense_date=?, added_on=? WHERE id=?";
      $stmt = mysqli_prepare($con, $query);
      mysqli_stmt_bind_param($stmt, "sdsdsi", $item, $price, $details, $expense_date, $added_on, $id);
      
      if (mysqli_stmt_execute($stmt)) {
         echo "<br/>Data updated successfully.<br/>";
      } else {
         echo "Error updating data: " . mysqli_error($con);
      }
      mysqli_stmt_close($stmt);
   }

   // Fetch expense details for editing
   if(isset($_GET['id']) && $_GET['id'] > 0) {
      $id = get_safe_value($_GET['id']);
      $result = mysqli_query($con, "SELECT * FROM expense WHERE id=$id");
      $row = mysqli_fetch_assoc($result);
   } else {
      echo "Invalid expense ID.";
      exit;
   }
?>

<script>
   setTitle("Edit Expense");
   selectLink('expense_link');
</script>

<div class="main-content">
   <div class="section__content section__content--p30">
      <div class="container-fluid">
         <div class="row">
            <div class="col-lg-12">
               <h2>Edit Expense</h2>
               <a href="expense.php">Back to Expense List</a>
               <br/><br/>
               <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                  <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                  <div class="form-group">
                     <label for="item">Item:</label>
                     <input type="text" class="form-control" id="item" name="item" value="<?php echo $row['item']; ?>">
                  </div>
                  <div class="form-group">
                     <label for="price">Price:</label>
                     <input type="number" class="form-control" id="price" name="price" value="<?php echo $row['price']; ?>">
                  </div>
                  <div class="form-group">
                     <label for="details">Details:</label>
                     <textarea class="form-control" id="details" name="details"><?php echo $row['details']; ?></textarea>
                  </div>
                  <div class="form-group">
                     <label for="expense_date">Expense Date:</label>
                     <input type="date" class="form-control" id="expense_date" name="expense_date" value="<?php echo $row['expense_date']; ?>">
                  </div>
                  <div class="form-group">
                     <label for="added_on">Added On:</label>
                     <input type="datetime-local" class="form-control" id="added_on" name="added_on" value="<?php echo $row['added_on']; ?>">
                  </div>
                  <button type="submit" class="btn btn-primary">Update Expense</button>
               </form>
            </div>
         </div>
      </div>
   </div>
</div>

<?php
   include('footer.php');
?>
