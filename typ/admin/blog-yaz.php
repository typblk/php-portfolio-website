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

if (isset($_POST["submit"])) {
    $errors = []; // Initialize errors array
    $success = "";

    // Retrieve raw POST data
    $baslik_raw = $_POST["baslik"] ?? '';
    $etiket_raw = $_POST["etiket"] ?? '';
    $kisa_aciklama_raw = $_POST["kisa_aciklama"] ?? '';
    $blog_raw = $_POST["blog"] ?? '';
    $tarih = date('Y-m-d');

    // Validation
    if (empty(trim($baslik_raw))) {
        $errors[] = "Başlık boş bırakılamaz.";
    }
    if (empty(trim($kisa_aciklama_raw))) {
        $errors[] = "Kısa açıklama boş bırakılamaz.";
    }
    if (empty(trim($blog_raw))) {
        $errors[] = "Blog içeriği boş bırakılamaz.";
    }
    // Optional: Max length checks
    if (mb_strlen(trim($baslik_raw)) > 255) {
        $errors[] = "Başlık 255 karakterden uzun olamaz.";
    }
    if (mb_strlen(trim($kisa_aciklama_raw)) > 500) { // Example length
        $errors[] = "Kısa açıklama 500 karakterden uzun olamaz.";
    }

    // Proceed only if initial validation passes
    if (empty($errors)) {
        // Sanitization
        $baslik_sanitized = htmlspecialchars(trim($baslik_raw), ENT_QUOTES, 'UTF-8');
        $etiket_sanitized = htmlspecialchars(trim($etiket_raw), ENT_QUOTES, 'UTF-8');
        $kisa_aciklama_sanitized = htmlspecialchars(trim($kisa_aciklama_raw), ENT_QUOTES, 'UTF-8');
        // For 'blog' content, if it's intended to be plain text or needs escaping for security.
        // If it's HTML from a WYSIWYG, this might be too aggressive.
        // Assuming for now it should be escaped.
        $blog_sanitized = htmlspecialchars(trim($blog_raw), ENT_QUOTES, 'UTF-8');

        // Use sanitized slugify from helpers
        $blogUrl = Helpers\slugify($baslik_sanitized);

        // Dosya yükleme işlemleri
        $uploadDir = '../images/'; // Relative to this script (typ/admin/blog-yaz.php)
        $allowedTypes = [
            'image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml'
        ];
        $maxFileSize = 10 * 1024 * 1024; // 10MB

        $fotograf = $_FILES['fotograf'];
        $uploadedFileName = null; // Initialize

        // Check if a file was actually uploaded
        if (isset($fotograf['name']) && $fotograf['error'] !== UPLOAD_ERR_NO_FILE) {
            // Call processFileUpload from helpers using sanitized baslik for baseName
            $uploadedFileName = Helpers\processFileUpload($fotograf, $baslik_sanitized, $uploadDir, $allowedTypes, $maxFileSize, $errors);
        } else {
            // If no file is uploaded, or it's optional, you might not add an error.
            // If a file is required, add an error:
            $errors[] = "Blog fotoğrafı yüklenmelidir."; 
        }

        // Proceed with database insertion only if file upload was successful (or optional and no file was uploaded)
        // and no new errors occurred during file upload.
        if (empty($errors)) { // Re-check errors after potential file upload issues
            $bloglar = new Tayyip();
            // Use sanitized variables for database insertion
            if ($bloglar->createBlog($baslik_sanitized, $uploadedFileName, $etiket_sanitized, $kisa_aciklama_sanitized, $blog_sanitized, $tarih, $blogUrl)) {
                $success = "Blog başarıyla eklendi.";
            } else {
                $errors[] = "Blog eklenirken bir veritabanı hatası oluştu.";
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
    } elseif (!empty($success)) { // Check if $success is not empty
        // The success message ($success) is set, but will not be displayed on this page
        // due to the immediate header redirect.
        // Consider using session flash messages to display the success message on the target page (bloglar.php).
        // For example, $_SESSION['flash_message'] = $success;
        header("Location: bloglar.php");
        exit();
    }
}

?>

<!-- Content wrapper -->
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 mb-4">
            <span class="text-muted fw-light">Dashboard /</span> Blog Yaz
        </h4>
        <div class="card mb-4">
            <div class="card-body">
                <form method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label" for="baslik">Başlık</label>
                        <input type="text" class="form-control" id="baslik" name="baslik" placeholder="Başlık">
                    </div>
                    <div class="mb-3">
                        <label for="blog-foto" class="form-label">Fotoğraf</label>
                        <input class="form-control" type="file" id="blog-foto" name="fotograf">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="etiket">Etiket</label>
                        <input type="text" class="form-control" id="etiket" name="etiket" placeholder="Etiket">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="blog-kısa">Kısa Açıklama</label>
                        <textarea id="blog-kısa" class="form-control" name="kisa_aciklama" placeholder="Kısa açıklama ekle"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="blog">Blog</label>
                        <textarea id="blog" class="form-control" name="blog" rows="15">İçeriğinizi buraya yazınız.</textarea>
                    </div>
                    <div class="mb-3 d-none">
                        <input type="text" class="form-control" id="tarih" name="tarih">
                    </div>
                    <button type="submit" name="submit" class="btn btn-primary">Kaydet</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- / Content -->

<?php include "admin-partials/_footer.php" ?>