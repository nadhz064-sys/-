<?php
session_start();
include 'db_connect.php'; // الاتصال بقاعدة البيانات

// ✅ Check if a user or donor is logged in
if (!isset($_SESSION['user_id']) && !isset($_SESSION['donor_id'])) {
    die("Error: يجب تسجيل الدخول لإرسال البلاغ.");
}

$user_id = $_SESSION['user_id'] ?? NULL;
$donor_id = $_SESSION['donor_id'] ?? NULL;
$address = $_POST['address'];
$date = $_POST['date'];
$time = $_POST['time'];
$status = "قيد المعالجة"; // Default status
$photo = NULL;

// ✅ Handle file upload
if (!empty($_FILES['photo']['name'])) {
    $target_dir = "uploads/reports/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true); // Create directory if not exists
    }

    $file_name = time() . "_" . basename($_FILES["photo"]["name"]); // Unique filename
    $target_file = $target_dir . $file_name;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // ✅ Validate file type (only images allowed)
    $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($imageFileType, $allowed_types)) {
        die("Error: فقط ملفات JPG, JPEG, PNG، و GIF مسموحة!");
    }

    // ✅ Move file to the server
    if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
        $photo = $file_name; // Save filename in DB
    } else {
        die("Error: فشل في تحميل الصورة.");
    }
}

// ✅ Insert report into database
$sql = "INSERT INTO reports (user_id, donor_id, address, time, date, status, photo) 
        VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iisssss", $user_id, $donor_id, $address, $time, $date, $status, $photo);

if ($stmt->execute()) {
    if ($donor_id !== NULL) {
        header("Location: Reports_donor.php");
    } elseif ($user_id !== NULL) {
        header("Location: reports_user.php");
    } else {
        echo "تم إرسال البلاغ، ولكن لم يتم تحديد نوع المستخدم.";
    }
    exit();
} else {
    echo "Error: لم يتم إرسال البلاغ.";
}

$stmt->close();
$conn->close();
?>
