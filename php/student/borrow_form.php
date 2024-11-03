<?php
session_start();
require_once '../db_config.php'; // Include the database connection file

if (isset($_POST['submit'])) {
    $user_id = $_POST['user_id'];

    // Fetch books from the cart table for the logged-in user along with their status in inventory
    $stmt = $conn->prepare("SELECT cart.book_id, inventory.status 
                            FROM cart 
                            JOIN book_table AS book ON cart.book_id = book.book_id 
                            LEFT JOIN inventory_table AS inventory ON book.book_id = inventory.book_id 
                            WHERE cart.user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the cart has any contents
    if ($result->num_rows == 0) {
        echo "<script>alert('Your cart is empty!'); window.location.href='student_home.php';</script>";
        exit();
    }

    // Get today's date
    $today = date("Y-m-d");

    // Function to calculate the due date
    function calculateDueDate($startDate, $days) {
        $dueDate = $startDate;
        $dayCount = 0;

        while ($dayCount < $days) {
            $dueDate = date("Y-m-d", strtotime("+1 day", strtotime($dueDate)));
            $dayOfWeek = date("w", strtotime($dueDate));
            if ($dayOfWeek != 0) { // 0 represents Sunday
                $dayCount++;
            }
        }

        return $dueDate;
    }

    // Calculate the due date (7 days from today)
    $dueDate = calculateDueDate($today, 7);

    // Verify that the student ID exists
    $stmt = $conn->prepare("SELECT COUNT(*) FROM student_table WHERE student_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($exists);
    $stmt->fetch();
    $stmt->close();

    if (!$exists) {
        echo "<script>alert('Invalid student ID!'); window.location.href='student_home.php';</script>";
        exit();
    }

    // Check how many active borrow records the student currently has
    $stmt = $conn->prepare("SELECT COUNT(*) FROM borrow_table WHERE student_id = ? AND status = 'Active'");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($active_count);
    $stmt->fetch();
    $stmt->close();

    // Calculate how many more books can be borrowed
    $max_borrows = 5;
    $current_borrows = $active_count;
    $can_borrow = $max_borrows - $current_borrows;

    if ($can_borrow <= 0) {
        echo "<script>alert('You cannot borrow more than $max_borrows books at a time. Please return some books first.'); window.location.href='student_home.php';</script>";
        exit();
    }

    // Prepare the insert statement for the borrow_table
    $stmt = $conn->prepare("INSERT INTO borrow_table (student_id, book_id, status, penalty, date_borrowed, due_date) VALUES (?, ?, 'Active', 'None', ?, ?)");

    // Loop through the results to process borrowing
    $borrowed_books = 0; // Track how many books are successfully borrowed
    $unavailable_books = []; // Track unavailable books
    while ($row = $result->fetch_assoc()) {
        $book_id = $row['book_id'];
        $inventory_status = $row['status'];

        // Check if the book is available in the inventory
        if ($inventory_status === 'Available') {
            // Bind the parameters
            $stmt->bind_param("iiss", $user_id, $book_id, $today, $dueDate);

            // Execute the insert
            if ($stmt->execute()) {
                $borrowed_books++;
            } else {
                echo "<script>alert('Borrow Unsuccessful for book ID $book_id: " . $stmt->error . "');</script>";
            }
        } else {
            $unavailable_books[] = $book_id; // Add unavailable book ID to the list
        }
    }

    // Close the statement
    $stmt->close();

    // Clear the cart after processing
    $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();

    // Notify the user about borrowing results
    if ($borrowed_books > 0) {
        echo "<script>alert('Successfully borrowed $borrowed_books book(s)!');</script>";
    }
    if (!empty($unavailable_books)) {
        $unavailable_ids = implode(", ", $unavailable_books);
        echo "<script>alert('The following book(s) were not available for borrowing: $unavailable_ids');</script>";
    }
    if ($borrowed_books == 0 && empty($unavailable_books)) {
        echo "<script>alert('No books were borrowed. Please check availability.');</script>";
    }

    // Redirect to the home page after the process
    echo "<script>window.location.href='student_home.php';</script>";
}

// Close the database connection
mysqli_close($conn);
?>
