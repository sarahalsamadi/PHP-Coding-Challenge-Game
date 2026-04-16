<?php
session_start();

if (!isset($_SESSION['score']) || !isset($_SESSION['question_number'])) {
    header("Location: home.php");
    exit;
}

$score = $_SESSION['score'];
$total = $_SESSION['question_number'] - 1;
$percentage = ($total > 0) ? round(($score / $total) * 100) : 0;

// ملاحظات حسب النتيجة
$feedback = "";
$audioType = ""; // لتحديد نوع الصوت

if ($percentage == 100) {
    $feedback = "مذهل! لقد أجبت على كل الأسئلة بشكل صحيح 👏";
    $audioType = "win";
} elseif ($percentage >= 80) {
    $feedback = "أداء رائع! أنت خبير في البرمجة 💻";
    $audioType = "win";
} elseif ($percentage >= 50) {
    $feedback = "جيد، لكن يمكنك التحسين أكثر 👍";
    $audioType = "win";
} else {
    $feedback = "لا تقلق، استمر في التعلم! 💡";
    $audioType = "lose";
}

// إزالة بيانات اللعبة فقط
unset($_SESSION['score']);
unset($_SESSION['question_number']);
unset($_SESSION['game_started']);
?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>النتيجة النهائية</title>
    <style>
        body {
            background: #0f2027;
            font-family: 'Cairo', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            color: #fff;
        }

        .result-box {
            background: #1e2b37;
            padding: 50px 40px;
            border-radius: 20px;
            box-shadow: 0 0 30px #0ef;
            text-align: center;
            max-width: 600px;
            width: 90%;
        }

        .result-box h1 {
            font-size: 36px;
            margin-bottom: 20px;
        }

        .result-box p {
            font-size: 20px;
            margin: 10px 0;
        }

        .btns {
            margin-top: 30px;
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .btns a {
            text-decoration: none;
            padding: 12px 30px;
            border: 2px solid #0ef;
            border-radius: 30px;
            font-size: 16px;
            font-weight: bold;
            color: #0ef;
            position: relative;
            overflow: hidden;
            transition: 0.3s ease;
        }

        .btns a:hover {
            background: #0ef;
            color: #000;
        }
    </style>
</head>
<body>

<div class="result-box">
    <h1>🎉 النتيجة النهائية</h1>
    <p>عدد الإجابات الصحيحة: <?= $score ?> من <?= $total ?> سؤال</p>
    <p>النسبة المئوية: <?= $percentage ?>%</p>
    <p><?= $feedback ?></p>

    <div class="btns">
        <a href="home.php">🏠 العودة للرئيسية</a>
        <a href="game.php">🔁 إعادة اللعب</a>
    </div>
</div>

<!-- 🔊 تشغيل الصوت حسب النتيجة -->
<?php if ($audioType === 'win'): ?>
    <audio autoplay>
        <source src="win.mp3" type="audio/mpeg">
    </audio>
<?php elseif ($audioType === 'lose'): ?>
    <audio autoplay>
        <source src="lose.mp3" type="audio/mpeg">
    </audio>
<?php endif; ?>

</body>
</html>