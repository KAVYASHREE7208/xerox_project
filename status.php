<?php 
include 'db.php';
include 'header.php'; 
?>
<h2>Check Order Status</h2>

<?php if (isset($_POST['id'])): 
    $stmt = $db->prepare("SELECT status FROM orders WHERE id = ?");
    $stmt->execute([$_POST['id']]);
    $order = $stmt->fetch();
    if ($order): ?>
        <h3>Order ID: <?php echo htmlspecialchars($_POST['id']); ?></h3>
        <h4>Status: <?php echo htmlspecialchars($order['status']); ?></h4>
    <?php else: ?>
        <h3>Invalid Order ID!</h3>
    <?php endif; ?>
    <br><a href="status.php">Try Again</a>
<?php else: ?>
    <form method="POST">
        Order ID: <input type="number" name="id" required><br><br>
        <button type="submit">Check Status</button>
    </form>
<?php endif; ?>

<br><a href="index.php">Back</a>
<?php include 'footer.php'; ?>