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

