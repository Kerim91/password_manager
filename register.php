<?php
// register.php
$message = ""; // Başlangıçta boş bir mesaj tanımlayın
$messageColor = "black"; // Varsayılan renk

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Şifreyi hash'le
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    // Kullanıcı verisini oluştur (username ve password_hash)
    $userData = "username=$username;password_hash=$hashedPassword\n";
    
    // Dosya yolu
    $file = '/opt/lampp/htdocs/sifre_yoneticisi/users.txt';
   
    // Dosyaya yazma işlemi
    if (file_put_contents($file, $userData, FILE_APPEND) === false) {
        $message = "Dosyaya yazılamadı.";
        $messageColor = "red";
    } else {
        $message = "Kullanıcı başarıyla kaydedildi.";
        $messageColor = "green";
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kayıt Ol</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background: #fff;
            padding: 20px 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }
        h1 {
            margin-bottom: 20px;
            color: #333;
        }
        label {
            font-size: 14px;
            color: #555;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }
        button {
            background-color: #007BFF;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            width: 100%;
        }
        button:hover {
            background-color: #0056b3;
        }
        .message {
            margin-top: 15px;
            font-size: 14px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Kayıt Ol</h1>
      
        <form method="POST" action="register.php">
            <label for="username">Kullanıcı Adı:</label>
            <input type="text" name="username" required>
            
            <label for="password">Şifre:</label>
            <input type="password" name="password" required>
            
            <button type="submit">Kaydol</button>
        </form>
        
        <!-- Mesaj çıktısı burada görünecek -->
        <?php
        if (!empty($message)) {
            echo "<p class='message' style='color: $messageColor;'>$message</p>";
        }
        ?>
    </div>
</body>
</html>
