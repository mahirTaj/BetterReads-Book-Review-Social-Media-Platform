<?php
    ob_start();
    include("database.php");
    session_start();
?>
<html><link rel="stylesheet" href="genrebrowserstyle.css"></html>
<?php
    // Fetch all genres and their respective book count
    $stmt_genres = $conn->prepare("
        SELECT g.genre_name, COUNT(bg.isbn) AS book_count
        FROM genre g
        LEFT JOIN book_belongs_to_genre bg ON g.genre_name = bg.genre_name
        GROUP BY g.genre_name
    ");
    $stmt_genres->execute();
    $result_genres = $stmt_genres->get_result();

    // Display genres with book count
    if ($result_genres->num_rows > 0) {
        echo "<h1>Browse Genres</h1>";
        echo "<ul>"; // Start an unordered list for genres

        while ($genre = $result_genres->fetch_assoc()) {
            $genre_name = htmlspecialchars($genre['genre_name']);
            $book_count = $genre['book_count'];

            // Create a link to genre.php with the genre_name
            echo "<li><a href='genre.php?genre_name=$genre_name'>$genre_name</a> ($book_count books)</li>";
        }

        echo "</ul>"; // End the unordered list
    } else {
        echo "<p>No genres found.</p>";
    }

    $stmt_genres->close();
?>
