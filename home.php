<?php
include 'db_connect.php';

// إجمالي عدد المتبرعين
$sql_donors = "SELECT COUNT(*) as total_donors FROM donors";
$result_donors = $conn->query($sql_donors);
$total_donors = $result_donors->fetch_assoc()['total_donors'] ?? 0;
// عدد المستخدمين
$sql_users = "SELECT COUNT(*) as total_users FROM users";
$result_users = $conn->query($sql_users);
$total_users = $result_users->fetch_assoc()['total_users'] ?? 0;

// عدد البلاغات
$sql_reports = "SELECT COUNT(*) as total_reports FROM reports";
$result_reports = $conn->query($sql_reports);
$total_reports = $result_reports->fetch_assoc()['total_reports'] ?? 0;

// إجمالي التبرعات
$sql_payments = "SELECT SUM(amount_donated) as total_amount FROM donations";
$result_payments = $conn->query($sql_payments);
$total_amount = $result_payments->fetch_assoc()['total_amount'] ?? 0;

// تنسيق التبرعات: مثال → 1,234,567
$total_amount_formatted = number_format($total_amount, 2);
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مشروع التخرج</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f8f2e9;
        }

        .header {
            background-color: #5a3826;
            padding: 10px 30px;
            display: flex;
            flex-direction: column;
            color: white;
            width: 100%;
            /* اجعل الشريط يغطي العرض بالكامل */

        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            background-color: white;
            padding: 10px 10px;
            color: black;
            font-size: 18px;
            font-weight: bold;
            position: relative;
        }

        .nav {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            font-size: 20px;
            font-weight: bold;
        }

        .header-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
            padding: 10px 0;
            gap: 15px;
        }

        .header .logo img {
            height: 40px;
        }

        .search-container {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
        }

        .search {
            background: white;
            padding: 10px;
            width: 50%;
            border-radius: 10px;
            font-size: 16px;
            text-align: center;
            border: 1px solid #ccc;
        }

        .search-container .logo {
            margin-left: 10px;
        }

        .hero {
            text-align: center;
            color: #5a3826;
            padding: 50px 20px;
        }

        .hero h2 {
            font-size: 40px;
            font-weight: bold;
        }

        .hero p {
            font-size: 28px;
        }

        .hero-img {
            width: 100%;
            height: auto;
            border-radius: 10px;
        }

        .buttons {
            margin-top: 20px;
        }

        .button {
            background-color: #5a3826;
            color: white;
            padding: 15px 30px;
            margin: 5px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 18px;
        }

        .footer {
            background-color: #5a3826;
            text-align: center;
            color: white;
            padding: 15px;
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .social-icons {
            display: flex;
            gap: 15px;
            margin-left: 20px;
        }

        .social-icons img {
            width: 30px;
            height: 30px;
        }

        .top-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .user-icon {
            width: 30px;
            height: 30px;
            background-color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: black;
            font-weight: bold;
        }

        /* تنسيقات الإحصائيات */
        .statistics {
            position: absolute;
            right: 20px;
            top: 150px;
            background-color: #f8f2e9;
            padding: 10px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .stat-item {
            margin-bottom: 10px;
            font-size: 18px;
            color: #5a3826;
        }
    </style>
</head>

<body>

    <div class="header">
        <div class="top-bar">
            <style>
                .dropdown {
                    position: relative;
                    display: inline-block;
                    cursor: pointer;
                }

                .dropbtn {
                    display: flex;
                    align-items: center;
                    gap: 5px;
                }

                .dropbtn::after {
                    content: "▼";
                    /* سهم للأسفل */
                    font-size: 12px;
                    transition: transform 0.3s ease;
                }

                .dropdown-content {
                    display: none;
                    position: absolute;
                    background: white;
                    min-width: 150px;
                    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
                    text-align: right;
                    z-index: 1;
                }

                .dropdown-content a {
                    display: block;
                    padding: 10px;
                    text-decoration: none;
                    color: black;
                    transition: 0.3s ease;
                }

                .dropdown-content a:hover {
                    background: #ddd;
                }

                .dropdown.open .dropdown-content {
                    display: block;
                }

                .dropdown.open .dropbtn::after {
                    transform: rotate(180deg);
                }
            </style>

         

            <script>
                function toggleDropdown() {
                    document.querySelector(".dropdown").classList.toggle("open");
                }
            </script>
            <div class="top-right">
                <span>عربي | Eng</span>
                <a href="login1.php">
                    <div class="user-icon">👤
                </a>

            </div>

        </div>
    </div>

    <div class="header-content">
        <div class="search-container">
            <div class="logo">
                <img src="img/logo.png" alt="شعار">
            </div>
            <input class="search" type="text" placeholder="البحث">
        </div>
    </div>
    </div>
    <div class="hero">
        <img src="img/alula2.png" alt="صورة بيئية" class="hero-img">
        <h1>معًا نوصل المياه إلى كل من يحتاجها 💧</h1>
        <p>نساعد المجتمعات المحتاجة عبر مشاريع مستدامة توفر لهم مياهًا نظيفة و حلولا بيئية متطورة . <br>انضم الينا و ساهم في احداث فرق حقيقي . </p>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f8f2e9;
                text-align: center;
                padding: 50px;
            }

            .sections {
                display: flex;
                justify-content: center;
                gap: 20px;
                margin: 20px 0;
            }

            .section-button {
                background-color: white;
                border: none;
                padding: 15px 30px;
                width: 180px;
                height: 80px;
                border-radius: 10px;
                cursor: pointer;
                font-size: 18px;
                text-align: center;
                font-weight: bold;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                transition: transform 0.2s, box-shadow 0.2s;
            }

            .section-button:hover {
                transform: translateY(-3px);
                box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
            }

            .icon {
                background-color: #e8d6c3;
                width: 40px;
                height: 40px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                margin-bottom: 10px;
            }
        </style>


        <div class="sections">
            <button class="section-button">
                <a href="user.php">
                    <div class="icon">👤</div>
                </a>
                المستخدم
            </button>
            <button class="section-button">
                <a href="Reports.php">
                    <div class="icon">🏢</div>
                </a>
                شركة المياه
            </button>
            <button class="section-button">
                <a href="Donor file.php">
                    <div class="icon">👥</div>
                </a>
                المتبرعين
            </button>
        </div>

        <style>
            body {
                font-family: Arial, sans-serif;
                text-align: center;
                background: #f4e5d5;
                direction: rtl;
            }

            .content {
                font-size: 20px;
                font-weight: bold;
                color: #333;
            }
        </style>
        <div class="content">
            👥 <?php echo number_format($total_donors); ?> إجمالي عدد المتبرعين &nbsp;&nbsp;&nbsp;&nbsp;
            👤 <?php echo number_format($total_users); ?> مستخدمين واحة<br><br>
            🔔 <?php echo number_format($total_reports); ?> عدد البلاغات التي تلقتها واحة &nbsp;&nbsp;&nbsp;&nbsp;
            💳 <?php echo $total_amount_formatted; ?> ريال مجموع التبرعات
        </div>



        <div class="footer">
            <div class="social-icons">
                <img src="img/youtt.png" alt="يوتيوب">
                <img src="img/x.png" alt="منصة X">
                <img src="img/phone.png" alt="واتساب">
                <img src="img/snap.png" alt="سناب شات">
            </div>
        </div>
</body>

</html>