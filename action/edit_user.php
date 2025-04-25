<?php
include 'includes/header.php';
include 'includes/navbar.php';
include 'connection.php';

// Check if user_id is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid user ID.");
}
$user_id = $_GET['id'];

// Fetch user data
$sql = "SELECT user_id, name FROM users WHERE user_id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("User not found.");
}

$row = $result->fetch_assoc();
$name = $row['name'];
$stmt->close();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    if (empty($name) || !preg_match('/^[a-zA-Z\s]+$/', $name)) {
        $error_message = "Invalid input. Name must contain only letters and spaces.";
    } else {
        $update_sql = "UPDATE users SET name = ? WHERE user_id = ?";
        $update_stmt = $con->prepare($update_sql);
        $update_stmt->bind_param("si", $name, $user_id);
        if ($update_stmt->execute()) {
            header("Location: users.php");
            exit();
        } else {
            $error_message = "Error updating user: " . $update_stmt->error;
        }
        $update_stmt->close();
    }
}

?>

<h2>Edit User</h2>
<?php if (isset($error_message)) echo "<p style='color:red;'>$error_message</p>"; ?>
<form method="post">
  <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
  <div class="mb-3">
    <label for="name" class="form-label">Name</label>
    <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" required>
  </div>
  <button type="submit" class="btn btn-primary">Update</button>
</form>

<?php
mysqli_close($con);
include 'includes/footer.php';
?>