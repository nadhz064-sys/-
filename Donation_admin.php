<?php
session_start();
include 'db_connect.php';

// ✅ تحقق من تسجيل دخول الأدمن
if (!isset($_SESSION['admin_id']) || $_SESSION['admin_id'] != 1) {
    die("غير مصرح لك بالوصول إلى هذه الصفحة! يرجى تسجيل الدخول كمشرف.");
}

// استعلام التبرعات
$sql = "SELECT donations.*, donors.name AS donor_name 
        FROM donations 
        JOIN donors ON donations.donor_id = donors.id 
        ORDER BY donations.date DESC, donations.time DESC";

$result = $conn->query($sql);
?>



<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="Donor.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f3ed;
            margin: 0;
            padding: 0;
        }

        .navbar {
            background-color: #5d3a2d;
            padding: 15px;
            color: white;
            text-align: center;
            font-size: 24px;
        }

        .container {
            width: 90%;
            margin: 30px auto;
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #5d3a2d;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: center;
        }

        th {
            background-color: #f0e0d0;
            color: #5d3a2d;
        }

        tr:hover {
            background-color: #f9f3ef;
        }
    </style>
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
    <div>سجل التبرعات</div>
    <input type="text" placeholder="البحث...">
</div>
<div class="nav">
    <a href="home.php" data-lang="home">الصفحة الرئيسية |</a>
    <div class="dropdown" onclick="toggleDropdown()">
        <span class="dropbtn" data-lang="services">الخدمات</span>
        <div class="dropdown-content">
            <a href="Reports.php">البلاغات</a>
            <a href="Donation_admin.php">التبرعات</a>
            <a href="map_admin.html">الخريطة</a>
            <a href="Water areas_admin.html"> مناطق المياه الجافة </a>
            <a href="logout.php">تسجيل الخروج </a>
        </div>
    </div>
</div>

<div class="container">
    <h2>جميع التبرعات</h2>
    <table>
        <thead>
            <tr>
                <th>اسم المتبرع</th>
                <th>عدد الجالونات</th>
                <th>قيمة التبرع (ر.س)</th>
                <th>تاريخ التبرع</th>
                <th>الوقت</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['donor_name']}</td>
                            <td>{$row['water_liters']} لتر</td>
                            <td>{$row['amount_donated']} ر.س</td>
                            <td>{$row['date']}</td>
                            <td>{$row['time']}</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='5'>لا توجد تبرعات حالياً.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

</body>
</html>

<?php $conn->close(); ?>
