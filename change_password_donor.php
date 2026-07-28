<?php
session_start();
include 'db_connect.php'; // الاتصال بقاعدة البيانات

// التأكد من أن المتبرع مسجّل الدخول
if (!isset($_SESSION['donor_id'])) {
    die("Error: Unauthorized access!");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $donor_id = $_SESSION['donor_id'];
    $new_password = isset($_POST['password']) ? trim($_POST['password']) : '';

    // التحقق من صحة كلمة المرور الجديدة
    if (empty($new_password) || strlen($new_password) < 6) {
        die("Error: يجب أن تكون كلمة المرور 6 أحرف أو أكثر!");
    }

    // تحديث كلمة المرور في قاعدة البيانات بدون تشفير
    $sql = "UPDATE donors SET password=? WHERE id=?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("si", $new_password, $donor_id);

        if ($stmt->execute()) {
            echo "تم تغيير كلمة المرور بنجاح!";
        } else {
            die("Error: لم يتم تحديث كلمة المرور.");
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
