<?php

namespace Typ\Helpers;

// Helper function to convert a title to a URL-friendly slug
function slugify($title)
{
    $title = strtolower($title);
    $title = str_replace(
        ['ç', 'ğ', 'ı', 'ö', 'ş', 'ü', ' '], // Added space to be replaced by hyphen
        ['c', 'g', 'i', 'o', 's', 'u', '-'],
        $title
    );
    $title = preg_replace('/[^a-z0-9-]+/', '-', $title); // Keep hyphens
    $title = trim($title, '-');
    return $title;
}

// Validates a file based on MIME type (using finfo) and size.
function isValidFile($file, $allowedTypes, $maxFileSize, &$errors)
{
    // Robust check using finfo for MIME type
    if (function_exists('finfo_open')) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mimeType, $allowedTypes)) {
            $errors[] = "Geçersiz dosya türü (sunucuya göre): " . htmlspecialchars($mimeType) . ". İzin verilen türler: " . implode(", ", $allowedTypes) . ". Dosya Adı: " . htmlspecialchars($file['name']);
            return false;
        }
    } else {
        // Fallback to client-provided type if finfo is not available
        // This is less secure.
        $clientMimeType = $file['type'];
        if (!in_array($clientMimeType, $allowedTypes)) {
             $errors[] = "Geçersiz dosya türü (finfo etkin değil, istemciye göre): " . htmlspecialchars($clientMimeType) . ". İzin verilen türler: " . implode(", ", $allowedTypes) . ". Dosya Adı: " . htmlspecialchars($file['name']);
             return false;
        }
    }

    // Check file size
    if ($file['size'] > $maxFileSize) {
        $errors[] = "Dosya çok büyük (" . round($file['size'] / 1024 / 1024, 2) . "MB). İzin verilen maksimum boyut: " . round($maxFileSize / 1024 / 1024, 2) . "MB. Dosya Adı: " . htmlspecialchars($file['name']);
        return false;
    }
    return true;
}

// Generates a unique filename for an uploaded file, incorporating a counter and uniqid.
function generateFileName($baseName)
{
    // Path to counter.txt, relative to this file (upload_utils.php in typ/helpers/)
    $counterFile = __DIR__ . '/../admin/counter.txt'; 
    
    if (!is_dir(dirname($counterFile))) {
        // Attempt to create the admin directory if it doesn't exist (though it should)
        mkdir(dirname($counterFile), 0755, true);
    }

    if (!file_exists($counterFile)) {
        file_put_contents($counterFile, '0');
    }

    $counter = (int)file_get_contents($counterFile);
    $counter++;
    file_put_contents($counterFile, (string)$counter);

    $slug = slugify($baseName); // Use the slugify function from this file
    return $slug . '_' . $counter . '_' . uniqid() . '.webp';
}

// Converts an image file to WebP format.
function convertToWebp($sourcePath, $destinationPath)
{
    if (!file_exists($sourcePath)) {
        // error_log("convertToWebp: Source file does not exist - " . $sourcePath);
        return false;
    }
    $info = getimagesize($sourcePath);
    if ($info === false) {
        // error_log("convertToWebp: Could not get image size (not a valid image or not accessible) - " . $sourcePath);
        return false;
    }
    $mime = $info['mime'];

    $image = null;
    switch ($mime) {
        case 'image/jpeg':
            $image = @imagecreatefromjpeg($sourcePath);
            break;
        case 'image/png':
            $image = @imagecreatefrompng($sourcePath);
            // Preserve transparency for PNG
            imagepalettetotruecolor($image);
            imagealphablending($image, true);
            imagesavealpha($image, true);
            break;
        case 'image/webp':
            // If it's already webp, we can just copy it or return true if no further processing needed
            // For simplicity, let's assume we always want to re-encode to control quality, etc.
            $image = @imagecreatefromwebp($sourcePath);
            break;
        case 'image/gif':
            $image = @imagecreatefromgif($sourcePath);
            break;
        default:
            // error_log("convertToWebp: Unsupported MIME type - " . $mime . " for file " . $sourcePath);
            return false;
    }

    if (!$image) {
        // error_log("convertToWebp: Failed to create image resource from " . $sourcePath . " with MIME type " . $mime);
        return false;
    }

    if (!imagewebp($image, $destinationPath)) {
        // error_log("convertToWebp: imagewebp() failed to save to " . $destinationPath);
        imagedestroy($image);
        return false;
    }
    
    imagedestroy($image);
    return true;
}

// Processes a file upload: validates, generates a name, converts to WebP (if not SVG), and moves the file.
function processFileUpload($file, $baseName, $uploadDir, $allowedTypes, $maxFileSize, &$errors)
{
    if (isValidFile($file, $allowedTypes, $maxFileSize, $errors)) {
        if (!file_exists($uploadDir)) {
            // Use 0755 permissions for directory creation
            if (!mkdir($uploadDir, 0755, true) && !is_dir($uploadDir)) {
                 $errors[] = "Yükleme dizini oluşturulamadı: " . htmlspecialchars($uploadDir);
                 return false;
            }
        }
        
        // Double check writability of uploadDir after attempting to create it
        if (!is_writable($uploadDir)) {
            $errors[] = "Yükleme dizini yazılabilir değil: " . htmlspecialchars($uploadDir);
            return false;
        }


        $fileType = '';
        // Use finfo to get the reliable MIME type for deciding if it's an SVG
        if (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $fileType = finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);
        } else {
            // Fallback to client-provided type if finfo is not available
            $fileType = $file['type'];
        }


        if ($fileType === 'image/svg+xml') {
            // For SVG, generate a unique name but keep the .svg extension
            $slug = slugify($baseName);
            $counterFile = __DIR__ . '/../admin/counter.txt';
            if (!file_exists($counterFile)) { file_put_contents($counterFile, '0'); }
            $counter = (int)file_get_contents($counterFile);
            $counter++;
            file_put_contents($counterFile, (string)$counter);
            $fileName = $slug . '_' . $counter . '_' . uniqid() . '.svg';
            $destinationPath = $uploadDir . $fileName;

            if (move_uploaded_file($file['tmp_name'], $destinationPath)) {
                return $fileName;
            } else {
                $errors[] = "SVG dosyası taşınırken bir hata oluştu: " . htmlspecialchars($file['name']);
                return false;
            }
        } else {
            // For other image types, generate a .webp filename
            $fileName = generateFileName($baseName); // This already uses slugify, counter, uniqid, and .webp
            $destinationPath = $uploadDir . $fileName;
            
            // No need for a separate temp path for non-SVG, convertToWebp handles the source path directly
            if (convertToWebp($file['tmp_name'], $destinationPath)) {
                // The original uploaded file ($file['tmp_name']) is automatically cleaned up by PHP
                // after the script ends or if it's moved. If convertToWebp creates intermediates, it should clean them.
                return $fileName;
            } else {
                $errors[] = "Dosya webp formatına dönüştürülürken veya kaydedilirken bir hata oluştu: " . htmlspecialchars($file['name']);
                // Attempt to remove partially created webp file if conversion failed
                if (file_exists($destinationPath)) {
                    unlink($destinationPath);
                }
                return false;
            }
        }
    }
    return false; // isValidFile returned false
}

?>
