<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    echo "يجب تسجيل الدخول أولاً!";
    exit();
}

$user_id = $_SESSION['user_id'];
$new_password = $_POST['password']; // حفظ كلمة المرور كما هي

$sql = "UPDATE users SET password=? WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $new_password, $user_id);

if ($stmt->execute()) {
    echo "تم تغيير كلمة المرور بنجاح!";
} else {
    echo "حدث خطأ أثناء تحديث كلمة المرور!";
}

$stmt->close();
$conn->close();
?>
