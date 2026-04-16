<?php
// عند الضغظ على زر العودة لرئيسية
session_start();

if (isset($_SESSION['user_id'])) {
    // تم تسجيل الدخول مسبقاً → ابدأ التحدي
    header("Location: game.php");
    exit;
} 
else {
    // غير مسجل دخول → أرسله لتسجيل الدخول
    header("Location: login.html");
    exit;
}