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
    <title>Search for Edit</title>
    <link rel="stylesheet" href="searchstyle.css">
</head>
<body>
    <h2>Search</h2>
    
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="search">Search</label>
        <input type="text" name="search" id="search" placeholder="Search by Book Title, Author, or ISBN">
        <input type="submit" value="Search" name="submit">
    </form>
</body>
</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
    if (!empty($_POST["search"])) {
        // Sanitize user input
        $search = filter_input(INPUT_POST, "search", FILTER_SANITIZE_SPECIAL_CHARS);
        $search = "%" . strtolower(trim($search)) . "%"; // Add wildcards for partial matches

        // Initialize $search_type to 'book' since we are only searching for books
        $search_type = 'book';
        
        // Prepare the SQL query to search for books
        $stmt = $conn->prepare("SELECT * FROM book WHERE LOWER(title) LIKE ? OR LOWER(author_name) LIKE ? OR isbn LIKE ?");
        $stmt->bind_param("sss", $search, $search, $search);
        
        if ($stmt) {
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                echo "<div class='book-container'>"; // Add book container
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='book-card'>";

                    // Display book information
                    if ($search_type == 'book') {
                        echo "<img src='{$row["cover"]}' alt='Book Cover'>";
                        echo "<h3><a href='book_edit.php?isbn={$row["isbn"]}'>{$row["title"]}</a></h3>";
                        echo "<p>Author: {$row["author_name"]}</p>";
                        echo "<p>Published: {$row["publish_date"]}</p>";
                    }

                    echo "</div>";
                }
                echo "</div>"; // Close book container
            } else {
                echo "No results found.";
            }
            $stmt->close();
        } else {
            echo "Error: Unable to prepare the SQL statement.";
        }
    } else {
        echo "Please enter a search term.";
    }
}

$conn->close();
?>

<?php
    include("footer.html");
?>
