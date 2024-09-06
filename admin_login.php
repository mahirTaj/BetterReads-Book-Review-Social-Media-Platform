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
    <h2>Admin Login Page</h2>
    <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"])?>" method="post">
            <div>
                <label for="username">Username:</label>
                <input type="text" name="username" id="username">
            </div>
            <div>
                <label for="password">Password:</label>
                <input type="password" name="password" id="password">
            </div>
            <div>
                <input type="submit" value="Login" name="login">
            </div>
    </form>
</body>

<?php
    if($_SERVER["REQUEST_METHOD"]=="POST"){
        if (!empty($_POST["username"]) && !empty($_POST["password"])){
            $username=filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
            $password=filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);      
            
            $sql = "select * from admin where username='$username';";
            $result=mysqli_query($conn, $sql); //returns object

            if (mysqli_num_rows($result)>0){
                $row = mysqli_fetch_assoc($result); //returns next row as disctionary
                $hash = $row["password"];
                if (password_verify($password, $hash)) {
                    $_SESSION["username"] = $row["username"];
                    header("Location: admin_panel.php");
                }
                else{
                    echo "Password is incorrect";
                }
            }
            else{
                "Username is not registered";
            }
        }
        else{
            echo "Please enter Username and password!";
        }
    }
?>
<?php
    include("footer.html"); 
    mysqli_close($conn); //last of all code
?>