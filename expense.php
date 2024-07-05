<?php
include('header.php');
checkUser();
userArea();

// Deletion logic
if(isset($_GET['type']) && $_GET['type'] == 'delete' && isset($_GET['id']) && $_GET['id'] > 0){
    $id = get_safe_value($_GET['id']);
    mysqli_query($con, "DELETE FROM expense WHERE id=$id");
    echo "<br/>Data deleted<br/>";
}

// Fetching expenses with filtering
$sub_sql = '';
$cat_id = '';
$from = '';
$to = '';

// Handle filter form submission
if(isset($_GET['submit'])){
    $cat_id = get_safe_value($_GET['category_id']);
    $from = get_safe_value($_GET['from']);
    $to = get_safe_value($_GET['to']);

    if($cat_id > 0){
        $sub_sql .= " AND expense.category_id = $cat_id ";
    }
    if($from != '' && $to != ''){
        $sub_sql .= " AND expense.expense_date BETWEEN '$from' AND '$to' ";
    }
}

$res = mysqli_query($con, "SELECT expense.*, category.name 
                           FROM expense, category  
                           WHERE expense.category_id = category.id 
                           AND expense.added_by = '".$_SESSION['UID']."' $sub_sql
                           ORDER BY expense.expense_date ASC");
?>

<script>
   setTitle("Expense");
   selectLink('expense_link');
</script>

<div class="main-content">
   <div class="section__content section__content--p30">
      <div class="container-fluid">
         <div class="row">
            <div class="col-lg-12">
               <h2>Expense</h2>
               <div class="filter_form">
                  <form method="get">
                     <div class="form-group">
                        <?php echo getCategory($cat_id, 'expense'); ?>
                        From <input type="date" name="from" value="<?php echo $from ?>" max="<?php echo date('Y-m-d') ?>" onchange="set_to_date()" id="from_date" class="form-control w250">
                        To <input type="date" name="to" value="<?php echo $to ?>" max="<?php echo date('Y-m-d') ?>" id="to_date" class="form-control w250">
                        <input type="submit" name="submit" value="Submit" class="btn btn-lg btn-info my-submit-button">
                     <button onclick="location.href='expense.php'" class="btn btn-lg btn-info my-submit-button">Reset</button>
                     </div>
                  </form>
               </div>
               <br/><br/>
               <?php
                  if (mysqli_num_rows($res) > 0) {
               ?>
               <div class="table-responsive table--no-card m-b-30">
                  <table class="table table-borderless table-striped table-earning">
                     <thead>
                        <tr>
                           <th>Category</th>
                           <th>Item</th>
                           <th>Price</th>
                           <th>Expense Date</th>
                           <th></th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php while ($row = mysqli_fetch_assoc($res)) { ?>
                        <tr>
                           <td><?php echo $row['name']; ?></td>
                           <td><?php echo $row['item']; ?></td>
                           <td><?php echo $row['price']; ?></td>
                           <td><?php echo $row['expense_date']; ?></td>
                           <td>
                              <a href="Edit_expense.php?id=<?php echo $row['id']; ?>">Edit</a>&nbsp;
                              <a href="javascript:void(0)" onclick="delete_confir('<?php echo $row['id']; ?>', 'expense.php')"style="color: red;">Delete</a>
                           </td>
                        </tr>
                        <?php } ?>
                     </tbody>
                  </table>
               </div>
               <?php
                  } else {
                     echo "<div>No data found</div>";
                  }
               ?>
            </div>
            <button onclick="location.href='manage_expense.php'" class="btn btn-lg btn-info btn-block my-submit-button">Add Expense</button>
         </div>
      </div>
   </div>
</div>

<?php
include('footer.php');
?>
