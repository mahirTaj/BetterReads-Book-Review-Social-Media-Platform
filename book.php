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
                SELECT AVG(r.rating) AS avg_rating, COUNT(r.rating) AS total_ratings
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
                echo "{$row_avg['total_ratings']} ratings";
            } else {
                echo "No ratings yet for this book.";
            }

            // Count how many reviews have descriptions
            $stmt_review_count = $conn->prepare("
                SELECT COUNT(r.review_id) AS total_reviews
                FROM review r
                JOIN user_reviews_book urb ON r.review_id = urb.review_id
                WHERE urb.isbn = ? AND r.description IS NOT NULL AND r.description != ''
            ");
            $stmt_review_count->bind_param("i", $book_isbn);
            $stmt_review_count->execute();
            $result_review_count = $stmt_review_count->get_result();
            if ($result_review_count->num_rows > 0) {
                $row_reviews = $result_review_count->fetch_assoc();
                echo " . {$row_reviews['total_reviews']} reviews";
            } else {
                echo " . No reviews yet for this book.\n";
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

        // Fetch and display all other reviews excluding the user's review
        $stmt_reviews = $conn->prepare("
            SELECT r.review_id, r.description, r.rating, r.posting_date, u.user_id, 
                CONCAT(u.fname, ' ', u.lname) AS full_name,
                (SELECT COUNT(*) FROM user_likes_review WHERE review_id = r.review_id) AS like_count
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
                $profile_link = "visit_reader.php?reader_id={$review['user_id']}";
                
                // Check if the user has liked this review
                $stmt_check_like = $conn->prepare("
                    SELECT * FROM user_likes_review
                    WHERE review_id = ? AND reader_id = ?
                ");
                $stmt_check_like->bind_param("ii", $review['review_id'], $user_id);
                $stmt_check_like->execute();
                $result_check_like = $stmt_check_like->get_result();
                $has_liked = $result_check_like->num_rows > 0;

                // Display review and like button
                echo "<div style='border:1px solid #ccc; padding:10px; margin:10px 0;'>";
                echo "<p><strong>Username:</strong> <a href='{$profile_link}'>{$review['full_name']}</a></p>";
                echo "<p><strong>Rating:</strong> {$review['rating']}/5</p>";
                echo "<p><strong>Review:</strong> " . (!empty($review['description']) ? $review['description'] : "No review provided.") . "</p>";
                echo "<p><strong>Posted on:</strong> {$review['posting_date']}</p>";
                echo "<p><strong>Likes:</strong> {$review['like_count']}</p>";
                
                // Display the like/unlike button
                if ($has_liked) {
                    echo "<form action='' method='POST'>
                        <input type='hidden' name='unlike_review_id' value='{$review['review_id']}'>
                        <input type='submit' name='unlike_review' value='Unlike'>
                    </form>";
                } else {
                    echo "<form action='' method='POST'>
                        <input type='hidden' name='like_review_id' value='{$review['review_id']}'>
                        <input type='submit' name='like_review' value='Like'>
                    </form>";
                }
                // Display comments and comment form
                echo "<h3>Comments:</h3>";
                
               // Fetch and display comments for this review
                $stmt_comments = $conn->prepare("
                SELECT c.comment_id, c.comment, CONCAT(u.fname, ' ', u.lname) AS full_name, c.reader_id
                FROM user_comments_review c
                JOIN reader r ON c.reader_id = r.reader_id
                JOIN user u ON r.reader_id = u.user_id
                WHERE c.review_id = ?
                ");
                $stmt_comments->bind_param("i", $review['review_id']);
                $stmt_comments->execute();
                $result_comments = $stmt_comments->get_result();

                if ($result_comments->num_rows > 0) {
                while ($comment = $result_comments->fetch_assoc()) {
                    echo "<div style='border:1px solid #ddd; padding:5px; margin:5px 0;'>";
                    echo "<p><strong>{$comment['full_name']}:</strong> {$comment['comment']}</p>";
                    
                    // Show the edit button if the comment belongs to the logged-in user
                    if ($comment['reader_id'] == $user_id) {
                        echo "
                        <form action='' method='POST' style='display:inline;'>
                            <input type='hidden' name='edit_comment_id' value='{$comment['comment_id']}'>
                            <input type='submit' name='edit_comment' value='Edit'>
                        </form>";
                    }
                    
                    echo "</div>";
                }
                } else {
                echo "<p>No comments yet.</p>";
                }

            // Show edit comment form
            if (isset($_POST['edit_comment'])) {
                $comment_id = $_POST['edit_comment_id'];

                // Fetch the existing comment
                $stmt_get_comment = $conn->prepare("SELECT comment FROM user_comments_review WHERE comment_id = ? AND reader_id = ?");
                $stmt_get_comment->bind_param("ii", $comment_id, $user_id);
                $stmt_get_comment->execute();
                $result_comment = $stmt_get_comment->get_result();

                if ($result_comment->num_rows > 0) {
                    $existing_comment = $result_comment->fetch_assoc();
                    echo "
                    <form action='' method='POST'>
                        <textarea name='updated_comment' rows='3' cols='50' required>{$existing_comment['comment']}</textarea><br>
                        <input type='hidden' name='comment_id' value='$comment_id'>
                        <input type='submit' name='update_comment' value='Update Comment'>
                    </form>";
                } else {
                    echo "<p>Comment not found or you do not have permission to edit this comment.</p>";
                }

                $stmt_get_comment->close();
            }



                // Comment form
                echo "<form action='' method='POST'>
                    <label for='comment'>Add a comment:</label><br>
                    <textarea name='comment' id='comment' rows='3' cols='50' required></textarea><br>
                    <input type='hidden' name='comment_review_id' value='{$review['review_id']}'>
                    <input type='submit' name='submit_comment' value='Submit Comment'>
                </form>";

                echo "</div>";

                $stmt_check_like->close();
                $stmt_comments->close();
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

            // Process like submission
            if (isset($_POST['like_review'])) {
                $review_id = $_POST['like_review_id'];

                // Check if the user has already liked this review
                $stmt_check_like = $conn->prepare("
                    SELECT * FROM user_likes_review
                    WHERE review_id = ? AND reader_id = ?
                ");
                $stmt_check_like->bind_param("ii", $review_id, $user_id);
                $stmt_check_like->execute();
                $result_check_like = $stmt_check_like->get_result();

                if ($result_check_like->num_rows == 0) {
                    // Insert a like into `user_likes_review` table
                    $stmt_like = $conn->prepare("
                        INSERT INTO user_likes_review (review_id, reader_id) VALUES (?, ?)
                    ");
                    $stmt_like->bind_param("ii", $review_id, $user_id);
                    $stmt_like->execute();

                    echo "<p>You liked this review!</p>";
                    
                    // Redirect to refresh the page after liking
                    header("Location: ".$_SERVER['PHP_SELF']."?isbn=$book_isbn");
                    exit();

                    $stmt_like->close();
                } else {
                    echo "<p>You have already liked this review.</p>";
                }

                $stmt_check_like->close();
            }

            // Process unlike submission
            if (isset($_POST['unlike_review'])) {
                $review_id = $_POST['unlike_review_id'];

                // Check if the user has liked this review
                $stmt_check_like = $conn->prepare("
                    SELECT * FROM user_likes_review
                    WHERE review_id = ? AND reader_id = ?
                ");
                $stmt_check_like->bind_param("ii", $review_id, $user_id);
                $stmt_check_like->execute();
                $result_check_like = $stmt_check_like->get_result();

                if ($result_check_like->num_rows > 0) {
                    // Delete the like from `user_likes_review` table
                    $stmt_unlike = $conn->prepare("
                        DELETE FROM user_likes_review
                        WHERE review_id = ? AND reader_id = ?
                    ");
                    $stmt_unlike->bind_param("ii", $review_id, $user_id);
                    $stmt_unlike->execute();

                    echo "<p>You unliked this review!</p>";

                    // Redirect to refresh the page after unliking
                    header("Location: ".$_SERVER['PHP_SELF']."?isbn=$book_isbn");
                    exit();

                    $stmt_unlike->close();
                } else {
                    echo "<p>You have not liked this review.</p>";
                }

                $stmt_check_like->close();
            }

            // Process comment submission
            if (isset($_POST['submit_comment'])) {
                $comment = $_POST['comment'];
                $review_id = $_POST['comment_review_id'];

                if (!empty($comment)) {
                    // Insert into `user_comments_review` table
                    $stmt_comment = $conn->prepare("
                        INSERT INTO user_comments_review (review_id, reader_id, comment) 
                        VALUES (?, ?, ?)
                    ");
                    $stmt_comment->bind_param("iis", $review_id, $user_id, $comment);
                    $stmt_comment->execute();

                    echo "<p>Your comment has been added!</p>";

                    // Redirect to refresh the page after commenting
                    header("Location: ".$_SERVER['PHP_SELF']."?isbn=$book_isbn");
                    exit();

                    $stmt_comment->close();
                } else {
                    echo "<p>Comment cannot be empty.</p>";
                }
            }

            // Process comment deletion
            if (isset($_POST['delete_comment'])) {
                $comment_id = $_POST['delete_comment_id'];

                // Delete the comment from `user_comments_review` table
                $stmt_delete_comment = $conn->prepare("DELETE FROM user_comments_review WHERE comment_id = ? AND reader_id = ?");
                $stmt_delete_comment->bind_param("ii", $comment_id, $user_id);
                $stmt_delete_comment->execute();

                if ($stmt_delete_comment->affected_rows > 0) {
                    echo "<p>Your comment has been deleted.</p>";
                } else {
                    echo "<p>Failed to delete comment or you don't have permission.</p>";
                }

                $stmt_delete_comment->close();

                // Redirect to refresh the page after deletion
                header("Location: ".$_SERVER['PHP_SELF']."?isbn=$book_isbn");
                exit();
            }

            // Process comment update
            if (isset($_POST['update_comment'])) {
                $updated_comment = $_POST['updated_comment'];
                $comment_id = $_POST['comment_id'];

                if (!empty($updated_comment)) {
                    // Update the comment in the `user_comments_review` table
                    $stmt_update_comment = $conn->prepare("UPDATE user_comments_review SET comment = ? WHERE comment_id = ? AND reader_id = ?");
                    $stmt_update_comment->bind_param("sii", $updated_comment, $comment_id, $user_id);
                    $stmt_update_comment->execute();

                    echo "<p>Your comment has been updated!</p>";

                    // Redirect to refresh the page after updating
                    header("Location: " . $_SERVER['PHP_SELF'] . "?isbn=$book_isbn");
                    exit();

                    $stmt_update_comment->close();
                } else {
                    echo "<p>Comment cannot be empty.</p>";
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
