<?php
function sendTelegramMessage($chat_id, $text) {
    $url = "https://api.telegram.org/bot<YOUR-BOT-TOKEN>/sendMessage";
    $data = [
        'chat_id' => $chat_id,
        'text' => $text
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        error_log('cURL error: ' . curl_error($ch));
    }
    curl_close($ch);

    return $response;
}

// Veritabanı bağlantı bilgileri
$host = 'localhost'; // Veritabanı sunucusu
$db   = 'Veritabanı_adı'; // Veritabanı adı
$user = 'Veritabanı_kullanıcı_adı'; // Veritabanı kullanıcı adı
$pass = 'Veritabanı_şifresi'; // Veritabanı şifresi

// PDO ile veritabanı bağlantısı
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass, [
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
    ]);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    error_log("Veritabanı bağlantı hatası: " . $e->getMessage());
    die("Veritabanı bağlantı hatası: " . $e->getMessage());
}

// Zaman dilimini Türkiye saati olarak ayarla
date_default_timezone_set('Europe/Istanbul');

// Mevcut zamanı al
$current_time = date('Y-m-d H:i:s');
$target_chat_id = chat_id_alanı; // Hatırlatma mesajlarının gönderileceği chat ID

// Hatırlatıcıları kontrol et ve gönder
try {
    $stmt = $pdo->prepare("SELECT id, reminder_name FROM reminders WHERE reminder_time <= :current_time AND sent = 0");
    $stmt->bindParam(':current_time', $current_time);
    $stmt->execute();

    $reminders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($reminders as $reminder) {
        $reminder_name = $reminder['reminder_name'];

        // Hatırlatma mesajı gönder
        $message = "Hatırlatma: $reminder_name";
        sendTelegramMessage($target_chat_id, $message);

        // Hatırlatıcıyı gönderilmiş olarak işaretle
        $update_stmt = $pdo->prepare("UPDATE reminders SET sent = 1 WHERE id = :id");
        $update_stmt->bindParam(':id', $reminder['id']);
        $update_stmt->execute();
    }
} catch (Exception $e) {
    error_log("Hatırlatıcı kontrol hatası: " . $e->getMessage());
    die("Hatırlatıcı kontrol hatası: " . $e->getMessage());
}
?>
