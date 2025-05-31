<?php
// Create uploads directory if it doesn't exist
if (!file_exists('uploads')) {
    mkdir('uploads', 0777, true);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $account = isset($_POST['account']) ? $_POST['account'] : 'hangma';
    
    if (isset($_FILES["media"]) && $_FILES["media"]["error"] === UPLOAD_ERR_OK) {
        $target_dir = "uploads/";
        $file_extension = strtolower(pathinfo($_FILES["media"]["name"], PATHINFO_EXTENSION));
        $timestamp = time();
        $new_filename = $account . '_' . $timestamp . '.' . $file_extension;
        $target_file = $target_dir . $new_filename;
        
        // Check file type
        $allowed_types = array('jpg', 'jpeg', 'png', 'gif', 'mp4');
        if (!in_array($file_extension, $allowed_types)) {
            header("Location: index.php?status=error&message=Only JPG, JPEG, PNG, GIF & MP4 files are allowed.");
            exit();
        }

        // Check file size (10MB max)
        if ($_FILES["media"]["size"] > 10000000) {
            header("Location: index.php?status=error&message=File is too large. Maximum size is 10MB.");
            exit();
        }

        // For images, verify it's a valid image
        if (in_array($file_extension, array('jpg', 'jpeg', 'png', 'gif'))) {
            $check = getimagesize($_FILES["media"]["tmp_name"]);
            if ($check === false) {
                header("Location: index.php?status=error&message=File is not a valid image.");
                exit();
            }
        }
        
        // For videos, verify it's a valid video
        if ($file_extension === 'mp4') {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime_type = finfo_file($finfo, $_FILES["media"]["tmp_name"]);
            finfo_close($finfo);
            
            if (strpos($mime_type, 'video/') !== 0) {
                header("Location: index.php?status=error&message=File is not a valid video.");
                exit();
            }
        }

        // Try to move the uploaded file
        if (move_uploaded_file($_FILES["media"]["tmp_name"], $target_file)) {
            // Set proper permissions
            chmod($target_file, 0644);
            
            // For images, try to optimize them
            if (in_array($file_extension, array('jpg', 'jpeg', 'png'))) {
                $image_info = getimagesize($target_file);
                if ($image_info !== false) {
                    $max_dimension = 1920; // Maximum dimension for images
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
                                imagecopyresampled($new_image, $source, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
                                imagejpeg($new_image, $target_file, 85);
                                break;
                            case 'png':
                                $source = imagecreatefrompng($target_file);
                                imagecopyresampled($new_image, $source, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
                                imagepng($new_image, $target_file, 8);
                                break;
                        }
                        
                        imagedestroy($new_image);
                        imagedestroy($source);
                    }
                }
            }
            
            header("Location: index.php?status=success");
        } else {
            $error = error_get_last();
            header("Location: index.php?status=error&message=Error uploading file: " . ($error ? $error['message'] : 'Unknown error'));
        }
    } else {
        $error_message = "No file selected or upload error occurred.";
        if (isset($_FILES["media"])) {
            switch ($_FILES["media"]["error"]) {
                case UPLOAD_ERR_INI_SIZE:
                    $error_message = "The uploaded file exceeds the upload_max_filesize directive in php.ini";
                    break;
                case UPLOAD_ERR_FORM_SIZE:
                    $error_message = "The uploaded file exceeds the MAX_FILE_SIZE directive in the HTML form";
                    break;
                case UPLOAD_ERR_PARTIAL:
                    $error_message = "The uploaded file was only partially uploaded";
                    break;
                case UPLOAD_ERR_NO_FILE:
                    $error_message = "No file was uploaded";
                    break;
                case UPLOAD_ERR_NO_TMP_DIR:
                    $error_message = "Missing a temporary folder";
                    break;
                case UPLOAD_ERR_CANT_WRITE:
                    $error_message = "Failed to write file to disk";
                    break;
                case UPLOAD_ERR_EXTENSION:
                    $error_message = "A PHP extension stopped the file upload";
                    break;
            }
        }
        header("Location: index.php?status=error&message=" . urlencode($error_message));
    }
} else {
    header("Location: index.php");
}
?>