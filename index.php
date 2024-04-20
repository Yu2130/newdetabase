<?php
    // 首先，建立数据库连接
    $servername = "localhost";
    $username = "hj"; // 更换为你的数据库用户名
    $password = "test1234"; // 更换为你的数据库密码
    $dbname = "course selection"; // 更换为你的数据库名称

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("连接失败：" . $conn->connect_error);
    }
    $sql_check_empty = "SELECT COUNT(*) as count FROM selected_courses";
    $result_check_empty = $conn->query($sql_check_empty);
    $count = $result_check_empty->fetch_assoc()["count"];
    if ($count == 0) {
        
        // 查询所有非选修课程
        $sql_courses = "SELECT * FROM class WHERE req != '選修'";
        $result_courses = $conn->query($sql_courses);

        if ($result_courses->num_rows > 0) {
            // 查询所有学生
            $sql_students = "SELECT * FROM student";
            $result_students = $conn->query($sql_students);

            if ($result_students->num_rows > 0) {
                // 遍历每个学生
                while ($row_student = $result_students->fetch_assoc()) {
                    $s_id = $row_student["s_id"];
                    $s_dep = $row_student["s_dep"];
                    $grade = $row_student["grade"];

                    // 遍历每门非选修课程
                    while ($row_course = $result_courses->fetch_assoc()) {
                        $class_id = $row_course["id"];
                        $class_c_dep = $row_course["c_dep"];
                        $class_req = $row_course["req"];

                        // 检查课程的 c_dep 和 req 是否与学生的 s_dep 和 grade 匹配
                        if ($class_c_dep == $s_dep && $class_req == $grade) {
                            // 插入记录到 selected_courses 表中
                            $sql_insert = "INSERT INTO selected_courses (s_id, class_id) VALUES ('$s_id', '$class_id')";
                            $conn->query($sql_insert);
                        }
                    }
                    // 重置结果集以便下一次循环
                    $result_courses->data_seek(0);
                }
            } 
        } 
    }
    // 关闭数据库连接
    $conn->close();
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>選課系統登入</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }
        .container {
            width: 700px;
            margin: 0 auto;
            padding: 200px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 200px;
        }
        p {
            text-align: center;
            margin-bottom: 100px;
        }
        input[type="text"],
        input[type="password"],
        input[type="submit"] {
            width: 100%;
            padding: 20px;
            margin-bottom: 50px;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #4caf50;
            color: white;
            border: none;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
    <p><b><font size="7" face="標楷體">選課系統登入</font></b></p>
        <form action="found.php" method="post">
            <input type="text" name="s_id" placeholder="學號" required>
            <input type="submit" value="登入">
        </form>
    </div>
</body>
</html>
