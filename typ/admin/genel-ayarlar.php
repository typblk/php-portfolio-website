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
    $site_adi = $_POST["site_adi"];
    $site_desc = $_POST["site_desc"];
    $site_keyw = $_POST["site_keyw"];

    // Dosya yükleme işlemleri
    $uploadDir = '../images/';
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg', 'image/webp', 'image/ico', 'image/svg+xml', 'image/x-icon', 'image/vnd.microsoft.icon'];
    $maxFileSize = 5 * 1024 * 1024; // 5MB

    $logo = $_FILES['logo'];
    $favicon = $_FILES['favicon'];

    $errors = [];
    $success = "";

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
    function generateFileName($site_adi)
    {
        $counterFile = 'counter.txt';
        if (!file_exists($counterFile)) {
            file_put_contents($counterFile, '0');
        }

        $counter = (int)file_get_contents($counterFile);
        $counter++;
        file_put_contents($counterFile, $counter);

        // Dosya adında kullanılabilir karakterleri ve boşlukları _ ile değiştirme
        $cleanedAyar = preg_replace("/[^a-zA-Z0-9\_\-]/", "_", strtolower($site_adi));
        // Dosya adını oluştur
        $fileName = $cleanedAyar . '_' . $counter . '.webp';
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
    function processFileUpload($file, $site_adi, $uploadDir, $allowedTypes, $maxFileSize, &$errors)
    {
        if (isValidFile($file, $allowedTypes, $maxFileSize, $errors)) {
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $fileType = $file['type'];
            $fileName = generateFileName($site_adi);

            if ($fileType === 'image/svg+xml' || $fileType === 'image/x-icon' || $fileType === 'image/vnd.microsoft.icon') {
                // SVG veya ICO dosyasını doğrudan taşı
                $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
                $destinationPath = $uploadDir . pathinfo($file['name'], PATHINFO_FILENAME) . '.' . $extension;
                if (move_uploaded_file($file['tmp_name'], $destinationPath)) {
                    return pathinfo($file['name'], PATHINFO_FILENAME) . '.' . $extension;
                } else {
                    $errors[] = "SVG veya ICO dosyası taşınırken bir hata oluştu: " . $file['name'];
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

    $logoNewName = $item->logo;
    $faviconNewName = $item->favicon;

    if (!empty($logo['name'])) {
        $uploadedFileName = processFileUpload($logo, $site_adi, $uploadDir, $allowedTypes, $maxFileSize, $errors);
        if ($uploadedFileName) {
            $logoNewName = $uploadedFileName;
        }
    }

    if (!empty($favicon['name'])) {
        $uploadedFileName = processFileUpload($favicon, $site_adi, $uploadDir, $allowedTypes, $maxFileSize, $errors);
        if ($uploadedFileName) {
            $faviconNewName = $uploadedFileName;
        }
    }

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
        if ($ayarlar->editGenelAyarlar($site_adi, $site_desc, $site_keyw, $logoNewName, $faviconNewName)) {
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
            echo '<script>window.location.href = "genel-ayarlar.php";</script>';
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