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
    $link = $_POST["link"];
    $kisa_aciklama = $_POST["kisa_aciklama"];
    $aciklama = $_POST["aciklama"];

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
    $urunUrl = slugify($baslik);

    // Dosya yükleme işlemleri
    $uploadDir = '../images/';
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg', 'image/svg+xml', 'image/webp'];
    $maxFileSize = 5 * 1024 * 1024; // 5MB

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
            $errors[] = "Geçersiz dosya türü: " . $file['name'];
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
        $urunler = new Tayyip();
        if ($urunler->createUrun($baslik, $link, $uploadedFileName, $kisa_aciklama, $aciklama, $urunUrl)) {
            $success = "Ürün başarıyla eklendi.";
        } else {
            $errors[] = "Ürün eklenirken bir hata oluştu.";
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
        echo '<script>window.location.href = "urunler.php";</script>';
        exit();
    }
}
?>

<!-- Content wrapper -->
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 mb-4">
            <span class="text-muted fw-light">Dashboard /</span> Ürün Ekle
        </h4>
        <div class="card mb-4">
            <div class="card-body">
                <form method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col mb-3">
                            <label for="baslik" class="form-label">Başlık</label>
                            <input type="text" id="baslik" name="baslik" class="form-control" placeholder="Başlık">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-3">
                            <label for="link" class="form-label">Deme Linki</label>
                            <input type="text" id="link" name="link" class="form-control" placeholder="Deme Linki">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-0">
                            <label for="urun-foto" class="form-label">Fotoğraf</label>
                            <input class="form-control" type="file" name="fotograf" id="urun-foto">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-3">
                            <label class="form-label" for="kisa_aciklama">Kısa Açıklama</label>
                            <textarea id="kisa_aciklama" name="kisa_aciklama" class="form-control" placeholder="Kısa Açıklama"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-3">
                            <label class="form-label" for="aciklama">Açıklama</label>
                            <textarea id="aciklama" rows="15" name="aciklama" class="form-control" placeholder="Açıklamayı buraya yazınız..."></textarea>
                        </div>
                    </div>
                    <button type="submit" name="submit" class="btn btn-primary">Kaydet</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- / Content -->

<?php include "admin-partials/_footer.php" ?>