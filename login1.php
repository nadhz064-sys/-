<?php
session_start();
include 'db_connect.php'; // الاتصال بقاعدة البيانات

$error = ""; // متغير لتخزين رسالة الخطأ

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // البحث عن المستخدم في جدول users
    $sql = "SELECT id FROM users WHERE email = '$email' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // تخزين بيانات المستخدم في الجلسة
        $_SESSION['user_id'] = $user['id'];

        header("Location: user.php"); // تحويل المستخدم إلى صفحته
        exit();
    } else {
        // إذا لم يتم العثور على المستخدم، البحث في جدول الشركات
        $sql = "SELECT id FROM company WHERE email = '$email' AND password = '$password'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $company = $result->fetch_assoc();
            
            // تخزين بيانات الأدمن في الجلسة
            $_SESSION['admin_id'] = $company['id'];

            header("Location: Reports.php"); // تحويل الأدمن إلى لوحة التحكم
            exit();
        } else {
            // إذا لم يتم العثور على المستخدم ولا الأدمن، البحث في جدول المتبرعين
            $sql = "SELECT id FROM donors WHERE email = '$email' AND password = '$password'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $donor = $result->fetch_assoc();
                
                // تخزين بيانات المتبرع في الجلسة
                $_SESSION['donor_id'] = $donor['id'];

                header("Location: Donor file.php"); // تحويل المتبرع إلى صفحته
                exit();
            } else {
                $error = "البريد الإلكتروني أو كلمة المرور غير صحيحة!";
            }
        }
    }
}
?>



<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<div class="container">
    <div class="form-section">
        <h1>مرحبًا بعودتك!</h1>
        <p>أدخل بيانات الاعتماد الخاصة بك للوصول إلى حسابك</p>

        <!-- عرض رسالة الخطأ إن وجدت -->
        <?php if (!empty($error)) { ?>
            <p style="color: red; font-weight: bold;"><?php echo $error; ?></p>
        <?php } ?>

        <form action="login1.php" method="POST">
            <label for="email">عنوان البريد الإلكتروني</label>
            <input type="email" name="email" id="email" placeholder="البريد الإلكتروني" required>

            <label for="password">كلمة المرور </label>
            <input type="password" name="password" id="password" placeholder="********" required>

            <div class="remember-me">
                <input type="checkbox" id="remember">
                <label for="remember">تذكرني</label>
            </div>

            <a href="#">نسيت كلمة المرور؟</a>

            <button type="submit">تسجيل دخول</button>
        </form>

        <p class="or-text">أو</p>

        <div class="social-login">
            <button class="google-btn"><img src="img/google.png" alt="Google"> تسجيل الدخول مع Google</button>
            <button class="apple-btn"><img src="https://upload.wikimedia.org/wikipedia/commons/f/fa/Apple_logo_black.svg" alt="Apple"> تسجيل الدخول مع Apple</button>
        </div>

        <p class="register-text">ليس لديك حساب؟ <a href="signup.html">إنشاء حساب</a></p>
    </div>

    <div class="image-section">
        <img src="img/alula.png" alt="صورة تسجيل الدخول">
    </div>
</div>

</body>
</html>