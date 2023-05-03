# dialogflow_restokukuhtwbot
/*
Demo website : 
https://examenginebot.com/opensource_demorestokukuhtwbot/demowebsite/

Penjelasan di youtube :
https://www.youtube.com/watch?v=_FjJfYaxyhI

Demo Chatbot Dialogflow
Studi Kasus : Restaurant 
Menyediakan menu nasi goreng, mie ayam, mie bihun, es teh tawar, es jeruk, es kelapa muda
Pembeli dapat pesan melalui whatsapp bot dengan Bahasa natural seperti
1.	Berapa harga nasi goreng ?
2.	Oke saya Pesan 1 nasi goreng dong
3.	Minta 1 es teh tawar
4.	Mie ayam pesan 2 dong
5.	Es jeruk 1 es kelapa muda 1

Flow Arsitektur chatbot

website/Whatsapp -> Google cloud Dialogflow ->  Backend Chatbot  ->

======== ENGLISH ========
Later on, a WhatsApp bot can use the official WA API, WA API Gateway, or WA Autoresponder apps that can be downloaded from the Google Play Store.

To make the demo on Dialogflow work, you need to:
1. Open your own VPS hosting account
2. Direct the fulfillment server to the webhook on your own VPS hosting
3. Generate a Service Account Key in your Dialogflow project
4. Place the JSON service account key file in your website folder
5. Create your own database, the script is available in the databasemysql folder.

To make the demo on the website work, you need to:
1. Generate a Service Account Key in your Dialogflow project
2. Place the JSON service account key file in your website folder
3. Update the PHP client Google Cloud Dialogflow, download it here or use Composer to update the latest PHP client library at https://github.com/googleapis/google-cloud-php-dialogflow
4. Make sure the database settings, database username, database password, and database name match the configuration settings on your VPS hosting.

====== INDONESIA ===============
Nantinya whatsapp bot bisa menggunakan WA API official resmi , WA API Gateway ataupun apps WA Autoresponder yang bisa didownload di google play store

Agar demo pada dialogflow bisa berjalan, anda perlu
1. Buka VPS hosting account anda sendiri
2. Arahkan fulfillment server ke webhook pada VPS hosting anda sendiri
3. Generate Service Account Key pada project Dialogflow anda
4. Letakkan file json service account key pada folder website anda
5. Buat database anda sendiri, script tersedia di folder databasemysql

Agar demo pada website bisa berjalan, anda perlu

1. Generate Service Account Key pada project Dialogflow anda
2. Letakkan file json service account key pada folder website anda
3. Update php client google cloud dialogflow, download disini atau gunakan composer untuk update
library terbaru php client https://github.com/googleapis/google-cloud-php-dialogflow
4. pastikan setting database, username database, password database, nama database sesuai dengan setting konfigurasi di VPS hosting anda


support me
https://patreon.com/kukuhtw


