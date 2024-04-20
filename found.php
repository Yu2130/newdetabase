<?php
// 假設這是登入後獲得的學號和名字
$s_id = $_POST['s_id']; // 從表單獲取學號
$servername = "localhost";
$username = "hj"; // 請替換為你的資料庫用戶名
$password = "test1234"; // 請替換為你的資料庫密碼
$dbname = "course selection"; // 請替換為你的資料庫名稱
// 建立連接
$conn = new mysqli($servername, $username, $password, $dbname);

// 檢查連接是否成功
if ($conn->connect_error) {
    die("連接失敗：" . $conn->connect_error);
}

// 查詢學號對應的姓名
$sql = "SELECT name FROM student WHERE s_id='$s_id'";
$result = $conn->query($sql);

// 檢查查詢結果是否存在
if ($result->num_rows > 0) {
    // 输出数据
    while($row = $result->fetch_assoc()) {
        $studentName = $row["name"];
    }
} else {
    // 找不到學號對應的姓名，將用戶重定向到 notfound.php
    header("Location: notfound.php");
    exit(); // 重定向后，确保立即退出执行，防止后续代码继续执行
}

$sql = "SELECT id, title, c_dep, credits, select_people, capacity FROM class";
$result = $conn->query($sql);

$available_courses = []; // 存储可选课程

if ($result->num_rows > 0) {
    // 输出数据
    while($row = $result->fetch_assoc()) {
        $course_id = $row["id"];
        $course_title = $row["title"];
        $course_c_dep = $row["c_dep"];
        $course_credits = $row["credits"];
        $course_select_people = $row["select_people"];
        $course_capacity = $row["capacity"];
        
        // 将课程信息添加到可选课程数组中
        $available_courses[] = [
            "id" => $course_id,
            "title" => $course_title,
            "c_dep" => $course_c_dep,
            "credits" => $course_credits,
            "select_people" => $course_select_people,
            "capacity" => $course_capacity
        ];
    }
}

// 获取特定学生已选课程的记录
$s_id = $_POST["s_id"];
$sql_selected_courses = "SELECT DISTINCT class_id FROM selected_courses WHERE s_id='$s_id'";
$result_selected_courses = $conn->query($sql_selected_courses);

$totalCredits = 0; // 总学分

if ($result_selected_courses->num_rows > 0) {
    // 遍历选课记录
    while($row_selected_courses = $result_selected_courses->fetch_assoc()) {
        $classId = $row_selected_courses["class_id"];
        
        // 查询课程的学分
        $sql_course_credits = "SELECT credits FROM class WHERE id='$classId'";
        $result_course_credits = $conn->query($sql_course_credits);
        
        if ($result_course_credits->num_rows > 0) {
            // 获取课程学分并累加到总学分
            while($row_course_credits = $result_course_credits->fetch_assoc()) {
                $credits = $row_course_credits["credits"];
                $totalCredits += $credits;
            }
        }
    }
} else {
    echo "学生未选课";
}

// 更新总学分到 student 表中的 total_cred 字段中
$sql_update_total_cred = "UPDATE student SET total_cred='$totalCredits' WHERE s_id='$s_id'";
$conn->query($sql_update_total_cred);
// 關閉連接
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>選課系統主頁</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            display: flex;
            justify-content: space-between;
            padding: 20px;
        }
        .timetable, .courses {
            width: 48%;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            overflow-y: scroll; 
            max-height: 1000px; 
        }
        .timetable h2, .courses h2 {
            margin-top: 0;
        }
        .timetable table {
            border-collapse: collapse;
            width: 100%;
        }
        .timetable th, .timetable td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        .courses ul {
            list-style-type: none;
            padding: 0;
        }
        .courses li {
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px;
            position: relative;
        }
        .course-details {
            display: flex;
            align-items: center;
        }
        .course-name {
            flex: 1;
        }
        .add-btn, .drop-btn {
            padding: 5px 10px;
            background-color: #4caf50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-left: 10px;
        }
        .add-btn:hover, .drop-btn:hover {
            background-color: #45a049;
        }
        .return-btn {
            background-color: #000;
            color: #fff;
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
            width: fit-content;
        }
        .return-btn:hover {
            background-color: #333;
        }
        .user-info {
            margin-top: 20px;
            font-size: 18px;
            text-align: center;
        }
        p {
            text-align: center;
            margin-bottom: 50px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="timetable">
            <p><b><font size="7" face="標楷體">我的課表</font></b></p>
            <div class="timetable-table">
                <table>
                <?php
                // 连接到数据库
                $servername = "localhost";
                $username = "hj"; // 替换为你的数据库用户名
                $password = "test1234"; // 替换为你的数据库密码
                $dbname = "course selection"; // 替换为你的数据库名

                // 创建连接
                $conn = new mysqli($servername, $username, $password, $dbname);

                // 检查连接
                if ($conn->connect_error) {
                    die("连接失败: " . $conn->connect_error);
                }


                $s_id = $_POST['s_id'];
                $sql_selected_courses = "SELECT class_id FROM selected_courses WHERE s_id='$s_id'";
                $result_selected_courses = $conn->query($sql_selected_courses);

                if ($result_selected_courses->num_rows > 0) {
            // 學生已選課程的 class_id 陣列
            $class_ids = array();
            while ($row_selected_courses = $result_selected_courses->fetch_assoc()) {
                $class_ids[] = $row_selected_courses['class_id'];
            }
            $timetable = array(
                array("", "", "", "", ""),
                array("", "", "", "", ""),
                array("", "", "", "", ""),
                array("", "", "", "", ""),
                array("", "", "", "", ""),
                array("", "", "", "", ""),
                array("", "", "", "", ""),
                array("", "", "", "", ""),
                array("", "", "", "", ""),
                array("", "", "", "", ""),
                array("", "", "", "", ""),
                array("", "", "", "", ""),
                array("", "", "", "", ""),
                array("", "", "", "", "")
            );
            // 查詢每個課程的時間信息並顯示課表
            foreach ($class_ids as $class_id) {
                $sql_course_time = "SELECT date, start_time, end_time FROM date WHERE id='$class_id'";
                $result_course_time = $conn->query($sql_course_time);
                
                if ($result_course_time->num_rows > 0) {
                    // 學生的課表
                    

                    // 將課程時間填入課表中
                    while ($row_course_time = $result_course_time->fetch_assoc()) {
                        $date = $row_course_time["date"];
                        $start_time = $row_course_time["start_time"];
                        $end_time = $row_course_time["end_time"];
                            
                        $dayIndex = 0;
                        switch ($date) {
                                case "1":
                                    $dayIndex = 0;
                                    break;
                                case "2":
                                    $dayIndex = 1;
                                    break;
                                case "3":
                                    $dayIndex = 2;
                                    break;
                                case "4":
                                    $dayIndex = 3;
                                    break;
                                case "5":
                                    $dayIndex = 4;
                                    break;
                                // 如果有更多的工作日，可以继续添加
                        }
                        // 將課程時間填入課表中
                        $sql = "SELECT title, c_dep FROM class WHERE id='$class_id'";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()){
                                $title = $row["title"];
                                $c_dep = $row["c_dep"];
                            }
                        }
                        for ($i = $start_time; $i <= $end_time; $i++) {
                            $timetable[$i-1][$dayIndex] = "<div class='course-box'><div class='course-details'>" . $title . "<br>" . $c_dep . "</div></div>"; // 填入課程名稱或其他相關信息
                        }
                         // 更新课程的 select_people
                        $sql_update = "UPDATE class SET select_people = (SELECT COUNT(DISTINCT s_id) FROM selected_courses WHERE class_id='$class_id') WHERE id='$class_id'";
                        $conn->query($sql_update);

                    }
                } else {
                    echo "找不到課程時間";
                }
                 
            }
            // 輸出課表
            echo "<table border='1'>";
            echo "<tr><th></th><th>周一</th><th>周二</th><th>周三</th><th>周四</th><th>周五</th></tr>";
            for ($i = 0; $i < count($timetable); $i++) {
                echo "<tr>";
                echo "<td>第" . ($i + 1) . "節</td>";
                for ($j = 0; $j < count($timetable[$i]); $j++) {
                    echo "<td>" . $timetable[$i][$j] . "</td>";
                }
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "學生未選擇任何課程";
        }
                $conn->close();
                ?>
                <p>總學分：<?php echo $totalCredits; ?></p>
                </table>
            </div>
        </div>
        <div class="courses">
        <p><b><font size="7" face="標楷體">可選課程</font></b></p>
            <ul>
                <?php foreach ($available_courses as $course): ?>
                    <li>
                        <div class="course-box">
                            <div class="course-details">
                                <?php echo $course['id'] . ' | ' . $course['title'] . ' | ' . $course['c_dep'] .  "<br>學分 : " . $course['credits'] . ' | 人數 :' . $course['select_people'] . '/' . $course['capacity']; ?>
                                <div>
                                <form method="post" action="update_timetable.php">
                                <input type="hidden" name="course_id" value="<?php echo $course['id']; ?>">
                                <input type="hidden" name="s_id" value="<?php echo $_POST['s_id']; ?>">
                                <input type="hidden" name="action" value="add">
                                <button type="submit" class="add-btn">加選</button>
                                </form>
                                </div>
                                <div>
                                <form method="post" action="update_timetable.php">
                                    <input type="hidden" name="course_id" value="<?php echo $course['id']; ?>">
                                    <input type="hidden" name="s_id" value="<?php echo $_POST['s_id']; ?>">
                                    <input type="hidden" name="action" value="drop">
                                    <button type="submit" class="drop-btn">退選</button>
                                </form>
                            </div>
                            </div>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <div class="user-info" style="text-align: center;">
        <?php
        echo "學號：" . $s_id . "<br>";
        echo "姓名：" . $studentName;
        ?>
    </div>
    <div class="container" style="justify-content: center;">
        <a class="return-btn" href="index.php">返回</a>
    </div>
</body>
</html>
