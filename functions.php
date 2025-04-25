<?php

function check_login($con) {
    if (isset($_SESSION['id'])) {
        $id = $_SESSION['id'];

        // Use prepared statement to prevent SQL injection
        $stmt = $con->prepare("SELECT * FROM users WHERE id = ? LIMIT 1");
        if (!$stmt) {
            error_log("Prepared statement failed: " . $con->error);
            return false; // Or handle the error appropriately for your application
        }
        $stmt->bind_param("i", $id); // Assuming user_id is an integer. Adjust as needed.
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $user_data = $result->fetch_assoc();
            $stmt->close();
            return $user_data;
        } else {
            // Handle potential session invalidation or corrupted data. Log the error.
            error_log("Invalid session or user ID: " . $id);
            session_unset(); //Invalidate the session
            session_destroy();
            header("Location: login.php");
            die;
        }
        $stmt->close();
    }

    // Redirect to login
    header("Location: login.php");
    die;
}

function random_num($length) {
    if (!is_numeric($length) || $length < 5) {
        $length = 5; // Default length if input is invalid.  Log the error.
        error_log("Invalid length provided to random_num(). Using default length of 5.");
    }

    // Use random_int for cryptographically secure random numbers
    $text = "";
    for ($i = 0; $i < $length; $i++) {
        $text .= random_int(0, 9);
    }
    return $text;
}
?>