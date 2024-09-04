<?php
    include("database.php"); //First of all code
    session_start(); //before all html code
    include("header.html");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h2>Login Page</h2>
    <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"])?>" method="post">
            <div>
                <label for="email">Email:</label>
                <input type="text" name="email" id="email">
            </div>
            <div>
                <label for="password">Password:</label>
                <input type="password" name="password" id="password">
            </div>
            <div>
                <input type="submit" value="Login" name="login">
            </div>
    </form>
    <a href="registration.php">Not registered? Click here</a>
</body>
</html>

<?php
    if($_SERVER["REQUEST_METHOD"]=="POST"){
        if (!empty($_POST["email"]) && !empty($_POST["password"])){
            $email=filter_input(INPUT_POST, "email", FILTER_SANITIZE_SPECIAL_CHARS);
            $email=filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL);
            $password=filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);      
            
            $sql = "select * from user where email='$email';";
            $result=mysqli_query($conn, $sql); //returns object

            if (mysqli_num_rows($result)>0){
                $row = mysqli_fetch_assoc($result); //returns next row as disctionary
                $hash = $row["password"];
                if (password_verify($password, $hash)) {
                    $_SESSION["user_id"] = $row["user_id"];
                    header("Location: profile.php");
                }
            }
            else{
                "Email is not registered";
            }
        }
        else{
            echo "Please enter Email and password!";
        }
    }
?>
<?php
    include("footer.html"); 
    mysqli_close($conn); //last of all code
?>