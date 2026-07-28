<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    echo "يجب تسجيل الدخول أولاً!";
    exit();
}

$user_id = $_SESSION['user_id'];

// Retrieve data from the form
$username = $_POST['username'];
$email = $_POST['email'];
$gender = $_POST['gender'];
$phone_number = $_POST['phone_number'];
$address = $_POST['address']; // Get the address

$sql = "UPDATE users SET username=?, email=?, gender=?, phone_number=?, address=? WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssi", $username, $email, $gender, $phone_number, $address, $user_id);

if ($stmt->execute()) {
    echo "تم تحديث البيانات بنجاح!" ;
} else {
    echo "حدث خطأ أثناء التحديث! " ;
}


$stmt->close();
$conn->close();
?>
