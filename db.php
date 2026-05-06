<?php
// db.php - Database connection
try {
    $db = new PDO('sqlite:xerox.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Table create panrathuku (Python init_db logic)
    $db->exec("CREATE TABLE IF NOT EXISTS orders (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT,
        phone TEXT,
        service TEXT,
        total REAL,
        file_name TEXT,
        status TEXT
    )");
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>