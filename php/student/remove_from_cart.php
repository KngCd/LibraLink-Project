<?php
// remove_from_cart.php
session_start();
include '../db_config.php'; // Include your database connection file

$user_id = $_SESSION['user_id'];
$book_id = $_GET['book_id'];

// Prepare the SQL statement to delete the item from the cart
$stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ? AND book_id = ?");
$stmt->bind_param("ii", $user_id, $book_id);
$stmt->execute();
$stmt->close();

// Redirect back to the previous page
header('Location: student_home.php');
exit();

?>