<?php
include("database.php");
session_start();
include("header.html");

// Check if user_id is provided in the URL
if (!isset($_GET['user_id'])) {
    echo "User ID not provided.";
    exit();
}

$viewed_user_id = $_GET['user_id'];
$current_user_id = $_SESSION['user_id'];

// Fetch user details
$sql = "SELECT u.*, r.about_me FROM user u LEFT JOIN reader r ON u.user_id = r.reader_id WHERE u.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $viewed_user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    echo "User not found.";
    exit();
}

// Check if the current user is following the viewed user
$sql = "SELECT * FROM user_follows_user WHERE follower_id = ? AND followed_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $current_user_id, $viewed_user_id);
$stmt->execute();
$result = $stmt->get_result();
$is_following = $result->num_rows > 0;

// Handle follow/unfollow action
if (isset($_POST['follow_action'])) {
    if ($_POST['follow_action'] == 'follow') {
        $sql = "INSERT INTO user_follows_user (follower_id, followed_id) VALUES (?, ?)";
    } else {
        $sql = "DELETE FROM user_follows_user WHERE follower_id = ? AND followed_id = ?";
    }
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $current_user_id, $viewed_user_id);
    $stmt->execute();
    $is_following = ($_POST['follow_action'] == 'follow');
}

// Function to check if a user is an author
function is_author($conn, $user_id) {
    $sql = "SELECT * FROM author WHERE author_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0;
}

// Determine the correct profile link for the current user
if (is_author($conn, $current_user_id)) {
    $profile_link = "author_profile.php";
    $profile_text = "My Author Profile";
} else {
    $profile_link = "view_user_profile.php?user_id=" . $current_user_id;
    $profile_text = "My Reader Profile";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($user['fname'] . ' ' . $user['lname']); ?>'s Profile</title>
    <style>
        .profile-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .profile-picture {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            object-fit: cover;
        }
        .profile-info {
            margin-top: 20px;
        }
        .follow-button {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <h1><?php echo htmlspecialchars($user['fname'] . ' ' . $user['lname']); ?>'s Profile</h1>
        <img src="<?php echo htmlspecialchars($user['profile_picture'] ?: 'dp/person-circle.svg'); ?>" alt="Profile Picture" class="profile-picture">
        
        <div class="profile-info">
            <p><strong>Date of birth</strong> <?php echo htmlspecialchars($user['date_of_birth']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
            <p><strong>Country:</strong> <?php echo htmlspecialchars($user['country']); ?></p>
            <p><strong>Joined:</strong> <?php echo htmlspecialchars($user['joining_date']); ?></p>
            <?php if (!empty($user['about_me'])): ?>
                <p><strong>About Me:</strong> <?php echo ($user['about_me']); ?></p>
            <?php endif; ?>
        </div>

        <?php if ($current_user_id != $viewed_user_id): ?>
            <form method="post" class="follow-button">
                <input type="hidden" name="follow_action" value="<?php echo $is_following ? 'unfollow' : 'follow'; ?>">
                <button type="submit"><?php echo $is_following ? 'Unfollow' : 'Follow'; ?></button>
            </form>
        <?php endif; ?>
    </div>
    <div style="font-family: Arial, sans-serif; font-size: 18px;">
        <a href="<?php echo $profile_link; ?>" style="text-decoration: none; color: #0066cc; margin-right: 20px;">
            &#128100; <?php echo $profile_text; ?>
        </a>
        <a href="index.php" style="text-decoration: none; color: #0066cc;">
            &#127968; Home page
        </a>
    </div>
</body>
</html>

<?php
include("footer.html");
mysqli_close($conn);
?>