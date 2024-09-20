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
    <title>Author Profile</title>
    <link rel="stylesheet" href="authorprofilestyle.css">
</head>
<body>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <input type="submit" value="Logout">
    </form>

    <a href="edit_author_profile.php">Edit Profile</a>
    <a href="search.php">Search</a>
    <a href="genre_browse.php">Browse Genre</a>

    <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            session_destroy();
            header('Location: index.php');
        }

        $sql = "SELECT * FROM user WHERE user_id='$_SESSION[user_id]';";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            
            // Display profile picture at the top
            $imagePath = !empty($row["profile_picture"]) ? $row["profile_picture"] : 'dp/person-circle.svg';
            echo "<div class='profile-info'>";
            echo "<img src='" . htmlspecialchars($imagePath) . "' alt='Profile Picture' width='200' height='200' style='border-radius: 50%; margin-bottom: 20px;'>";

            // Display user information
            echo "<h1>" . htmlspecialchars($row["fname"] . " " . $row["mname"] . " " . $row["lname"]) . "</h1>";

            if (!empty($row["user_id"])) {
                echo "<p><strong>Author ID:</strong> " . htmlspecialchars($row["user_id"]) . "</p>";
            }
            if (!empty($row["date_of_birth"])) {
                echo "<p><strong>Date of Birth:</strong> " . htmlspecialchars($row["date_of_birth"]) . "</p>";
            }
            if (!empty($row["country"])) {
                echo "<p><strong>Country:</strong> " . htmlspecialchars($row["country"]) . "</p>";
            }
            if (!empty($row["gender"])) {
                echo "<p><strong>Gender:</strong> " . htmlspecialchars($row["gender"]) . "</p>";
            }
            if (!empty($row["email"])) {
                echo "<p><strong>Email:</strong> " . htmlspecialchars($row["email"]) . "</p>";
            }

            // Fetch and display author biography
            $sql = "SELECT * FROM author WHERE author_id='$_SESSION[user_id]';";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                $det = mysqli_fetch_assoc($result);
                if (!empty($det["biography"])) {
                    echo "<p><strong>Bio:</strong> " . ($det["biography"]) . "</p>";
                }
                echo "<p><strong>Joined betterreads on:</strong> " . htmlspecialchars($row["joining_date"]) . "</p>";
                if (!empty($det["personal_website"])) {
                    $url = $det["personal_website"];
                    if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
                        $url = "https://" . $url;
                    }
                    echo "<div style='font-size: 16px; margin-top: 10px;'>
                            <a href='" . htmlspecialchars($url) . "' target='_blank' style='text-decoration: none; color: #3498db; font-weight: bold;'>
                            Personal Website
                            </a>
                          </div><br>";
                }
            }
            echo "</div>";
        } else {
            echo "Email not registered";
        }
    ?>

    <?php
        $author_id = $_SESSION['user_id'];
        $sql = "SELECT b.isbn, b.title, b.author_name, b.publish_date, b.pages, b.description, b.format, 
                        b.purchase_link, b.publisher, b.language, b.cover
                 FROM book b
                 INNER JOIN author_writes_book awb ON b.isbn = awb.isbn
                 WHERE awb.author_id = '$author_id'";
        
        $result = mysqli_query($conn, $sql);
        
        if ($result && mysqli_num_rows($result) > 0) {
            echo "<h2>Books by This Author</h2>";
            echo "<div class='book-list'>";
            
            while ($book = mysqli_fetch_assoc($result)) {
                $isbn = $book['isbn'];
                $book_title = htmlspecialchars($book['title']);
                $publish_date = $book['publish_date'];
                $cover_image = !empty($book['cover']) ? $book['cover'] : 'path/to/default/book/cover.jpg';
                
                echo "<div class='book-item'>";
                echo "<a href='book.php?isbn=$isbn' style='text-decoration: none; color: inherit;'>";
                echo "<img src='" . htmlspecialchars($cover_image) . "' alt='$book_title'><br>";
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
</body>
</html>
