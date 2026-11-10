<?php
function sanitize($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

function formatDate($date) {
    if (!$date) return '-';
    return date('d M Y', strtotime($date));
}

function uploadImage($file, $targetDir) {
    if (!isset($file['name']) || $file['error'] !== UPLOAD_ERR_OK) {
        return null;
    }
    
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }
    
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid() . '.' . strtolower($extension);
    $targetFilePath = $targetDir . $filename;
    
    $allowedTypes = ['jpg', 'jpeg', 'png', 'webp'];
    if (in_array(strtolower($extension), $allowedTypes)) {
        if (move_uploaded_file($file['tmp_name'], $targetFilePath)) {
            return $filename;
        }
    }
    return null;
}
?>