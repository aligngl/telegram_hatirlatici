Bu script php ile telegram için oluşturulmuş bir hatırlatıcıdır. <br>
Adımları izleyerek kurulumu sağlayıp kullanabilirsiniz.

Veritabanı bilgilerini doldurun.<br>
$host = 'localhost'; // Veritabanı sunucusu<br>
$db   = 'Veritabanı_adı'; // Veritabanı adı<br>
$user = 'Veritabanı_kullanıcı_adı'; // Veritabanı kullanıcı adı<br>
$pass = 'Veritabanı_şifresi'; // Veritabanı şifresi
<br><br>
Telegram botunuzun tokenini belirtilen bu alanlara girin<br>
YOUR-BOT-TOKEN>
<br><br>
Son olarak chat id alanını doldurun<br>
chat-id-alanı
<br><br>
Cron oluşturma <br>
https://console.cron-job.org/login sitesinde üyelik oluşturun.<br>
cron link: domain.com/check_reminders.php <br>
dakikada 1 veya istediğiniz sürede cronu oluşturun.
<br><br>
telegram_webhook.php<br>
https://api.telegram.org/bot<YOUR-BOT-TOKEN>/setWebhook?url=https://domain.com/telegram_webhook.php<br>
verilen linkte <YOUR-BOT-TOKEN> alanına botunuzun tokenini girerek tarayıcıda 1 kez çalıştırın. <br>
<br><br>
Botunuza gidin ve örnekte olduğu gibi bir hatırlatıcı oluşturun.<br>
Örnek: Deneme Hatırlatıcı 20 Ocak 17:00
