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
?>

<?php

$yorumlar = new Tayyip();

if (isset($_POST["deleteYorum"])) {
    $id = $_POST["yorumId"];

    if ($yorumlar->deleteYorum($id)) {
        echo '
        <div class="bs-toast toast fade show bg-success" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <i class="bx bx-bell me-2"></i>
                <div class="me-auto fw-medium">Başarılı</div>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                Yorum başarıyla silindi.
            </div>
        </div>';
        header('location: yorumlar.php');
        exit();
    } else {
        echo '
        <div class="bs-toast toast fade show bg-danger" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <i class="bx bx-bell me-2"></i>
                <div class="me-auto fw-medium">Hata</div>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                Yorum silinirken bir hata oluştu.
            </div>
        </div>';
    }
}

?>