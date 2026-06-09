<?php

return [
    'common' => [
        'greeting' => 'Yth. Bapak/Ibu,',
        'whoops' => 'Mohon maaf,',
        'salutation' => 'Hormat kami,',
        'regard' => config('app.name'),
    ],

    'verify' => [
        'subject' => 'Verifikasi Alamat Email',
        'line_1'  => 'Mohon klik tombol di bawah ini untuk memverifikasi alamat email Anda.',
        'action'  => 'Verifikasi Email',
        'line_2'  => 'Apabila Anda tidak merasa membuat akun, mohon abaikan email ini.',
    ],

    'reset' => [
        'subject' => 'Pemberitahuan Pengaturan Ulang Kata Sandi',
        'line_1'  => 'Anda menerima email ini karena kami menerima permintaan pengaturan ulang kata sandi untuk akun Anda.',
        'action'  => 'Atur Ulang Kata Sandi',
        'line_2'  => 'Tautan pengaturan ulang kata sandi ini akan kedaluwarsa dalam :count menit.',
        'line_3'  => 'Apabila Anda tidak meminta pengaturan ulang kata sandi, tidak diperlukan tindakan lebih lanjut.',
    ],
];
