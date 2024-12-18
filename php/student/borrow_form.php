<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Home</title>
    <link href="../../css/bootstrap.min.css" rel="stylesheet">
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Sweet Alert -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.0/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.0/dist/sweetalert2.min.js"></script>
</head>
<body>
<?php
session_start();
require_once '../db_config.php'; // Include the database connection file

if (isset($_POST['submit'])) {
    $user_id = $_POST['user_id'];

    // Fetch books from the cart table for the logged-in user along with their status in inventory
    $stmt = $conn->prepare("SELECT cart.book_id, book.title, inventory.status 
                            FROM cart 
                            JOIN book_table AS book ON cart.book_id = book.book_id 
                            LEFT JOIN inventory_table AS inventory ON book.book_id = inventory.book_id 
                            WHERE cart.user_id = ?");

    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the cart has any contents
    if ($result->num_rows == 0) {
        // echo "<script>alert('Your cart is empty!'); window.location.href='student_home.php';</script>";
        echo "<script>
            Swal.fire({
                title: 'Error!',
                text: 'Your cart is empty!',
                icon: 'error',
                confirmButtonText: 'Okay',
                confirmButtonColor: '#dc3545',
            }).then(function() {
                window.location.href = 'student_home.php';
            });
        </script>";
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
    $dueDate = calculateDueDate($today, 1);

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
        // echo "<script>alert('You cannot borrow more than $max_borrows books at a time. Please return some books first.'); window.location.href='student_home.php';</script>";
        echo "<script>
            Swal.fire({
                title: 'Error!',
                text: 'You cannot borrow more than $max_borrows books at a time, Please return some books first!',
                icon: 'error',
                confirmButtonText: 'Okay',
                confirmButtonColor: '#dc3545',
            }).then(function() {
                window.location.href = 'student_home.php';
            });
        </script>";
        exit();
    }

    // Prepare the insert statement for the borrow_table
    $stmt = $conn->prepare("INSERT INTO borrow_table (student_id, book_id, status, penalty, date_borrowed, due_date) VALUES (?, ?, 'Active', '0.00', ?, ?)");

    // Loop through the results to process borrowing
    $borrowed_books = 0; // Track how many books are successfully borrowed
    $unavailable_books = []; // Track unavailable books
    while ($row = $result->fetch_assoc()) {
        $book_id = $row['book_id'];
        $book_title = $row['title']; // Get the book title
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
            // Add unavailable book ID and title to the list
            $unavailable_books[] = $book_title; // Store book title instead
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
        // Prepare to log the borrowing action
        $studentId = $user_id; // Assuming user_id is the student ID
        $action = 'Borrow';
        $details = "You borrowed $borrowed_books book(s)";

        // Insert log activity for borrowing
        $logStmt = $conn->prepare("INSERT INTO activity_logs (student_id, action, details, timestamp) VALUES (?, ?, ?, NOW())");
        $logStmt->bind_param("iss", $studentId, $action, $details);
        $logStmt->execute();
        $logStmt->close();
        // echo "<script>alert('Successfully borrowed $borrowed_books book(s)!');</script>";
        echo "<script>
            Swal.fire({
                title: 'Success!',
                text: 'Successfully borrowed $borrowed_books book(s)!',
                icon: 'success',
                confirmButtonText: 'Okay',
                confirmButtonColor: '#198754',
            }).then(function() {
                window.location.href = 'student_home.php';
            });
        </script>";
        exit();
    }
    if (!empty($unavailable_books)) {
        $unavailable_names = implode(", ", $unavailable_books); // Join book titles
        // echo "<script>alert('The following book(s) were not available for borrowing: $unavailable_names');</script>";
            echo "<script>
            Swal.fire({
                title: 'Error!',
                text: 'The following book(s) were not available for borrowing: $unavailable_names',
                icon: 'error',
                confirmButtonText: 'Okay',
                confirmButtonColor: '#dc3545',
            }).then(function() {
                window.location.href = 'student_home.php';
            });
        </script>";
        exit();
    }
    if ($borrowed_books == 0 && empty($unavailable_books)) {
        // echo "<script>alert('No books were borrowed. Please check availability.');</script>";
        echo "<script>
            Swal.fire({
                title: 'Error!',
                text: 'No books were borrowed. Please check availability again.',
                icon: 'error',
                confirmButtonText: 'Okay',
                confirmButtonColor: '#dc3545',
            }).then(function() {
                window.location.href = 'student_home.php';
            });
        </script>";
        exit();
    }

    // Redirect to the home page after the process
    echo "<script>window.location.href='student_home.php';</script>";
}

// Close the database connection
mysqli_close($conn);
?>

</body>
</html>