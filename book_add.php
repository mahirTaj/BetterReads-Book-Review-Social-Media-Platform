<?php
    include("database.php"); // First of all code
    session_start(); // Before all HTML code
    include("header.html");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Book</title>
</head>
<body>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
        <div>
            <label for="isbn">ISBN (13 digit):</label>
            <input type="number" name="isbn" id="isbn" required>
        </div>
        <div>
            <label for="title">Title:</label>
            <input type="text" name="title" id="title" required>
        </div>
        <div>
            <label for="author">Author:</label>
            <input type="text" name="author" id="author" required>
        </div>
        <div>
            <label for="date">Publish Date:</label>
            <input type="date" name="date" id="date">
        </div>
        <div>
            <label for="publisher">Publisher:</label>
            <input type="text" name="publisher" id="publisher">
        </div>
        <div>
            <label for="language">Language:</label>
            <input type="text" name="language" id="language">
        </div>
        <div>
            <label for="pages">Pages:</label>
            <input type="number" name="pages" id="pages">
        </div>
        <div>
            <label for="format">Format:</label>
            <select name="format" id="format">
                <option value="paperback">Paperback</option>
                <option value="hardcover">Hardcover</option>
                <option value="ebook">Ebook</option>
            </select>
        </div>
        <div>
            <label for="description">Description:</label>
            <input type="text" name="description" id="description">
        </div>
        <div>
            <label for="purchase_link">Purchase Link:</label>
            <input type="url" name="purchase_link" id="purchase_link">
        </div>
        <div>
            <div>
            <label for="genres">Genres:</label>
            <input type="checkbox" name="genres[]" value="fiction"> Fiction
            <input type="checkbox" name="genres[]" value="non-fiction"> Non-Fiction
            <input type="checkbox" name="genres[]" value="mystery"> Mystery
            <input type="checkbox" name="genres[]" value="thriller"> Thriller
            <input type="checkbox" name="genres[]" value="fantasy"> Fantasy
            <input type="checkbox" name="genres[]" value="religion"> Religion
            <input type="checkbox" name="genres[]" value="history"> History
            <input type="checkbox" name="genres[]" value="fantasy"> Fantasy
            <input type="checkbox" name="genres[]" value="self-help"> Self-help
            <input type="checkbox" name="genres[]" value="science-fiction"> Science Fiction
            <input type="checkbox" name="genres[]" value="horror"> Horror
            <input type="checkbox" name="genres[]" value="biography"> Biography
            <input type="checkbox" name="genres[]" value="children"> Children
            <input type="checkbox" name="genres[]" value="classic"> Classic
            <input type="checkbox" name="genres[]" value="business"> Business
            </div>
        </div>
        <div>
            <label for="cover">Cover Photo:</label>
            <input type="file" name="file" id="cover">
        </div>
        <div>
            <input type="submit" value="Add Book" name="add">
        </div>
    </form>
</body>
</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["isbn"]) || empty($_POST["title"]) || empty($_POST["author"]) || strlen($_POST["isbn"]) !== 13) {
        echo "ISBN, Title, and Author are mandatory fields, and ISBN must be exactly 13 characters long.";
        return; // Stop further execution if validation fails
    } else {
        // Mandatory fields
        $title = $_POST["title"];
        $isbn = $_POST["isbn"];
        $author = $_POST["author"];

        // Optional fields
        $date = !empty($_POST["date"]) ? $_POST["date"] : NULL;
        $publisher = !empty($_POST["publisher"]) ? $_POST["publisher"] : NULL;
        $language = !empty($_POST["language"]) ? $_POST["language"] : NULL;
        $pages = !empty($_POST["pages"]) ? $_POST["pages"] : NULL;
        $format = !empty($_POST["format"]) ? $_POST["format"] : NULL;
        $description = !empty($_POST["description"]) ? htmlspecialchars($_POST["description"]) : NULL;
        $purchase_link = !empty($_POST["purchase_link"]) ? $_POST["purchase_link"] : NULL;
        $fileDestination = 'book_cover/open-book.jpg'; // Default cover photo

        // File upload processing
        if (isset($_FILES["file"]) && $_FILES["file"]["error"] !== 4) { // Check if file is uploaded
            $file = $_FILES["file"];
            $fileName = $_FILES["file"]['name'];
            $fileTmpName = $_FILES["file"]['tmp_name'];
            $fileSize = $_FILES["file"]['size'];
            $fileError = $_FILES["file"]['error'];

            $fileExt = explode(".", $fileName);
            $fileActualExt = strtolower(end($fileExt));
            $allowed = array('jpg', 'jpeg', 'png');

            if (in_array($fileActualExt, $allowed)) {
                if ($fileError == 0) {
                    if ($fileSize < 5000000) {
                        $fileNameNew = uniqid('', true) . "." . $fileActualExt;
                        $fileDestination = 'book_cover/' . $fileNameNew;
                        move_uploaded_file($fileTmpName, $fileDestination);
                    } else {
                        echo "Your file is too big";
                        return; // Stop further execution
                    }
                } else {
                    echo "There was an error uploading your file!";
                    return; // Stop further execution
                }
            } else {
                echo "Only jpeg, jpg, and png files are supported";
                return; // Stop further execution
            }
        }

        // Proceed with inserting data into the database
        $sql = "INSERT INTO book (isbn, title, author_name, publish_date, publisher, language, pages, format, description, purchase_link, cover) 
                VALUES ('$isbn', '$title', '$author', '$date', '$publisher', '$language', '$pages', '$format', '$description', '$purchase_link', '$fileDestination')";
        
        if (mysqli_query($conn, $sql)) {
            echo "Book added successfully";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }

        // Insert into the book_genre table
        if (!empty($_POST['genres'])) {
            $genres = $_POST['genres'];
            foreach ($genres as $genre_name) {
                $sql_check_genre = "SELECT * FROM genre WHERE genre_name = ?";
                $stmt_check_genre = mysqli_prepare($conn, $sql_check_genre);
                mysqli_stmt_bind_param($stmt_check_genre, "s", $genre_name);
                mysqli_stmt_execute($stmt_check_genre);
                $result_genre = mysqli_stmt_get_result($stmt_check_genre);
                
                if (mysqli_num_rows($result_genre) > 0) {
                    $sql_genre = "INSERT INTO book_belongs_to_genre (isbn, genre_name) VALUES (?, ?)";
                    $stmt = mysqli_prepare($conn, $sql_genre);
                    mysqli_stmt_bind_param($stmt, "ss", $isbn, $genre_name);
                    mysqli_stmt_execute($stmt);
                } else {
                    echo "Error: Genre '$genre_name' does not exist in the genre table.";
                }
            }
        }
    }
}

?>

<?php
    include("footer.html");
    mysqli_close($conn);
?>
