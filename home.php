<?php session_start(); ?>

<!DOCTYPE html>
<html lang="ar">
<head>
  <meta charset="UTF-8" />
  <title>الصفحة الرئيسية - تحدي البرمجة</title>
  <link rel="stylesheet" href="style.css" />
  <style>
    body {
      flex-direction: column;
      text-align: center;
      background: #081b29;
      color: #fff;
    }

  </style>
</head>
<body>

  <div class="main-container">
    <h1 style="font-size: 40px; margin-bottom: 20px;">🧠 تحدي البرمجة والحاسوب</h1>
    <p style="font-size: 18px; line-height: 1.8;">
      مرحبًا بك في تحدي البرمجة!<br />
      ستقوم بالإجابة على مجموعة من الأسئلة المتنوعة في مجال الويب ، تصميم الويب ، وتطوير الويب .<br />
      الهدف هو اختبار معلوماتك بطريقة ممتعة وسريعة.
    </p>

    <form action="start.php" method="post">
      <button class="start-btn">🚀 نبدأ التحدي</button>
    </form>

  </div>

</body>
</html>