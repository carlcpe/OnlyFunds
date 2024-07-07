<?php
include('config.php');
include('functions.php'); 

$msg = "";
if (isset($_POST['signUp'])) {
    $username = get_safe_value($_POST['username']);
    $email = get_safe_value($_POST['email']);
    $password = get_safe_value($_POST['password']);
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Use password_hash for better security
    $role = 'User'; // Default role for new users

    $checkEmail = "SELECT * FROM users WHERE email='$email'";
    $result = $con->query($checkEmail); // Use $con instead of $conn

    if ($result->num_rows > 0) {
        $msg = "Email Address Already Exists!";
    } else {
        // Insert user into users table
        $insertQuery = "INSERT INTO users (username, email, password, role)
                        VALUES ('$username', '$email', '$hashedPassword', '$role')";
        
        if ($con->query($insertQuery) === TRUE) {
            // Retrieve user ID of the inserted user
            $userId = $con->insert_id;

            // Insert initial wallet row for the new user
            $insertWalletQuery = "INSERT INTO wallet (user_id, balance)
                                  VALUES ('$userId', 0)";

            if ($con->query($insertWalletQuery) === TRUE) {
                header("Location: index.php"); // Redirect to login page after successful registration
                exit();
            } else {
                $msg = "Error creating wallet entry: " . $con->error;
            }
        } else {
            $msg = "Error: " . $con->error;
        }
    }
}

if (isset($_POST['signIn'])) {
    $email = get_safe_value($_POST['email']);
    $password = get_safe_value($_POST['password']);

    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = $con->query($sql); // Use $con instead of $conn

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            session_start();
            $_SESSION['email'] = $row['email'];
            $_SESSION['role'] = $row['role'];
            header("Location: homepage.php");
            exit();
        } else {
            $msg = "Incorrect Password";
        }
    } else {
        $msg = "Email Not Found";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Register</title>
    <link href="vendor/bootstrap-4.1/bootstrap.min.css" rel="stylesheet" media="all">
    <link href="css/theme.css" rel="stylesheet" media="all">
</head>
<style>
    .bg-image{
        position: absolute;
        left: 5%;
        top: 150px;
    }
    .logo-image{
        position: absolute;
        width: 120px;
        top: 10px;

    }
    .headerOF{
        position:absolute;
        top:50px;
        left: 15%;
        height: 150px;
        width: 500px;
    }
    .title{
        font-size: 50px; 
        color:black;
        font-family: 'Times New Roman', Times, serif;
        position: absolute;
        left: 125px;
    }
    .subtitle{
        font-size: 25px; 
        color:black;
        font-family: 'Times New Roman', Times, serif;
        position: absolute;
        left: 150px;
        top: 75px;
    }
</style>
<body class="animsition">
    <div class="page-wrapper">
        <div class="page-content--bge5">
            <div class="container">
                <div class="bg-image">
                    <img src="media/signup2.png">
                </div> 
                <div class="login-wrap">
                    <div class="login-content">
                        <div class="login-form">
                            <form action="" method="post">
                                <div class="form-group">
                                    <label>Username</label>
                                    <input class="au-input au-input--full" type="text" name="username" placeholder="Username" required> <!-- Correct name attribute -->
                                </div>
                                <div class="form-group">
                                    <label>Email</label>
                                    <input class="au-input au-input--full" type="email" name="email" placeholder="Email" required>
                                </div>
                                <div class="form-group">
                                    <label>Password</label>
                                    <input class="au-input au-input--full" type="password" name="password" placeholder="Password" required>
                                </div>
                                <button class="au-btn au-btn--block au-btn--green m-b-20" type="submit" name="signUp">Sign Up</button>
                            </form>
                            <div id="msg"><?php echo $msg ?></div>
                            <div class="register-link m-t-15 text-center">
                                Already have an account? <a href="index.php">Sign In Here</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="headerOF">
        <div class="logo-image">
            <img src="media/logo2.png">
        </div> 
        <label class="title">Sign Up Today!<br><br></label>
        <label class="subtitle">"Be part of OnlyFunds"</labe>
    </div>

    <script src="vendor/jquery-3.2.1.min.js"></script>
    <script src="vendor/bootstrap-4.1/popper.min.js"></script>
    <script src="vendor/bootstrap-4.1/bootstrap.min.js"></script>
    <script src="js/main.js"></script>
</body>

</html>
