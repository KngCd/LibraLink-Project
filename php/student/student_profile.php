<?php
    // Include the PHP QR Code library
    require_once 'phpqrcode/qrlib.php'; // Update this path as necessary

    session_start();

    // Check if the user is logged in
    if (!isset($_SESSION['user_id'])) {
        header("Location: student-login.php"); // Redirect to login if not logged in
        exit;
    }

    // Get user information from the session
    $user_id = $_SESSION['user_id'];
    $first_name = $_SESSION['first_name'];
    $last_name = $_SESSION['last_name'];
    $email = $_SESSION['email'];
    $contact_num = $_SESSION['contact_num'];
    $program = $_SESSION['program'];
    $department = $_SESSION['department'];

    // Fetch profile picture from the database
    require_once '../db_config.php'; // Include your database configuration
    $stmt = $conn->prepare("SELECT profile_pic FROM student_table WHERE student_id = ?");
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    // Initialize QR code path
    $qrCodeFilePath = 'qrcodes/' . $user_id . '-' . $first_name . '.png'; // Ensure the qrcodes directory exists and is writable

    // Check if the button is clicked to generate QR code
    $qrCodeGenerated = false; // Flag to check if QR code was generated
    if (isset($_POST['generate_qr'])) {
        // Data to be encoded in the QR code in JSON format
        $qrData = json_encode([
            'student_id' => $user_id,
            'name' => $first_name . ' ' . $last_name,
            'email' => $email,
            'contact' => $contact_num,
            'program' => $program,
            'department' => $department
        ]);

        // Generate QR code
        QRcode::png($qrData, $qrCodeFilePath, QR_ECLEVEL_L, 4);
        $qrCodeGenerated = true; // Set flag to true after generation
    }

    // Check if form is submitted to update profile
    if (isset($_POST['update_profile'])) {
        $first_name = $_POST['firstName'];
        $last_name = $_POST['lastName'];
        $email = $_POST['email'];
        $contact_num = $_POST['contact_num'];
        $program = $_POST['program'];
        $department = $_POST['department'];

        // Update user details in the database
        $stmt = $conn->prepare("UPDATE student_table SET first_name = ?, last_name = ?, email = ?, contact_num = ?, program = ?, department = ? WHERE student_id = ?");
        $stmt->bind_param("ssssssi", $first_name, $last_name, $email, $contact_num, $program, $department, $user_id);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                // Update session data
                $_SESSION['first_name'] = $first_name;
                $_SESSION['last_name'] = $last_name;
                $_SESSION['email'] = $email;
                $_SESSION['contact_num'] = $contact_num;
                $_SESSION['program'] = $program;
                $_SESSION['department'] = $department;

                header("Location: student_profile.php?status=success");
                exit;
            } else {
                echo "No changes were made to the profile.";
            }
        } else {
            echo "Error updating record: " . $conn->error;
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LibraLink - Student Profile</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>

<style>
    #firstName-error, #lastName-error, #email-error, #password-error, #confirmPassword-error, 
    #contact_num-error, #program-error, #department-error, #cor-error, #id-error, #pic-error{
        color: red;
        /* font-size: 0.90rem; */
        /* display: block; */
    }
    .input-group label{
        display: block;
        width: 100%;
    } 
</style>

</head>
<body style="background-color: #e3e3e3;">
    <div class="container mt-5">
        <div class="card p-3">
            <div class="d-flex justify-content-between">
                <h4>Student Profile</h4>
                <a href="student_home.php" class="btn btn-outline-secondary">Back</a>
            </div>
            <div class="row mt-4">
                
                <div class="col-md-4 text-center">
                    <div class="border p-3" style="height: 200px; width: 200px; display: flex; justify-content: center; align-items: center; overflow: hidden; margin: 0 auto;">
                        <img class="img-fluid" src="data:image/jpeg;base64,<?php echo base64_encode($row['profile_pic']); ?>" alt="Profile Picture" style="height: 100%; width: 100%; object-fit: cover;">
                    </div>
                    <h5 class="mt-3"><?php echo htmlspecialchars($first_name . ' ' . $last_name); ?></h5>
                    <form method="POST" action="">
                        <button type="submit" name="generate_qr" class="btn btn-primary mt-2">Generate QR Code</button>
                    </form>
                    <div id="qr-code" class="text-center mt-3">
                        <?php if ($qrCodeGenerated && file_exists($qrCodeFilePath)) : ?>
                            <img src="<?php echo $qrCodeFilePath; ?>" alt="QR Code" />
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-md-8">
                    <h5>Personal Information</h5>
                    
                    <form id="editProfileForm" method="POST" action="">
                        <input type="hidden" name="student_id" value="<?php echo $user_id; ?>">
                        <?php
                            // Fetch departments and programs for dropdowns
                            $departments = [];
                            $programs = [];

                            // Fetch departments
                            $departmentQuery = "SELECT name FROM department_table";
                            $result = $conn->query($departmentQuery);
                            if ($result) {
                                while ($row = $result->fetch_assoc()) {
                                    $departments[] = $row['name'];
                                }
                            }

                            // Fetch programs
                            foreach ($departments as $dept) {
                                $programQuery = "SELECT p.name 
                                    FROM program_table p 
                                    JOIN department_table d ON p.department_id = d.id 
                                    WHERE d.name = ?";
                                $stmt = $conn->prepare($programQuery);
                                $stmt->bind_param("s", $dept);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                $programs[$dept] = [];
                                if ($result) {
                                    while ($row = $result->fetch_assoc()) {
                                        $programs[$dept][] = $row['name'];
                                    }
                                }
                            }
                        ?>

                        <div class="form-group">
                            <div class="row">
                                <div class="input-group col-6">
                                    <label for="firstName">First Name</label>
                                    <input type="text" class="form-control w-25" id="firstName" name="firstName" value="<?php echo htmlspecialchars($first_name); ?>">
                                </div>
                                <div class="input-group col-6">
                                    <label for="lastName">Last Name</label>
                                    <input type="text" class="form-control w-25" id="lastName" name="lastName" value="<?php echo htmlspecialchars($last_name); ?>">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="input-group col-6">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>">
                                </div>
                                <div class="input-group col-6">
                                    <label for="contact_num">Contact Number</label>
                                    <input type="tel" class="form-control" id="contact_num" name="contact_num" value="<?php echo htmlspecialchars($contact_num); ?>">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="input-group col-6">
                                    <label for="department">Department</label>
                                    <select class="form-control" id="department" name="department" onchange="updatePrograms(this.value)">
                                        <?php foreach ($departments as $dept): ?>
                                            <option value="<?php echo htmlspecialchars($dept); ?>" <?php echo ($dept === $department) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($dept); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="input-group col-6">
                                    <label for="program">Program</label>
<select class="form-control" id="program" name="program">
    <?php if (!empty($programs[$department])): ?>
        <?php foreach ($programs[$department] as $prog): ?>
            <option value="<?php echo htmlspecialchars($prog); ?>" <?php echo ($prog === $program) ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($prog); ?>
            </option>
        <?php endforeach; ?>
    <?php else: ?>
        <option value="" disabled selected>No programs available yet</option>
    <?php endif; ?>
</select>

                                </div>
                        </div>
                    </div>
                        <button type="submit" name="update_profile" class="btn btn-success mt-3">Save Changes</button>
                    </form>

                    <script>
function updatePrograms(department) {
    const programs = <?php echo json_encode($programs); ?>;
    const programSelect = document.getElementById('program');
    programSelect.innerHTML = ''; // Clear existing options

    if (programs[department] && programs[department].length > 0) {
        programs[department].forEach(prog => {
            const option = document.createElement('option');
            option.value = prog;
            option.textContent = prog;
            programSelect.appendChild(option);
        });
    } else {
        // If no programs available, add a message
        const option = document.createElement('option');
        option.value = '';
        option.textContent = 'No programs available yet';
        option.disabled = true; // Disable the option
        programSelect.appendChild(option);
    }

    // Optionally set the selected program if it exists
    const currentProgram = "<?php echo htmlspecialchars($program); ?>";
    if (programs[department] && programs[department].includes(currentProgram)) {
        programSelect.value = currentProgram;
    } else {
        programSelect.value = ''; // Reset if current program is not in the list
    }
}

                    </script>

                </div>
            </div>
        </div>
    </div>

    <script src="../../js/loginValidate.js"></script>

    <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        // Custom validation method for contact number
        $.validator.addMethod("validContact", function(value, element) {
            return this.optional(element) || (value.length === 11 && value.startsWith("09"));
        }, "Contact number must be valid");

        // Initialize validation for the edit profile form
        $("#editProfileForm").validate({
            errorClass: "error", // Use the error class for error messages
            rules: {
                full_name: {
                    required: true
                },
                email: {
                    required: true,
                    email: true
                },
                contact_num: {
                    required: true,
                    validContact: true
                },
                program: {
                    required: true
                },
                department: {
                    required: true
                }
            },
            messages: {
                full_name: {
                    required: "Please enter your full name"
                },
                email: {
                    required: "Please enter your email address",
                    email: "Please provide a valid email"
                },
                contact_num: {
                    required: "Please enter your contact number",
                    validContact: "Contact number must be valid and please remove any special characters (e.g., '-')"
                },
                program: {
                    required: "Please enter your program"
                },
                department: {
                    required: "Please enter your department"
                }
            },
            highlight: function(element) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element) {
                $(element).removeClass('is-invalid');
            },
            submitHandler: function(form) {
                form.submit(); // Submit the form if valid
            }
        });
    });
</script> -->
</body>
</html> 