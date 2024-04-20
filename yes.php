<?php
// 检查是否存在所需的 POST 参数
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["course_id"]) && isset($_POST["action"]) && isset($_POST["s_id"])) {
    $courseId = $_POST["course_id"];
    $action = $_POST["action"];
    $s_id = $_POST["s_id"];

    if ($action == "confirmed_drop") {
        // 数据库连接设置
        $servername = "localhost";
        $username = "hj"; // 更换为你的数据库用户名
        $password = "test1234"; // 更换为你的数据库密码
        $dbname = "course selection"; // 更换为你的数据库名称

        // 创建数据库连接
        $conn = new mysqli($servername, $username, $password, $dbname);

        // 检查连接是否成功
        if ($conn->connect_error) {
            die("连接失败：" . $conn->connect_error);
        }

        // 删除选课记录
        $sql_delete_selected_course = "DELETE FROM selected_courses WHERE s_id='$s_id' AND class_id='$courseId'";
        $conn->query($sql_delete_selected_course);
        if ($conn->query($sql_delete_selected_course) === TRUE) {
            $sql_update = "UPDATE class SET select_people = (SELECT COUNT(DISTINCT s_id) FROM selected_courses WHERE class_id='$courseId') WHERE id='$courseId'";
            $conn->query($sql_update);
            function centerText($text, $s_id, $fontSize = "16px", $buttonSize = "100px", $buttonBorder = "1px solid #ccc") {
                return "<div style='display: flex; flex-direction: column; justify-content: center; align-items: center; height: 100vh;'><div style='font-size: $fontSize;'>$text</div><br><form action='found.php' method='post'><input type='hidden' name='s_id' value='$s_id'><button type='submit' style='display: inline-block; padding: 10px; border: $buttonBorder; border-radius: 5px; text-decoration: none; font-size: 14px; color: white; background-color: black;'>&#8592; 返回</button></form></div>";
            }            
            $text = "課程退選成功";
            $centeredText = centerText($text, $s_id, "48px", "240px", "2px solid #999"); // Adjust size and border here
            echo $centeredText;
        } 

        // 关闭数据库连接
        $conn->close();
    }
}
?>
