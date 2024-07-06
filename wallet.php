<?php
include('header.php');


if (isset($_SESSION['msg'])) {
   echo '<div class="alert alert-info">' . $_SESSION['msg'] . '</div>';
   unset($_SESSION['msg']);
}

function getTotalExpenses() {
    global $con;
    $query = "SELECT SUM(price) AS total_expenses FROM expense WHERE added_by = '".$_SESSION['UID']."'";
    $result = mysqli_query($con, $query);

    if (!$result) {
        die("Query Failed: " . mysqli_error($con));
    }

    $row = mysqli_fetch_assoc($result);
    return $row['total_expenses'] ?? 0; // Return 0 if null
}

function getTotalIncome() {
    global $con;
    $query = "SELECT SUM(amount) AS total_income FROM income WHERE user_id = '".$_SESSION['UID']."'";
    $result = mysqli_query($con, $query);

    if (!$result) {
        die("Query Failed: " . mysqli_error($con));
    }

    $row = mysqli_fetch_assoc($result);
    return $row['total_income'] ?? 0; // Return 0 if null
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

// Calculate totals and update wallet balance
$totalExpenses = getTotalExpenses();
$totalIncome = getTotalIncome();
$difference = $totalIncome - $totalExpenses;
$walletBalance = getWalletBalance(); // Get current wallet balance

$newWalletBalance = $difference;
updateWalletBalance($newWalletBalance);

// Reload wallet balance after update
$walletBalance = getWalletBalance(); // Refresh wallet balance
?>

<div class="main-content">
   <div class="section__content section__content--p30">
      <div class="container-fluid">
         <div class="row">
            <div class="col-lg-12">
            <h2>Wallet</h2>
               <div class="card">
                  <div class="card-header wallet-header-black">
                     <strong>Wallet Balance</strong>
                  </div>
                  <div class="card-body">
                     <p class="balance">Current Balance: $<?php echo number_format($walletBalance, 2); ?></p>
                     <hr>
                     <h5 class="card-title">Summary</h5>
                     <p class="card-text">Total Income: $<?php echo number_format($totalIncome, 2); ?></p>
                     <p class="card-text">Total Expenses: $<?php echo number_format($totalExpenses, 2); ?></p>
                  </div>
               </div>
            </div>
         </div>

        <!-- Deposit and Withdraw Buttons -->
            <div class="row mt-4">
               <div class="col-lg-6">
                  <a href="deposit.php" class="btn btn-primary btn-block">Deposit to Wallet</a>
               </div>
               <div class="col-lg-6">
                  <a href="withdraw.php" class="btn btn-danger btn-block">Withdraw from Wallet</a>
               </div>
               <div class="col-lg-12 mt-4 text-center"> 
               <a href="transfer.php" class="btn btn-success my-submit-button">Transfer Money</a> 
                </div>
            </div>

         <!-- Wallet Balance History Section -->
         <div class="row mt-4">
            <div class="col-lg-12">
               <div class="card">
                  <div class="card-header wallet-header-black">
                     <strong>Wallet Balance History</strong>
                  </div>
                  <div class="card-body">
                     <div class="table-responsive">
                        <table class="table table-bordered">
                           <thead>
                              <tr>
                                 <th>Date</th>
                                 <th>Balance</th>
                                 <th>Change Amount</th>
                              </tr>
                           </thead>
                           <tbody>
                              <!-- Fetch and display wallet history logs -->
                              <?php
                              $historyQuery = "SELECT balance, change_amount, date_updated FROM wallet_history WHERE user_id = '".$_SESSION['UID']."' ORDER BY date_updated DESC";
                              $historyResult = mysqli_query($con, $historyQuery);

                              if ($historyResult) {
                                  while ($log = mysqli_fetch_assoc($historyResult)) {
                                      echo "<tr>";
                                      echo "<td>" . $log['date_updated'] . "</td>";
                                      echo "<td>$" . number_format($log['balance'], 2) . "</td>";

                                      // Calculate change amount display
                                      $changeAmount = $log['change_amount'];
                                      echo "<td>$" . number_format($changeAmount, 2) . "</td>";

                                      echo "</tr>";
                                  }
                              } else {
                                  echo "<tr><td colspan='3'>No history found.</td></tr>";
                              }
                              ?>
                           </tbody>
                        </table>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<?php include('footer.php'); ?>
