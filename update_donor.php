<?php
session_start();
include 'db_connect.php'; // الاتصال بقاعدة البيانات

// التحقق مما إذا كان المتبرع مسجلاً الدخول
if (!isset($_SESSION['donor_id'])) {
    die("Error: Unauthorized access!");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $donor_id = $_SESSION['donor_id'];
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $gender = isset($_POST['gender']) ? trim($_POST['gender']) : '';
    $phone_number = isset($_POST['phone_number']) ? trim($_POST['phone_number']) : '';
    $address = isset($_POST['address']) ? trim($_POST['address']) : ''; // New address field
    $donation = isset($_POST['donation']) ? trim($_POST['donation']) : '';

    // التحقق من القيم المدخلة
    if (empty($name) || empty($email) || empty($gender) || empty($donation)) {
        die("Error: جميع الحقول مطلوبة!");
    }

    // تحديث بيانات المتبرع في قاعدة البيانات
    $sql = "UPDATE donors SET name=?, email=?, gender=?, phone_number=?, address=?, donation=? WHERE id=?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssssssi", $name, $email, $gender, $phone_number, $address, $donation, $donor_id);

        if ($stmt->execute()) {
            echo "تم تحديث البيانات بنجاح!";
        } else {
            die("Error: لم يتم تحديث البيانات.");
        }
        $stmt->close();
    } else {
        die("Error: فشل في تجهيز الاستعلام.");
    }

    $conn->close();
} else {
    die("Error: Invalid request method.");
}
?>
