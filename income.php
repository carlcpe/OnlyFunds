<?php
include('header.php');
checkUser();
userArea();

// Deletion logic
if(isset($_GET['type']) && $_GET['type'] == 'delete' && isset($_GET['id']) && $_GET['id'] > 0){
   $id = get_safe_value($_GET['id']);
   mysqli_query($con, "DELETE FROM income WHERE id=$id");
   echo "<br/>Data deleted<br/>";
}

// Filter form variables initialization
$cat_id = '';
$from = '';
$to = '';

// Handle filter form submission
if(isset($_GET['submit'])){
   $cat_id = get_safe_value($_GET['category_id']);
   $from = get_safe_value($_GET['from']);
   $to = get_safe_value($_GET['to']);
}

// Constructing SQL query with filters
$userId = $_SESSION['UID'];
$sql = "SELECT income.*, income_categories.name AS category_name
        FROM income 
        LEFT JOIN income_categories ON income.category_id = income_categories.id
        WHERE income.user_id = '$userId'";

if($cat_id > 0){
   $sql .= " AND income.category_id = $cat_id";
}

if($from != '' && $to != ''){
   $sql .= " AND income.income_date BETWEEN '$from' AND '$to'";
}

$sql .= " ORDER BY income.income_date DESC";

// Fetching income records based on filtered query
$incomeRecords = mysqli_query($con, $sql);
?>

<script>
   setTitle("Income Records");
   selectLink('income_link');
</script>

<div class="main-content">
   <div class="section__content section__content--p30">
      <div class="container-fluid">
         <div class="row">
            <div class="col-lg-12">
               <h2>Income Records</h2>
               <div class="filter_form">
                  <form method="get">
                    <div class="form-group">
                     <?php echo getCategory($cat_id, 'income'); ?>
                     From <input type="date" name="from" value="<?php echo $from ?>" max="<?php echo date('Y-m-d') ?>" id="from_date" class="form-control w250">
                     To <input type="date" name="to" value="<?php echo $to ?>" max="<?php echo date('Y-m-d') ?>" id="to_date" class="form-control w250">
                     <input type="submit" name="submit" value="Submit" class="btn btn-lg btn-info btn-block">
                     <a href="income.php">Reset</a>
                    </div>
                  </form>
               </div>
               <a href="manage_income.php">Add Income</a>
               <br/><br/>
               <?php
                  if (mysqli_num_rows($incomeRecords) > 0) {
               ?>
               <div class="table-responsive table--no-card m-b-30">
                  <table class="table table-borderless table-striped table-earning">
                     <thead>
                        <tr>
                           <th>Category</th>
                           <th>Description</th>
                           <th>Amount</th>
                           <th>Income Date</th>
                           <th></th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php while ($record = mysqli_fetch_assoc($incomeRecords)) { ?>
                           <tr>
                              <td><?php echo $record['category_name']; ?></td>
                              <td><?php echo $record['description']; ?></td>
                              <td><?php echo $record['amount']; ?></td>
                              <td><?php echo $record['income_date']; ?></td>
                              <td>
                                 <a href="Edit_income.php?id=<?php echo $record['id']; ?>">Edit</a>
                                 &nbsp;
                                 <a href="javascript:void(0)" onclick="delete_confir('<?php echo $record['id']; ?>', 'income.php')">Delete</a>
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
         </div>
      </div>
   </div>
</div>

<?php
include('footer.php');
?>
