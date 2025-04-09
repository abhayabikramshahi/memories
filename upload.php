<?php
// Create uploads directory if it doesn't exist
if (!file_exists('uploads')) {
    mkdir('uploads', 0777, true);
}

// Check if a file was uploaded
if (isset($_FILES['media']) && $_FILES['media']['error'] === UPLOAD_ERR_OK) {
    $file = $_FILES['media'];
    
    // Validate file type
    $allowedTypes = [
        // Images
        'image/jpeg',
        'image/png',
        'image/gif',
        // Videos
        'video/mp4',
        'video/webm',
        'video/ogg'
    ];
    
    $fileType = mime_content_type($file['tmp_name']);
    
    if (!in_array($fileType, $allowedTypes)) {
        header('Location: index.php?status=error&message=Invalid file type');
        exit();
    }
    
    // Check file size (limit to 100MB)
    if ($file['size'] > 100 * 1024 * 1024) {
        header('Location: index.php?status=error&message=File too large');
        exit();
    }
    
    // Generate unique filename
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid() . '.' . $extension;
    $destination = 'uploads/' . $filename;
    
    // Move uploaded file
    if (move_uploaded_file($file['tmp_name'], $destination)) {
        header('Location: index.php?status=success');
    } else {
        header('Location: index.php?status=error');
    }
} else {
    header('Location: index.php?status=error');
}