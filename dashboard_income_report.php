<?php
include('header.php');
checkUser();
userArea();

$from = '';
$to = '';
$sub_sql = "";

if (isset($_GET['from'])) {
    $from = get_safe_value($_GET['from']);
}
if (isset($_GET['to'])) {
    $to = get_safe_value($_GET['to']);
}

if ($from !== '' && $to != '') {
    $sub_sql .= " AND income.income_date BETWEEN '$from' AND '$to' ";
}

$query = "SELECT income.amount, income_categories.name AS category, income.description, income.income_date 
          FROM income 
          INNER JOIN income_categories ON income.category_id = income_categories.id 
          WHERE income.user_id = '".$_SESSION['UID']."' $sub_sql";

$result = mysqli_query($con, $query);
?>

<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div>
                        <h2>
                            <?php if ($from !== '' && $to !== '') { ?>
                                From <?php echo $from ?> : To <?php echo $to ?>
                            <?php } ?>
                        </h2>
                        <br/>
                    </div>
                    <?php
                    if (mysqli_num_rows($result) > 0) {
                    ?>
                        <div class="table-responsive table--no-card m-b-30">
                            <table class="table table-borderless table-striped table-earning">
                                <tr>
                                    <th>Category</th>
                                    <th>description</th>
                                    <th>Income Date</th>
                                    <th>Amount</th>
                                </tr>
                                <?php
                                $final_amount = 0;
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $final_amount += $row['amount'];
                                ?>
                                    <tr>
                                        <td><?php echo $row['category'] ?></td>
                                        <td><?php echo $row['description'] ?></td>
                                        <td><?php echo $row['income_date'] ?></td>
                                        <td><?php echo $row['amount'] ?></td>
                                    </tr>
                                <?php } ?>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th>Total</th>
                                    <th><?php echo $final_amount ?></th>
                                </tr>
                            </table>
                        <?php } else {
                            echo "<b>No data found</b>";
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('footer.php'); ?>
