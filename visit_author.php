<?php
include("database.php");
session_start();
include("header.html");
?>
<html><link rel="stylesheet" href="visitauthor.css"></html>

<?php
// Function to check if user is an author
function is_author($conn, $user_id) {
    $sql = "SELECT * FROM author WHERE author_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0;
}

// Determine the correct profile link for the current user
if (isset($_SESSION['user_id'])) {
    if (is_author($conn, $_SESSION['user_id'])) {
        $profile_link = "author_profile.php";
        $profile_text = "My Author Profile";
    } else {
        $profile_link = "view_user_profile.php?user_id=" . $_SESSION['user_id'];
        $profile_text = "My Reader Profile";
    }
} else {
    $profile_link = "login.php";
    $profile_text = "Login";
}

// Check if an author is selected
if (isset($_GET['author_id'])) {
    $author_id = intval($_GET['author_id']);
    $visitor_id = $_SESSION['user_id']; // Assuming you store the logged-in user ID in session

    // Handle the follow/unfollow button submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $follower_id = intval($_POST['follower_id']);
        $followed_id = intval($_POST['followed_id']);

        if (isset($_POST['follow'])) {
            // Ensure that the visitor cannot follow themselves
            if ($follower_id !== $followed_id) {
                // Check if the follower is already following the user
                $check_stmt = $conn->prepare("SELECT * FROM user_follows_user WHERE follower_id = ? AND followed_id = ?");
                $check_stmt->bind_param("ii", $follower_id, $followed_id);
                $check_stmt->execute();
                $result = $check_stmt->get_result();

                if ($result->num_rows === 0) {
                    // Insert follow relationship
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
            // Ensure that the visitor cannot unfollow themselves
            if ($follower_id !== $followed_id) {
                // Delete the follow relationship
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

    // Fetch author details from the database
    $stmt = $conn->prepare("SELECT *
                            FROM user u
                            INNER JOIN author a ON u.user_id = a.author_id
                            WHERE u.user_id = ?");
    $stmt->bind_param("i", $author_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $full_name = trim("{$row["fname"]} {$row["mname"]} {$row["lname"]}");
        echo "<h2>{$full_name}</h2>";
        echo "<img height=200 src='" . (!empty($row["profile_picture"]) ? $row["profile_picture"] : "dp/person-circle.svg") . "' alt='Profile Picture'>";

        if (!empty($row["joining_date"])) {
            echo "<p>Joining Date: {$row["joining_date"]}</p>";
        }

        if (!empty($row["gender"])) {
            echo "<p>Gender: {$row["gender"]}</p>";
        }

        if (!empty($row["country"])) {
            echo "<p>Country: {$row["country"]}</p>";
        }
        if (!empty($row["biography"])) {
            echo "<p>Biography: {$row["biography"]}</p>";
        }
        if (!empty($row["date_of_birth"])) {
            echo "<p>Date of Birth: {$row["date_of_birth"]}</p>";
        }

        // Check if the visitor is already following the author
        $follow_stmt = $conn->prepare("SELECT * FROM user_follows_user WHERE follower_id = ? AND followed_id = ?");
        $follow_stmt->bind_param("ii", $visitor_id, $author_id);
        $follow_stmt->execute();
        $follow_result = $follow_stmt->get_result();

        if ($follow_result->num_rows === 0 && $visitor_id != $author_id) {
            // If not following and visitor is not viewing their own profile, show the follow button
            echo "<form method='POST'>
                    <input type='hidden' name='follower_id' value='$visitor_id'>
                    <input type='hidden' name='followed_id' value='$author_id'>
                    <button type='submit' name='follow'>Follow</button>
                  </form>";
        } elseif ($follow_result->num_rows > 0 && $visitor_id != $author_id) {
            // If already following, show the unfollow button
            echo "<form method='POST'>
                    <input type='hidden' name='follower_id' value='$visitor_id'>
                    <input type='hidden' name='followed_id' value='$author_id'>
                    <button type='submit' name='unfollow'>Unfollow</button>
                  </form>";
        } elseif ($visitor_id == $author_id) {
            echo "<p>This is your own profile.</p>";
        } else {
            echo "<p>Following</p>";
        }

        $follow_stmt->close();
    } else {
        echo "Author not found.";
    }

    $stmt->close();
} else {
    echo "No author selected.";
}

$conn->close();
?>

<?php include("footer.html"); ?>