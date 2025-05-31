<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set maximum execution time
set_time_limit(300); // 5 minutes

// Set memory limit
ini_set('memory_limit', '256M');

// Create uploads directory if it doesn't exist
$upload_dir = "uploads";
if (!file_exists($upload_dir)) {
    if (!mkdir($upload_dir, 0755, true)) {
        error_log("Failed to create upload directory");
        header("Location: index.php?status=error&message=Server configuration error");
        exit();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $account = isset($_POST['account']) ? $_POST['account'] : 'hangma';
        
        if (!isset($_FILES["media"]) || $_FILES["media"]["error"] !== UPLOAD_ERR_OK) {
            throw new Exception("No file uploaded or upload error occurred");
        }

        $file = $_FILES["media"];
        $file_extension = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
        $timestamp = time();
        $new_filename = $account . '_' . $timestamp . '.' . $file_extension;
        $target_file = $upload_dir . '/' . $new_filename;

        // Validate file type
        $allowed_types = array('jpg', 'jpeg', 'png', 'gif', 'mp4');
        if (!in_array($file_extension, $allowed_types)) {
            throw new Exception("Only JPG, JPEG, PNG, GIF & MP4 files are allowed");
        }

        // Check file size (10MB max)
        if ($file["size"] > 10000000) {
            throw new Exception("File is too large. Maximum size is 10MB");
        }

        // Validate image/video
        if (in_array($file_extension, array('jpg', 'jpeg', 'png', 'gif'))) {
            if (!getimagesize($file["tmp_name"])) {
                throw new Exception("Invalid image file");
            }
        } elseif ($file_extension === 'mp4') {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime_type = finfo_file($finfo, $file["tmp_name"]);
            finfo_close($finfo);
            
            if (strpos($mime_type, 'video/') !== 0) {
                throw new Exception("Invalid video file");
            }
        }

        // Move uploaded file
        if (!move_uploaded_file($file["tmp_name"], $target_file)) {
            throw new Exception("Failed to save file");
        }

        // Set permissions
        chmod($target_file, 0644);

        // Process image if needed
        if (in_array($file_extension, array('jpg', 'jpeg', 'png'))) {
            $image_info = getimagesize($target_file);
            if ($image_info !== false) {
                $max_dimension = 1920;
                list($width, $height) = $image_info;
                
                if ($width > $max_dimension || $height > $max_dimension) {
                    $ratio = $width / $height;
                    if ($ratio > 1) {
                        $new_width = $max_dimension;
                        $new_height = $max_dimension / $ratio;
                    } else {
                        $new_height = $max_dimension;
                        $new_width = $max_dimension * $ratio;
                    }
                    
                    $new_image = imagecreatetruecolor($new_width, $new_height);
                    
                    switch ($file_extension) {
                        case 'jpg':
                        case 'jpeg':
                            $source = imagecreatefromjpeg($target_file);
                            break;
                        case 'png':
                            $source = imagecreatefrompng($target_file);
                            break;
                        default:
                            throw new Exception("Unsupported image format");
                    }
                    
                    if (!$source) {
                        throw new Exception("Failed to process image");
                    }
                    
                    imagecopyresampled($new_image, $source, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
                    
                    switch ($file_extension) {
                        case 'jpg':
                        case 'jpeg':
                            imagejpeg($new_image, $target_file, 85);
                            break;
                        case 'png':
                            imagepng($new_image, $target_file, 8);
                            break;
                    }
                    
                    imagedestroy($new_image);
                    imagedestroy($source);
                }
            }
        }

        header("Location: index.php?status=success");
        exit();

    } catch (Exception $e) {
        error_log("Upload error: " . $e->getMessage());
        header("Location: index.php?status=error&message=" . urlencode($e->getMessage()));
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}
?>