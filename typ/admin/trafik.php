<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
  header("Location: ../authenticate/login.php");
  exit();
}
?>

<?php
$timeoutDuration = 900;

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > $timeoutDuration) {
  session_unset();
  session_destroy();
  header("Location: login.php");
  exit();
}

$_SESSION['LAST_ACTIVITY'] = time();
?>

<?php

include '../controllers/baglanti.php';
include '../controllers/islem.php';

$tayyip = new Tayyip();
$trafficData = $tayyip->getTrafficByMonth();

// JSON formatında veri döndür
header('Content-Type: application/json');
echo json_encode($trafficData);
?>
