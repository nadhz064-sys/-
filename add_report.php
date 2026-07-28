<?php
session_start();
include 'db_connect.php'; // الاتصال بقاعدة البيانات

if (!isset($_SESSION['admin_id']) || $_SESSION['admin_id'] != 1) {
    die("Error: Unauthorized access!");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $address = isset($_POST['address']) ? trim($_POST['address']) : '';
    $date = isset($_POST['date']) ? trim($_POST['date']) : '';
    $time = isset($_POST['time']) ? trim($_POST['time']) : '';
    $status = isset($_POST['status']) ? trim($_POST['status']) : '';

    if (empty($address) || empty($date) || empty($time) || empty($status)) {
        die("Error: Missing fields");
    }

    $sql = "INSERT INTO reports (address, date, time, status) VALUES (?, ?, ?, ?)";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssss", $address, $date, $time, $status);
        if ($stmt->execute()) {
            echo "تمت إضافة البلاغ بنجاح!";
        } else {
            die("Error: Failed to add report.");
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
