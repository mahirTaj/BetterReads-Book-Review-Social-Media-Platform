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
    <title>Document</title>
</head>
<body>
    <h2>Search</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="search">Search</label>
        <input type="text" name="search" id="search" placeholder="Search by Book Title, Author or ISBN">
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

        // Use prepared statement to avoid SQL injection
        $stmt = $conn->prepare("SELECT * FROM book WHERE LOWER(title) LIKE ? OR LOWER(author_name) LIKE ? OR isbn LIKE ?");
        $stmt->bind_param("sss", $search, $search, $search);

        // Execute and check results
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div>";
                echo "<img src='{$row["cover"]}' alt=''>";
                // Create an anchor link for the book title
                echo "<h3><a href='book.php?isbn={$row["isbn"]}'>{$row["title"]}</a></h3>";
                echo "<p>{$row["author_name"]}</p>";
                echo "<p>{$row["publish_date"]}</p>";
                echo "<p><a href='{$row["purchase_link"]}'>Buy</a></p>";
                echo "</div>";
            }
        } else {
            echo "No results found";
        }
        $stmt->close();
    } else {
        echo "Please enter a search term";
    }
}

$conn->close();
?>

<?php
    include("footer.html");
?>
