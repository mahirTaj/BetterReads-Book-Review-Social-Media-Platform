<?php
    include("database.php");
    session_start();
    include("header.html");

    // Check if a reader is selected
    if (isset($_GET['author_id'])) {
        $reader_id = intval($_GET['author_id']);
        $visitor_id = $_SESSION['user_id']; // Assuming you store the logged-in user ID in session

        // Handle the follow button submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $follower_id = intval($_POST['follower_id']);
            $followed_id = intval($_POST['followed_id']);

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

        // Fetch reader details from the database
        $stmt = $conn->prepare("SELECT *
                                FROM user u
                                INNER JOIN author a ON u.user_id = a.author_id
                                WHERE u.user_id = ?");
        $stmt->bind_param("i", $reader_id);
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

            if (!empty($row["date_of_birth"])) {
                echo "<p>Date of Birth: {$row["date_of_birth"]}</p>";
            }

            // Check if the visitor is already following the reader
            $follow_stmt = $conn->prepare("SELECT * FROM user_follows_user WHERE follower_id = ? AND followed_id = ?");
            $follow_stmt->bind_param("ii", $visitor_id, $reader_id);
            $follow_stmt->execute();
            $follow_result = $follow_stmt->get_result();

            if ($follow_result->num_rows === 0 && $visitor_id != $reader_id) {
                // If not following and visitor is not viewing their own profile, show the follow button
                echo "<form method='POST'>
                        <input type='hidden' name='follower_id' value='$visitor_id'>
                        <input type='hidden' name='followed_id' value='$reader_id'>
                        <button type='submit'>Follow</button>
                      </form>";
            } elseif ($visitor_id == $reader_id) {
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

<?php
    include("footer.html");
?>
