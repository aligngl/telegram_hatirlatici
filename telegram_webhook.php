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
    curl_close($ch);

    return $response;
}

// Veritabanı bağlantı bilgileri
$host = 'localhost'; // Veritabanı sunucusu
$db   = 'aligaykk_muhasebe'; // Veritabanı adı
$user = 'aligaykk_muhasebe'; // Veritabanı kullanıcı adı
$pass = '25660780802Ali'; // Veritabanı şifresi

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

// Türkçe ay isimleri ile İngilizce ay isimleri arasındaki çeviri tablosu
$aylar = [
    'Ocak' => 'January',
    'Şubat' => 'February',
    'Mart' => 'March',
    'Nisan' => 'April',
    'Mayıs' => 'May',
    'Haziran' => 'June',
    'Temmuz' => 'July',
    'Ağustos' => 'August',
    'Eylül' => 'September',
    'Ekim' => 'October',
    'Kasım' => 'November',
    'Aralık' => 'December',
    
    'ocak' => 'January',
    'şubat' => 'February',
    'mart' => 'March',
    'nisan' => 'April',
    'mayıs' => 'May',
    'haziran' => 'June',
    'temmuz' => 'July',
    'ağustos' => 'August',
    'eylül' => 'September',
    'ekim' => 'October',
    'kasım' => 'November',
    'aralık' => 'December'
    
];

// Telegram'dan gelen JSON verisini al
$content = file_get_contents("php://input");
$update = json_decode($content, true);

if ($update && isset($update['message'])) {
    $chat_id = $update['message']['chat']['id'];
    $text = $update['message']['text'];
    $text = mb_convert_encoding($text, 'UTF-8', 'auto'); // Gelen veriyi UTF-8'e dönüştür
    

    // Mesajı ayır ve hatırlatıcı bilgilerini al
    if (preg_match('/^(.+?) (\d{1,2}) (\w+) (\d{1,2}[:.]\d{2})$/u', $text, $matches)) {
        $reminder_name = $matches[1];
        $day = $matches[2];
        $month = $matches[3];
        $time = $matches[4];

        // Türkçe ay ismini İngilizceye çevir
        if (isset($aylar[$month])) {
            $month = $aylar[$month];
        } else {
            error_log("Geçersiz ay ismi: " . $month);
            sendTelegramMessage($chat_id, "Geçersiz ay ismi.");
            exit;
        }

        // Tarih ve saat formatını doğru oluştur
        $reminder_time_str = $day . ' ' . $month . ' ' . date('Y') . ' ' . str_replace('.', ':', $time);
        $reminder_time = DateTime::createFromFormat('d F Y H:i', $reminder_time_str);
        if (!$reminder_time) {
            error_log("Tarih ve saat formatı yanlış: " . $reminder_time_str);
            sendTelegramMessage($chat_id, "Geçersiz tarih ve saat formatı.");
            exit;
        }
        $reminder_time = $reminder_time->format('Y-m-d H:i:s');
        

        // Hatırlatıcıyı veritabanına ekleme
        $stmt = $pdo->prepare("INSERT INTO reminders (chat_id, reminder_name, reminder_time) VALUES (:chat_id, :reminder_name, :reminder_time)");
        $stmt->bindParam(':chat_id', $chat_id);
        $stmt->bindParam(':reminder_name', $reminder_name);
        $stmt->bindParam(':reminder_time', $reminder_time);

        if ($stmt->execute()) {
            sendTelegramMessage($chat_id, "Hatırlatıcı kaydedildi: $reminder_name, $reminder_time");
        } else {
            error_log("Hatırlatıcı kaydedilemedi.");
            sendTelegramMessage($chat_id, "Hatırlatıcı kaydedilemedi.");
        }
    } else {
        error_log("Geçersiz format.");
        sendTelegramMessage($chat_id, "Geçersiz format. Lütfen 'hatırlatıcı adı tarih saat' formatında gönderin.");
    }
} else {
    
    echo "Geçersiz veri.";
}
?>
