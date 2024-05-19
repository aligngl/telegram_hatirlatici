Bu script php ile telegram için oluşturulmuş bir hatırlatıcıdır. 
Adımları izleyerek kurulumu sağlayıp kullanabilirsiniz.

Veritabanı bilgilerini doldurun.
$host = 'localhost'; // Veritabanı sunucusu
$db   = 'Veritabanı_adı'; // Veritabanı adı
$user = 'Veritabanı_kullanıcı_adı'; // Veritabanı kullanıcı adı
$pass = 'Veritabanı_şifresi'; // Veritabanı şifresi

Telegram botunuzun tokenini belirtilen bu alanlara girin
<YOUR-BOT-TOKEN>

Son olarak chat id alanını doldurun
chat-id-alanı

Cron oluşturma 
https://console.cron-job.org/login sitesinde üyelik oluşturun.
cron link: domain.com/check_reminders.php 
dakikada 1 veya istediğiniz sürede cronu oluşturun.

telegram_webhook.php
https://api.telegram.org/bot<YOUR-BOT-TOKEN>/setWebhook?url=https://domain.com/telegram_webhook.php
verilen linkte <YOUR-BOT-TOKEN> alanına botunuzun tokenini girerek tarayıcıda 1 kez çalıştırın. 

Botunuza gidin ve örnekte olduğu gibi bir hatırlatıcı oluşturun.
Örnek: Deneme Hatırlatıcı 20 Ocak 17:00
