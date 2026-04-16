<?php
declare(strict_types=1);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); 
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    //يعرض رسالة خطأ ويعود للخلف في حال كانت طريقة الطلب غير مدعومة
    exit("<script>alert('❌ طريقة الطلب غير مدعومة.'); window.history.back();</script>");
}

// إعداد الاتصال
$SERVERNAME = "localhost";
$USERNAME   = "root";
$password   = "";
$dbname     = "login_signup";

$conn = new mysqli($SERVERNAME, $USERNAME, $password, $dbname);
$conn->set_charset('utf8mb4');

// ---- inputs ----
$rawAction = $_POST['action'] ?? '';
$action    = strtolower(trim($rawAction));
$userName  = trim($_POST['username_reg'] ?? '');
$userMail  = trim($_POST['email_reg'] ?? '');
$userPass  = $_POST['password_reg'] ?? '';

// ---------------- Register ----------------
if ($action === 'signup' || $action === 'register') {
    if ($userName === '' || $userMail === '' || $userPass === '') {
        http_response_code(422);
        exit("<script>alert('⚠️ الرجاء تعبئة الاسم والإيميل وكلمة المرور.'); window.history.back();</script>");
    }
    // التأكد أن الاسم أو البريد غير مستخدم مسبقًا
    $stmt = $conn->prepare('SELECT username, user_email FROM data WHERE username=? OR user_email=? LIMIT 1');
    $stmt->bind_param('ss', $userName, $userMail);
    $stmt->execute();
    $exists = $stmt->get_result()->fetch_assoc();

    if ($exists) {
        if (strcasecmp($exists['username'] ?? '', $userName) === 0 &&
            strcasecmp($exists['user_email'] ?? '', $userMail) === 0) {
            exit("<script>alert('❌ الاسم والإيميل مستخدمان مسبقًا.'); window.history.back();</script>");
        } elseif (strcasecmp($exists['username'] ?? '', $userName) === 0) {
            exit("<script>alert('❌ اسم المستخدم مستخدم مسبقًا.'); window.history.back();</script>");
        } else {
            exit("<script>alert('❌ الإيميل مستخدم مسبقًا.'); window.history.back();</script>");
        }
    }

    $hash = password_hash($userPass, PASSWORD_DEFAULT);
    $stmt = $conn->prepare('INSERT INTO data (username, user_email, user_password) VALUES (?,?,?)');
    $stmt->bind_param('sss', $userName, $userMail, $hash);
    $stmt->execute();

    $_SESSION['user_id'] = $stmt->insert_id;
    $_SESSION['username'] = $userName;

    exit("<script>alert('✅ تم إنشاء الحساب بنجاح!'); window.location.href = 'game.php';</script>");
}

// ---------------- Login ----------------
elseif ($action === 'login') {
    if ($userName === '' || $userPass === '') {
        http_response_code(422);
        exit("<script>alert('⚠️ الرجاء إدخال الاسم وكلمة المرور.'); window.history.back();</script>");
    }

    $stmt = $conn->prepare('SELECT id, username, user_password FROM data WHERE username=? LIMIT 1');
    $stmt->bind_param('s', $userName);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();

    if (!$row || !password_verify($userPass, $row['user_password'])) {
        http_response_code(401);
        exit("<script>alert('❌ اسم المستخدم أو كلمة المرور غير صحيحة.'); window.history.back();</script>");
    }

    $_SESSION['user_id']  = (int)$row['id'];
    $_SESSION['username'] = $row['username'];

    exit("<script>alert('🎉 تم تسجيل الدخول بنجاح!'); window.location.href = 'game.php';</script>");
}

else {
    http_response_code(400);
    exit("<script>alert('⚠️ قيمة action غير معروفة: {$rawAction}'); window.history.back();</script>");
}