<?php
session_start();
include 'db_connect.php'; // الاتصال بقاعدة البيانات

// التأكد من أن المستخدم هو متبرع مسجّل الدخول
if (!isset($_SESSION['donor_id'])) {
    echo "يجب تسجيل الدخول كمتبرع للوصول إلى هذه الصفحة!";
    exit();
}

$donor_id = $_SESSION['donor_id']; // تخزين رقم المتبرع المسجل

// جلب البلاغات التي قدمها هذا المتبرع فقط
$sql = "SELECT id, address, time, date, status FROM reports WHERE donor_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $donor_id);
$stmt->execute();
$result = $stmt->get_result();

// جلب عدد البلاغات الكلي الخاصة بهذا المتبرع
$sql_total = "SELECT COUNT(*) as total FROM reports WHERE donor_id = ?";
$stmt_total = $conn->prepare($sql_total);
$stmt_total->bind_param("i", $donor_id);
$stmt_total->execute();
$result_total = $stmt_total->get_result();
$total_reports = ($result_total->num_rows > 0) ? $result_total->fetch_assoc()['total'] : 0;

// جلب عدد البلاغات قيد المعالجة الخاصة بهذا المتبرع
$sql_inProgress = "SELECT COUNT(*) as in_progress FROM reports WHERE donor_id = ? AND status = 'قيد المعالجة'";
$stmt_inProgress = $conn->prepare($sql_inProgress);
$stmt_inProgress->bind_param("i", $donor_id);
$stmt_inProgress->execute();
$result_inProgress = $stmt_inProgress->get_result();
$inProgress_count = ($result_inProgress->num_rows > 0) ? $result_inProgress->fetch_assoc()['in_progress'] : 0;

?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="Donor.css">

    <title>نظام البلاغات</title>
   
</head>
<body>
<div class="language-switch">
    <select onchange="changeLanguage(this.value)">
        <option value="ar" selected>عربي</option>
        <option value="en">English</option>
    </select>
</div>

<div class="navbar">
    <div>نظام البلاغات</div>
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
                <a href="Water areas.html">مناطق المياه الجافة</a>
                <a href="Donor File.php">حسابي</a>
                <a href="logout.php">تسجيل الخروج </a>
            </div>
        </div>

    </div>


    
<div class="progress-container">
<div class="progress-step">
    <div><a href="Filereport.html">🔼</a></div>
    <span data-lang="رفع البلاغ">رفع البلاغ</span>
</div>
</div>

<div class="table-container">
    <h2>بلاغاتي</h2>
    <table>
    <thead>
    <tr>
        <th>رقم البلاغ</th>
        <th>موقع البلاغ</th>
        <th>التاريخ</th>
        <th>الوقت</th>
        <th>الحالة</th>
        <th>الصورة</th>
    </tr>
    </thead>
    <tbody>
    <?php
$stmt = $conn->prepare("SELECT id, address, date, time, status, photo FROM reports WHERE donor_id = ? ORDER BY date DESC");
$stmt->bind_param("i", $donor_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['address']}</td>
                <td>{$row['date']}</td>
                <td>{$row['time']}</td>
                <td>{$row['status']}</td>
                <td>";
        if ($row['photo']) {
            echo "<a href='uploads/reports/{$row['photo']}' target='_blank'>
            <img src='uploads/reports/{$row['photo']}' width='100' alt='Report Photo'>
          </a>";
        } else {
            echo "لا توجد صورة";
        }
        echo "</td></tr>";
    }
} else {
    echo "<tr><td colspan='6'>لا توجد بلاغات</td></tr>";
}
?>

    </tbody>
</table>

</div>

<div class="stats">
    <div class="stat-box">
        <h3>بلاغات قيد المعالجة</h3>
        <p id="inProgressCount"><?php echo $inProgress_count; ?></p>
    </div>
    <div class="stat-box">
        <h3>عدد البلاغات</h3>
        <p id="totalReports"><?php echo $total_reports; ?></p>
    </div>
</div>

</body>
</html>

<?php
$conn->close();
?>


</body>


</html>
