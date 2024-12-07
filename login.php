<?php
//oturum kısmı finish
session_start();
$errorMessage = ''; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // users.txt dosyasını oku
    $users = file('users.txt', FILE_IGNORE_NEW_LINES);

    // Kullanıcıyı kontrol et
    foreach ($users as $user) {
        // ';' ile kullanıcı adını ve şifreyi ayır
        list($storedUsername, $storedPasswordHash) = explode(';', $user);

        // username ve password_hash bilgilerini çıkar
        $storedUsername = str_replace('username=', '', $storedUsername);
        $storedPasswordHash = str_replace('password_hash=', '', $storedPasswordHash);

        // Kullanıcı adı ve şifreyi karşılaştır
        if ($storedUsername == $username && password_verify($password, $storedPasswordHash)) {
            // Başarılı giriş, oturum başlat
            $_SESSION['username'] = $username;
            header('Location: dashboard.php'); // Yönlendirme
            exit;
        }
    }

  
    $errorMessage = 'Geçersiz kullanıcı adı veya şifre!';
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giriş Yap</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f9fc;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .form-container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }
        .form-container h2 {
            margin-bottom: 20px;
            color: #333;
            text-align: center;
        }
        .form-container label {
            font-weight: bold;
            color: #555;
            display: block;
            margin-bottom: 8px;
        }
        .form-container input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }
        .form-container button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            border: none;
            border-radius: 4px;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
        }
        .form-container button:hover {
            background-color: #0056b3;
        }
        .error-message {
            color: red;
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Giriş Yap</h2>
    <form method="POST" action="login.php">
        <label for="username">Kullanıcı Adı:</label>
        <input type="text" name="username" id="username" required>

        <label for="password">Şifre:</label>
        <input type="password" name="password" id="password" required>
        
        <button type="submit">Giriş Yap</button>
    </form>

    <?php
    // Hata mesajı göster
    if ($errorMessage) {
        echo '<p class="error-message">' . $errorMessage . '</p>';
    }
    ?>
</div>

</body>
</html>
