<?php
$host = 'localhost';  
$dbname = 'Donation';  
$username = 'root';  
$password = ''; 

// إنشاء الاتصال
$conn = new mysqli($host, $username, $password, $dbname);

// التحقق من الاتصال
if ($conn->connect_error) {
    die("❌ فشل الاتصال بقاعدة البيانات: " . $conn->connect_error);
} 

// ضبط ترميز الأحرف إلى UTF-8
$conn->set_charset("utf8");

?>
