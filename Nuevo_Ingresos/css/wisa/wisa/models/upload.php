<?php
$uploadDir = '../evidencias/';

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
    chmod($uploadDir, 0777);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $uploadFile = $uploadDir . basename($_FILES['file']['name']);

    if ($_FILES['file']['error'] === UPLOAD_ERR_OK) {
        if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile)) {
            echo 'File uploaded successfully.';
        } else {
            echo 'File upload failed: Unable to move the file.';
        }
    } else {
        echo 'File upload failed: Error code ' . $_FILES['file']['error'];
    }
} else {
    echo 'No file uploaded.';
}
?>