<?php
session_start();
include 'db_connect.php'; // الاتصال بقاعدة البيانات

// التأكد من أن المستخدم هو الأدمن ID = 1
if (!isset($_SESSION['admin_id']) || $_SESSION['admin_id'] != 1) {
    echo "غير مصرح لك بالوصول إلى هذه الصفحة!";
    exit();
}

// جلب جميع البلاغات
$sql = "SELECT id, address, time, date, status, photo FROM reports";
$result = $conn->query($sql);

// جلب عدد البلاغات الكلي
$sql_total = "SELECT COUNT(*) as total FROM reports";
$result_total = $conn->query($sql_total);
$total_reports = ($result_total->num_rows > 0) ? $result_total->fetch_assoc()['total'] : 0;

// جلب عدد البلاغات قيد المعالجة
$sql_inProgress = "SELECT COUNT(*) as in_progress FROM reports WHERE status = 'قيد المعالجة'";
$result_inProgress = $conn->query($sql_inProgress);
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
            <a href="Reports.php">البلاغات</a>
            <a href="Donation_admin.php">التبرعات</a>
            <a href="map_admin.html">الخريطة</a>
            <a href="Water areas_admin.html">مناطق المياه</a>
            <a href="logout.php">تسجيل الخروج </a>
        </div>
    </div>
</div>


<div class="table-container">
    <table>
        <thead>
        <tr>
            <th>رقم البلاغ</th>
            <th>موقع البلاغ</th>
            <th>اليوم</th>
            <th>الوقت</th>
            <th>الحالة</th>
            <th>الصورة</th>
            <th>إجراء</th>
        </tr>
        </thead>
        <tbody id="reportTable">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr data-id='{$row['id']}'>
                        <td>{$row['id']}</td>
                        <td contenteditable='false'>{$row['address']}</td>
                        <td contenteditable='false'>{$row['date']}</td>
                        <td contenteditable='false'>{$row['time']}</td>
                        <td>
                            <select class='status-dropdown' disabled>
                                <option value='قيد المعالجة' " . ($row['status'] == 'قيد المعالجة' ? 'selected' : '') . ">قيد المعالجة</option>
                                <option value='تم الحل' " . ($row['status'] == 'تم الحل' ? 'selected' : '') . ">تم الحل</option>
                                <option value='مرفوض' " . ($row['status'] == 'مرفوض' ? 'selected' : '') . ">مرفوض</option>
                            </select>
                        </td>
                        <td>";
                if (!empty($row['photo'])) {
                    echo "<a href='uploads/reports/{$row['photo']}' target='_blank'>
                    <img src='uploads/reports/{$row['photo']}' width='100' alt='Report Photo'>
                  </a>";
                } else {
                    echo "لا توجد صورة";
                }
                echo "</td>
                        <td><button class='edit-btn' onclick='enableEdit(this)'>تعديل</button></td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='7' style='text-align:center;'>لا توجد بلاغات حتى الآن</td></tr>";
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

<script>
function enableEdit(button) {
    let row = button.closest("tr");
    let cells = row.querySelectorAll("td[contenteditable='false']");
    let select = row.querySelector(".status-dropdown");

    if (button.textContent === "تعديل") {
        // Enable editing
        cells.forEach(cell => cell.setAttribute("contenteditable", "true"));
        select.removeAttribute("disabled");
        button.textContent = "حفظ";

        // Ensure the button still works when clicked
        button.removeEventListener("click", saveChanges);
        button.addEventListener("click", saveChanges);
    }
}

function saveChanges(event) {
    let button = event.target;
    let row = button.closest("tr");
    let cells = row.querySelectorAll("td[contenteditable='true']");
    let select = row.querySelector(".status-dropdown");

    let reportId = row.getAttribute("data-id");
    let address = cells[0].textContent.trim();
    let date = cells[1].textContent.trim();
    let time = cells[2].textContent.trim();
    let status = select.value;

    console.log("Sending Data:", { reportId, address, date, time, status });

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "update_report.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            console.log("Response:", xhr.responseText);
            if (xhr.status === 200) {
                alert("تم تحديث البلاغ بنجاح!");
                // Disable editing
                cells.forEach(cell => cell.setAttribute("contenteditable", "false"));
                select.setAttribute("disabled", "true");
                button.textContent = "تعديل";

                // Reattach original edit function
                button.removeEventListener("click", saveChanges);
                button.addEventListener("click", () => enableEdit(button));
            } else {
                alert("حدث خطأ أثناء التحديث!");
            }
        }
    };
    xhr.send(`id=${reportId}&address=${encodeURIComponent(address)}&date=${date}&time=${time}&status=${status}`);
}
function addReport() {
    let table = document.getElementById("reportTable");

    // Create a new row with proper input fields
    let newRow = document.createElement("tr");
    newRow.innerHTML = `
        <td><input type="text" placeholder="أدخل موقع البلاغ..." required></td>
        <td><input type="date" required></td>
        <td><input type="time" required></td>
        <td>
            <select class="status-dropdown">
                <option value="قيد المعالجة">قيد المعالجة</option>
                <option value="تم الحل">تم الحل</option>
                <option value="مرفوض">مرفوض</option>
            </select>
        </td>
        <td>
            <button onclick="saveNewReport(this)">حفظ</button>
        </td>
    `;

    table.appendChild(newRow);
}

function saveNewReport(button) {
    let row = button.closest("tr");
    let address = row.querySelector("input[type='text']").value.trim();
    let date = row.querySelector("input[type='date']").value;
    let time = row.querySelector("input[type='time']").value;
    let status = row.querySelector(".status-dropdown").value;

    if (!address || !date || !time) {
        alert("يرجى ملء جميع الحقول!");
        return;
    }

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "add_report.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            console.log("Response:", xhr.responseText);
            if (xhr.status === 200) {
                alert("تمت إضافة البلاغ بنجاح!");
                location.reload(); // Reload to update the table
            } else {
                alert("حدث خطأ أثناء الإضافة!");
            }
        }
    };
    xhr.send(`address=${encodeURIComponent(address)}&date=${date}&time=${time}&status=${status}`);
}


</script>
</body>


</html>

<?php
$conn->close();
?>