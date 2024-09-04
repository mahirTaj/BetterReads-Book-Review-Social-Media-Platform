<?php
    include("database.php")
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pharmacy management system</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Handjet:wght@100..900&display=swap" rel="stylesheet">
</head>
<body >
    <h1>BOOK REVIEW SYSTEM</h1>
    <p1> Admin login </p>
    <form action="index.php" method="post">
        <label>Username:</label><br>
        <input type="text" name="username"><br>
        <label>Password:</label><br>
        <input type="password" name="password"><br>
        <br>
        <input type="submit" name = "login" value= "Sign in">  <br>
        <input type="submit" name="regester" value= "Regester new account"> <br>
    </form>
</body>
</html>
<?php
    if (isset($_POST["regester"])){
        header("Location: Admin_Regester.php");
    }
    elseif ($_SERVER["REQUEST_METHOD"]=="POST"){

        $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
        $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);

        if(empty($username)){
            echo"Please enter a username";
        }
        elseif(empty($password)){
            echo"Please enter password";
        }
        else{
            $sql= "SELECT * from Pharmacy_owners WHERE Username = '$username' ";
            $Data= mysqli_query($conn, $sql);
            try{
                $User = mysqli_fetch_assoc($Data);
                echo $User["Birth_date"] ;
                if(password_verify($password, $User["Password"])){
                    $_SESSION["User"] = $User;
                    echo"You are now logged in. <br>" ;   
                    header("Location: home.php");
 
                }
                else{
                    echo"Wrong password or username";
                }

            }
            catch(mysqli_sql_exception){
                echo "User not found please regester first";
            }
   
        }
        
    }