<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>是否确定退选课程</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            text-align: center;
        }
        form {
            margin-top: 20px;
        }
        input[type="submit"] {
            padding: 10px 20px;
            background-color: #4caf50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>發現您正在退選必修課程，是否真的要退選?</h2>
        <form action="yes.php" method="post">
            <input type="hidden" name="course_id" value="<?php echo $_GET['course_id']; ?>">
            <input type="hidden" name="action" value="confirmed_drop">
            <input type="hidden" name="s_id" value="<?php echo $_GET['s_id']; ?>">
            <input type="submit" value="是">
        </form>
        <form action="found.php" method="post">
            <input type="hidden" name="action" value="cancel_drop">
            <input type="hidden" name="s_id" value="<?php echo $_GET['s_id']; ?>">
            <input type="submit" value="否">
        </form>
    </div>
</body>
</html>
