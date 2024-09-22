<?php
include("database.php");
session_start();
include("header.html");

// Check if ISBN is passed via GET or POST
if (isset($_GET['isbn'])) {
    $isbn = $_GET['isbn'];
} elseif (isset($_POST['isbn'])) {
    $isbn = $_POST['isbn'];
} else {
    echo "No ISBN specified.";
    exit;
}

// Fetch current book details
$sql = "SELECT * FROM book WHERE isbn = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $isbn);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 1) {
    $book = mysqli_fetch_assoc($result);
} else {
    echo "Book not found.";
    exit;
}

// Fetch genres for this book
$sql_genres = "SELECT genre_name FROM book_belongs_to_genre WHERE isbn = ?";
$stmt_genres = mysqli_prepare($conn, $sql_genres);
mysqli_stmt_bind_param($stmt_genres, "s", $isbn);
mysqli_stmt_execute($stmt_genres);
$result_genres = mysqli_stmt_get_result($stmt_genres);
$selected_genres = [];
while ($row = mysqli_fetch_assoc($result_genres)) {
    $selected_genres[] = $row['genre_name'];
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit'])) {
    // Allow empty values (they will be updated to NULL in the database)
    $title = !empty($_POST["title"]) ? $_POST["title"] : null;
    $author = !empty($_POST["author"]) ? $_POST["author"] : null;
    $date = !empty($_POST["date"]) ? $_POST["date"] : null;
    $publisher = !empty($_POST["publisher"]) ? $_POST["publisher"] : null;
    $language = !empty($_POST["language"]) ? $_POST["language"] : null;
    $pages = !empty($_POST["pages"]) ? $_POST["pages"] : null;
    $format = !empty($_POST["format"]) ? $_POST["format"] : null;
    $description = !empty($_POST["description"]) ? htmlspecialchars($_POST["description"]) : null;
    $purchase_link = !empty($_POST["purchase_link"]) ? $_POST["purchase_link"] : null;
    $fileDestination = $book['cover'];

    // File upload processing if a new file is uploaded
    if (isset($_FILES["file"]) && $_FILES["file"]["error"] !== 4) {
        $file = $_FILES["file"];
        $fileName = $_FILES["file"]['name'];
        $fileTmpName = $_FILES["file"]['tmp_name'];
        $fileSize = $_FILES["file"]['size'];
        $fileError = $_FILES["file"]['error'];

        $fileExt = explode(".", $fileName);
        $fileActualExt = strtolower(end($fileExt));
        $allowed = array('jpg', 'jpeg', 'png');

        if (in_array($fileActualExt, $allowed)) {
            if ($fileError == 0 && $fileSize < 5000000) {
                $fileNameNew = uniqid('', true) . "." . $fileActualExt;
                $fileDestination = 'book_cover/' . $fileNameNew;
                move_uploaded_file($fileTmpName, $fileDestination);
            } else {
                echo "There was an error with your file.";
                exit;
            }
        } else {
            echo "Invalid file type. Only jpeg, jpg, and png files are allowed.";
            exit;
        }
    }

    // Update book details
    $sql_update = "UPDATE book SET title = ?, author_name = ?, publish_date = ?, publisher = ?, language = ?, pages = ?, format = ?, description = ?, purchase_link = ?, cover = ? WHERE isbn = ?";
    $stmt_update = mysqli_prepare($conn, $sql_update);
    mysqli_stmt_bind_param($stmt_update, "sssssssssss", $title, $author, $date, $publisher, $language, $pages, $format, $description, $purchase_link, $fileDestination, $isbn);

    if (mysqli_stmt_execute($stmt_update)) {
        echo "Book updated successfully.";
    } else {
        echo "Error updating book: " . mysqli_error($conn);
    }

    // Update genres
    $sql_delete_genres = "DELETE FROM book_belongs_to_genre WHERE isbn = ?";
    $stmt_delete_genres = mysqli_prepare($conn, $sql_delete_genres);
    mysqli_stmt_bind_param($stmt_delete_genres, "s", $isbn);
    mysqli_stmt_execute($stmt_delete_genres);

    if (!empty($_POST['genres'])) {
        $genres = $_POST['genres'];
        foreach ($genres as $genre_name) {
            $sql_genre = "INSERT INTO book_belongs_to_genre (isbn, genre_name) VALUES (?, ?)";
            $stmt_genre = mysqli_prepare($conn, $sql_genre);
            mysqli_stmt_bind_param($stmt_genre, "ss", $isbn, $genre_name);
            mysqli_stmt_execute($stmt_genre);
        }
    }

    // Redirect to the book's own page
    header("Location: book.php?isbn=$isbn");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Book</title>
    <link rel="stylesheet" href="bookaddstyle.css">
</head>
<body>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
        <input type="hidden" name="isbn" value="<?php echo $isbn; ?>">
        
        <div>
            <label for="title">Title:</label>
            <input type="text" name="title" id="title" value="<?php echo htmlspecialchars($book['title']); ?>">
        </div>
        
        <div>
            <label for="author">Author:</label>
            <input type="text" name="author" id="author" value="<?php echo htmlspecialchars($book['author_name']); ?>">
        </div>
        
        <div>
            <label for="date">Publish Date:</label>
            <input type="date" name="date" id="date" value="<?php echo $book['publish_date']; ?>">
        </div>
        
        <div>
            <label for="publisher">Publisher:</label>
            <input type="text" name="publisher" id="publisher" value="<?php echo htmlspecialchars($book['publisher']); ?>">
        </div>
        
        <div>
            <label for="language">Language:</label>
            <input type="text" name="language" id="language" value="<?php echo htmlspecialchars($book['language']); ?>">
        </div>
        
        <div>
            <label for="pages">Pages:</label>
            <input type="number" name="pages" id="pages" value="<?php echo $book['pages']; ?>">
        </div>
        
        <div>
            <label for="format">Format:</label>
            <select name="format" id="format">
                <option value="paperback" <?php if ($book['format'] == "paperback") echo 'selected'; ?>>Paperback</option>
                <option value="hardcover" <?php if ($book['format'] == "hardcover") echo 'selected'; ?>>Hardcover</option>
                <option value="ebook" <?php if ($book['format'] == "ebook") echo 'selected'; ?>>Ebook</option>
            </select>
        </div>
        
        <div>
            <label for="description">Description:</label>
            <input type="text" name="description" id="description" value="<?php echo htmlspecialchars($book['description']); ?>">
        </div>
        
        <div>
            <label for="purchase_link">Purchase Link:</label>
            <input type="url" name="purchase_link" id="purchase_link" value="<?php echo $book['purchase_link']; ?>">
        </div>
        
        <div>
            <label for="genres">Genres:</label>
            <?php
            $genres_list = ['fiction', 'non-fiction', 'mystery', 'thriller', 'fantasy', 'religion', 'history', 'self-help', 'science-fiction', 'horror', 'biography', 'children', 'classic', 'business'];
            foreach ($genres_list as $genre) {
                $checked = in_array($genre, $selected_genres) ? 'checked' : '';
                echo "<input type='checkbox' name='genres[]' value='$genre' $checked> $genre ";
            }
            ?>
        </div>

        <div>
            <label for="cover">Cover Photo:</label>
            <input type="file" name="file" id="cover">
            <?php if (!empty($book['cover'])): ?>
                <img src="<?php echo $book['cover']; ?>" alt="Current cover" width="100">
            <?php endif; ?>
        </div>

        <div>
            <input type="submit" value="Update Book" name="edit">
        </div>
    </form>
</body>
</html>

<?php
include("footer.html");
mysqli_close($conn);
?>
