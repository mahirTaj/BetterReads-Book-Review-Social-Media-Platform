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
    <link rel="stylesheet" href="adminpanelstyle.css">
</head>
<body>
    <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"])?>" method="post">
        <input type="submit" value="logout">
    </form>
    <h2>Admin Panel</h2>
    <a href="book_add.php">Add Book</a>

</body>
</html>



<?php
    if ($_SERVER['REQUEST_METHOD']=='POST'){
        session_destroy();
        header('Location: admin_login.php');
    }
?>


<?php
    echo "<br>{$_SESSION["username"]} this is your homepage<br>";
    //write all the features here
?>

<?php
    include("footer.html");
    mysqli_close($conn);
?>