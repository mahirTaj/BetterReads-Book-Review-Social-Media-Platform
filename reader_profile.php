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

    <div style="font-family: Arial, sans-serif; font-size: 18px;">
  <a href="edit_reader_profile.php" style="text-decoration: none; color: #0066cc; margin-right: 20px;">
    &#128100; &#9998; Edit Profile
  </a>
  <a href="search.php" style="text-decoration: none; color: #0066cc;">
    &#128269; Search
  </a>
  <a href="genre_browse.php">Browse Genre</a>
</div>
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
        // Fetch user's books and their status
        $user_id = $_SESSION['user_id'];
        $sql = "SELECT ubrs.ISBN, ubrs.reading_status, 
                       b.title, b.cover
                FROM user_books_read_status ubrs
                JOIN book b ON ubrs.ISBN = b.isbn
                WHERE ubrs.reader_id = '$user_id'
                ORDER BY ubrs.reading_status, b.title";
        
        $result = mysqli_query($conn, $sql);
        
        if (mysqli_num_rows($result) > 0) {
            echo "<h2 style='font-family: Arial, sans-serif; color: #333;'>My Books</h2>";
            
            $current_status = '';
            while ($row = mysqli_fetch_assoc($result)) {
                if ($current_status != $row['reading_status']) {
                    if ($current_status != '') {
                        echo "</div>"; // Close previous status div
                    }
                    $current_status = $row['reading_status'];
                    echo "<h3 style='font-family: Arial, sans-serif; color: #0066cc; margin-top: 20px;'>" . ucfirst($current_status) . "</h3>";
                    echo "<div style='display: flex; flex-wrap: wrap; gap: 20px;'>";
                }
                
                echo "<div style='width: 120px; text-align: center; margin-bottom: 20px;'>";
                echo "<img src='" . htmlspecialchars($row['cover']) . "' alt='Cover of " . htmlspecialchars($row['title']) . "' style='width: 100px; height: 150px; object-fit: cover; margin-bottom: 5px;'><br>";
                echo "<a href='book.php?isbn=" . htmlspecialchars($row['ISBN']) . "' style='font-family: Arial, sans-serif; font-size: 14px; color: #333; text-decoration: none; display: block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;' title='" . htmlspecialchars($row['title']) . "'>" . htmlspecialchars($row['title']) . "</a>";
                echo "</div>";
            }
            echo "</div>"; // Close last status div
        } else {
            echo "<p style='font-family: Arial, sans-serif; color: #666;'>You haven't added any books to your collection yet.</p>";
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