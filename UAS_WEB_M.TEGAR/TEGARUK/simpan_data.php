<?php
// Konfigurasi koneksi database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hubungi_km";

// reCAPTCHA Secret Key
$recaptchaSecret = "6Lelp7gqAAAAAHb200kAY6FvYuPX3_oEatvgPnmb";

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil data dari form
$nama = $conn->real_escape_string($_POST['name']);
$email = $conn->real_escape_string($_POST['email']);
$perusahaan = $conn->real_escape_string($_POST['company']);
$telepon = $conn->real_escape_string($_POST['phone']);
$pesan = $conn->real_escape_string($_POST['message']);

// Validasi reCAPTCHA
$recaptchaResponse = $_POST['g-recaptcha-response'];
$verifyResponse = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$recaptchaSecret&response=$recaptchaResponse");
$responseData = json_decode($verifyResponse);

if ($responseData->success) {
    // Simpan data ke tabel
    $sql = "INSERT INTO hubungi_kami (nama, email, perusahaan_organisasi, telepon, isi_pesan) 
            VALUES ('$nama', '$email', '$perusahaan', '$telepon', '$pesan')";

    if ($conn->query($sql) === TRUE) {
        echo "<h2>Selamat, pesan Anda telah terkirim ke Database. Terimakasihh....</h2>";
    } else {
        echo "<h2>Terjadi kesalahan: " . $conn->error . "</h2>";
    }
} else {
    echo "<h2>Verifikasi reCAPTCHA gagal. Silakan coba lagi.</h2>";
}

// Tutup koneksi
$conn->close();
?>
