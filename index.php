<?php
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
    die("Veritabanı bağlantı hatası: " . $e->getMessage());
}

// Form verilerini işleme
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reminder_name = $_POST['reminder_name'];
    $reminder_time = date('Y-m-d H:i:s', strtotime($_POST['reminder_time']));
    $chat_id = null; // Web arayüzünden gelen veriler için chat_id yok

    // Hatırlatıcıyı veritabanına ekleme
    $stmt = $pdo->prepare("INSERT INTO reminders (chat_id, reminder_name, reminder_time) VALUES (:chat_id, :reminder_name, :reminder_time)");
    $stmt->bindParam(':chat_id', $chat_id);
    $stmt->bindParam(':reminder_name', $reminder_name);
    $stmt->bindParam(':reminder_time', $reminder_time);

    if ($stmt->execute()) {
        $message = "Hatırlatıcı kaydedildi.";
    } else {
        $message = "Hatırlatıcı kaydedilemedi.";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aglsoft Hatırlatıcı</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: #f8f9fa;
        }
        .container {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .form-group label {
            font-weight: bold;
        }
        .btn-block {
            width: 100%;
            padding: 10px;
            font-size: 1.2em;
        }
        .shortcut-group {
            margin-bottom: 20px;
        }
        .shortcut-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .shortcut-btn {
            margin: 2px;
            flex: 1 1 20%;
            font-size: 0.9em;
            padding: 5px;
        }
        .d-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
        }
        @media (max-width: 768px) {
            .shortcut-btn {
                flex: 1 1 30%;
            }
        }
        @media (max-width: 576px) {
            .shortcut-btn {
                flex: 1 1 45%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Aglsoft Hatırlatıcı</h2>
        <?php if (isset($message)): ?>
            <div class="alert alert-info">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="reminder_name">Hatırlatıcı Adı:</label>
                <input type="text" class="form-control" id="reminder_name" name="reminder_name" placeholder="Sana ne hatırlatalım?" required>
            </div>
            <div class="form-group">
                <label for="reminder_time">Hatırlatma Zamanı:</label>
                <input type="datetime-local" class="form-control" id="reminder_time" name="reminder_time" required>
            </div>
            <div class="form-group shortcut-group">
                <label>Kısayollar:</label>
                <div class="d-grid">
                    <button type="button" class="btn btn-secondary shortcut-btn" onclick="setReminderTime(15)">15 dk</button>
                    <button type="button" class="btn btn-secondary shortcut-btn" onclick="setReminderTime(30)">30 dk</button>
                    <button type="button" class="btn btn-secondary shortcut-btn" onclick="setReminderTime(60)">1 saat</button>
                    <button type="button" class="btn btn-secondary shortcut-btn" onclick="setReminderTime(120)">2 saat</button>
                    <button type="button" class="btn btn-secondary shortcut-btn" onclick="setReminderTime(180)">3 saat</button>
                    <button type="button" class="btn btn-secondary shortcut-btn" onclick="setReminderTime(240)">4 saat</button>
                    <button type="button" class="btn btn-secondary shortcut-btn" onclick="setReminderTime(300)">5 saat</button>
                    <button type="button" class="btn btn-secondary shortcut-btn" onclick="setReminderTime(360)">6 saat</button>
                    <button type="button" class="btn btn-secondary shortcut-btn" onclick="setReminderTime(1440)">1 gün</button>
                    <button type="button" class="btn btn-secondary shortcut-btn" onclick="setReminderTime(2880)">2 gün</button>
                    <button type="button" class="btn btn-secondary shortcut-btn" onclick="setReminderTime(4320)">3 gün</button>
                    <button type="button" class="btn btn-secondary shortcut-btn" onclick="setReminderTime(10080)">1 hafta</button>
                    <button type="button" class="btn btn-secondary shortcut-btn" onclick="setReminderTime(20160)">2 hafta</button>
                    <button type="button" class="btn btn-secondary shortcut-btn" onclick="setReminderTime(30240)">3 hafta</button>
                    <button type="button" class="btn btn-secondary shortcut-btn" onclick="setReminderTime(43200)">1 ay</button>
                    <button type="button" class="btn btn-secondary shortcut-btn" onclick="setReminderTime(86400)">2 ay</button>
                    <button type="button" class="btn btn-secondary shortcut-btn" onclick="setReminderTime(129600)">3 ay</button>
                </div>
            </div>
            <button type="submit" class="btn btn-success btn-block">Hatırlat!</button>
        </form>
    </div>
    <script>
        function setReminderTime(minutes) {
            var now = new Date();
            now.setMinutes(now.getMinutes() + minutes);
            var year = now.getFullYear();
            var month = ('0' + (now.getMonth() + 1)).slice(-2);
            var day = ('0' + now.getDate()).slice(-2);
            var hours = ('0' + now.getHours()).slice(-2);
            var minutes = ('0' + now.getMinutes()).slice(-2);
            var formattedDateTime = year + '-' + month + '-' + day + 'T' + hours + ':' + minutes;
            document.getElementById('reminder_time').value = formattedDateTime;
        }
    </script>
    
</body>
</html>
