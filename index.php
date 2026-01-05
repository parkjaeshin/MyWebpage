<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>연락처 | 문의하기</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 600px; margin: 50px auto; padding: 20px; background: #f5f5f5; }
        h1 { text-align: center; color: #333; }
        form { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        label { display: block; margin: 15px 0 5px; font-weight: bold; color: #555; }
        input[type="text"], input[type="email"], textarea { 
            width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; 
            font-size: 16px; 
        }
        textarea { height: 120px; resize: vertical; }
        button { 
            width: 100%; background: #007bff; color: white; padding: 15px; border: none; 
            border-radius: 5px; font-size: 18px; cursor: pointer; margin-top: 20px; 
        }
        button:hover { background: #0056b3; }
        .message { padding: 15px; margin: 20px 0; border-radius: 5px; text-align: center; font-weight: bold; }
        .success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>
    <h1>문의하기</h1>
    
    <?php
    $message = '';
    $messageType = '';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $inquiry = trim($_POST['inquiry'] ?? '');

        // 유효성 검사
        if (empty($name) || empty($email) || empty($inquiry)) {
            $message = '모든 필드를 입력해주세요.';
            $messageType = 'error';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $message = '올바른 이메일 주소를 입력해주세요.';
            $messageType = 'error';
        } else {
            // mail() 전송 설정 (받는 메일 주소 변경하세요)
            $to = 'park.jaeshin.02@gmail.com';  // 실제 받는 메일로 변경
            $subject = 'php이메일전송테스트: ' . $name;
            $body = "이름: $name\n이메일: $email\n문의 내용:\n$inquiry";
            $headers = "From: $email\r\nReply-To: $email\r\nContent-Type: text/plain; charset=UTF-8\r\n";

            if (mail($to, $subject, $body, $headers)) {
                $message = "문의가 성공적으로 전송되었습니다! ($name)";
                $messageType = 'success';
            } else {
                $message = '전송 중 오류가 발생했습니다. 다시 시도해주세요.';
                $messageType = 'error';
            }
        }
    }
    ?>

    <?php if (empty($_POST['name'])): ?>  <!-- 처음 로드 또는 성공 시 폼 표시 -->
    <form method="POST">
        <label for="name">이름:</label>
        <input type="text" id="name" name="name" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>

        <label for="email">이메일:</label>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>

        <label for="inquiry">문의 내용:</label>
        <textarea id="inquiry" name="inquiry" required><?= htmlspecialchars($_POST['inquiry'] ?? '') ?></textarea>

        <button type="submit">전송</button>
    </form>
    <?php endif; ?>

    <?php if ($message): ?>
        <div class="message <?= $messageType ?>">
            <?= htmlspecialchars($message) ?>
            <?php if (strpos($message, '성공') !== false): ?>
                <br><a href="contact.php" style="color: #007bff;">← 다시 문의하기</a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</body>
</html>