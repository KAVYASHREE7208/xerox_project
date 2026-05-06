<?php
session_start();
include 'header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_POST['username'] == "admin" && $_POST['password'] == "1234") {
        $_SESSION['admin_logged_in'] = true;
        header("Location: admin.php");
        exit;
    } else {
        echo "<h3>Invalid Login! <a href='login.php'>Try Again</a></h3>";
        exit;
    }
}
?>
<h2>🔐 Admin Login</h2>
<form method="POST">
    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <button type="submit">Login</button>
</form>
<br><a href="index.php">Back to Home</a>
<?php include 'footer.php'; ?>