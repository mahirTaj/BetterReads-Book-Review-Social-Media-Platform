<?php
    include("database.php"); //First of all code
    session_start(); //before all html code
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
    <form  action="<?php htmlspecialchars($_SERVER["PHP_SELF"])?>" method="post">
            <div>
                <label for="isbn">ISBN:</label>
                <input type="int" name="isbn" id="isbn">
            </div>
            <div>
                <label for="title">Title:</label>
                <input type="text" name="title" id="title">
            </div>
            <div>
                <label for="author">Author:</label>
                <input type="text" name="author" id="author">
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
                <input type="int" name="pages" id="pages">
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
                <input type="text" name="purchase_link" id="purchase_link">
            </div>
            <div>
                <label for="cover">Cover:</label>
                <input type="file" name="cover" id="cover">
            </div>
            <div>
                <input type="submit" value="Add Book" name="add">
            </div>

        </form>
</body>
</html>

<?php
    if($_SERVER["REQUEST_METHOD"]=="POST"){
       
?>

<?php
    include("footer.html"); 
    mysqli_close($conn); //last of all code
?>