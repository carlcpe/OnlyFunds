<?php
include('header.php');
checkUser();
userArea();

function getExpensesByCategory() {
   global $con;
   $query = "SELECT category.name AS category, SUM(expense.price) AS total 
             FROM expense 
             INNER JOIN category ON expense.category_id = category.id 
             WHERE expense.added_by = '".$_SESSION['UID']."'
             GROUP BY expense.category_id";
   $result = mysqli_query($con, $query);
   $data = [];
   while ($row = mysqli_fetch_assoc($result)) {
       $data[] = $row;
   }
   return $data;
}

function getIncomesByCategory() {
   global $con;
   $query = "SELECT income_categories.name AS category, SUM(income.amount) AS total 
             FROM income 
             INNER JOIN income_categories ON income.category_id = income_categories.id 
             WHERE income.user_id = '".$_SESSION['UID']."'
             GROUP BY income.category_id";
   $result = mysqli_query($con, $query);
   $data = [];
   while ($row = mysqli_fetch_assoc($result)) {
       $data[] = $row;
   }
   return $data;
}


function getWalletHistory() {
    global $con;
    $query = "SELECT balance, date_updated FROM wallet_history WHERE user_id = '".$_SESSION['UID']."' ORDER BY date_updated";
    $result = mysqli_query($con, $query);
    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
    return $data;
}

$expensesByCategory = getExpensesByCategory();
$incomesByCategory = getIncomesByCategory();
$walletHistory = getWalletHistory();
?>

<script>
    setTitle("Dashboard");
    selectLink('dashboard_link');
    document.addEventListener('DOMContentLoaded', (event) => {

    // Wallet history line chart
    const walletHistoryData = <?php echo json_encode($walletHistory); ?>;
    const walletLabels = walletHistoryData.map(item => item.date_updated);
    const walletData = walletHistoryData.map(item => parseFloat(item.balance));

    new Chart(document.getElementById('walletHistoryChart'), {
        type: 'line',
        data: {
            labels: walletLabels,
            datasets: [{
                color: 'black',
                label: 'Wallet Balance',
                data: walletData,
                fill: false,
                borderColor: '#4BC0C0',
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'right',
                }
            },
            interaction: {
                mode: 'index',
                intersect: false,
            },
            plugins: {
                tooltip: {
                    mode: 'index',
                    intersect: false,
                }
            }
        }
    });




 // Expense by category doughnut chart
 const expenseChartData = <?php echo json_encode($expensesByCategory); ?>;
    const expenseLabels = expenseChartData.map(item => item.category);
    const expenseData = expenseChartData.map(item => parseFloat(item.total));

    const expenseChartCanvas = document.getElementById('expenseByCategoryChart');
    expenseChartCanvas.height = 400; // Set the height of the chart canvas
    new Chart(expenseChartCanvas, {
        type: 'doughnut',
        data: {
            labels: expenseLabels,
            datasets: [{
                data: expenseData,
                backgroundColor: ['#FFFF4D', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'],
                hoverBackgroundColor: ['#FFFF4D', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'right',
                }
            },
            animation: {
                animateScale: true,
                animateRotate: true
            }
        }
    });

    // Income by category doughnut chart
    const incomeChartData = <?php echo json_encode($incomesByCategory); ?>;
    const incomeLabels = incomeChartData.map(item => item.category);
    const incomeData = incomeChartData.map(item => parseFloat(item.total));

    const incomeChartCanvas = document.getElementById('incomeByCategoryChart');
    incomeChartCanvas.height = 400; // Set the height of the chart canvas
    new Chart(incomeChartCanvas, {
        type: 'doughnut',
        data: {
            labels: incomeLabels,
            datasets: [{
                data: incomeData,
                backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'],
                hoverBackgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'right',
                }
            },
            animation: {
                animateScale: true,
                animateRotate: true
            }
        }
    });
});

</script>

<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <!-- Wallet History Chart -->
                <div class="col-lg-12">
                    <div class="overview-item overview-item--c1">
                        <div class="overview__inner">
                            <div class="overview-box clearfix">
                                <div class="text">
                                    <canvas id="walletHistoryChart" height="300"></canvas>
                                    <span>Wallet History</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Incomes Column -->
                <div class="col-lg-6">
                    <!-- Income by Category Chart -->
                    <div class="overview-item overview-item--c1">
                        <div class="overview__inner">
                            <div class="overview-box clearfix">
                                <div class="text">
                                    <canvas id="incomeByCategoryChart"></canvas>
                                    <span>Incomes by Category</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Expenses Column -->
                <div class="col-lg-6">
                    <!-- Expense by Category Chart -->
                    <div class="overview-item overview-item--c1">
                        <div class="overview__inner">
                            <div class="overview-box clearfix">
                                <div class="text">
                                    <canvas id="expenseByCategoryChart"></canvas>
                                    <span>Expenses by Category</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Expense and Income Statistics -->
                <div class="col-lg-6">
                    <!-- Today's Income -->
                    <div class="overview-item overview-item--c1">
                        <div class="overview__inner">
                            <div class="overview-box clearfix">
                                <div class="text">
                                    <h2><?php echo getDashboardIncome('today')?></h2>
                                    <span>Today's Income</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Yesterday's Income -->
                    <div class="overview-item overview-item--c1">
                        <div class="overview__inner">
                            <div class="overview-box clearfix">
                                <div class="text">
                                    <h2><?php echo getDashboardIncome('yesterday')?></h2>
                                    <span>Yesterday's Income</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- This Week's Income -->
                    <div class="overview-item overview-item--c1">
                        <div class="overview__inner">
                            <div class="overview-box clearfix">
                                <div class="text">
                                    <h2><?php echo getDashboardIncome('week')?></h2>
                                    <span>This Week's Income</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- This Month's Income -->
                    <div class="overview-item overview-item--c1">
                        <div class="overview__inner">
                            <div class="overview-box clearfix">
                                <div class="text">
                                    <h2><?php echo getDashboardIncome('month')?></h2>
                                    <span>This Month's Income</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- This Year's Income -->
                    <div class="overview-item overview-item--c1">
                        <div class="overview__inner">
                            <div class="overview-box clearfix">
                                <div class="text">
                                    <h2><?php echo getDashboardIncome('year')?></h2>
                                    <span>This Year's Income</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Income -->
                    <div class="overview-item overview-item--c1">
                        <div class="overview__inner">
                            <div class="overview-box clearfix">
                                <div class="text">
                                    <h2><?php echo getDashboardIncome('total')?></h2>
                                    <span>Total Income</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Expenses Column -->
                <div class="col-lg-6">
                    <!-- Today's Expense -->
                    <div class="overview-item overview-item--c1">
                        <div class="overview__inner">
                            <div class="overview-box clearfix">
                                <div class="text">
                                    <h2><?php echo getDashboardExpense('today')?></h2>
                                    <span>Today's Expense</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Yesterday's Expense -->
                    <div class="overview-item overview-item--c1">
                        <div class="overview__inner">
                            <div class="overview-box clearfix">
                                <div class="text">
                                    <h2><?php echo getDashboardExpense('yesterday')?></h2>
                                    <span>Yesterday's Expense</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- This Week's Expense -->
                    <div class="overview-item overview-item--c1">
                        <div class="overview__inner">
                            <div class="overview-box clearfix">
                                <div class="text">
                                    <h2><?php echo getDashboardExpense('week')?></h2>
                                    <span>This Week's Expense</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- This Month's Expense -->
                    <div class="overview-item overview-item--c1">
                        <div class="overview__inner">
                            <div class="overview-box clearfix">
                                <div class="text">
                                    <h2><?php echo getDashboardExpense('month')?></h2>
                                    <span>This Month's Expense</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- This Year's Expense -->
                    <div class="overview-item overview-item--c1">
                        <div class="overview__inner">
                            <div class="overview-box clearfix">
                                <div class="text">
                                    <h2><?php echo getDashboardExpense('year')?></h2>
                                    <span>This Year's Expense</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Expense -->
                    <div class="overview-item overview-item--c1">
                        <div class="overview__inner">
                            <div class="overview-box clearfix">
                                <div class="text">
                                    <h2><?php echo getDashboardExpense('total')?></h2>
                                    <span>Total Expense</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- END MAIN CONTENT-->
<!-- END PAGE CONTAINER-->
<?php include('footer.php'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
