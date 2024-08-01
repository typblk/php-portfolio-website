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
    
    include "admin-partials/_header.php" 
    ?>

<?php
$ayarlar = new Tayyip();
$item = $ayarlar->getAyarlar();

if (isset($_POST["submit"])) {
    $site_url = $_POST["site_url"];
    $analytic = $_POST["analytic"];
    $alt_aciklama = $_POST["alt_aciklama"];

    // Hataları ve başarı mesajını göster
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo '
            <div class="bs-toast toast toast-placement-ex m-2 top-0 end-0 fade show bg-danger" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header">
                    <i class="bx bx-bell me-2"></i>
                    <div class="me-auto fw-medium">Hata</div>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    ' . htmlspecialchars($error) . '
                </div>
            </div>';
        }
    } else {
        if ($ayarlar->editSeoAyarlar( $site_url, $analytic, $alt_aciklama)) {
            echo '
            <div class="bs-toast toast toast-placement-ex m-2 top-0 end-0 fade show bg-success" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header">
                    <i class="bx bx-bell me-2"></i>
                    <div class="me-auto fw-medium">Başarılı</div>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    Ayarlar başarıyla güncellendi.
                </div>
            </div>';
            echo '<script>window.location.href = "seo-ayarlari.php";</script>';
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
                    Ayarlar güncellenirken bir hata oluştu.
                </div>
            </div>';
        }
    }
}
?>

<!-- Content wrapper -->
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 mb-4">
            <span class="text-muted fw-light">Dashboard /</span> İletişim Ayarlar
        </h4>
        <div class="pb-3">
            <ul class="nav nav-pills mb-3" role="tablist">
                <li class="nav-item" role="presentation">
                    <a href="genel-ayarlar.php" type="button" class="nav-link">Genel Ayarlar</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a href="seo-ayarlari.php" type="button" class="nav-link active">SEO Ayarları</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a href="iletisim.php" type="button" class="nav-link">İletişim Ayarları</a>
                </li>
            </ul>
        </div>
        <div class="card mb-4">
            <div class="card-body">
                <form method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label" for="site_url">Site Url</label>
                        <input type="url" class="form-control" id="site_url" name="site_url" value="<?php echo htmlspecialchars($item->site_url) ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="Analytics">Google Analytics</label>
                        <textarea id="Analytics" class="form-control" name="analytic"><?php echo htmlspecialchars($item->analytic) ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="alt_aciklama">Footer Açıklaması</label>
                        <textarea id="alt_aciklama" class="form-control" name="alt_aciklama"><?php echo htmlspecialchars($item->alt_aciklama) ?></textarea>
                    </div>
                    <button type="submit" name="submit" class="btn btn-primary">Kaydet</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- / Content -->

<?php include "admin-partials/_footer.php" ?>