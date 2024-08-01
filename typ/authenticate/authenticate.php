<?php
session_start();
include_once '../controllers/baglanti.php';
include_once '../controllers/islem.php';

$username = $_POST['email'];
$password = $_POST['password'];

$kullanici = new Tayyip();
$kullaniciData = $kullanici->getKullanici($username);

if ($kullaniciData) {
    if (password_verify($password, $kullaniciData->password)) {
        $_SESSION['loggedin'] = true;
        $_SESSION['email'] = $username;
        header("Location: ../admin/index.php");
        exit();
    } else {
        echo "Hatalı kullanıcı adı veya şifre." . password_verify($password, $kullaniciData->password);
    }
} else {
    echo "Hatalı kullanıcı adı veya şifre.";
}
