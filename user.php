<?php
session_start();
include 'db_connect.php';

// التحقق مما إذا كان المستخدم مسجلاً الدخول
if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php"); // تحويل المستخدم إلى صفحة تسجيل الدخول إذا لم يكن مسجلاً
    exit();
}

// جلب بيانات المستخدم من قاعدة البيانات
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "حدث خطأ، المستخدم غير موجود.";
    exit();
}

$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="Donor.css">
    
    <style>
    label {
        text-align: right;
        display: block;
    }
    </style>

    <title>ملف المستخدم</title>
    
</head>

<body>
    <div class="navbar">
        <div>بيانات المستخدم</div>
        <input type="text" placeholder="البحث...">
    </div>

    <div class="nav">
        <a href="home.php" data-lang="home">الصفحة الرئيسية |</a>
       
        <div class="dropdown" onclick="toggleDropdown()">
            <span class="dropbtn" data-lang="services">الخدمات</span>
            <div class="dropdown-content">
                <a href="Reports_user.php">البلاغات</a>
                <a href="map_user.html">الخريطة</a>
                <a href="Water areas_user.html">مناطق المياه الجافة  </a>
                <a href="user.php">حسابي</a>
                <a href="logout.php">تسجيل الخروج </a>
            </div>
        </div>

    </div>

    <div class="user-section">
        <div class="card">
            <h2>ملف المستخدم</h2>
            <p><?php echo date('l, d F Y'); ?></p>
            <div class="user-info">
                <img src="img/pr.png" alt="صورة المستخدم">
                <div>
                    <h2><?php echo htmlspecialchars($user['username']); ?></h2>
                    <p>البريد الإلكتروني: <span id="user-email"><?php echo htmlspecialchars($user['email']); ?></span></p>
                </div>
                <button class="btn" onclick="updateUser()">تحديث</button>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label>الاسم كامل</label>
                    <input type="text" id="username" value="<?php echo htmlspecialchars($user['username']); ?>">
                </div>

                <div class="form-group">
                    <label>البريد الإلكتروني</label>
                    <input type="email" id="email" value="<?php echo htmlspecialchars($user['email']); ?>">
                </div>

                <div class="form-group">
                    <label>الجنس</label>
                    <select id="gender">
                        <option value="ذكر" <?php echo ($user['gender'] == 'ذكر') ? 'selected' : ''; ?>>ذكر</option>
                        <option value="أنثى" <?php echo ($user['gender'] == 'أنثى') ? 'selected' : ''; ?>>أنثى</option>
                    </select>
                </div>

               

                <div class="form-group">
    <label>الدولة</label>
    <input type="text" id="address" value="<?php echo htmlspecialchars($user['address'] ?? ''); ?>">
</div>

                <div class="form-group">
                    <label>اللغة</label>
                    <input type="text" value="العربية" disabled>
                </div>



                <div class="form-group">
                    <label>رقم الهاتف</label>
                    <input type="text" id="phone_number" value="<?php echo htmlspecialchars($user['phone_number'] ?? 'غير متوفر'); ?>">
                </div>
            </div>

          <label> <h3>إعدادات الحساب</h3></label> 
            <div class="actions">
                <button class="btn" onclick="changePassword()">تغيير كلمة المرور</button>
                <button class="btn" onclick="logout()">تسجيل خروج</button>
            </div>
        </div>
    </div>

    <script>
        function updateUser() {
    let username = $("#username").val();
    let email = $("#email").val();
    let gender = $("#gender").val();
    let phone_number = $("#phone_number").val();
    let address = $("#address").val(); // Get the address

    console.log("Address:", address); // Debugging log

    $.post("update_user.php", {
        username: username,
        email: email,
        gender: gender,
        phone_number: phone_number,
        address: address // Send address to the backend
    }, function (response) {
        console.log("Response:", response);
        alert(response);
    });
}



        function changePassword() {
            let newPassword = prompt("أدخل كلمة السر الجديدة:");
            if (newPassword && newPassword.length >= 6) {
                $.post("change_password.php", {
                    password: newPassword
                }, function(response) {
                    alert(response);
                });
            } else {
                alert("يجب أن تكون كلمة السر 6 أحرف أو أكثر!");
            }
        }

        function logout() {
            window.location.href = 'logout.php';
        }
    </script>
</body>

</html>