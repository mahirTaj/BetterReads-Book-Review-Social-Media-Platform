<?php
    ob_start();
    include("database.php");
    session_start();
?>
<html><link rel="stylesheet" href="genrestyle.css"></html>
<?php
    // Check if genre_name is set in the URL
    if (!isset($_GET['genre_name'])) {
        echo "No genre specified.";
        exit();
    }

    $genre_name = $_GET['genre_name'];

    // Fetch all books for this genre
    $stmt_books = $conn->prepare("
        SELECT b.isbn, b.title, b.cover, b.author_name, b.publish_date
        FROM book b
        JOIN book_belongs_to_genre bg ON b.isbn = bg.isbn
        WHERE bg.genre_name = ?
    ");
    $stmt_books->bind_param("s", $genre_name);
    $stmt_books->execute();
    $result_books = $stmt_books->get_result();

    // Display books under the specified genre
    if ($result_books->num_rows > 0) {
        echo "<h1>Books in Genre: " . htmlspecialchars($genre_name) . "</h1>"; // Display genre name
        echo "<div style='display: flex; flex-wrap: wrap;'>"; // Add a flexbox container for styling
        
        while ($book = $result_books->fetch_assoc()) {
            echo "<div style='border: 1px solid #ddd; padding: 10px; margin: 10px; width: 200px;'>"; // Style each book block
            // Display book cover
            if (!empty($book['cover'])) {
                echo "<img src='{$book['cover']}' alt='{$book['title']}' style='width: 100%; height: auto;'>";
            } else {
                echo "<img src='default_cover.jpg' alt='No Cover Available' style='width: 100%; height: auto;'>";
            }
            // Display book title as a link to book.php
            $isbn = htmlspecialchars($book['isbn']);
            $title = htmlspecialchars($book['title']);
            echo "<h3><a href='book.php?isbn=$isbn'>$title</a></h3>";
            // Display author name and publish date
            echo "<p>Author: " . htmlspecialchars($book['author_name']) . "</p>";
            if (!empty($book['publish_date'])) {
                echo "<p>Published on: " . htmlspecialchars($book['publish_date']) . "</p>";
            }
            echo "</div>";
        }

        echo "</div>"; // Close the flexbox container
    } else {
        echo "<p>No books found in this genre.</p>";
    }

    $stmt_books->close();
?>
