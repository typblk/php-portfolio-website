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
require_once __DIR__ . '/../helpers/upload_utils.php'; // Include the new utility file

include "admin-partials/_header.php";

use Typ\Helpers; // Use the namespace
?>

<?php
$ayarlar = new Tayyip();
$item = $ayarlar->getAyarlar();

if (isset($_POST["submit"])) {
    $errors = []; // Initialize errors array
    $success = "";

    // Retrieve raw POST data
    $site_adi_raw = $_POST["site_adi"] ?? '';
    $site_desc_raw = $_POST["site_desc"] ?? '';
    $site_keyw_raw = $_POST["site_keyw"] ?? '';

    // Validation
    if (empty(trim($site_adi_raw))) {
        $errors[] = "Site adı boş bırakılamaz.";
    }
    if (empty(trim($site_desc_raw))) {
        $errors[] = "Site açıklaması boş bırakılamaz.";
    }
    if (empty(trim($site_keyw_raw))) {
        $errors[] = "Site anahtar kelimeleri boş bırakılamaz.";
    }
    // Optional: Max length checks
    if (mb_strlen(trim($site_adi_raw)) > 100) { // Example length
        $errors[] = "Site adı 100 karakterden uzun olamaz.";
    }
    if (mb_strlen(trim($site_desc_raw)) > 255) { // Example length
        $errors[] = "Site açıklaması 255 karakterden uzun olamaz.";
    }
    if (mb_strlen(trim($site_keyw_raw)) > 255) { // Example length
        $errors[] = "Site anahtar kelimeleri 255 karakterden uzun olamaz.";
    }

    // Proceed only if initial validation passes
    if (empty($errors)) {
        // Sanitization
        $site_adi_sanitized = htmlspecialchars(trim($site_adi_raw), ENT_QUOTES, 'UTF-8');
        $site_desc_sanitized = htmlspecialchars(trim($site_desc_raw), ENT_QUOTES, 'UTF-8');
        $site_keyw_sanitized = htmlspecialchars(trim($site_keyw_raw), ENT_QUOTES, 'UTF-8');

        // File upload settings
        $uploadDir = '../images/'; 
        $allowedTypes = [
            'image/jpeg', 'image/png', 'image/gif', 'image/webp', 
            'image/svg+xml', 'image/x-icon', 'image/vnd.microsoft.icon'
        ];
        $maxFileSize = 5 * 1024 * 1024; // 5MB

        $logo = $_FILES['logo'];
        $favicon = $_FILES['favicon'];

        $logoNewName = $item->logo; // Default to existing logo
        $faviconNewName = $item->favicon; // Default to existing favicon

        // Process logo upload
        if (!empty($logo['name']) && $logo['error'] == UPLOAD_ERR_OK) {
            $uploadedLogoName = Helpers\processFileUpload($logo, $site_adi_sanitized . "_logo", $uploadDir, $allowedTypes, $maxFileSize, $errors);
            if ($uploadedLogoName) {
                $logoNewName = $uploadedLogoName;
            }
        } elseif (!empty($logo['name']) && $logo['error'] !== UPLOAD_ERR_NO_FILE) {
            // File was provided but an error occurred (other than no file)
            $errors[] = "Logo yüklenirken bir hata oluştu: " . $logo['name'] . " (Hata kodu: " . $logo['error'] . ")";
        }

        // Process favicon upload (only if no errors so far from text validation or logo upload)
        if (empty($errors)) {
            if (!empty($favicon['name']) && $favicon['error'] == UPLOAD_ERR_OK) {
                $uploadedFaviconName = Helpers\processFileUpload($favicon, $site_adi_sanitized . "_favicon", $uploadDir, $allowedTypes, $maxFileSize, $errors);
                if ($uploadedFaviconName) {
                    $faviconNewName = $uploadedFaviconName;
                }
            } elseif (!empty($favicon['name']) && $favicon['error'] !== UPLOAD_ERR_NO_FILE) {
                // File was provided but an error occurred
                $errors[] = "Favicon yüklenirken bir hata oluştu: " . $favicon['name'] . " (Hata kodu: " . $favicon['error'] . ")";
            }
        }

        // Proceed with database update only if all validations and file uploads (if any) were successful
        if (empty($errors)) {
            if ($ayarlar->editGenelAyarlar($site_adi_sanitized, $site_desc_sanitized, $site_keyw_sanitized, $logoNewName, $faviconNewName)) {
                $success = "Ayarlar başarıyla güncellendi.";
            } else {
                $errors[] = "Ayarlar güncellenirken bir veritabanı hatası oluştu.";
            }
        }
    }

    // Display errors or success message
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
    } elseif (!empty($success)) { // Ensure $success is not empty for success message
        // The success message ($success) is set, but will not be displayed on this page
        // due to the immediate header redirect.
        // Consider using session flash messages to display the success message on the target page (genel-ayarlar.php).
        // For example, $_SESSION['flash_message'] = $success;
        header("Location: genel-ayarlar.php");
        exit();
    }
}
?>




<!-- Content wrapper -->
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 mb-4">
            <span class="text-muted fw-light">Dashboard /</span> Genel Ayarlar
        </h4>
        <div class="pb-3">
            <ul class="nav nav-pills mb-3" role="tablist">
                <li class="nav-item" role="presentation">
                    <a href="genel-ayarlar.php" type="button" class="nav-link active">Genel Ayarlar</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a href="seo-ayarlari.php" type="button" class="nav-link">SEO Ayarları</a>
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
                        <label class="form-label" for="title">Site Adı</label>
                        <input type="text" class="form-control" id="title" name="site_adi" value="<?php echo htmlspecialchars($item->site_adi) ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="desc">Site Açıklaması</label>
                        <input type="text" class="form-control" id="desc" name="site_desc" value="<?php echo htmlspecialchars($item->site_desc) ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="keyw">Site Anahtar Kelimeler</label>
                        <input type="text" class="form-control" id="keyw" name="site_keyw" value="<?php echo htmlspecialchars($item->site_keyw) ?>">
                    </div>
                    <div class="mb-3">
                        <label for="logo" class="form-label">Logo</label> <br>
                        <img src="../images/<?php echo htmlspecialchars($item->logo) ?>" width="50" alt="">
                        <input class="form-control" type="file" id="logo" name="logo">
                    </div>
                    <div class="mb-3">
                        <label for="favicon" class="form-label">Favikon</label> <br>
                        <img src="../images/<?php echo htmlspecialchars($item->favicon) ?>" width="50" alt="">
                        <input class="form-control" type="file" id="favicon" name="favicon">
                    </div>
                    <button type="submit" name="submit" class="btn btn-primary">Kaydet</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- / Content -->

<?php include "admin-partials/_footer.php" ?>