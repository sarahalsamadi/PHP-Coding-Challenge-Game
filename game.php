<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

//require_once 'connection.php';
$host = 'localhost';
$dbname = 'login_signup'; 
$username = 'root';      
$password = '';          

$conn = new mysqli($host, $username, $password, $dbname);

// التحقق من الاتصال
if ($conn->connect_error) {
    die("فشل الاتصال: " . $conn->connect_error);
}

// تحقق من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

// بدء اللعبة
if (!isset($_SESSION['game_started'])) {
    $_SESSION['score'] = 0;
    $_SESSION['question_number'] = 1;
    $_SESSION['game_started'] = true;//   اللعبة بدأت
}

// رقم السؤال الحالي
$question_number = $_SESSION['question_number'];

// جلب السؤال من قاعدة البيانات
$stmt = $conn->prepare("SELECT * FROM question WHERE id = ?");
$stmt->bind_param("i", $question_number);
$stmt->execute();
$result = $stmt->get_result();
$question = $result->fetch_assoc();

// إذا لا يوجد سؤال انتقل إلى صفحة النتائج
if (!$question) {
    header("Location: results.php");
    exit;
}

// التحقق من الإجابة بعد الإرسال
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selected = $_POST['answer'] ?? '';
    if ($selected === $question['correct_option']) {
        $_SESSION['score']++;
    }
    $_SESSION['question_number']++;
    header("Location: game.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>السؤال رقم <?= $question_number ?></title>
    <style>
        body {
            background: #0f2027;
            color: #fff;
            font-family: 'Arial', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            direction: rtl;
        }

        .question-box {
            background: #1e2b37;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 0 20px #0ef;
            max-width: 700px;
            width: 90%;
            text-align: center;
            position: relative;
        }

        .question-box h2 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        .timer {
            position: absolute;
            top: 15px;
            left: 20px;
            font-size: 16px;
            color: #0ef;
            font-weight: bold;
        }

        .options {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-top: 20px;
        }

        .options label {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 20px;
            background: #7de3ef;
            color: #000;
            border-radius: 10px;
            font-weight: bold;
            cursor: pointer;
        }

        .options input[type="radio"] {
            margin-right: 10px;
            transform: scale(1.2);
        }

        .submit-btn {
            margin-top: 30px;
            padding: 10px 30px;
            background: transparent;
            border: 2px solid #0ef;
            color: #0ef;
            font-weight: bold;
            border-radius: 30px;
            cursor: pointer;
            transition: 0.3s;
        }

        .submit-btn:hover {
            background: #0ef;
            color: #000;
        }
    </style>
</head>
<body>

<div class="question-box">
    <div class="timer" id="timer">⏳ 30 ثانية</div>

    <h2>سؤال <?= $question_number ?>: <?= htmlspecialchars($question['question']) ?></h2>

    <form method="post" id="quizForm">
        <div class="options">
            <label>
                <?= htmlspecialchars($question['option_a']) ?>
                <input type="radio" name="answer" value="A" required>
            </label>
            <label>
                <?= htmlspecialchars($question['option_b']) ?>
                <input type="radio" name="answer" value="B">
            </label>
            <label>
                <?= htmlspecialchars($question['option_c']) ?>
                <input type="radio" name="answer" value="C">
            </label>
            <label>
                <?= htmlspecialchars($question['option_d']) ?>
                <input type="radio" name="answer" value="D">
            </label>
        </div>

        <button class="submit-btn" type="submit">التالي</button>
    </form>
</div>

<script>
    let timeLeft = 30;
    const timer = document.getElementById("timer");
    const form = document.getElementById("quizForm");

    const countdown = setInterval(() => {
        timeLeft--;
        timer.textContent = `⏳ ${timeLeft} ثانية`;

        if (timeLeft <= 0) {
            clearInterval(countdown);
            form.submit(); // إرسال النموذج تلقائيًا عند انتهاء الوقت
        }
    }, 1000);
</script>

</body>
</html>