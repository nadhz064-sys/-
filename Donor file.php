<?php
session_start();
include 'db_connect.php'; // الاتصال بقاعدة البيانات

// التحقق مما إذا كان المتبرع مسجلاً الدخول
if (!isset($_SESSION['donor_id'])) {
    header("Location: signin.php"); // تحويل المتبرع إلى صفحة تسجيل الدخول إذا لم يكن مسجلاً
    exit();
}

// جلب بيانات المتبرع من قاعدة البيانات
$donor_id = $_SESSION['donor_id'];
$sql = "SELECT * FROM donors WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $donor_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "حدث خطأ، المتبرع غير موجود.";
    exit();
}

$donor = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>ملف المتبرع</title>
    <link rel="stylesheet" href="Donor.css">

</head>

<body>
    <div class="navbar">
        <div>بيانات المتبرع</div>
        <input type="text" placeholder="البحث...">
    </div>

    
    <div class="nav">
        <a href="home.php" data-lang="home">الصفحة الرئيسية |</a>
       
        <div class="dropdown" onclick="toggleDropdown()">
            <span class="dropbtn" data-lang="services">الخدمات</span>
            <div class="dropdown-content">
                <a href="Donations.html">تبرع</a>
                <a href="map.html">الخريطة</a>
                <a href="Reports_donor.php">البلاغات</a>
                <a href="Water areas.html">مناطق المياه</a>
                <a href="Donor File.php">حسابي</a>
                <a href="logout.php">تسجيل الخروج </a>
            </div>
        </div>

    </div>

    <div class="user-section">
        <div class="card">
            <h2>ملف المتبرع</h2>
            <p><?php echo date('l, d F Y'); ?></p>
            <div class="user-info">
                <img src="img/pr.png" alt="صورة المستخدم">
                <div>
                    <h2><?php echo htmlspecialchars($donor['name']); ?></h2>
                    <p>البريد الإلكتروني: <span id="donor-email"><?php echo htmlspecialchars($donor['email']); ?></span></p>
                </div>
                <button class="btn" onclick="updateDonor()">تحديث</button>
            </div>
            <div class="form-grid">
            <div class="form-group">
                <label>الاسم كامل</label>
                <input type="text" id="donor_name" value="<?php echo htmlspecialchars($donor['name']); ?>">
            </div>

            <div class="form-group">
                <label>البريد الإلكتروني</label>
                <input type="email" id="donor_email" value="<?php echo htmlspecialchars($donor['email']); ?>">
            </div>

            <div class="form-group">
                <label>الجنس</label>
                <select id="gender">
                    <option value="ذكر" <?php echo ($donor['gender'] == 'ذكر') ? 'selected' : ''; ?>>ذكر</option>
                    <option value="أنثى" <?php echo ($donor['gender'] == 'أنثى') ? 'selected' : ''; ?>>أنثى</option>
                </select>
            </div>

            
            <div class="form-group">
    <label>العنوان</label>
    <input type="text" id="address" value="<?php echo htmlspecialchars($donor['address'] ?? ''); ?>">
</div>

            


            <div class="form-group">
                <label>رقم الهاتف</label>
                <input type="text" id="phone_number" value="<?php echo htmlspecialchars($donor['phone_number'] ?? 'غير متوفر'); ?>">
            </div>

            <div class="form-group">
                <label>نوع التبرع</label>
                <input type="text" id="donation" value="<?php echo htmlspecialchars($donor['donation']); ?>">
            </div>
            </div>

            <h3>إعدادات الحساب</h3>
            <div class="actions">
                <button class="btn" onclick="changePassword()">تغيير كلمة المرور</button>
                <button class="btn" onclick="logout()">تسجيل خروج</button>
            </div>
        </div>
    </div>

    <script>
        function updateDonor() {
    let name = $("#donor_name").val();
    let email = $("#donor_email").val();
    let gender = $("#gender").val();
    let phone_number = $("#phone_number").val();
    let address = $("#address").val(); // New address field
    let donation = $("#donation").val();

    console.log("Updating donor:", { name, email, gender, phone_number, address, donation });

    $.post("update_donor.php", {
        name: name,
        email: email,
        gender: gender,
        phone_number: phone_number,
        address: address, // Send address
        donation: donation
    }, function (response) {
        console.log("Response:", response);
        alert(response);
    });
}

        function changePassword() {
            let newPassword = prompt("أدخل كلمة السر الجديدة:");
            if (newPassword && newPassword.length >= 6) {
                $.post("change_password_donor.php", {
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