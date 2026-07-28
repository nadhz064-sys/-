<?php
session_start();
include 'db_connect.php'; // الاتصال بقاعدة البيانات

// التحقق من تسجيل دخول المتبرع
if (!isset($_SESSION['donor_id'])) {
    die("Error: يجب تسجيل الدخول أولاً!");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $donor_id = $_SESSION['donor_id'];
    $liters = isset($_POST['liters']) ? intval($_POST['liters']) : 0;
    $amount = isset($_POST['amount']) ? floatval($_POST['amount']) : 0;
    $date = date("Y-m-d");
    $time = date("H:i:s");

    if ($liters <= 0 || $amount <= 0) {
        die("Error: يرجى تحديد كمية الماء الصحيحة!");
    }

    $sql = "INSERT INTO donations (donor_id, date, time, water_liters, amount_donated) VALUES (?, ?, ?, ?, ?)";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("issdd", $donor_id, $date, $time, $liters, $amount);
        if ($stmt->execute()) {
            echo "تم التبرع بنجاح!";
        } else {
            die("Error: حدث خطأ أثناء حفظ التبرع.");
        }
        $stmt->close();
    } else {
        die("Error: فشل في تجهيز الاستعلام.");
    }

    $conn->close();
} else {
    die("Error: Invalid request.");
}
?>
