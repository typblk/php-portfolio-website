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
    $email = $_POST["email"];
    $tel = $_POST["tel"];
    $face = $_POST["face"];
    $insta = $_POST["insta"];
    $x = $_POST["x"];
    $linke = $_POST["linke"];
    $git = $_POST["git"];
    $tele = $_POST["tele"];
    $you = $_POST["you"];
    $iletisim = "İletişim";

    // Dosya yükleme işlemleri
    $uploadDir = '../images/';
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg', 'image/webp', 'image/ico', 'image/svg+xml'];
    $maxFileSize = 5 * 1024 * 1024; // 5MB

    $fotograf = $_FILES['fotograf'];

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
    function generateFileName($iletisim)
    {
        $counterFile = 'counter.txt';
        if (!file_exists($counterFile)) {
            file_put_contents($counterFile, '0');
        }

        $counter = (int)file_get_contents($counterFile);
        $counter++;
        file_put_contents($counterFile, $counter);

        // Dosya adında kullanılabilir karakterleri ve boşlukları _ ile değiştirme
        $cleanedAyar = preg_replace("/[^a-zA-Z0-9\_\-]/", "_", strtolower($iletisim));
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
    function processFileUpload($file, $iletisim, $uploadDir, $allowedTypes, $maxFileSize, &$errors)
    {
        if (isValidFile($file, $allowedTypes, $maxFileSize, $errors)) {
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $fileType = $file['type'];
            $fileName = generateFileName($iletisim);

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

    $fotografNewName = $item->fotograf;

    if (!empty($fotograf['name'])) {
        $uploadedFileName = processFileUpload($fotograf, $iletisim, $uploadDir, $allowedTypes, $maxFileSize, $errors);
        if ($uploadedFileName) {
            $fotografNewName = $uploadedFileName;
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
        if ($ayarlar->editIletisimAyarlar($email, $tel, $fotografNewName, $face, $insta, $x, $linke, $git, $tele, $you)) {
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
            echo '<script>window.location.href = "iletisim.php";</script>';
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
                    <a href="seo-ayarlari.php" type="button" class="nav-link">SEO Ayarları</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a href="iletisim.php" type="button" class="nav-link active">İletişim Ayarları</a>
                </li>
            </ul>
        </div>
        <div class="card mb-4">
            <div class="card-body">
                <form method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label" for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($item->email) ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="tel">Telefon</label>
                        <input type="tel" class="form-control" id="tel" name="tel" value="<?php echo htmlspecialchars($item->tel) ?>">
                    </div>
                    <div class="mb-3">
                        <label for="fotograf" class="form-label">İletişim Fotoğrafı</label> <br>
                        <img src="../images/<?php echo htmlspecialchars($item->fotograf) ?>" width="50" alt="">
                        <input class="form-control" type="file" id="fotograf" name="fotograf">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="face">Facebook</label>
                        <input type="text" class="form-control" id="face" name="face" value="<?php echo htmlspecialchars($item->face) ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="insta">İnstagram</label>
                        <input type="text" class="form-control" id="insta" name="insta" value="<?php echo htmlspecialchars($item->insta) ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="x">X</label>
                        <input type="text" class="form-control" id="x" name="x" value="<?php echo htmlspecialchars($item->x) ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="linke">Linkedin</label>
                        <input type="text" class="form-control" id="linke" name="linke" value="<?php echo htmlspecialchars($item->linke) ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="git">Github</label>
                        <input type="text" class="form-control" id="git" name="git" value="<?php echo htmlspecialchars($item->git) ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="tele">Telegram</label>
                        <input type="text" class="form-control" id="tele" name="tele" value="<?php echo htmlspecialchars($item->tele) ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="you">YouTube</label>
                        <input type="text" class="form-control" id="you" name="you" value="<?php echo htmlspecialchars($item->you) ?>">
                    </div>
                    <button type="submit" name="submit" class="btn btn-primary">Kaydet</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- / Content -->

<?php include "admin-partials/_footer.php" ?>