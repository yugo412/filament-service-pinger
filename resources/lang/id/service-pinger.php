<?php

return [
    'navigations' => [
        'service' => 'Layanan',
    ],

    'titles' => [
        'service' => 'Layanan',
        'check' => 'Riwayat Pengecekan',
        'view_check' => 'Lihat Riwayat Pengecekan',
    ],

    'fields' => [
        'is_active' => 'Aktif',
        'is_up' => 'Online',
        'name' => 'Nama',
        'url' => 'URL',
        'method' => 'Metode',
        'interval' => 'Interval',
        'timeout' => 'Waktu Tunggu',
        'status' => 'Status',
        'last_status_code' => 'Kode status terakhir',
        'last_checked_at' => 'Terakhir diperiksa',
        'created_at' => 'Dibuat pada',
        'updated_at' => 'Diperbarui pada',
        'body_as_json' => 'Kirim body sebagai JSON',
        'auth_type' => 'Tipe autentikasi',
        'auth_type_bearer' => 'Bearer',
        'auth_type_basic' => 'Basic',
        'username' => 'Nama pengguna',
        'password' => 'Kata sandi',
        'token' => 'Token',
        'checked_at' => 'Diperiksa pada',
        'response_time' => 'Waktu respons',
        'status_code' => 'Kode status',
        'error_message' => 'Pesan',
        'store_payload_history' => 'Simpan payload di riwayat ping',
        'expected_status' => 'Status yang diharapkan',
        'no_auth' => 'Tanpa autentikasi',
        'raw' => 'Salin mentah',
        'ms' => ' ms',
        'no_error_message' => 'Tidak ada pesan error',
        'do_not_store_check' => 'Jangan simpan riwayat pengecekan',
        'do_not_store_check_helper' => 'Jalankan pengecekan tanpa menyimpan riwayat. Hanya status terbaru dan waktu pengecekan yang disimpan.',
        'request_payload' => 'Payload Permintaan',
    ],

    'tabs' => [
        'requests' => 'Permintaan',
        'headers' => 'Header',
        'body' => 'Body',
        'auth' => 'Autentikasi',
    ],

    'tooltips' => [
        'expected_status' => 'Status yang diharapkan adalah :status',
    ],

    'modals' => [
        'ping_now_description' => 'Apakah Anda yakin ingin ping layanan ini sekarang?',
    ],

    'actions' => [
        'ping_now' => 'Ping',
        'view_check' => 'Lihat riwayat',
    ],

    'notifications' => [
        'ping_dispatched_title' => 'Ping dikirim',
    ],

    'widgets' => [
        'total_service' => 'Total Layanan',
        'service_up' => 'Layanan UP',
        'service_down' => 'Layanan DOWN',
        'total_check' => 'Total Pengecekan',
        'check_up' => 'Pengecekan UP',
        'check_down' => 'Pengecekan DOWN',
        'uptime' => 'Uptime',
        'uptime_description' => 'Berdasarkan :count pengecekan',
        'avg_response_time' => 'Rata-rata Waktu Respons',
        'latest_response_time' => 'Terbaru: :time ms',
        'last_checked' => 'Terakhir Diperiksa',
        'status_up' => 'Up',
        'status_down' => 'Down',
    ],
];
