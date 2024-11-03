<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
</head>
<body>
        <?php
            session_start();
            require_once '../db_config.php'; // Include the database connection file

            if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
                echo "<h2>Your cart is empty.</h2>";
                exit;
            }

            echo '<h2>Your Cart</h2>';
            echo '<div class="list-group">';

            foreach ($_SESSION['cart'] as $book_id) {
                $stmt = $conn->prepare("SELECT title, author FROM book_table WHERE book_id = ?");
                $stmt->bind_param("i", $book_id);
                $stmt->execute();
                $result = $stmt->get_result();
                $book = $result->fetch_assoc();

                if ($book) {
                    echo '<div class="list-group-item d-flex justify-content-between align-items-center">';
                    echo '<span>' . htmlspecialchars($book['title']) . ' by ' . htmlspecialchars($book['author']) . '</span>';
                    echo '<a href="remove_from_cart.php?book_id=' . $book_id . '" class="btn btn-danger btn-sm">Remove</a>';
                    echo '</div>';
                }

                $stmt->close();
            }

            echo '</div>'; // Close the list group

            // Button to proceed to borrowing
            echo '<form action="borrow_form.php" method="post" class="mt-3">';
            echo '<input type="hidden" name="user_id" value="' . $_SESSION['user_id'] . '">'; // Ensure you have the user ID
            echo '<button type="submit" name="submit" class="btn btn-danger">Confirm Borrowing</button>';
            echo '</form>';

            $conn->close();
        ?>

    <script src="../../js/bootstrap.min.js"></script>
    <script src="../../js/bootstrap.bundle.min.js"></script>
</body>
</html>
