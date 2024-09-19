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

    <a href="edit_author_profile.php">Edit Profile</a>
    <a href="search.php">Search</a>
    <a href="genre_browse.php">Browse Genre</a>
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
            echo $row["fname"]." ";
        }
        if (!empty($row["mname"])) {
            echo $row["mname"]." ";
        }
        if (!empty($row["lname"])) {
            echo $row["lname"]."<br>";
        }
        if (!empty($row["user_id"])) {
            echo "Author_id: ";
            echo $row["user_id"]."<br>";
        }
        if (!empty($row["date_of_birth"])) {
            echo "Date of birth: ";
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
            echo "Authors Email: ";
            echo $row["email"]."<br>";
        }
        $imagePath=$row["profile_picture"];
        if (!empty($row["profile_picture"])){
            $imagePath=$row["profile_picture"];
        }
        else{
            $imagePath = 'dp\person-circle.svg';
        }
        $sql = "SELECT * from author where author_id='$_SESSION[user_id]';";


        $result=mysqli_query($conn, $sql); //returns object
        if (mysqli_num_rows($result)>0){ //if any row exixst with that user_id
            $det = mysqli_fetch_assoc($result); 
        }
        if (!empty($det["biography"])) {
            echo "Bio: ";
            echo $det["biography"]."<br>";
        }
        echo "Joined betterreads on: ";
        echo  $row["joining_date"]."<br>";
        if (!empty($det["personal_website"])) {
            // Ensure the website URL starts with 'http://' or 'https://'
            $url = $det["personal_website"];
            if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
                $url = "https://" . $url;
            }
        
            // Display as a styled clickable link
            echo "<div style='font-size: 16px; margin-top: 10px;'>
                    <a href='" . htmlspecialchars($url) . "' target='_blank' style='text-decoration: none; color: #3498db; font-weight: bold;'>
                    Personal Website
                    </a>
                  </div><br>";
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
   $author_id = $_SESSION['user_id'];

   // Construct the SQL query using all columns including the new ones
   $sql = "SELECT b.isbn, b.title, b.author_name, b.publish_date, b.pages, b.description, b.format, 
                  b.purchase_link, b.publisher, b.language, b.cover
           FROM book b
           INNER JOIN author_writes_book awb ON b.isbn = awb.isbn
           WHERE awb.author_id = '$author_id'";
   
   $result = mysqli_query($conn, $sql);
   
   if ($result && mysqli_num_rows($result) > 0) {
       echo "<h2>Books by This Author</h2>";
       echo "<div style='display: flex; flex-wrap: wrap; gap: 20px;'>";
       
       while ($book = mysqli_fetch_assoc($result)) {
           $isbn = $book['isbn'];
           $book_title = htmlspecialchars($book['title']);
           $publish_date = $book['publish_date'];
           $cover_image = !empty($book['cover']) ? $book['cover'] : 'path/to/default/book/cover.jpg';
           
           echo "<div style='width: 200px; text-align: center;'>";
           echo "<a href='book.php?isbn=$isbn' style='text-decoration: none; color: inherit;'>";
           echo "<img src='" . htmlspecialchars($cover_image) . "' alt='$book_title' style='width: 150px; height: 200px; object-fit: cover;'><br>";
           echo "<strong>$book_title</strong><br>";
           echo "Published: $publish_date<br>";
           echo "</a>";
           echo "</div>";
       }
       
       echo "</div>";
   } else {
       echo "<p>No books found for this author.</p>";
   }
    include("footer.html");
    mysqli_close($conn);
?>
