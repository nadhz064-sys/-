<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate input
    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];
    $type     = $_POST['account_type'];

    if (!$name || !$email || !$password || !$type) {
        die("يرجى تعبئة جميع الحقول.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("البريد الإلكتروني غير صالح.");
    }

    // Check if email exists in both tables
    $emailExists = false;

    if ($type === 'user') {
        $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    } elseif ($type === 'donor') {
        $check = $conn->prepare("SELECT id FROM donors WHERE email = ?");
    } else {
        die("نوع الحساب غير صحيح.");
    }

    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();
    if ($check->num_rows > 0) {
        die("البريد الإلكتروني مستخدم بالفعل.");
    }
    $check->close();

    
    // Insert based on account type
    if ($type === 'user') {
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $password);
    } elseif ($type === 'donor') {
        $default_donation = "تبرع";
        $gender = "غير محدد";
        $stmt = $conn->prepare("INSERT INTO donors (name, email, password, donation, gender) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $email, $password, $default_donation, $gender);
    }

    if ($stmt->execute()) {
        echo "تم إنشاء الحساب بنجاح!";
    } else {
        echo "حدث خطأ أثناء إنشاء الحساب: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "الطريقة غير مدعومة.";
}
?>
