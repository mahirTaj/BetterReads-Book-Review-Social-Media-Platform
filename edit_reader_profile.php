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
    <form action="<?PHP htmlspecialchars($_SERVER["PHP_SELF"])?>" method="post" enctype="multipart/form-data">
        <div>
            <label for="dp">Change Profile Photo:</label>
            <input type="file" name="file" id="dp">
        </div>

        <div>
            <label for="fname">First Name:</label>
            <input type="text" name="fname" id="fname">
        </div>
        <div>
            <label for="lname">Middle Name:</label>
            <input type="text" name="lname" id="fname">
        </div>
        <div>
            <label for="fname">Last Name:</label>
            <input type="text" name="fname" id="fname">
        </div>
        <div>
            <label for="dob">Date of Birth:</label>
            <input type="date" name="dob" id="dob">
        </div>
        <div>
            <label for="country">Country:</label>
            <select name="country" id="country">
            <?php
                $countries = array("USA", "Bangladesh","Canada", "UK", "Australia", "Germany", "France", "Italy", "Spain", "Japan", "China", "Brazil", "India", "Mexico", "Russia", "South Korea", "Netherlands");
                foreach ($countries as $country) {
                echo "<option value='$country'>$country</option>";
                }
            ?>
            </select>
        </div>

        <div>
            <label for="gender">Gender:</label>
            <select name="gender" id="gender">
            <option value="male">Male</option>
            <option value="female">Female</option>
            <option value="other">Other</option>
            </select>
        </div>
        <div>
            <label for="email">Email:</label>
            <input type="email" name="email" id="email">
        </div>
        <div>
            <label for="password">Current Password:</label>
            <input type="password" name="password" id="password">
        </div>
        <div>
            <label for="new_password">New Password:</label>
            <input type="password" name="new_password" id="new_password">
        </div>
        <div>
            <label for="confirm_password">Confirm Password:</label>
            <input type="password" name="confirm_password" id="confirm_password">
        </div>

        <div><input type="submit" value="Save Changes"></div>
    </form>
</body>
</html>


<?php
    if ($_SERVER["REQUEST_METHOD"]){
        if (isset($_FILES["file"])){
            // photo
            $file = $_FILES["file"]; //dictionary

            $fileName= $_FILES["file"]['name'];
            $fileTmpName= $_FILES["file"]['tmp_name']; // tempporary location of the pic
            $fileSize= $_FILES["file"]['size'];
            $fileError= $_FILES["file"]['error']; //0 means no error
            $fileType= $_FILES["file"]['type']; // we will not use this


            $fileExt = explode(".", $fileName); //array of name and ext
            $fileActualExt= strtolower(end($fileExt)); //extention find

            $allowed= array('jpg', 'jpeg', 'png'); //allowed extention
            
            if (in_array($fileActualExt, $allowed)){ //check ext
                if ($fileError==0){ //check error
                    if ($fileSize<5000000){ //check size
                        $fileNameNew = uniqid('', true).".".$fileActualExt; //generate unique name before ext
                        $fileDestination = 'dp/'.$fileNameNew; //new location of the pic
                        move_uploaded_file($fileTmpName, $fileDestination); //move the file

                        
                        $sql= "UPDATE `user` SET `profile_picture` = '$fileDestination' WHERE `user`.`user_id` = '$_SESSION[user_id]'";

                        try{
                            mysqli_query($conn, $sql);
                            echo "Profile picture changed successfully";
                        }
                        catch(mysqli_sql_exception){
                            echo "Profile picture change failed";
                        }

                    }
                    else{
                        echo "Your file is too big";
                    }
                }
                else{
                    echo "There was an error uploading your file!";
                }
            }
            else{
                echo "Only jpeg, jpg and png file is supported";
            }
        }   
    }
    if (isset($_POST["fname"])){
        $fname=filter_input(INPUT_POST, "fname", FILTER_SANITIZE_SPECIAL_CHARS);
        $sql = "update user set fname='$fname' where user_id='$_SESSION[user_id]';";
    }
    if (isset($_POST["mname"])){
        $mname=filter_input(INPUT_POST, "mname", FILTER_SANITIZE_SPECIAL_CHARS);
        $sql = "update user set mname='$mname' where user_id='$_SESSION[user_id]';";
    }
    if (isset($_POST["lname"])){
        $lname=filter_input(INPUT_POST, "lname", FILTER_SANITIZE_SPECIAL_CHARS);
        $sql = "update user set lname='$lname' where user_id='$_SESSION[user_id]';";
    }
    if (isset($_POST["dob"])){
        $dob=filter_input(INPUT_POST, "dob", FILTER_SANITIZE_SPECIAL_CHARS);
        $sql = "update user set dob='$dob' where user_id='$_SESSION[user_id]';";
    }
    if (isset($_POST["country"])){
        $country=filter_input(INPUT_POST, "country", FILTER_SANITIZE_SPECIAL_CHARS);
        $sql = "update user set country='$country' where user_id='$_SESSION[user_id]';";
    }
    if (isset($_POST["gender"])){
        $gender=filter_input(INPUT_POST, "gender", FILTER_SANITIZE_SPECIAL_CHARS);
        $sql = "update user set gender='$gender' where user_id='$_SESSION[user_id]';";
    }

    if (isset($_POST["email"])){
        $email=filter_input(INPUT_POST, "email", FILTER_SANITIZE_SPECIAL_CHARS);
        $email=filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL);
        $sql = "update user set email='$email' where user_id='$_SESSION[user_id]';";
    }
    

    if (isset($_POST["password"]) && isset($_POST["new_password"]) && isset($_POST["confirm_password"])){
        $password=filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);
        $new_password=filter_input(INPUT_POST, "new_password", FILTER_SANITIZE_SPECIAL_CHARS);
        $confirm_password=filter_input(INPUT_POST, "confirm_password", FILTER_SANITIZE_SPECIAL_CHARS);

    $sql = "select * from user where user_id='$_SESSION[user_id]';";
    $result=mysqli_query($conn, $sql); //returns object

    if (mysqli_num_rows($result)>0){
        $row = mysqli_fetch_assoc($result); //returns next row as disctionary
        $hash = $row["password"];
        if (password_verify($password, $hash)) {
            if ($new_password==$confirm_password){
                if (!preg_match($password_pattern, $password)) {
                    echo "Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.<br>";
                }
                else{
                $hash= password_hash($new_password, PASSWORD_DEFAULT);
                $sql = "UPDATE `user` SET `Password` = '$hash' WHERE `user`.`user_id` = '$_SESSION[user_id]';";
                try{
                    mysqli_query($conn, $sql);
                    echo "Password changed successfully";
                }
                catch(mysqli_sql_exception){
                    echo "Password change failed";
                }
                }   
            }
            else{
                echo "Passwords do not match";
            }
                  
        }
    }
}
    
?>
<?php
    include("footer.html");
    mysqli_close($conn); //last of all code
?>