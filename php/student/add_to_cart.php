<?php
// add_to_cart.php
session_start();
include '../db_config.php';

$user_id = $_SESSION['user_id'];
$book_id = $_GET['book_id'];

// Check if the book exists in the database
$stmt = $conn->prepare("SELECT COUNT(*) FROM book_table WHERE book_id = ?");
$stmt->bind_param("i", $book_id);
$stmt->execute();
$stmt->bind_result($book_exists);
$stmt->fetch();
$stmt->close();

if ($book_exists) {
    // Insert the book into the cart table
    $stmt = $conn->prepare("INSERT INTO cart (user_id, book_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $user_id, $book_id);
    
    if ($stmt->execute()) {
        // Successfully added to cart
        header('Location: student_home.php?message=Book added to cart');
    } else {
        // Handle error
        header('Location: student_home.php?error=Could not add book to cart');
    }
    
    $stmt->close();
} else {
    header('Location: student_home.php?error=Book does not exist');
}
exit();

?>
