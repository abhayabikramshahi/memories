<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $account = isset($_POST['account']) ? $_POST['account'] : 'hangma';
    
    if (isset($_FILES["media"])) {
        $target_dir = "uploads/";
        $file_extension = strtolower(pathinfo($_FILES["media"]["name"], PATHINFO_EXTENSION));
        $timestamp = time();
        $new_filename = $account . '_' . $timestamp . '.' . $file_extension;
        $target_file = $target_dir . $new_filename;
        
        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES["media"]["tmp_name"]);
        if($check === false) {
            header("Location: index.php?status=error&message=File is not an image or video.");
            exit();
        }
        
        // Check file size (10MB max)
        if ($_FILES["media"]["size"] > 10000000) {
            header("Location: index.php?status=error&message=File is too large. Maximum size is 10MB.");
            exit();
        }
        
        // Allow certain file formats
        $allowed_types = array('jpg', 'jpeg', 'png', 'gif', 'mp4');
        if (!in_array($file_extension, $allowed_types)) {
            header("Location: index.php?status=error&message=Only JPG, JPEG, PNG, GIF & MP4 files are allowed.");
            exit();
        }
        
        if (move_uploaded_file($_FILES["media"]["tmp_name"], $target_file)) {
            header("Location: index.php?status=success");
        } else {
            header("Location: index.php?status=error&message=Error uploading file.");
        }
    } else {
        header("Location: index.php?status=error&message=No file selected.");
    }
} else {
    header("Location: index.php");
}
?>