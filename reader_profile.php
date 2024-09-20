<?php
    include("database.php");
    session_start();
    include("header.html");

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        session_destroy();
        header('Location: index.php');
        exit();
    }

    // Fetch user data
    $sql = "SELECT * FROM user WHERE user_id='$_SESSION[user_id]';";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        // Determine the image path
        if (!empty($row["profile_picture"])) {
            $imagePath = $row["profile_picture"];
        } else {
            $imagePath = 'dp/person-circle.svg';
        }
    } else {
        echo "Email not registered";
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- meta tags -->
    <title>Reader Profile</title>
    <link rel="stylesheet" href="readerprofile.css">
</head>
<body>

    <!-- Logout Form -->
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <input type="submit" value="Logout">
    </form>

    <!-- Navigation Links -->
    <div class="profile-actions">
        <a href="edit_reader_profile.php">&#128100; &#9998; Edit Profile</a>
        <a href="search.php">&#128269; Search</a>
        <a href="genre_browse.php">Browse Genre</a>
    </div>

    <!-- Profile Container -->
    <div class="profile-container">
        <!-- Profile Picture -->
        <img src="<?php echo htmlspecialchars($imagePath); ?>" alt="Profile Picture">

        <!-- Profile Information -->
        <div class="profile-info">
            <?php
                if (!empty($row)) {
                    echo "<h2>";
                    if (!empty($row["fname"])) {
                        echo $row["fname"] . " ";
                    }
                    if (!empty($row["mname"])) {
                        echo $row["mname"] . " ";
                    }
                    if (!empty($row["lname"])) {
                        echo $row["lname"];
                    }
                    echo "</h2>";

                    if (!empty($row["date_of_birth"])) {
                        echo "<p>Date of Birth: " . $row["date_of_birth"] . "</p>";
                    }
                    if (!empty($row["country"])) {
                        echo "<p>Country: " . $row["country"] . "</p>";
                    }
                    if (!empty($row["gender"])) {
                        echo "<p>Gender: " . $row["gender"] . "</p>";
                    }
                    if (!empty($row["email"])) {
                        echo "<p>Email: " . $row["email"] . "</p>";
                    }
                    echo "<p>Using Betterreads since: " . $row["joining_date"] . "</p>";
                }

                // Fetch additional reader details
                $sql = "SELECT * FROM reader WHERE reader_id='$_SESSION[user_id]';";
                $result = mysqli_query($conn, $sql);
                if (mysqli_num_rows($result) > 0) {
                    $det = mysqli_fetch_assoc($result);

                    if (!empty($det["about_me"])) {
                        echo "<section>";
                        echo "<h3>About Me</h3>";
                        echo "<blockquote>" . ($det["about_me"]) . "</blockquote>";
                        echo "</section>";
                    }

                    // Social Platform Link section
                    if (!empty($det["social_platform"]) && !empty($det["social_link"])) {
                        // Function to get Unicode icon for social media platforms
                        function getSocialIcon($platform) {
                            $icons = [
                                'facebook' => '📘',
                                'twitter' => '🐦',
                                'linkedin' => '💼',
                                'instagram' => '📷',
                                'youtube' => '🎥',
                                'github' => '🐱',
                                'default' => '🌐'
                            ];
                            return isset($icons[strtolower($platform)]) ? $icons[strtolower($platform)] : $icons['default'];
                        }

                        $platform = htmlspecialchars(ucfirst($det["social_platform"]));
                        $link = htmlspecialchars($det["social_link"]);
                        $icon = getSocialIcon($det["social_platform"]);

                        echo "<div class='social-media'>";
                        echo "<h3>Connect with me on Social Media</h3>";
                        echo "<a href='$link' target='_blank' rel='noopener noreferrer'>";
                        echo "$icon $platform";
                        echo "</a>";
                        echo "</div>";
                    }
                }
            ?>
        </div>
    </div>

    <!-- My Books Section -->
    <div class="books-section">
        <?php
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
                echo "<h2>My Books</h2>";

                $current_status = '';
                while ($row = mysqli_fetch_assoc($result)) {
                    if ($current_status != $row['reading_status']) {
                        if ($current_status != '') {
                            echo "</div>"; // Close previous status div
                        }
                        $current_status = $row['reading_status'];
                        echo "<h3>" . ucfirst($current_status) . "</h3>";
                        echo "<div class='books-grid'>";
                    }

                    echo "<div class='book-item'>";
                    echo "<img src='" . htmlspecialchars($row['cover']) . "' alt='Cover of " . htmlspecialchars($row['title']) . "'>";
                    echo "<a href='book.php?isbn=" . htmlspecialchars($row['ISBN']) . "'>" . htmlspecialchars($row['title']) . "</a>";
                    echo "</div>";
                }
                echo "</div>"; // Close last status div
            } else {
                echo "<p class='no-books'>You haven't added any books to your collection yet.</p>";
            }
        ?>
    </div>

    <?php
        include("footer.html");
        mysqli_close($conn);
    ?>
</body>
</html>
