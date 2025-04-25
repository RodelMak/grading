<?php
session_start();
include("config/userdb.php");
include("functions.php");

$error_message = "";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $first_name = $_POST['first_Name'];
    $last_name = $_POST['last_Name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $userType = $_POST['userType'];

    // Input Validation
    if (empty($first_name) || empty($last_name) || empty($email) || empty($password) ||
        !preg_match('/^[a-zA-Z]+$/', $first_name) || !preg_match('/^[a-zA-Z]+$/', $last_name) ||
        !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 8) {
        $error_message = "Invalid input. Please check your details.";
    } else {
        if ($userType == 'student') {
            $check_query = $con->prepare("SELECT COUNT(*) FROM students WHERE email = ?");
            $insert_query = $con->prepare("INSERT INTO students (first_Name, last_Name, email, password) VALUES (?, ?, ?, ?)");
        } else { 
            $check_query = $con->prepare("SELECT COUNT(*) FROM teachers WHERE email = ?");
            $insert_query = $con->prepare("INSERT INTO teachers (first_Name, last_Name, email, password) VALUES (?, ?, ?, ?)");
        }

        if (!$check_query || !$insert_query) {
            $error_message = "Database error: " . $con->error;
        } else {
            $check_query->bind_param("s", $email);
            $check_query->execute();
            $check_query->bind_result($count);
            $check_query->fetch();
            $check_query->close();

            if ($count > 0) {
                $error_message = "That email is already registered.";
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                $insert_query->bind_param("ssss", $first_name, $last_name, $email, $hashed_password);
                try {
                    $insert_query->execute();
                    $insert_query->close();
                    header("Location: index.php");
                    exit;
                } catch (Exception $e) {
                    $error_message = "Database error: " . $e->getMessage();
                }
            }
        }
    }
}
?>

<?php include 'includes/header.php' ?>

<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="form-register bg-success text-white p-4 rounded" style="width: 100%; max-width: 400px;">
        <form method="post">
            <h1>Register Form</h1>
            <?php if (isset($error_message)): ?>
                <div class="alert alert-danger"><?php echo $error_message; ?></div>
            <?php endif; ?>

            <div class="mb-3">
                <select name="userType" class="form-control">
                    <option value="student">Student</option>
                    <option value="teacher">Teacher</option>
                </select>
            </div>

            <div class="mb-3">
                <input type="text" class="form-control" placeholder="First Name" name="first_Name" required>
            </div>
            <div class="mb-3">
                <input type="text" class="form-control" placeholder="Last Name" name="last_Name" required>
            </div>
            <div class="mb-3">
                <input type="email" class="form-control" placeholder="Email" name="email" required>
            </div>
            <div class="mb-3">
                <input type="password" class="form-control" placeholder="Password (at least 8 characters)" name="password" required>
            </div>

            <div class="d-grid mb-3">
                <button type="submit" name="submit" class="btn btn-primary bg-info">Register</button>
            </div>

            <div class="text-center mb-3">
                <p>Already have an Account? <a href="index.php" class="text-info">Log-in</a></p>
            </div>
        </form>
    </div>
</div>

<?php include 'includes/footer.php' ?>