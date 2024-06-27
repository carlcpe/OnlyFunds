<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>

<style>
    
    #signup_button{
        background-color: gray;
        color: black;
        width: 140px;
        text-align: center;
        padding: 4px;
        border-radius: 8px;
        margin: auto;
    }
    #login_bar{
        background-color: rgba(128, 128, 128, 0.7);
        width: 400px; 
        height: 600px; 
        margin:auto;
        margin-top:5%;
        text-align: center;
        align-content: center;
        border-radius: 5%;

    }
    #text{
        height: 40px;
        width: 300px;
        border-radius: 4px;
        border: solid 1px #888;
        padding: 4px;
        font-size: 14px;
    }
    #button{
        width: 300px;
        height: 40px;
        border-radius: 4px;
        font-weight: bold;
        border: none;
        background-color: gray;
    }
</style>

<body style="background:linear-gradient(to right,yellow, orange);">


    <div id="intro">
        <div style="font-size: 40px;color:black">
            OnlyFunds
        </div>
    </div>

    

    <div id="login_bar">
        <div style="font-size: 40px;">Sign Up<br></div>
        Sign up to join us<br><br>
        <input type="text" id="text" placeholder="Username"><br><br>
        <input type="text" id="text" placeholder="Email"><br><br>
        <input type="text" id="text" placeholder="Password"><br><br>
        <input type="text" id="text" placeholder="Retype Password"><br><br>
        <input type="submit" id="button" value="Log in"><br><br>

        <div id="signup_button">
            Signup
        </div>
    </div>

    <?php
        echo "Hello <br>\n";
        echo "Date is: " .date('j-m-y, h:i:s');
    ?> 
</body>
</html>