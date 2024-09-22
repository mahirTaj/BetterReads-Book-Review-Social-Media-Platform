<?php
include("database.php");
session_start();
include("header.html");

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$author_id = $_SESSION['user_id'];

function get_follower_count($conn, $author_id) {
    $sql = "SELECT COUNT(*) as count FROM user_follows_user WHERE followed_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $author_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['count'];
}

function get_followers($conn, $author_id) {
    $sql = "SELECT u.user_id, u.fname, u.lname, u.profile_picture 
            FROM user_follows_user f 
            JOIN user u ON f.follower_id = u.user_id 
            WHERE f.followed_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $author_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function get_followed_readers($conn, $author_id) {
    $sql = "SELECT u.user_id, u.fname, u.lname, u.profile_picture 
            FROM user_follows_user f 
            JOIN user u ON f.followed_id = u.user_id 
            JOIN reader r ON u.user_id = r.reader_id
            WHERE f.follower_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $author_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function get_followed_authors($conn, $author_id) {
    $sql = "SELECT u.user_id, u.fname, u.lname, u.profile_picture 
            FROM user_follows_user f 
            JOIN user u ON f.followed_id = u.user_id 
            JOIN author a ON u.user_id = a.author_id
            WHERE f.follower_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $author_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

$follower_count = get_follower_count($conn, $author_id);
$show_followers = isset($_GET['show_followers']) && $_GET['show_followers'] == 1;
$show_followed_readers = isset($_GET['show_followed_readers']) && $_GET['show_followed_readers'] == 1;
$show_followed_authors = isset($_GET['show_followed_authors']) && $_GET['show_followed_authors'] == 1;

$followers = $show_followers ? get_followers($conn, $author_id) : [];
$followed_readers = $show_followed_readers ? get_followed_readers($conn, $author_id) : [];
$followed_authors = $show_followed_authors ? get_followed_authors($conn, $author_id) : [];

// Fetch author details
$sql = "SELECT u.*, a.biography, a.personal_website 
        FROM user u 
        JOIN author a ON u.user_id = a.author_id 
        WHERE u.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $author_id);
$stmt->execute();
$result = $stmt->get_result();
$author = $result->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Author Profile</title>
    <link rel="stylesheet" href="author-profile-style.css">
    <style>
        .profile-container {
            max-width: 800px;
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
        .user-list {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-top: 20px;
        }
        .user-item {
            width: 100px;
            text-align: center;
        }
        .user-item img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
        }
    </style>
</head>
<body>
    <a href="search.php" style="text-decoration: none; color: #0066cc;">
        &#128269; Search
    </a>
    <div class="profile-container">
        <h1><?php echo htmlspecialchars($author['fname'] . ' ' . $author['lname']); ?>'s Author Profile</h1>
        <img src="<?php echo htmlspecialchars($author['profile_picture'] ?: 'dp/person-circle.svg'); ?>" alt="Profile Picture" class="profile-picture">
        
        <div class="profile-info">
            <p><strong>Email:</strong> <?php echo htmlspecialchars($author['email']); ?></p>
            <p><strong>Gender:</strong> <?php echo htmlspecialchars($author['gender']); ?></p>
            <p><strong>Birthday:</strong> <?php echo htmlspecialchars($author['date_of_birth']); ?></p>
            <p><strong>Country:</strong> <?php echo htmlspecialchars($author['country']); ?></p>
            <p><strong>Joined:</strong> <?php echo htmlspecialchars($author['joining_date']); ?></p>
            <?php if (!empty($author['biography'])): ?>
                <p><strong>Biography:</strong> <?php echo htmlspecialchars($author['biography']); ?></p>
            <?php endif; ?>
            <?php if (!empty($author['personal_website'])): ?>
                <p><strong>Website:</strong> <a href="<?php echo htmlspecialchars($author['personal_website']); ?>" target="_blank"><?php echo htmlspecialchars($author['personal_website']); ?></a></p>
            <?php endif; ?>
        </div>

        <h2>
            <a href="?show_followers=<?php echo $show_followers ? '0' : '1'; ?>">
                Followers: <?php echo $follower_count; ?>
            </a>
        </h2>

        <?php if ($show_followers): ?>
            <div class="user-list">
                <?php foreach ($followers as $follower): ?>
                    <div class="user-item">
                        <a href="view_user_profile.php?user_id=<?php echo $follower['user_id']; ?>">
                            <img src="<?php echo htmlspecialchars($follower['profile_picture'] ?: 'dp/person-circle.svg'); ?>" alt="Profile Picture">
                            <p><?php echo htmlspecialchars($follower['fname'] . ' ' . $follower['lname']); ?></p>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <h2>
            <a href="?show_followed_readers=<?php echo $show_followed_readers ? '0' : '1'; ?>">
                Followed Readers
            </a>
        </h2>

        <?php if ($show_followed_readers): ?>
            <div class="user-list">
                <?php foreach ($followed_readers as $reader): ?>
                    <div class="user-item">
                        <a href="view_user_profile.php?user_id=<?php echo $reader['user_id']; ?>">
                            <img src="<?php echo htmlspecialchars($reader['profile_picture'] ?: 'dp/person-circle.svg'); ?>" alt="Profile Picture">
                            <p><?php echo htmlspecialchars($reader['fname'] . ' ' . $reader['lname']); ?></p>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <h2>
            <a href="?show_followed_authors=<?php echo $show_followed_authors ? '0' : '1'; ?>">
                Followed Authors
            </a>
        </h2>

        <?php if ($show_followed_authors): ?>
            <div class="user-list">
                <?php foreach ($followed_authors as $author): ?>
                    <div class="user-item">
                        <a href="view_user_profile.php?user_id=<?php echo $author['user_id']; ?>">
                            <img src="<?php echo htmlspecialchars($author['profile_picture'] ?: 'dp/person-circle.svg'); ?>" alt="Profile Picture">
                            <p><?php echo htmlspecialchars($author['fname'] . ' ' . $author['lname']); ?></p>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <p><a href="edit_author_profile.php">Edit Profile</a></p>
    </div>
    

    <?php
    // Display author's books (keeping the existing code)
    $sql = "SELECT b.isbn, b.title, b.author_name, b.publish_date, b.pages, b.description, b.format, 
                   b.purchase_link, b.publisher, b.language, b.cover
            FROM book b
            INNER JOIN author_writes_book awb ON b.isbn = awb.isbn
            WHERE awb.author_id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $author_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result && $result->num_rows > 0) {
        echo "<h2>Books by This Author</h2>";
        echo "<div style='display: flex; flex-wrap: wrap; gap: 20px;'>";
        
        while ($book = $result->fetch_assoc()) {
            $isbn = $book['isbn'];
            $book_title = htmlspecialchars($book['title']);
            $publish_date = $book['publish_date'];
            $cover_image = !empty($book['cover']) ? $book['cover'] : 'path/to/default/book/cover.jpg';
            
            echo "<div style='width: 200px; text-align: center;'>";
            echo "<a href='book_details.php?isbn=$isbn' style='text-decoration: none; color: inherit;'>";
            echo "<img src='" . htmlspecialchars($cover_image) . "' alt='$book_title' style='width: 150px; height: 200px; object-fit: cover;'><br>";
            echo "<strong>$book_title</strong><br>";
            echo "Published: $publish_date<br>";
            echo "Pages: " . $book['pages'] . "<br>";
            echo "Publisher: " . htmlspecialchars($book['publisher']) . "<br>";
            echo "Language: " . htmlspecialchars($book['language']) . "<br>";
            echo "Format: " . $book['format'];
            echo "</a>";
            echo "</div>";
        }
        
        echo "</div>";
    } else {
        echo "<p>No books found for this author.</p>";
    }
    ?>
  
    <a href="index.php" class="btn btn-danger">Log out</a>
</body>
</html>

<?php
include("footer.html");
mysqli_close($conn);
?>