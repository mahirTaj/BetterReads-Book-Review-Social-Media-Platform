<?php
    include("database.php")
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action= "<?php htmlspecialchars($_SERVER["PHP_SELF"])?>" method = "post">
        <h2> Register to pharmacy management system </h2> 
        Username:<br>
        <input type = "text" name= "Username" placeholder="Enter your Username"><br>
        PASSWORD<br>
        <input type="password" name="password" placeholder="Create a strong password" ><br>
        <input type = "submit" name= "submit" value= "Regester">   
        
    </form>
    
</body>
</html>

<?php
    if ($_SERVER["REQUEST_METHOD"]=="POST"){

        $Username =  filter_input(INPUT_POST, "Username", FILTER_SANITIZE_SPECIAL_CHARS);
        $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);

        if(empty($Username)){
            echo"Please enter a username";
        }
        elseif(empty($password)){
            echo"Please enter password";
        }
        else{
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $sql= "INSERT INTO admin(username, password) VALUES ('$Username', '$hash')";
            try{
                mysqli_query($conn, $sql);
                echo"You are now regestered";
                header("Location: Admin_pannel.php") ;
            }
            catch(mysqli_sql_exception){
                echo "The username already exists choose another one";
            }
            
        }
        
    }
?>