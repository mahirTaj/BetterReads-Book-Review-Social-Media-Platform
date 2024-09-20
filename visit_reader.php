<?php
    include("database.php");
    session_start();
    include("header.html");
?>
<html><link rel="stylesheet" href="visitreader.css"></html>
<?php


    // Enable error reporting for debugging
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    // Check if a reader is selected
    if (isset($_GET['reader_id'])) {
        $reader_id = intval($_GET['reader_id']);
        $visitor_id = $_SESSION['user_id']; // Assuming you store the logged-in user ID in session

        // Handle the follow/unfollow button submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $follower_id = intval($_POST['follower_id']);
            $followed_id = intval($_POST['followed_id']);

            if (isset($_POST['follow'])) {
                if ($follower_id !== $followed_id) {
                    $check_stmt = $conn->prepare("SELECT * FROM user_follows_user WHERE follower_id = ? AND followed_id = ?");
                    $check_stmt->bind_param("ii", $follower_id, $followed_id);
                    $check_stmt->execute();
                    $result = $check_stmt->get_result();

                    if ($result->num_rows === 0) {
                        $stmt = $conn->prepare("INSERT INTO user_follows_user (follower_id, followed_id) VALUES (?, ?)");
                        $stmt->bind_param("ii", $follower_id, $followed_id);

                        if ($stmt->execute()) {
                            echo "<p>You are now following this user!</p>";
                        } else {
                            echo "<p>Error: Could not follow user.</p>";
                        }
                        $stmt->close();
                    } else {
                        echo "<p>You are already following this user.</p>";
                    }

                    $check_stmt->close();
                } else {
                    echo "<p>You cannot follow yourself.</p>";
                }
            }

            if (isset($_POST['unfollow'])) {
                if ($follower_id !== $followed_id) {
                    $stmt = $conn->prepare("DELETE FROM user_follows_user WHERE follower_id = ? AND followed_id = ?");
                    $stmt->bind_param("ii", $follower_id, $followed_id);

                    if ($stmt->execute()) {
                        echo "<p>You have unfollowed this user.</p>";
                    } else {
                        echo "<p>Error: Could not unfollow user.</p>";
                    }
                    $stmt->close();
                } else {
                    echo "<p>You cannot unfollow yourself.</p>";
                }
            }
        }

        // Fetch reader details from the database
        $stmt = $conn->prepare("SELECT *
                                FROM user u
                                INNER JOIN reader r ON u.user_id = r.reader_id
                                WHERE u.user_id = ?");
        $stmt->bind_param("i", $reader_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $full_name = trim("{$row["fname"]} {$row["mname"]} {$row["lname"]}");
            echo "<h2>{$full_name}</h2>";
            echo "<img height=10 src='" . (!empty($row["profile_picture"]) ? $row["profile_picture"] : "dp/person-circle.svg") . "' alt='Profile Picture'>";

            if (!empty($row["joining_date"])) {
                echo "<p>Joining Date: {$row["joining_date"]}</p>";
            }

            if (!empty($row["gender"])) {
                echo "<p>Gender: {$row["gender"]}</p>";
            }

            if (!empty($row["country"])) {
                echo "<p>Country: {$row["country"]}</p>";
            }

            if (!empty($row["date_of_birth"])) {
                echo "<p>Date of Birth: {$row["date_of_birth"]}</p>";
            }

            // Check if the visitor is already following the reader
            $follow_stmt = $conn->prepare("SELECT * FROM user_follows_user WHERE follower_id = ? AND followed_id = ?");
            $follow_stmt->bind_param("ii", $visitor_id, $reader_id);
            $follow_stmt->execute();
            $follow_result = $follow_stmt->get_result();

            if ($follow_result->num_rows === 0 && $visitor_id != $reader_id) {
                echo "<form method='POST'>
                        <input type='hidden' name='follower_id' value='$visitor_id'>
                        <input type='hidden' name='followed_id' value='$reader_id'>
                        <button type='submit' name='follow'>Follow</button>
                      </form>";
            } elseif ($follow_result->num_rows > 0 && $visitor_id != $reader_id) {
                echo "<form method='POST'>
                        <input type='hidden' name='follower_id' value='$visitor_id'>
                        <input type='hidden' name='followed_id' value='$reader_id'>
                        <button type='submit' name='unfollow'>Unfollow</button>
                      </form>";
            } elseif ($visitor_id == $reader_id) {
                echo "<p>This is your own profile.</p>";
            } else {
                echo "<p>Following</p>";
            }

            $follow_stmt->close();
        } else {
            echo "Reader not found.";
        }

        $stmt->close();

        // Display reader's books and their status
        $sql = "SELECT ubrs.ISBN, ubrs.reading_status, 
                       b.title, b.cover
                FROM user_books_read_status ubrs
                JOIN book b ON ubrs.ISBN = b.isbn
                WHERE ubrs.reader_id = '$reader_id'
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
            echo "<p style='font-family: Arial, sans-serif; color: #666;'>No books found.</p>";
        }
    } else {
        echo "No reader selected.";
    }

    $conn->close();
    include("footer.html");
?>
