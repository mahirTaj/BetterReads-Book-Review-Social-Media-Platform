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
    <title>Reader Profile</title>
    <link rel="stylesheet" href="reader-profile-style.css">
</head>
<body>

<div style="font-family: Arial, sans-serif; font-size: 18px;">
    <a href="edit_reader_profile.php" style="text-decoration: none; color: #0066cc; margin-right: 20px;">
        &#128100; &#9998; Edit Profile
    </a>
    <a href="search.php" style="text-decoration: none; color: #0066cc;">
        &#128269; Search
    </a>
</div>

<?php
    if ($_SERVER['REQUEST_METHOD']=='POST'){
        session_destroy();
        header('Location: index.php');
    }

    $user_id = $_SESSION['user_id'];

    // Query to count the followers
    $query = "SELECT COUNT(follower_id) AS follower_count FROM user_follows_user WHERE followed_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $follower_count = $row['follower_count'];
    
    // Query to count the following
    $query = "SELECT COUNT(followed_id) AS following_count FROM user_follows_user WHERE follower_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $following_count = $row['following_count'];
    
    // Check if the user clicked to show followers or following
    $show_followers = isset($_GET['show_followers']) && $_GET['show_followers'] == 1;
    $show_following = isset($_GET['show_following']) && $_GET['show_following'] == 1;
    
    // Query to get all the followers
    $followers = [];
    if ($show_followers) {
        $query_followers = "SELECT u.user_id, u.fname, u.lname, u.profile_picture, r.about_me 
                            FROM user_follows_user f 
                            JOIN user u ON f.follower_id = u.user_id 
                            LEFT JOIN reader r ON u.user_id = r.reader_id
                            WHERE f.followed_id = ?";
        $stmt_followers = $conn->prepare($query_followers);
        $stmt_followers->bind_param("i", $user_id);
        $stmt_followers->execute();
        $followers_result = $stmt_followers->get_result();
        while ($follower = $followers_result->fetch_assoc()) {
            $followers[] = $follower;
        }
    }
    
    // Query to get all the following
    $following = [];
    if ($show_following) {
        $query_following = "SELECT u.user_id, u.fname, u.lname, u.profile_picture, r.about_me 
                            FROM user_follows_user f 
                            JOIN user u ON f.followed_id = u.user_id 
                            LEFT JOIN reader r ON u.user_id = r.reader_id
                            WHERE f.follower_id = ?";
        $stmt_following = $conn->prepare($query_following);
        $stmt_following->bind_param("i", $user_id);
        $stmt_following->execute();
        $following_result = $stmt_following->get_result();
        while ($followed = $following_result->fetch_assoc()) {
            $following[] = $followed;
        }
    }
    
    // Fetch user details
    $sql = "SELECT * FROM user WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    
    // Fetch reader details
    $sql = "SELECT * FROM reader WHERE reader_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $reader = $result->fetch_assoc();
    
    $sql = "SELECT * from user where user_id='$_SESSION[user_id]';";

    $result=mysqli_query($conn, $sql);
    if (mysqli_num_rows($result)>0){
        $row = mysqli_fetch_assoc($result);
        $imagePath = !empty($row["profile_picture"]) ? $row["profile_picture"] : 'dp/person-circle.svg';
?>

<div class="profile-header">
    <img src="<?php echo htmlspecialchars($imagePath); ?>" alt="Profile Picture" class="profile-picture">
    <h1 class="profile-name">
        <?php 
        echo htmlspecialchars($row["fname"]);
        if (!empty($row["mname"])) echo " " . htmlspecialchars($row["mname"]);
        if (!empty($row["lname"])) echo " " . htmlspecialchars($row["lname"]);
        ?>
    </h1>
</div>

<div class="reader-info">
    <h2>Reader Information</h2>
    <?php
        if (!empty($row["date_of_birth"])) {
            echo "<p>Date of birth: " . htmlspecialchars($row["date_of_birth"]) . "</p>";
        }
        if (!empty($row["country"])) {
            echo "<p>Country: " . htmlspecialchars($row["country"]) . "</p>";
        }
        if (!empty($row["gender"])) {
            echo "<p>Gender: " . htmlspecialchars($row["gender"]) . "</p>";
        }
        if (!empty($row["email"])) {
            echo "<p>Email: " . htmlspecialchars($row["email"]) . "</p>";
        }
        echo "<p>Using Betterreads since: " . htmlspecialchars($row["joining_date"]) . "</p>";
    ?>
</div>

<?php
        $sql = "SELECT * from reader where reader_id='$_SESSION[user_id]';";
        $result=mysqli_query($conn, $sql);
        if (mysqli_num_rows($result)>0){
            $det = mysqli_fetch_assoc($result);
            if (!empty($det["about_me"])) {
                echo "<div class='reader-info'>";
                echo "<h2>About Me</h2>";
                echo "<blockquote>" . htmlspecialchars($det["about_me"]) . "</blockquote>";
                echo "</div>";
            }
        
            // Social Platform Link section
            if (!empty($det["social_platform"]) && !empty($det["social_link"])) {
                function getSocialIcon($platform) {
                    $icons = [
                        'facebook' => '📘', 'twitter' => '🐦', 'linkedin' => '💼',
                        'instagram' => '📷', 'youtube' => '🎥', 'github' => '🐱', 'default' => '🌐'
                    ];
                    return isset($icons[strtolower($platform)]) ? $icons[strtolower($platform)] : $icons['default'];
                }
            
                $platform = htmlspecialchars(ucfirst($det["social_platform"]));
                $link = htmlspecialchars($det["social_link"]);
                $icon = getSocialIcon($det["social_platform"]);
            
                echo "<div class='reader-info'>";
                echo "<h2>Stay connected on Social Media</h2>";
                echo "<a href='$link' target='_blank' rel='noopener noreferrer' class='social-link'>";
                echo "$icon $platform";
                echo "</a>";
                echo "</div>";
            }
        }
    }
?>

<div class="social-stats">
    <div class="stat-box">
        <h3>Followers</h3>
        <a href="?show_followers=<?php echo $show_followers ? '0' : '1'; ?>" class="user-count">
            <?php echo $follower_count; ?>
        </a>
        <?php if ($show_followers): ?>
            <div class="user-list">
                <?php foreach ($followers as $follower): ?>
                    <div class="user-item">
                        <img src="<?php echo htmlspecialchars($follower['profile_picture'] ?: 'dp/person-circle.svg'); ?>" alt="Profile Picture">
                        <div>
                            <a href="view_user_profile.php?user_id=<?php echo $follower['user_id']; ?>">
                                <strong><?php echo htmlspecialchars($follower['fname'] . ' ' . $follower['lname']); ?></strong>
                            </a><br>
                            <?php echo htmlspecialchars(substr($follower['about_me'], 0, 50) . '...'); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="stat-box">
        <h3>Following</h3>
        <a href="?show_following=<?php echo $show_following ? '0' : '1'; ?>" class="user-count">
            <?php echo $following_count; ?>
        </a>
        <?php if ($show_following): ?>
            <div class="user-list">
                <?php foreach ($following as $followed): ?>
                    <div class="user-item">
                        <img src="<?php echo htmlspecialchars($followed['profile_picture'] ?: 'dp/person-circle.svg'); ?>" alt="Profile Picture">
                        <div>
                            <a href="view_user_profile.php?user_id=<?php echo $followed['user_id']; ?>">
                                <strong><?php echo htmlspecialchars($followed['fname'] . ' ' . $followed['lname']); ?></strong>
                            </a><br>
                            <?php echo htmlspecialchars(substr($followed['about_me'], 0, 50) . '...'); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="books-section">
    <h2>My Books</h2>
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
        $current_status = '';
        while ($row = mysqli_fetch_assoc($result)) {
            if ($current_status != $row['reading_status']) {
                if ($current_status != '') {
                    echo "</div>"; // Close previous book-list div
                }
                $current_status = $row['reading_status'];
                echo "<div class='book-status'>";
                echo "<h3>" . ucfirst($current_status) . "</h3>";
                echo "<div class='book-list'>";
            }
             
            echo "<div class='book-item'>";
            echo "<img src='" . htmlspecialchars($row['cover']) . "' alt='Cover of " . htmlspecialchars($row['title']) . "' class='book-cover'>";
            echo "<a href='book.php?isbn=" . htmlspecialchars($row['ISBN']) . "' class='book-title' title='" . htmlspecialchars($row['title']) . "'>" . htmlspecialchars($row['title']) . "</a>";
            echo "</div>";
        }
        echo "</div></div>"; // Close last book-list and book-status div
    } else {
        echo "<p>You haven't added any books to your collection yet.</p>";
    }
    ?>
</div>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>" method="post" style="text-align: center;">
    <input type="submit" value="Log out" class="logout-btn">
</form>


</body>
</html>

<?php
    include("footer.html");
    mysqli_close($conn);
?>