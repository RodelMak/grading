<?php include 'includes/header.php'; ?>
<?php include 'includes/navbar.php'; ?>

<form action="send_email.php" method="post">  <!-- Point to your PHP script -->
  <label for="name">Name:</label><br>
  <input type="text" id="name" name="name" required><br>
  <label for="email">Email:</label><br>
  <input type="email" id="email" name="email" required><br>
  <label for="comment">Comment:</label><br>
  <textarea id="comment" name="comment" rows="4" cols="50" required></textarea><br><br>
  <input type="submit" value="Submit">
</form>


<?php include 'includes/footer.php'; ?>
