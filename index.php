<?php
include('config.php');
include('functions.php');
$msg="";
if(isset($_POST['login'])){
	$username=get_safe_value($_POST['username']);
	$password=get_safe_value($_POST['password']);
	
	$res=mysqli_query($con,"select * from users where username='$username'");
	
	if(mysqli_num_rows($res)>0){
		$row=mysqli_fetch_assoc($res);
		
		$verify=password_verify($password,$row['password']);
		
		if($verify==1){
			$_SESSION['UID']=$row['Id'];
			$_SESSION['UNAME']=$row['username'];
			$_SESSION['UROLE']=$row['role'];
			if($_SESSION['UROLE']=='User'){
				redirect('dashboard.php');
			}else{
				redirect('category.php');
			}
		}else{
			$msg="Please enter valid password";
		}
	}else{
		$msg="Please enter valid username";
	}
		
}
?>
<!DOCTYPE html>
<html lang="en">
<style>
    .bg-image{
        position: absolute;
        left: 30px;
        top: 150px;

    }
</style>
<head>
    <!-- Required meta tags-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="au theme template">
    <meta name="author" content="Hau Nguyen">
    <meta name="keywords" content="au theme template">

    <!-- Title Page-->
    <title>Login</title>

    <!-- Bootstrap CSS -->
    <link href="vendor/bootstrap-4.1/bootstrap.min.css" rel="stylesheet" media="all">

    <!-- Main CSS-->
    <link href="css/theme.css" rel="stylesheet" media="all">

</head>
    
<body class="animsition">
    <div class="page-wrapper">

 
        <div class="page-content--bge5">

        <div class="container">
            <div class="bg-image">
            <img src="media/test2.png">

            </div> 
                <div class="login-wrap">
                    <div class="login-content">
                        <div class="login-form">
                            <form action="" method="post">
                                <div class="form-group">
                                    <label>Username</label>
                                    <input class="au-input au-input--full" type="text" name="username" placeholder="Username" required>
                                </div>
                                <div class="form-group">
                                    <label>Password</label>
                                    <input class="au-input au-input--full" type="password" name="password" placeholder="Password" required>
                                </div>
                                
                                <button class="au-btn au-btn--block au-btn--green m-b-20" type="submit" name='login'>Sign In</button>
                            </form>
                            <div id="msg"><?php echo $msg?></div>
                            <div class="register-link m-t-15 text-center">
                                Don't have an account? <a href="register.php">Register here</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <label class="title" style="font-size: 50px; color:black;font-family: 'Times New Roman', Times, serif; position:absolute; top:50px;left:20%;">OnlyFunds<br></label>
    <label style="font-size: 25px; color:black;font-family: 'Times New Roman', Times, serif; position:absolute; top:120px;left:21%;"> "Plan a bright future"</labe>
       

    


    <!-- Jquery JS-->
    <script src="vendor/jquery-3.2.1.min.js"></script>
    <!-- Bootstrap JS-->
    <script src="vendor/bootstrap-4.1/popper.min.js"></script>
    <script src="vendor/bootstrap-4.1/bootstrap.min.js"></script>
    
    <!-- Main JS-->
    <script src="js/main.js"></script>

</body>

</html>
<!-- end document-->
