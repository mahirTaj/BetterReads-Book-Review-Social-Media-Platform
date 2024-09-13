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
        <input type="text" name="search" id="search" placeholder="Search by Book Title, Author, or ISBN">
        
        <!-- Radio buttons to select search type -->
        <label for="search_type">Search for:</label>
        <input type="radio" name="search_type" id="book" value="book" checked>
        <label for="book">Book</label>
        <input type="radio" name="search_type" id="author" value="author">
        <label for="author">Author</label>
        <input type="radio" name="search_type" id="people" value="people">
        <label for="people">People</label>
        
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

        // Get the search type
        $search_type = $_POST['search_type'];

        // Initialize $stmt to null to prevent errors
        $stmt = null;

        // Prepare different queries based on the search type
        if ($search_type == 'book') {
            $stmt = $conn->prepare("SELECT * FROM book WHERE LOWER(title) LIKE ? OR LOWER(author_name) LIKE ? OR isbn LIKE ?");
            $stmt->bind_param("sss", $search, $search, $search);
        
        } elseif ($search_type == 'author') {
            $stmt = $conn->prepare("SELECT *
                                    FROM user u 
                                    INNER JOIN author a ON u.user_id = a.author_id 
                                    WHERE LOWER(u.fname) LIKE ? 
                                    OR LOWER(u.mname) LIKE ? 
                                    OR LOWER(u.lname) LIKE ?");
            $stmt->bind_param("sss", $search, $search, $search);
        
        } elseif ($search_type == 'people') {
            $stmt = $conn->prepare("SELECT *
                                    FROM user u 
                                    INNER JOIN reader r ON u.user_id = r.reader_id 
                                    WHERE LOWER(u.fname) LIKE ? 
                                    OR LOWER(u.mname) LIKE ? 
                                    OR LOWER(u.lname) LIKE ?");
            $stmt->bind_param("sss", $search, $search, $search);
        }

        // Check if $stmt is initialized properly
        if ($stmt) {
            // Execute and check results
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div>";

                    // For books, display cover and details
                    if ($search_type == 'book') {
                        echo "<img src='{$row["cover"]}' alt=''>";
                        // Create an anchor link for the book title
                        echo "<h3><a href='book.php?isbn={$row["isbn"]}'>{$row["title"]}</a></h3>";
                        echo "<p>{$row["author_name"]}</p>";
                        echo "<p>{$row["publish_date"]}</p>";
                        echo "<p><a href='{$row["purchase_link"]}'>Buy</a></p>";

                    // For authors and readers, display profile picture and name
                    } elseif ($search_type == 'author') {
                        $full_name = trim("{$row["fname"]} {$row["mname"]} {$row["lname"]}");
                        echo "<img height=100 src='{$row["profile_picture"]}' alt='Profile Picture'>";
                        echo "<h3><a href='visit_author.php?author_id={$row["user_id"]}'>{$full_name}</a></h3>";

                    } elseif ($search_type == 'people') {
                        $full_name = trim("{$row["fname"]} {$row["mname"]} {$row["lname"]}");
                        echo "<img height=100 src='{$row["profile_picture"]}' alt='Profile Picture'>";
                        echo "<h3><a  href='visit_reader.php?reader_id={$row["user_id"]}'>{$full_name}</a></h3>";
                    }

                    echo "</div>";
                }
            } else {
                echo "No results found";
            }
            $stmt->close();
        } else {
            echo "Error: Unable to prepare the SQL statement.";
        }
    } else {
        echo "Please enter a search term";
    }
}

$conn->close();
?>

<?php
    include("footer.html");
?>
