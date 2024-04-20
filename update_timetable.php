<?php
// update_timetable.php

// 檢查是否存在所需的 POST 參數
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["course_id"]) && isset($_POST["action"])) {
    $courseId = $_POST["course_id"];
    $action = $_POST["action"];

    // 資料庫連接設定
    $servername = "localhost";
    $username = "hj"; // 更換為你的資料庫用戶名
    $password = "test1234"; // 更換為你的資料庫密碼
    $dbname = "course selection"; // 更換為你的資料庫名稱

    // 建立資料庫連接
    $conn = new mysqli($servername, $username, $password, $dbname);

    // 檢查連接是否成功
    if ($conn->connect_error) {
        die("連接失敗：" . $conn->connect_error);
    }

    // 根據操作類型進行相應的處理
    if ($action == "add") {
        $s_id = $_POST["s_id"]; // 從 POST 參數中獲取學號
        $sql_student_dep = "SELECT s_dep FROM student WHERE s_id='$s_id'";
        $result_student_dep = $conn->query($sql_student_dep);

        if ($result_student_dep->num_rows > 0) {
            $row_student_dep = $result_student_dep->fetch_assoc();
            $studentDep = $row_student_dep["s_dep"];

            // 查询课程所在系
            $sql_course_dep = "SELECT c_dep FROM class WHERE id='$courseId'";
            $result_course_dep = $conn->query($sql_course_dep);

            if ($result_course_dep->num_rows > 0) {
                $row_course_dep = $result_course_dep->fetch_assoc();
                $courseDep = $row_course_dep["c_dep"];

                // 检查学生是否只能选择自己所在系的课程
                if ($studentDep != $courseDep && $courseDep != "通識中心") {
                    function centerText($text, $fontSize = "16px", $buttonSize = "100px", $buttonBorder = "1px solid #ccc") {
                        return "<div style='display: flex; flex-direction: column; justify-content: center; align-items: center; height: 100vh;'><div style='font-size: $fontSize;'>$text</div><br><a href='javascript:history.back()' style='display: inline-block; padding: 10px; border: $buttonBorder; border-radius: 5px; text-decoration: none; font-size: 14px; color: white; background-color: black;'>&#8592; 返回</a></div>";
                    }
                    
                    $text = "這不是您的系的課!無法加選!";
                    
                    $centeredText = centerText($text, "48px", "240px", "2px solid #999"); // Adjust size and border here
                    echo $centeredText;
                    exit();
                }
            } 
        }


        $sql_total_cred = "SELECT total_cred FROM student WHERE s_id='$s_id'";
        $result_total_cred = $conn->query($sql_total_cred);

        if ($result_total_cred->num_rows > 0) {
            $row_total_cred = $result_total_cred->fetch_assoc();
            $totalCred = $row_total_cred["total_cred"];
            // 查询选课的学分
            $sql_course_cred = "SELECT credits FROM class WHERE id='$courseId'";
            $result_course_cred = $conn->query($sql_course_cred);

            if ($result_course_cred->num_rows > 0) {
                $row_course_cred = $result_course_cred->fetch_assoc();
                $courseCred = $row_course_cred["credits"];

                // 检查学分是否超过30
                if ($totalCred + $courseCred > 30) {
                    function centerText($text, $fontSize = "16px", $buttonSize = "100px", $buttonBorder = "1px solid #ccc") {
                        return "<div style='display: flex; flex-direction: column; justify-content: center; align-items: center; height: 100vh;'><div style='font-size: $fontSize;'>$text</div><br><a href='javascript:history.back()' style='display: inline-block; padding: 10px; border: $buttonBorder; border-radius: 5px; text-decoration: none; font-size: 14px; color: white; background-color: black;'>&#8592; 返回</a></div>";
                    }
                    
                    $text = "學分超過30了,無法加選!";
                    
                    $centeredText = centerText($text, "48px", "240px", "2px solid #999"); // Adjust size and border here
                    echo $centeredText;
                    exit();
                }
            } 
        }        
        // 將選課資料插入到 selected_courses 表中
        // 請注意這裡的 s_id 可能需要根據你的系統從登錄用戶中獲取
        $class_id = $_POST['course_id'];
        $sql_check_selected = "SELECT * FROM selected_courses WHERE s_id='$s_id' AND class_id='$class_id'";
        $result_check_selected = $conn->query($sql_check_selected);
        if ($result_check_selected->num_rows > 0) {
            function centerText($text, $fontSize = "16px", $buttonSize = "100px", $buttonBorder = "1px solid #ccc") {
                return "<div style='display: flex; flex-direction: column; justify-content: center; align-items: center; height: 100vh;'><div style='font-size: $fontSize;'>$text</div><br><a href='javascript:history.back()' style='display: inline-block; padding: 10px; border: $buttonBorder; border-radius: 5px; text-decoration: none; font-size: 14px; color: white; background-color: black;'>&#8592; 返回</a></div>";
            }
            
            $text = "您已經選過該課程，請勿重複加選。";
            
            $centeredText = centerText($text, "48px", "240px", "2px solid #999"); // Adjust size and border here
            echo $centeredText;
        }else{
            $sql = "INSERT INTO selected_courses (s_id, class_id) VALUES ('$s_id', '$class_id')";
        }

        $sql_course_date = "SELECT date, start_time, end_time FROM date WHERE id='$courseId'";
        $result_course_date = $conn->query($sql_course_date);

        $sql_course_title = "SELECT title FROM class WHERE id='$courseId'";
        $result_course_title = $conn->query($sql_course_title);

        if ($result_course_title->num_rows > 0) {
            $row_course_title = $result_course_title->fetch_assoc();
            $courseTitle = $row_course_title["title"];

            // 检查课程是否已经存在于学生的选课列表中
            $sql_check_course = "SELECT * FROM selected_courses WHERE s_id='$s_id' AND class_id IN (SELECT id FROM class WHERE title='$courseTitle')";
            $result_check_course = $conn->query($sql_check_course);
            
            if ($result_check_course->num_rows > 0) {
                function centerText($text, $fontSize = "16px", $buttonSize = "100px", $buttonBorder = "1px solid #ccc") {
                    return "<div style='display: flex; flex-direction: column; justify-content: center; align-items: center; height: 100vh;'><div style='font-size: $fontSize;'>$text</div><br><a href='javascript:history.back()' style='display: inline-block; padding: 10px; border: $buttonBorder; border-radius: 5px; text-decoration: none; font-size: 14px; color: white; background-color: black;'>&#8592; 返回</a></div>";
                }
                
                $text = "您已經加選不同時間的這堂課了<br>如果要加選，請先退選同名的課";
                
                $centeredText = centerText($text, "48px", "240px", "2px solid #999"); // Adjust size and border here
                echo $centeredText;
                exit();
            }
        } 

        if ($result_course_date->num_rows > 0) {
            $row_course_date = $result_course_date->fetch_assoc();
            $courseDate = $row_course_date["date"];
            $startTime = $row_course_date["start_time"];
            $endTime = $row_course_date["end_time"];

            // 检查学生是否在同一时间段已经选择了其他课程
            $sql_check_conflict = "SELECT date.date, date.start_time, date.end_time FROM selected_courses JOIN date ON selected_courses.class_id=date.id WHERE selected_courses.s_id='$s_id' AND date.date='$courseDate' AND date.start_time <= '$endTime' AND date.end_time >= '$startTime'";
            $result_check_conflict = $conn->query($sql_check_conflict);

            if ($result_check_conflict->num_rows > 0) {
                function centerText($text, $fontSize = "16px", $buttonSize = "100px", $buttonBorder = "1px solid #ccc") {
                    return "<div style='display: flex; flex-direction: column; justify-content: center; align-items: center; height: 100vh;'><div style='font-size: $fontSize;'>$text</div><br><a href='javascript:history.back()' style='display: inline-block; padding: 10px; border: $buttonBorder; border-radius: 5px; text-decoration: none; font-size: 14px; color: white; background-color: black;'>&#8592; 返回</a></div>";
                }
                
                $text = "您已經加選同時間的不同課了<br>如果要加選，請先退選同時段的課";
                
                $centeredText = centerText($text, "48px", "240px", "2px solid #999"); // Adjust size and border here
                echo $centeredText;
                exit();
            }
        } 
        
        $sql_capacity = "SELECT select_people, capacity FROM class WHERE id='$courseId'";
        $result_capacity = $conn->query($sql_capacity);

        if ($result_capacity->num_rows > 0) {
            $row_capacity = $result_capacity->fetch_assoc();
            $selectPeople = $row_capacity["select_people"];
            $capacity = $row_capacity["capacity"];

            // 检查课程是否已满员
            if ($selectPeople >= $capacity) {
                function centerText($text, $fontSize = "16px", $buttonSize = "100px", $buttonBorder = "1px solid #ccc") {
                    return "<div style='display: flex; flex-direction: column; justify-content: center; align-items: center; height: 100vh;'><div style='font-size: $fontSize;'>$text</div><br><a href='javascript:history.back()' style='display: inline-block; padding: 10px; border: $buttonBorder; border-radius: 5px; text-decoration: none; font-size: 14px; color: white; background-color: black;'>&#8592; 返回</a></div>";
                }
                
                $text = "這堂課的人數已經滿了，無法加選";
                
                $centeredText = centerText($text, "48px", "240px", "2px solid #999"); // Adjust size and border here
                echo $centeredText;
                exit();
            }
        } 

        if ($conn->query($sql) === TRUE) {
            function centerText($text, $s_id, $fontSize = "16px", $buttonSize = "100px", $buttonBorder = "1px solid #ccc") {
                return "<div style='display: flex; flex-direction: column; justify-content: center; align-items: center; height: 100vh;'><div style='font-size: $fontSize;'>$text</div><br><form action='found.php' method='post'><input type='hidden' name='s_id' value='$s_id'><button type='submit' style='display: inline-block; padding: 10px; border: $buttonBorder; border-radius: 5px; text-decoration: none; font-size: 14px; color: white; background-color: black;'>&#8592; 返回</button></form></div>";
            }            
            $text = "課程加選成功";
            $centeredText = centerText($text, $s_id, "48px", "240px", "2px solid #999"); // Adjust size and border here
            echo $centeredText;

            // 根據 class_id 查詢課程時間
            $sql_date = "SELECT date, start_time, end_time FROM date WHERE id='$courseId'";
            $result_date = $conn->query($sql_date);

            if ($result_date->num_rows > 0) {
                // 獲取課程時間並填入課表中
                while ($row_date = $result_date->fetch_assoc()) {
                    $date = $row_date["date"]; // 星期幾
                    $start_time = $row_date["start_time"]; // 開始時間
                    $end_time = $row_date["end_time"]; // 結束時間

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
                    $sql = "SELECT title, c_dep FROM class WHERE id='$courseId'";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()){
                            $title = $row["title"];
                            $c_dep = $row["c_dep"];
                        }
                    }
                    // 將課程時間填入課表中
                    for ($i = $start_time; $i <= $end_time; $i++) {
                        $timetable[$i-1][$dayIndex] = "<div class='course-box'><div class='course-details'>" . $title . " - " . $c_dep . "</div></div>"; // 填入課程名稱或其他相關信息
                    }
                    // 更新课程的 select_people
                    $sql_update = "UPDATE class SET select_people = (SELECT COUNT(DISTINCT s_id) FROM selected_courses WHERE class_id='$class_id') WHERE id='$class_id'";
                    $conn->query($sql_update);

                }
                
            } else {
                echo "找不到課程時間";
            }
           
        } else {
            echo "錯誤：" . $conn->error;
        }
    } elseif ($action == "drop") {
        $s_id = $_POST["s_id"];
        $sql_total_cred = "SELECT total_cred FROM student WHERE s_id='$s_id'";
        $result_total_cred = $conn->query($sql_total_cred);

        if ($result_total_cred->num_rows > 0) {
            $row_total_cred = $result_total_cred->fetch_assoc();
            $totalCred = $row_total_cred["total_cred"];

            // 查询退选课程的学分
            $sql_course_cred = "SELECT credits FROM class WHERE id='$courseId'";
            $result_course_cred = $conn->query($sql_course_cred);

            if ($result_course_cred->num_rows > 0) {
                $row_course_cred = $result_course_cred->fetch_assoc();
                $courseCred = $row_course_cred["credits"];

                // 检查学分是否低于9
                if ($totalCred - $courseCred < 9) {
                    function centerText($text, $fontSize = "16px", $buttonSize = "100px", $buttonBorder = "1px solid #ccc") {
                        return "<div style='display: flex; flex-direction: column; justify-content: center; align-items: center; height: 100vh;'><div style='font-size: $fontSize;'>$text</div><br><a href='javascript:history.back()' style='display: inline-block; padding: 10px; border: $buttonBorder; border-radius: 5px; text-decoration: none; font-size: 14px; color: white; background-color: black;'>&#8592; 返回</a></div>";
                    }
                    
                    $text = "您的學分已經過低,無法退選!";
                    
                    $centeredText = centerText($text, "48px", "240px", "2px solid #999"); // Adjust size and border here
                    echo $centeredText;
                    exit();
                }
            } 
        }
        $sql_student_grade = "SELECT grade FROM student WHERE s_id='$s_id'";
        $result_student_grade = $conn->query($sql_student_grade);

        if ($result_student_grade->num_rows > 0) {
            $row_student_grade = $result_student_grade->fetch_assoc();
            $studentGrade = $row_student_grade["grade"];

            // 查询课程的要求
            $sql_course_req = "SELECT req FROM class WHERE id='$courseId'";
            $result_course_req = $conn->query($sql_course_req);

            if ($result_course_req->num_rows > 0) {
                $row_course_req = $result_course_req->fetch_assoc();
                $req = $row_course_req["req"];

                // 检查学生的年级和课程的要求是否相同，如果是，则输出警告信息并询问是否确定要继续退选操作
                if ($studentGrade == $req) {
                    header("Location: yesorno.php?course_id=$courseId&action=$action&s_id=$s_id");
                    exit();
                }
            } 
        } 
        $sql_check = "SELECT * FROM selected_courses WHERE s_id='$s_id' AND class_id='$courseId'";
        $result_check = $conn->query($sql_check);
        if ($result_check->num_rows > 0) {
        // 從 selected_courses 表中移除相應的課程
        $sql = "DELETE FROM selected_courses WHERE s_id='{$_POST["s_id"]}' AND class_id='$courseId'";
        if ($conn->query($sql) === TRUE) {
            $sql_update = "UPDATE class SET select_people = (SELECT COUNT(DISTINCT s_id) FROM selected_courses WHERE class_id='$courseId') WHERE id='$courseId'";
            $conn->query($sql_update);
            function centerText($text, $s_id, $fontSize = "16px", $buttonSize = "100px", $buttonBorder = "1px solid #ccc") {
                return "<div style='display: flex; flex-direction: column; justify-content: center; align-items: center; height: 100vh;'><div style='font-size: $fontSize;'>$text</div><br><form action='found.php' method='post'><input type='hidden' name='s_id' value='$s_id'><button type='submit' style='display: inline-block; padding: 10px; border: $buttonBorder; border-radius: 5px; text-decoration: none; font-size: 14px; color: white; background-color: black;'>&#8592; 返回</button></form></div>";
            }            
            $text = "課程退選成功";
            $centeredText = centerText($text, $s_id, "48px", "240px", "2px solid #999"); // Adjust size and border here
            echo $centeredText;
            
        } else {
            echo "錯誤：" . $conn->error;
        }
        }
        else
        {
            function centerText($text, $fontSize = "16px", $buttonSize = "100px", $buttonBorder = "1px solid #ccc") {
                return "<div style='display: flex; flex-direction: column; justify-content: center; align-items: center; height: 100vh;'><div style='font-size: $fontSize;'>$text</div><br><a href='javascript:history.back()' style='display: inline-block; padding: 10px; border: $buttonBorder; border-radius: 5px; text-decoration: none; font-size: 14px; color: white; background-color: black;'>&#8592; 返回</a></div>";
            }
            $text = "你沒有此課程可退選";
            $centeredText = centerText($text, "48px", "240px", "2px solid #999"); // Adjust size and border here
            echo $centeredText;
        }
    }

    // 關閉資料庫連接
    $conn->close();
} else {
    // 如果未提供所需的 POST 參數，返回錯誤信息
    echo "錯誤：未提供所需的參數";
}
?>
