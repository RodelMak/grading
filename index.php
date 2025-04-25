<?php
session_start();
include("config/userdb.php");
include("functions.php");

$error_message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (!empty($email) && !empty($password) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email = strtolower(trim($email));
        $stmt = $con->prepare("SELECT id, password, role FROM users WHERE email = ? LIMIT 1"); //removed lower

        if (!$stmt) {
            $error_message = "Database preparation failed: " . $con->error;
            error_log("Database preparation failed: " . $con->error); // Log the error
        } else {
            $stmt->bind_param("s", $email);
            if (!$stmt->execute()) {
                $error_message = "Database execution failed: " . $stmt->error;
                error_log("Database execution failed: " . $stmt->error); // Log the error
            } else {
                $result = $stmt->get_result();
                if ($result === null) {
                    $error_message = "Database result retrieval failed: " . $con->error;
                    error_log("Database result retrieval failed: " . $con->error); // Log the error
                } elseif ($result && $result->num_rows > 0) {
                    $user_data = $result->fetch_assoc();
                    //Added this to check if password_verify is working correctly
                    if(password_verify($password, $user_data['password'])){
                        $_SESSION['id'] = $user_data['id'];
                        $_SESSION['role'] = $user_data['role'];
                        if ($user_data['role'] === 'student') {
                            header("Location: std_account.php");
                        } elseif ($user_data['role'] === 'teacher') {
                            header("Location: tch_account.php");
                        } else {
                            $error_message = "Invalid user role.";
                        }
                        exit;
                    } else {
                        $error_message = "Incorrect password.";
                    }
                } else {
                    $error_message = "User not found.";
                    error_log("User not found for email: " . $email); // Log the error
                }
                $stmt->close();
            }
        }
    } else {
        $error_message = "Please fill in all fields correctly with a valid email.";
    }
    //Debugging: Show the SQL query and email (remove or comment out in production)
    //echo "<p>SQL Query: SELECT id, password, role FROM users WHERE email = '" . $email . "' LIMIT 1</p>";
}
?>

<?php include 'includes/header.php' ?>

<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="form-register bg-success text-white p-4 rounded" style="width: 100%; max-width: 400px;">
        <form method="post">
            <h1>Log-In Form</h1>
            <?php if (!empty($error_message)): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>

            <div class="mb-3">
                <input type="email" class="form-control" placeholder="Email" name="email" required>
            </div>

            <div class="mb-3">
                <input type="password" class="form-control" placeholder="Password" name="password" required>
            </div>

            <div class="d-grid mb-3">
                <button type="submit" name="submit" class="btn btn-primary bg-info">Log-In</button>
            </div>

            <div class="text-center mb-3">
                <p>Don't Have an account? <a href="register.php">Register</a></p>
            </div>
        </form>
    </div>
</div>

<?php include 'includes/footer.php' ?>