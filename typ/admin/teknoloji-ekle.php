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
    $teknoloji = $_POST["teknoloji"];

    $uploadDir = '../images/';
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg', 'image/svg+xml', 'image/webp'];
    $maxFileSize = 5 * 1024 * 1024; // 5MB

    $logo = $_FILES['logo'];

    $errors = [];
    $success = "";

    $logoName = $logo['name'];
    $logoTmpName = $logo['tmp_name'];
    $logoSize = $logo['size'];
    $logoType = $logo['type'];

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

    function generateFileName($teknoloji)
    {
        $counterFile = 'counter.txt';
        if (!file_exists($counterFile)) {
            file_put_contents($counterFile, '0');
        }

        $counter = (int)file_get_contents($counterFile);
        $counter++;
        file_put_contents($counterFile, $counter);

        $cleanedTakim = preg_replace("/[^a-zA-Z0-9\_\-]/", "_", strtolower($teknoloji));
        $fileName = $cleanedTakim . '_' . $counter . '.webp';
        return $fileName;
    }

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
                $trueColorImage = imagecreatetruecolor(imagesx($image), imagesy($image));
                imagealphablending($trueColorImage, false);
                imagesavealpha($trueColorImage, true);
                imagecopy($trueColorImage, $image, 0, 0, 0, 0, imagesx($image), imagesy($image));
                imagedestroy($image);
                $image = $trueColorImage;
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

    function processFileUpload($file, $teknoloji, $uploadDir, $allowedTypes, $maxFileSize, &$errors)
    {
        if (isValidFile($file, $allowedTypes, $maxFileSize, $errors)) {
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $fileType = $file['type'];
            $fileName = generateFileName($teknoloji);

            if ($fileType === 'image/svg+xml') {
                $destinationPath = $uploadDir . pathinfo($file['name'], PATHINFO_FILENAME) . '.svg';
                if (move_uploaded_file($file['tmp_name'], $destinationPath)) {
                    return pathinfo($file['name'], PATHINFO_FILENAME) . '.svg';
                } else {
                    $errors[] = "SVG dosyası taşınırken bir hata oluştu: " . $file['name'];
                    return false;
                }
            } else {
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

    $uploadedFileName = processFileUpload($logo, $teknoloji, $uploadDir, $allowedTypes, $maxFileSize, $errors);

    if ($uploadedFileName) {
        $teknolojiler = new Tayyip();
        if ($teknolojiler->createTeknoloji($teknoloji, $uploadedFileName)) {
            $success = "Teknoloji başarıyla eklendi.";
        } else {
            $errors[] = "Teknoloji eklenirken bir hata oluştu.";
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
        echo '<script>window.location.href = "teknoloji.php";</script>';
        exit();
    }
}
?>

<!-- Content wrapper -->
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 mb-4">
            <span class="text-muted fw-light">Dashboard /</span> Teknoloji Ekle
        </h4>
        <div class="card mb-4">
            <div class="card-body">
                <form method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col mb-3">
                            <label for="teknoloji" class="form-label">Teknoloji</label>
                            <input type="text" id="teknoloji" class="form-control" name="teknoloji" placeholder="Teknoloji">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col mb-0">
                            <label for="logo" class="form-label">Logo</label>
                            <input class="form-control" type="file" name="logo" id="logo">
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