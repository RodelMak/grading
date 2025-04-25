<?php
session_start();
include("studentconn.php");
include("functions.php");

$user_data = check_login($con);

include 'includes/header.php';
include 'includes/navbar.php';

echo '<div class="card" style="width: 18rem; filter: drop-shadow(0 0 10px #fff);">';
echo '<img src="https://avatar.iran.liara.run/public/boy" class="card-img-top" alt="...">';
echo '<div class="card-body">';
echo '<h1><p class="card-text bg-success" ><?php echo "Welcome, " . $user_data["name"] .  " ! Your user ID is: " . $user_data["user_id"];?></p></h1>';
echo '</div>';
echo '</div>';


if (!$user_data) {
    // Redirect to login if not logged in
    header("Location: login.php");
    die;
} else {
    // Display user account information
    echo "Welcome, " . $user_data['first_name'] . " " . $user_data['last_name'] . "! Your user ID is: " . $user_data['user_id'];
    // Add more account-specific content here
}
?>






