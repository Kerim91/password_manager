
<?php
//data base kullananlare için ekledim ama ben şimdilik localde .txt   formatı için gerekli ayarları yaptım.
$servername = "localhost";
$username = "root";  // Kullanıcı adı
$password = "your_password";  // MySQL/MariaDB root şifresi
$dbname = "password_manager";  // Kullanmak istediğiniz veritabanı adı

// Bağlantıyı oluştur
$conn = new mysqli($servername, $username, $password, $dbname);

// Bağlantıyı kontrol et
if ($conn->connect_error) {
    die("Bağlantı hatası: " . $conn->connect_error);  
}

echo "bağlantınız başarılı..."
?>
