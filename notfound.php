<?php

function centerText($text, $fontSize = "16px", $buttonSize = "100px", $buttonBorder = "1px solid #ccc") {
    return "<div style='display: flex; flex-direction: column; justify-content: center; align-items: center; height: 100vh;'><div style='font-size: $fontSize;'>$text</div><br><a href='javascript:history.back()' style='display: inline-block; padding: 10px; border: $buttonBorder; border-radius: 5px; text-decoration: none; font-size: 14px; color: white; background-color: black;'>&#8592; 返回</a></div>";
}

$text = "此學號不存在";

$centeredText = centerText($text, "48px", "240px", "2px solid #999"); // Adjust size and border here
echo $centeredText;

?>
