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
    <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"])?>" method="post">
        <input type="submit" value="logout">
    </form>

    <a href="edit_reader_profile.php">Edit Profile</a>
    <a href="search.php">Search Books</a>
</body>
</html>



<?php
    if ($_SERVER['REQUEST_METHOD']=='POST'){
        session_destroy();
        header('Location: index.php');
    }
?>


<?php
    // echo "<br>Hi {$_SESSION["username"]} this is your homepage<br>";
    
    $sql = "SELECT * from user where user_id='$_SESSION[user_id]';";


    $result=mysqli_query($conn, $sql); //returns object
    if (mysqli_num_rows($result)>0){ //if any row exixst with that user_id
        $row = mysqli_fetch_assoc($result); //returns whole row as dictionary

        if (!empty($row["fname"])) {
            echo "Reader name: ";
            echo $row["fname"]." ";
        }
        if (!empty($row["mname"])) {
            echo $row["mname"]." ";
        }
        if (!empty($row["lname"])) {
            echo $row["lname"]."<br>";
        }
        if (!empty($row["date_of_birth"])) {
            echo "Date of birth : ";
            echo $row["date_of_birth"]."<br>";
        }
        if (!empty($row["country"])) {
            echo "Country: ";
            echo $row["country"]."<br>";
        }
        if (!empty($row["gender"])) {
            echo "Gender: ";
            echo $row["gender"]."<br>";
        }
        if (!empty($row["email"])) {
            echo "Email: ";
            echo $row["email"]."<br>";
        }
        echo "Using Betterreads since- ";
        echo $row["joining_date"]."<br>";
        $imagePath=$row["profile_picture"];
        if (!empty($row["profile_picture"])){
            $imagePath=$row["profile_picture"];
        }
        else{
            $imagePath = 'dp\person-circle.svg';
        }
        $sql = "SELECT * from reader where reader_id='$_SESSION[user_id]';";


        $result=mysqli_query($conn, $sql); //returns object
        if (mysqli_num_rows($result)>0){ //if any row exixst with that user_id
            $det = mysqli_fetch_assoc($result); 
        }
        if (!empty($det["about_me"])) {
            echo "<section>";
            echo "<h3>About Me</h3>";
            echo "<blockquote>" . htmlspecialchars($det["about_me"]) . "</blockquote>";
            echo "</section>";
        }
        
        // Social Platform Link section
        if (!empty($det["social_platform"]) && !empty($det["social_link"])) {
            // Function to get Unicode icon for social media platforms
            function getSocialIcon($platform) {
                $icons = [
                    'facebook' => '📘', // Blue book for Facebook
                    'twitter' => '🐦',  // Bird for Twitter
                    'linkedin' => '💼', // Briefcase for LinkedIn
                    'instagram' => '📷', // Camera for Instagram
                    'youtube' => '🎥',  // Video camera for YouTube
                    'github' => '🐱',   // Cat face for GitHub
                    'default' => '🌐'   // Globe for other platforms
                ];
                return isset($icons[strtolower($platform)]) ? $icons[strtolower($platform)] : $icons['default'];
            }
        
            $platform = htmlspecialchars(ucfirst($det["social_platform"]));
            $link = htmlspecialchars($det["social_link"]);
            $icon = getSocialIcon($det["social_platform"]);
        
            echo "<div>";
            echo "<h3>Connect with me on Social Media</h3>";
            echo "<table border='1' cellpadding='10'>";
            echo "<tr><td align='center'>";
            echo "<a href='$link' target='_blank' rel='noopener noreferrer' style='text-decoration: none;'>";
            echo "<font size='5'>$icon $platform</font><br>";
            echo "<font size='4'>▶ Visit My Profile ◀</font>";
            echo "</a>";
            echo "</td></tr>";
            echo "</table>";
            echo "</div>";
        }
    }
    else{
        "Email not registered";
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <img src="<?php echo htmlspecialchars($imagePath); ?>" alt="Profile Picture" width="200" height="200">
</body>
</html>
<?php
    include("footer.html");
    mysqli_close($conn);
?>