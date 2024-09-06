<?php
    include("database.php");
    session_start();
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
    <h2>Registration Page</h2>
    <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"])?>" method="post">
            <div>
                <label for="name">Your Name:</label>
                <input type="text" name="name" id="name">
            </div>
            <div>
                <label for="email">Email:</label>
                <input type="text" name="email" id="email">
            </div>
            <div>
                <label for="password">Password:</label>
                <input type="password" name="password" id="password">
            </div>

            <div>
                <input type="submit" value="Register" name="reg">
            </div>
            <div>Already have an account? <a href="index.php">Login</a></div>
    </form>
</body>
</html>

<?php
    if($_SERVER["REQUEST_METHOD"]=="POST"){
        if (!empty($_POST["name"]) && !empty($_POST["email"]) && !empty($_POST["password"]) && !empty($_POST["re_password"])){
            $name=filter_input(INPUT_POST, "name", FILTER_SANITIZE_SPECIAL_CHARS);

            $email= filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
            $email= filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL); //returns false if not valid

            $password=filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);
            $re_password=filter_input(INPUT_POST, "re_password", FILTER_SANITIZE_SPECIAL_CHARS);

            $hash= password_hash($password, PASSWORD_DEFAULT);
            $password_pattern = "/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_]).+$/";

            if (!($email)){
                echo "Email is not valid<br>";
            }

            elseif(!($password)){
                echo "Password is not valid<br>";
            }

            elseif (!preg_match($password_pattern, $password)) {
            echo "Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.<br>";
            }
            
            elseif(!($re_password)){
                echo "Re-entered Password is not valid<br>";
            }
            elseif($password!=$re_password){
                echo "Passwords do not match";
            }
            else{

                // Split the full name into parts based on spaces
                $nameParts = explode(" ", $name);

                // The first part is the first name
                $firstName = $nameParts[0];

                // Check if there is a second part for the middle name
                if (isset($nameParts[1])) {
                    $middleName = $nameParts[1];
                } else {
                    $middleName = ""; // If not, leave the middle name empty
                }

                // Combine any remaining parts as the last name
                $lastName = ""; // Initialize last name as empty
                if (count($nameParts) > 2) {
                    for ($i = 2; $i < count($nameParts); $i++) {
                        $lastName .= $nameParts[$i] . " "; // Add each part to the last name
                    }
                $lastName = trim($lastName); // Remove any extra spaces at the end



                $sql = "INSERT INTO `user` (`FName`, `MName`, `LName`, `Email`, `Password`) VALUES ('$firstName', '$middleName', '$lastName', '$email', '$hash');";

                try{
                    mysqli_query($conn, $sql);
                    $user_id = mysqli_insert_id($conn); // Get the ID of the user that was just inserted
                    $_SESSION["user_id"] = $user_id;
                    header("Location: profile.php");
                }
                catch(mysqli_sql_exception){
                    echo "Email is already registered";
                }
                
            }            
        }
    }
        else{
            echo "Username, Email and Password cannot be empty!";
        }
    }
?>
<?php
    include("footer.html");
    mysqli_close($conn);
?>