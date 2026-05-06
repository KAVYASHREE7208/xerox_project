<?php 
include 'db.php';
include 'header.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $service = $_POST['service'];
    $copies = (int)$_POST['copies'];
    
    // File upload logic
    $filename = "no_file";
    if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
        if (!is_dir('uploads')) { mkdir('uploads'); }
        $filename = time() . "_" . $_FILES['file']['name'];
        move_uploaded_file($_FILES['file']['tmp_name'], "uploads/" . $filename);
    }

    // Pricing Calculation (Same as your app.py)
    $total = 0;
    if ($service == "printing") {
        $type = $_POST['type'] ?? 'b';
        $side = $_POST['side'] ?? 's';
        $rate = ($type == 'b') ? 2 : 10;
        if ($side == 'd') $rate *= 1.5;
        $total = $rate * $copies;
    } elseif ($service == "lamination") {
        $l_type = $_POST['type'] ?? 'a';
        $total = ($l_type == 'a' ? 30 : 15) * $copies;
    } elseif ($service == "spiral") {
        $cover = $_POST['cover'] ?? 't';
        $total = ($cover == 'h' ? 40 : 25) * $copies;
    }

    // Save to Database
    $stmt = $db->prepare("INSERT INTO orders (name, phone, service, total, file_name, status) VALUES (?, ?, ?, ?, ?, 'Pending')");
    $stmt->execute([$name, $phone, $service, $total, $filename]);
    $order_id = $db->lastInsertId();

    echo "<h2>✅ Order Placed Successfully!</h2>";
    echo "<p>Your Order ID is: <b>$order_id</b></p>";
    echo "<p>Total Amount: ₹$total</p>";
    echo '<br><a href="index.php">Go Home</a>';
    exit;
}
?>

<script>
    function showFields() {
        var service = document.getElementById("service").value;
        document.getElementById("printingOptions").style.display = (service == "printing") ? "block" : "none";
        document.getElementById("laminationOptions").style.display = (service == "lamination") ? "block" : "none";
        document.getElementById("spiralOptions").style.display = (service == "spiral") ? "block" : "none";
    }
</script>

<h2>📄 Quick Order</h2>
<form method="POST" enctype="multipart/form-data">
    Name: <input type="text" name="name" required><br><br>
    Phone: <input type="text" name="phone" required><br><br>

    Service:
    <select name="service" id="service" onchange="showFields()">
        <option value="printing">Printing</option>
        <option value="lamination">Lamination</option>
        <option value="spiral">Spiral Binding</option>
    </select><br><br>

    Number of Copies: <input type="number" name="copies" value="1" min="1" required><br><br>

    <div id="printingOptions">
        Color: <select name="type"><option value="b">Black & White</option><option value="c">Color</option></select><br><br>
        Sides: <select name="side"><option value="s">Single Side</option><option value="d">Double Side</option></select><br><br>
    </div>

    <div id="laminationOptions" style="display:none;">
        Size: <select name="type"><option value="a">A4 Size</option><option value="i">ID Card</option></select><br><br>
    </div>

    <div id="spiralOptions" style="display:none;">
        Cover Type: <select name="cover"><option value="t">Transparent</option><option value="h">Hard Cover</option></select><br><br>
    </div>

    Upload Document: <input type="file" name="file" required><br><br>
    <button type="submit">Submit Order</button>
</form>
<br><a href="index.php">⬅ Back</a>
<?php include 'footer.php'; ?>