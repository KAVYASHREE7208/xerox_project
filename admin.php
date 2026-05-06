<?php
session_start();
include 'db.php';
if (!isset($_SESSION['admin_logged_in'])) { header("Location: login.php"); exit; }

// Update Logic
if (isset($_POST['update_id'])) {
    $stmt = $db->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->execute([$_POST['status'], $_POST['update_id']]);
}

// Logout Logic
if (isset($_GET['logout'])) { session_destroy(); header("Location: index.php"); exit; }

$orders = $db->query("SELECT * FROM orders ORDER BY id DESC")->fetchAll();
include 'header.php';
?>
<h2>🚀 Admin Dashboard</h2>
<a href="?logout=true">Logout</a>
<hr>
<table border="1" cellpadding="10">
    <tr>
        <th>ID</th><th>Name</th><th>Service</th><th>Total</th><th>Status</th><th>Action</th>
    </tr>
    <?php foreach ($orders as $order): ?>
    <tr>
        <td><?php echo $order['id']; ?></td>
        <td><?php echo $order['name']; ?></td>
        <td><?php echo $order['service']; ?></td>
        <td>₹<?php echo $order['total']; ?></td>
        <td><b><?php echo $order['status']; ?></b></td>
        <td>
            <form method="POST">
                <input type="hidden" name="update_id" value="<?php echo $order['id']; ?>">
                <select name="status">
                    <option value="Pending">Pending</option>
                    <option value="Ready">Ready</option>
                    <option value="Completed">Completed</option>
                </select>
                <button type="submit">Update</button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
<?php include 'footer.php'; ?>