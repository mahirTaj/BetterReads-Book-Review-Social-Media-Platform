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
            <label for="isbn">ISBN:</label>
            <input type="text" name="isbn" id="isbn" required>
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
    if (empty($_POST["isbn"]) || empty($_POST["title"]) || empty($_POST["author"])) {
        echo "ISBN, Title, and Author are mandatory fields.";
    } else {
        // Prepare data for insertion
        $title = $_POST["title"];
        $isbn = $_POST["isbn"];
        $author = $_POST["author"];
    }
     // Check optional fields and add them to the data array if filled
    if (!empty($_POST["date"])) {
        $date = $_POST["date"];
    }
    else{
        $date = NULL;
    }
    if (!empty($_POST["publisher"])) {
        $publisher = $_POST["publisher"];
    }
    else{
        $publisher = NULL;
    }
    if (!empty($_POST["language"])) {
        $language = $_POST["language"];
    }
    else{
        $language = NULL;
    }
    if (!empty($_POST["pages"])) {
        $pages = $_POST["pages"];
    }
    else{
        $pages = NULL;
    }
    if (!empty($_POST["format"])) {
        $format = $_POST["format"];
    }
    else{
        $format = NULL;
    }
    if (!empty($_POST["description"])) {
        $description = $_POST["description"];
    }
    else{
        $description = NULL;
    }
    if (!empty($_POST["purchase_link"])) {
        $purchase_link = $_POST["purchase_link"];
    }
    else{
        $purchase_link = NULL;
    }
    if (!empty($_FILES["cover"])) {
        $cover = $_FILES["cover"];
    }
    else{
        $cover = NULL;
    }
    $fileDestination = NULL;
    if (isset($_FILES["file"])){
        // photo
        $file = $_FILES["file"]; //dictionary

        $fileName= $_FILES["file"]['name'];
        $fileTmpName= $_FILES["file"]['tmp_name']; // tempporary location of the pic
        $fileSize= $_FILES["file"]['size'];
        $fileError= $_FILES["file"]['error']; //0 means no error
        $fileType= $_FILES["file"]['type']; // we will not use this


        $fileExt = explode(".", $fileName); //array of name and ext
        $fileActualExt= strtolower(end($fileExt)); //extention find

        $allowed= array('jpg', 'jpeg', 'png'); //allowed extention
        
        if (in_array($fileActualExt, $allowed)){ //check ext
            if ($fileError==0){ //check error
                if ($fileSize<5000000){ //check size
                    $fileNameNew = uniqid('', true).".".$fileActualExt; //generate unique name before ext
                    $fileDestination = 'book_cover/'.$fileNameNew; //new location of the pic
                    move_uploaded_file($fileTmpName, $fileDestination); //move the file

                }
                else{
                    echo "Your file is too big";
                }
            }
            else{
                echo "There was an error uploading your file!";
            }
        }
        else{
            echo "Only jpeg, jpg and png file is supported";
        }
    }
    else{
        $fileDestination = 'book_cover/open-book.jpg'; 
    }
    
    $sql = "INSERT INTO book (isbn, title, author_name, publish_date, publisher, language, pages, format, description, purchase_link, cover) VALUES ('$isbn', '$title', '$author', '$date', '$publisher', '$language', '$pages', '$format', '$description', '$purchase_link', '$fileDestination')";
    if (mysqli_query($conn, $sql)) {
        echo "Book added successfully";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
    // Insert into the book_genre table
    if (!empty($_POST['genres'])) {
        $genres = $_POST['genres'];
        foreach ($genres as $genre_name) {
            // Check if the genre exists in the genre table
            $sql_check_genre = "SELECT * FROM genre WHERE genre_name = ?";
            $stmt_check_genre = mysqli_prepare($conn, $sql_check_genre);
            mysqli_stmt_bind_param($stmt_check_genre, "s", $genre_name);
            mysqli_stmt_execute($stmt_check_genre);
            $result_genre = mysqli_stmt_get_result($stmt_check_genre);
            
            if (mysqli_num_rows($result_genre) > 0) {
                // Genre exists, proceed with insertion
                $sql_genre = "INSERT INTO book_belongs_to_genre (isbn, genre_name) VALUES (?, ?)";
                $stmt = mysqli_prepare($conn, $sql_genre);
                mysqli_stmt_bind_param($stmt, "ss", $isbn, $genre_name); // "ss" for string types
                mysqli_stmt_execute($stmt);
            } else {
                // Genre does not exist, show an error message or handle it
                echo "Error: Genre '$genre_name' does not exist in the genre table.";
            }
        }
    }
    
}
?>

<?php
    include("footer.html");
    mysqli_close($conn);
?>
