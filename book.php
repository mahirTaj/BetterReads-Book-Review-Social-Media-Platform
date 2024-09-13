<?php
    include("database.php");
    session_start();

    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        echo "Please log in to rate and review.";
        exit();
    }

    $user_id = $_SESSION['user_id'];

    // Check if `isbn` is set in the URL
    if (isset($_GET['isbn'])) {
        $book_isbn = $_GET['isbn'];

        // Prepare a statement to fetch book details based on the passed ID
        $stmt = $conn->prepare("SELECT * FROM book WHERE isbn = ?");
        $stmt->bind_param("i", $book_isbn); // Assuming `isbn` is an integer

        // Execute and fetch book details
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            // Display book details
            echo "<h1>{$row['title']}</h1>";
            if (!empty($row['cover'])) {
                echo "<img src='{$row["cover"]}' alt=''>";
            } else {
                echo "<img src='default_cover.jpg' alt=''>";
            }
            echo "<p>Author: {$row['author_name']}</p>";
            if (!empty($row['publish_date'])) {
                echo "<p>Published on: {$row['publish_date']}</p>";
            }
            echo "<p>ISBN: {$row['isbn']}</p>";
            if (!empty($row['description'])) {
                echo "<p>Description: {$row['description']}</p>";
            }
            if (!empty($row['pages'])) {
                echo "<p>Pages: {$row['pages']}</p>";
            }
            if (!empty($row['format'])) {
                echo "<p>Format: {$row['format']}</p>";
            }
            if (!empty($row['purchase_link'])) {
                echo "<p><a href='{$row['purchase_link']}'>Buy Now</a></p>";
            }
            if (!empty($row['publisher'])) {
                echo "<p>Publisher: {$row['publisher']}</p>";
            }
            if (!empty($row['language'])) {
                echo "<p>Language: {$row['language']}</p>";
            }


            // Calculate and display average rating
            $stmt_avg_rating = $conn->prepare("
                SELECT AVG(r.rating) AS avg_rating
                FROM review r
                JOIN user_reviews_book urb ON r.review_id = urb.review_id
                WHERE urb.isbn = ?
            ");
            $stmt_avg_rating->bind_param("i", $book_isbn);
            $stmt_avg_rating->execute();
            $result_avg = $stmt_avg_rating->get_result();
            if ($result_avg->num_rows > 0) {
                $row_avg = $result_avg->fetch_assoc();
                echo "<p>Average Rating: " . number_format($row_avg['avg_rating'], 2) . "</p>";
            } else {
                echo "<p>No ratings yet for this book.</p>";
            }

            // Check if user has already reviewed the book
            $stmt_check_review = $conn->prepare("
            SELECT * 
            FROM user_reviews_book urb
            JOIN review r ON urb.review_id = r.review_id
            WHERE urb.isbn = ? AND urb.reader_id = ?
            ");
            $stmt_check_review->bind_param("ii", $book_isbn, $user_id);
            $stmt_check_review->execute();
            $result_check_review = $stmt_check_review->get_result();

            if ($result_check_review->num_rows > 0) {
            echo "<p>You have already reviewed this book. You can update or delete your review below.</p>";
            } else {
            // Display form for new rating and review only if the user hasn't reviewed the book yet
            echo "
            <h2>Rate and Review this Book</h2>
            <form action='' method='POST'>
                <label for='rating'>Rating (required):</label>
                <select name='rating' id='rating' required>
                    <option value=''>--Select--</option>
                    <option value='1'>1</option>
                    <option value='2'>2</option>
                    <option value='3'>3</option>
                    <option value='4'>4</option>
                    <option value='5'>5</option>
                </select><br>

                <label for='review'>Review (optional):</label><br>
                <textarea name='review' id='review' rows='5' cols='50'></textarea><br><br>

                <input type='submit' name='submit_review' value='Submit'>
            </form>";
            }

            $stmt_check_review->close();



        // Fetch and display reviews
        echo "<h2>Reviews for this book:</h2>";

        // First, fetch the user's review, if available
        $stmt_user_review = $conn->prepare("
            SELECT r.review_id, r.description, r.rating, r.posting_date, u.user_id, 
                CONCAT(u.fname, ' ', u.lname) AS full_name
            FROM review r
            JOIN user_reviews_book urb ON r.review_id = urb.review_id
            JOIN reader rd ON urb.reader_id = rd.reader_id
            JOIN user u ON rd.reader_id = u.user_id
            WHERE urb.isbn = ? AND u.user_id = ?
        ");
        $stmt_user_review->bind_param("ii", $book_isbn, $user_id);
        $stmt_user_review->execute();
        $result_user_review = $stmt_user_review->get_result();

        // Display the user's review at the top
        if ($result_user_review->num_rows > 0) {
            $review = $result_user_review->fetch_assoc();

            echo "<div style='border:1px solid #ccc; padding:10px; margin:10px 0; background-color: #f9f9f9;'>";
            echo "<p><strong>Your Review</strong></p>";
            echo "<p><strong>Username:</strong> {$review['full_name']}</p>";
            echo "<p><strong>Rating:</strong> {$review['rating']}/5</p>";
            echo "<p><strong>Review:</strong> " . (!empty($review['description']) ? $review['description'] : "No review provided.") . "</p>";
            echo "<p><strong>Posted on:</strong> {$review['posting_date']}</p>";

            // Show the edit and delete options for the user's review
            echo "<form action='' method='POST'>
                <label for='rating_edit'>Edit Rating:</label>
                <select name='rating_edit' id='rating_edit' required>
                    <option value='1' " . ($review['rating'] == 1 ? "selected" : "") . ">1</option>
                    <option value='2' " . ($review['rating'] == 2 ? "selected" : "") . ">2</option>
                    <option value='3' " . ($review['rating'] == 3 ? "selected" : "") . ">3</option>
                    <option value='4' " . ($review['rating'] == 4 ? "selected" : "") . ">4</option>
                    <option value='5' " . ($review['rating'] == 5 ? "selected" : "") . ">5</option>
                </select><br>

                <label for='review_edit'>Edit Review (optional):</label><br>
                <textarea name='review_edit' id='review_edit' rows='5' cols='50'>{$review['description']}</textarea><br><br>

                <input type='hidden' name='review_id' value='{$review['review_id']}'>
                <input type='submit' name='edit_review' value='Update Review'>
                <input type='submit' name='delete_review' value='Delete Review' onclick=\"return confirm('Are you sure you want to delete this review?');\">
            </form>";
            echo "</div>";
        } else {
            echo "<p>You have not reviewed this book yet.</p>";
        }

        $stmt_user_review->close();

        // Now, fetch and display all other reviews excluding the user's review
        $stmt_reviews = $conn->prepare("
            SELECT r.review_id, r.description, r.rating, r.posting_date, u.user_id, 
                CONCAT(u.fname, ' ', u.lname) AS full_name
            FROM review r
            JOIN user_reviews_book urb ON r.review_id = urb.review_id
            JOIN reader rd ON urb.reader_id = rd.reader_id
            JOIN user u ON rd.reader_id = u.user_id
            WHERE urb.isbn = ? AND u.user_id != ?
        ");
        $stmt_reviews->bind_param("ii", $book_isbn, $user_id);
        $stmt_reviews->execute();
        $result_reviews = $stmt_reviews->get_result();

        if ($result_reviews->num_rows > 0) {
            while ($review = $result_reviews->fetch_assoc()) {
                echo "<div style='border:1px solid #ccc; padding:10px; margin:10px 0;'>";
                echo "<p><strong>Username:</strong> {$review['full_name']}</p>";
                echo "<p><strong>Rating:</strong> {$review['rating']}/5</p>";
                echo "<p><strong>Review:</strong> " . (!empty($review['description']) ? $review['description'] : "No review provided.") . "</p>";
                echo "<p><strong>Posted on:</strong> {$review['posting_date']}</p>";
                echo "</div>";
            }
        } else {
            echo "<p>No reviews found for this book.</p>";
        }

        $stmt_reviews->close();


            
            // Process the review submission
            if (isset($_POST['submit_review'])) {
                $rating = $_POST['rating'];
                $review = !empty($_POST['review']) ? $_POST['review'] : null;

                if ($rating) {
                    // Insert into `review` table
                    $stmt_review = $conn->prepare("INSERT INTO review (posting_date, description, rating) VALUES (NOW(), ?, ?)");
                    $stmt_review->bind_param("si", $review, $rating);
                    $stmt_review->execute();

                    // Get the last inserted review_id
                    $review_id = $stmt_review->insert_id;

                    // Insert into the cross-reference table
                    $stmt_cross_ref = $conn->prepare("INSERT INTO user_reviews_book (isbn, reader_id, review_id) VALUES (?, ?, ?)");
                    $stmt_cross_ref->bind_param("iii", $book_isbn, $user_id, $review_id);
                    $stmt_cross_ref->execute();

                    echo "<p>Thank you for your review!</p>";

                                // After processing the form submission
                    header("Location: ".$_SERVER['PHP_SELF']."?isbn=$book_isbn");
                    exit();

                    $stmt_review->close();
                    $stmt_cross_ref->close();
                } else {
                    echo "<p>Please select a rating.</p>";
                }
            }



            // Process review edit
            if (isset($_POST['edit_review'])) {
                $rating_edit = $_POST['rating_edit'];
                $review_edit = !empty($_POST['review_edit']) ? $_POST['review_edit'] : null;
                $review_id = $_POST['review_id'];

                if ($rating_edit) {
                    // Update the existing review
                    $stmt_edit = $conn->prepare("UPDATE review SET description = ?, rating = ? WHERE review_id = ?");
                    $stmt_edit->bind_param("sii", $review_edit, $rating_edit, $review_id);
                    $stmt_edit->execute();

                    echo "<p>Your review has been updated!</p>";

                    $stmt_edit->close();
                } else {
                    echo "<p>Please select a rating.</p>";
                }
            }
            // Process review deletion
            if (isset($_POST['delete_review'])) {
                $review_id = $_POST['review_id'];

                // First, delete from `user_reviews_book` table
                $stmt_delete_urb = $conn->prepare("DELETE FROM user_reviews_book WHERE review_id = ?");
                $stmt_delete_urb->bind_param("i", $review_id);
                $stmt_delete_urb->execute();

                // Then, delete from `review` table
                $stmt_delete_review = $conn->prepare("DELETE FROM review WHERE review_id = ?");
                $stmt_delete_review->bind_param("i", $review_id);
                $stmt_delete_review->execute();

                echo "<p>Your review has been deleted.</p>";

                $stmt_delete_urb->close();
                $stmt_delete_review->close();

                // Redirect to refresh the page after deletion
                header("Location: ".$_SERVER['PHP_SELF']."?isbn=$book_isbn");
                exit();
            }

        } else {
            echo "Book not found.";
        }
        $stmt->close();
    } else {
        echo "No book selected.";
    }

    $conn->close();
?>
