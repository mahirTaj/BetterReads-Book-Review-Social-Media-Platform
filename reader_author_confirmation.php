<?php
    include("database.php");
    session_start();
    include("header.html");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Confirm Role</title>
</head>
<body>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div>
            <label for="confirm">Are you:</label>
            <input type="radio" name="confirm" value="reader" id="reader">Reader
            <input type="radio" name="confirm" value="author" id="author">Author
        </div>
        <div>
            <input type="submit" value="Confirm">
        </div>
    </form>
</body>
</html>

<?php
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if (isset($_POST["confirm"])){
            $user_id = $_SESSION["user_id"];
            $role = $_POST["confirm"];

            if ($role == "reader"){
                $sql = "INSERT INTO `reader` (`reader_id`) VALUES ('$user_id');";
                try {
                    mysqli_query($conn, $sql);
                    header("Location: reader_profile.php");
                    exit(); // Ensure no further code is executed after redirection
                } catch (mysqli_sql_exception $e) {
                    echo "Error: " . $e->getMessage();
                }
            } elseif ($role == "author") {
                $sql = "INSERT INTO `author` (`author_id`) VALUES ('$user_id');";
                try {
                    mysqli_query($conn, $sql);
                    header("Location: author_profile.php");
                    exit(); // Ensure no further code is executed after redirection
                } catch (mysqli_sql_exception $e) {
                    echo "Error: " . $e->getMessage();
                }
            } else {
                echo "Please select a role.";
            }
        } else {
            echo "No role selected.";
        }
    }
?>

<?php
    include("footer.html");
    mysqli_close($conn);
?>
