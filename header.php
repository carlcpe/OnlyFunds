<?php
   include('config.php');
   include('functions.php');
   ?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <!-- Required meta tags-->
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <meta name="description" content="au theme template">
      <meta name="author" content="Hau Nguyen">
      <meta name="keywords" content="au theme template">
      <!-- Title Page-->
      <title>Dashboard</title>
      <!-- Fontfaces CSS-->
      <link href="css/font-face.css" rel="stylesheet" media="all">
      <link href="vendor/font-awesome-4.7/css/font-awesome.min.css" rel="stylesheet" media="all">
      <link href="vendor/font-awesome-5/css/fontawesome-all.min.css" rel="stylesheet" media="all">
      <link href="vendor/mdi-font/css/material-design-iconic-font.min.css" rel="stylesheet" media="all">
      <!-- Bootstrap CSS-->
      <link href="vendor/bootstrap-4.1/bootstrap.min.css" rel="stylesheet" media="all">
      <!-- Vendor CSS-->
      <link href="vendor/animsition/animsition.min.css" rel="stylesheet" media="all">
      <link href="vendor/bootstrap-progressbar/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet" media="all">
      <link href="vendor/wow/animate.css" rel="stylesheet" media="all">
      <link href="vendor/css-hamburgers/hamburgers.min.css" rel="stylesheet" media="all">
      <!-- Main CSS-->
      <link href="css/theme.css" rel="stylesheet" media="all">
      <script>
         function setTitle(title){
         	document.title=title;
         }
         function selectLink(id){
         	document.getElementById(id).classList.add('active');
         }
      </script>
   </head>
   <body class="animsition">
      <div class="page-wrapper">
      <!-- HEADER MOBILE-->
      <header class="header-mobile d-block d-lg-none">
         <div class="header-mobile__bar">
            <div class="container-fluid">
               <div class="header-mobile-inner">
                  <button class="hamburger hamburger--slider" type="button">
                  <span class="hamburger-box">
                  <span class="hamburger-inner"></span>
                  </span>
                  </button>
               </div>
            </div>
         </div>
         <nav class="navbar-mobile">
            <div class="container-fluid">
               <ul class="navbar-mobile__list list-unstyled">
                  <li class="has-sub">
                     <a class="js-arrow" href="#">
                     <i class="fas fa-tachometer-alt"></i>Dashboard</a>
                  </li>
               </ul>
            </div>
         </nav>
      </header>
      <!-- END HEADER MOBILE-->
      <!-- MENU SIDEBAR-->

      <<aside class="menu-sidebar d-none d-lg-block">
      <div class="sidebar-header">
            <img src="images\logo.png" alt="Logo">
            <h1>OnlyFunds</h1>
         </div>
    <div class="menu-sidebar__content js-scrollbar1">
        <nav class="navbar-sidebar">
            <ul class="list-unstyled navbar__list">
                <?php if($_SESSION['UROLE']=='User') { ?>
                <li id="dashboard_link">
                    <a class="js-arrow" href="dashboard.php">
                        <i class="fas fa-tachometer-alt"></i>Dashboard</a>
                </li>
                <li id="expense_link">
                    <a href="expense.php">
                        <i class="fas fa-rupee"></i>Expense</a>
                </li>
                <li id="income_link">
                    <a href="income.php">
                        <i class="fas fa-chart-bar"></i>Income</a>
                </li>
                <li id="wallet_link">
                    <a href="wallet.php">
                        <i class="fas fa-list-alt"></i>Wallet</a>
                </li>
                <!-- Existing code in sidebar.php -->
                <li id="about_us_link">
                    <a href="about.php">
                        <i class="fas fa-info-circle"></i>About Us</a>
                </li>

                <?php } else { ?>
                <li id="category_link">
                    <a href="category.php">
                        <i class="fas fa-list-alt"></i>Category</a>
                </li>
                <li id="users_link">
                    <a href="users.php">
                        <i class="fas fa-user-md"></i>Users</a>
                </li>
                <?php } ?>
                <li>
                    <a href="logout.php" onclick="return confirm('Are you sure you want to logout?');">
                        <i class="fas fa-power-off"></i>Logout</a>
                </li>
            </ul>
        </nav>
    </div>
</aside>
      <!-- END MENU SIDEBAR-->
      <!-- PAGE CONTAINER-->
      <div class="page-container">
      <!-- HEADER DESKTOP-->
      <header class="header-desktop">
         <div class="section__content section__content--p30">
         </div>
      </header>
      <!-- HEADER DESKTOP-->