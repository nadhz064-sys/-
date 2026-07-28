<?php
session_start();
include 'db_connect.php'; // الاتصال بقاعدة البيانات

if (!isset($_SESSION['admin_id']) || $_SESSION['admin_id'] != 1) {
    die("Error: Unauthorized access!");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $address = isset($_POST['address']) ? trim($_POST['address']) : '';
    $date = isset($_POST['date']) ? trim($_POST['date']) : '';
    $time = isset($_POST['time']) ? trim($_POST['time']) : '';
    $status = isset($_POST['status']) ? trim($_POST['status']) : '';

    if ($id == 0 || empty($address) || empty($date) || empty($time) || empty($status)) {
        die("Error: Missing fields");
    }

    error_log("Received Data: ID=$id, Address=$address, Date=$date, Time=$time, Status=$status");

    $sql = "UPDATE reports SET address=?, date=?, time=?, status=? WHERE id=?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssssi", $address, $date, $time, $status, $id);
        if ($stmt->execute()) {
            echo "تم التحديث بنجاح!";
        } else {
            die("Error: Failed to update record. " . $stmt->error);
        }
        $stmt->close();
    } else {
        die("Error: Failed to prepare SQL.");
    }

    $conn->close();
} else {
    die("Error: Invalid request method.");
}
?>
