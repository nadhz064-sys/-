<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "لم يتم تسجيل الدخول"]);
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'];

    if ($action == "add") {
        $address = trim($_POST['address']);
        $date = date("Y-m-d");
        $time = date("H:i:s");

        if (empty($address)) {
            echo json_encode(["success" => false, "message" => "العنوان مطلوب"]);
            exit;
        }

        $stmt = $conn->prepare("INSERT INTO reports (user_id, address, time, date) VALUES (:user_id, :address, :time, :date)");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':time', $time);
        $stmt->bindParam(':date', $date);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "تم إضافة البلاغ"]);
        } else {
            echo json_encode(["success" => false, "message" => "خطأ في إضافة البلاغ"]);
        }

    } elseif ($action == "update") {
        $id = $_POST['id'];
        $address = trim($_POST['address']);
        $date = trim($_POST['date']);
        $time = trim($_POST['time']);

        $stmt = $conn->prepare("UPDATE reports SET address = :address, date = :date, time = :time WHERE id = :id AND user_id = :user_id");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':time', $time);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "تم تحديث البلاغ"]);
        } else {
            echo json_encode(["success" => false, "message" => "خطأ في تحديث البلاغ"]);
        }
    }
}
?>
