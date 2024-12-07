<?php
// dashboard.php
session_start();


if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

$username = $_SESSION['username'];

$errorMessage = '';
$successMessage = '';
$file = '/opt/lampp/htdocs/sifre_yoneticisi/password.txt';

// Şifre Ekleme / Güncelleme / Silme İşlemleri
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'add') {
    
        $service = $_POST['service'];
        $password = $_POST['password'];
        $hashedPassword = password_hash( $password, algo: PASSWORD_ARGON2ID);

       
        $userData = "$service:$hashedPassword\n";
        if (file_put_contents($file, $userData, FILE_APPEND) === false) {
            $errorMessage = "Şifre kaydedilemedi!";
        } else {
            $successMessage = "Şifre başarıyla kaydedildi!";
        }
    } elseif ($action === 'delete') {
        // Şifre silme,admin tavsiyesi
        $serviceToDelete = $_POST['service'];
        $passwords = file($file, FILE_IGNORE_NEW_LINES);
        $newPasswords = array_filter($passwords, function ($line) use ($serviceToDelete) {
            return !str_starts_with($line, "$serviceToDelete:");
        });

        if (file_put_contents($file, implode("\n", $newPasswords) . "\n") === false) {
            $errorMessage = "Şifre silinemedi!";
        } else {
            $successMessage = "Şifre başarıyla silindi!";
        }
    } elseif ($action === 'update') {
     
        $serviceToUpdate = $_POST['service'];
        $newPassword = $_POST['password'];
        $hashedPassword = password_hash($newPassword, PASSWORD_ARGON2ID);
        $passwords = file($file, FILE_IGNORE_NEW_LINES);
        $updated = false;

        $newPasswords = array_map(function ($line) use ($serviceToUpdate, $hashedPassword, &$updated) {
            if (str_starts_with($line, "$serviceToUpdate:")) {
                $updated = true;
                return "$serviceToUpdate:$hashedPassword";
            }
            return $line;
        }, $passwords);

        if ($updated) {
            if (file_put_contents($file, implode("\n", $newPasswords) . "\n") === false) {
                $errorMessage = "Şifre güncellenemedi!";
            } else {
                $successMessage = "Şifre başarıyla güncellendi!";
            }
        } else {
            $errorMessage = "Hizmet bulunamadı!";
        }
    }
}

// Dosyadan şifreleri oku
$passwords = [];
if (file_exists($file)) {
    $passwords = file($file, FILE_IGNORE_NEW_LINES);
}

?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Şifre Yöneticisi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f9fc;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        h1, h2 {
            text-align: center;
            color: #007bff;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        label {
            font-weight: bold;
        }
        input, select, button {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            background-color: #007bff;
            color: white;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .message {
            margin: 10px 0;
            text-align: center;
            font-weight: bold;
        }
        .message.error {
            color: red;
        }
        .message.success {
            color: green;
        }
        .password-list ul {
            list-style-type: none;
            padding: 0;
        }
        .password-list li {
            background: #f1f1f1;
            margin: 5px 0;
            padding: 10px;
            border-radius: 4px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .password-list li button {
            background: red;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Hoş geldiniz, <?php echo htmlspecialchars($username); ?>!</h1>

        <h2>Yeni Şifre Ekle</h2>
        <?php if ($errorMessage): ?>
            <p class="message error"><?php echo $errorMessage; ?></p>
        <?php endif; ?>
        <?php if ($successMessage): ?>
            <p class="message success"><?php echo $successMessage; ?></p>
        <?php endif; ?>

        <form method="POST" action="">
            <input type="hidden" name="action" value="add">
            <label for="service">Hizmet Adı:</label>
            <input type="text" name="service" required>
            <label for="password">Şifre:</label>
            <input type="password" name="password" required>
            <button type="submit">Şifreyi Kaydet</button>
        </form>

        <h2>Kaydedilen Şifreler</h2>
        <div class="password-list">
            <ul>
                <?php foreach ($passwords as $password): ?>
                    <?php list($service, $hashedPassword) = explode(':', $password); ?>
                    <li>
                        Hizmet: <?php echo htmlspecialchars($service); ?>
                        <form method="POST" action="" style="display: inline;">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="service" value="<?php echo htmlspecialchars($service); ?>">
                            <button type="submit">Sil</button>
                        </form>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</body>
</html>
