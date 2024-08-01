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

include "admin-partials/_header.php";


if (isset($_POST["submit"])) {
    $baslik = $_POST["baslik"];
    $etiket = $_POST["etiket"];
    $kisa_aciklama = $_POST["kisa_aciklama"];
    $blog = $_POST["blog"];
    $tarih = date('Y-m-d');

    // Blog başlığını uygun URL formatına dönüştüren fonksiyon
    function slugify($title)
    {
        $title = strtolower($title);
        $title = str_replace(
            ['ç', 'ğ', 'ı', 'ö', 'ş', 'ü'],
            ['c', 'g', 'i', 'o', 's', 'u'],
            $title
        );
        $title = preg_replace('/[^a-z0-9]+/', '-', $title);
        $title = trim($title, '-');

        return $title;
    }
    $blogUrl = slugify($baslik);

    // Dosya yükleme işlemleri
    $uploadDir = '../images/';
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg', 'image/svg+xml', 'image/webp'];
    $maxFileSize = 10 * 1024 * 1024; // 5MB

    $fotograf = $_FILES['fotograf'];

    $errors = [];
    $success = "";

    $fotografName = $fotograf['name'];
    $fotografTmpName = $fotograf['tmp_name'];
    $fotografSize = $fotograf['size'];
    $fotografType = $fotograf['type'];

    // Dosya türü ve boyutu kontrolü
    function isValidFile($file, $allowedTypes, $maxFileSize, &$errors)
    {
        if (!in_array($file['type'], $allowedTypes)) {
            $errors[] = "Geçersiz dosya türü: " . $file['type'];
            return false;
        }
        if ($file['size'] > $maxFileSize) {
            $errors[] = "Dosya çok büyük: " . $file['name'];
            return false;
        }
        return true;
    }

    // Dosya adı oluşturma fonksiyonu
    function generateFileName($baslik)
    {
        $counterFile = 'counter.txt';
        if (!file_exists($counterFile)) {
            file_put_contents($counterFile, '0');
        }

        $counter = (int)file_get_contents($counterFile);
        $counter++;
        file_put_contents($counterFile, $counter);

        $fileName = slugify($baslik) . '_' . $counter . '.webp';
        return $fileName;
    }

    // Resim dosyasını webp formatına dönüştürme fonksiyonu
    function convertToWebp($sourcePath, $destinationPath)
    {
        $info = getimagesize($sourcePath);
        $mime = $info['mime'];

        switch ($mime) {
            case 'image/jpeg':
                $image = imagecreatefromjpeg($sourcePath);
                break;
            case 'image/png':
                $image = imagecreatefrompng($sourcePath);
                break;
            case 'image/webp':
                $image = imagecreatefromwebp($sourcePath);
                break;
            case 'image/gif':
                $image = imagecreatefromgif($sourcePath);
                break;
            default:
                return false;
        }

        imagewebp($image, $destinationPath);
        imagedestroy($image);
        return true;
    }

    // Dosya yükleme ve işleme
    function processFileUpload($file, $baslik, $uploadDir, $allowedTypes, $maxFileSize, &$errors)
    {
        if (isValidFile($file, $allowedTypes, $maxFileSize, $errors)) {
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $fileType = $file['type'];
            $fileName = generateFileName($baslik);

            if ($fileType === 'image/svg+xml') {
                // SVG dosyasını doğrudan taşı
                $destinationPath = $uploadDir . pathinfo($file['name'], PATHINFO_FILENAME) . '.svg';
                if (move_uploaded_file($file['tmp_name'], $destinationPath)) {
                    return pathinfo($file['name'], PATHINFO_FILENAME) . '.svg';
                } else {
                    $errors[] = "SVG dosyası taşınırken bir hata oluştu: " . $file['name'];
                    return false;
                }
            } else {
                // Geçici dosya yolları
                $tmpPath = $uploadDir . 'temp_' . uniqid() . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
                if (move_uploaded_file($file['tmp_name'], $tmpPath)) {
                    $destinationPath = $uploadDir . $fileName;
                    if (convertToWebp($tmpPath, $destinationPath)) {
                        unlink($tmpPath);
                        return $fileName;
                    } else {
                        $errors[] = "Dosya webp formatına dönüştürülürken bir hata oluştu: " . $file['name'];
                        return false;
                    }
                } else {
                    $errors[] = "Dosya geçici olarak taşınırken bir hata oluştu: " . $file['name'];
                    return false;
                }
            }
        }
        return false;
    }

    $uploadedFileName = processFileUpload($fotograf, $baslik, $uploadDir, $allowedTypes, $maxFileSize, $errors);

    if ($uploadedFileName) {
        $bloglar = new Tayyip();
        if ($bloglar->createBlog($baslik, $uploadedFileName, $etiket, $kisa_aciklama, $blog, $tarih, $blogUrl)) {
            $success = "Blog başarıyla eklendi.";
        } else {
            $errors[] = "Blog eklenirken bir hata oluştu.";
        }
    }

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
    } elseif ($success) {
        echo '
            <div class="bs-toast toast toast-placement-ex m-2 top-0 end-0 fade show bg-success" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header">
                    <i class="bx bx-bell me-2"></i>
                    <div class="me-auto fw-medium">Başarılı</div>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    ' . htmlspecialchars($success) . '
                </div>
            </div>';
        echo '<script>window.location.href = "bloglar.php";</script>';
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