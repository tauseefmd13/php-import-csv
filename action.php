<?php

require 'db_connect.php';
require 'functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'upload') {
    if (isset($_FILES['csvFile']) && $_FILES['csvFile']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['csvFile']['tmp_name'];
        $fileName = $_FILES['csvFile']['name'];
        $fileSize = $_FILES['csvFile']['size'];
        $fileType = $_FILES['csvFile']['type'];

        if (!file_exists('uploads')) {
            mkdir('uploads', 0777, true);
        }

        $destination = "./uploads/" . $fileName;

        // Validate file type
        $allowedTypes = ['text/csv', 'application/vnd.ms-excel'];
        if (!in_array($_FILES['csvFile']['type'], $allowedTypes)) {
            echo "Invalid file type. Please upload a valid CSV file.";
            exit;
        }

        // Move file to the "uploads" directory
        if (!move_uploaded_file($fileTmpPath, $destination)) {
            echo "There was an error moving the uploaded file.";
            exit;
        }

        $result = importCSVDataInChuncks($pdo, $destination, 1000);
        echo $result;
    } else {
        echo "No file uploaded or an error occurred during upload.";
    }
} else {
    echo "Invalid request method.";
}

?>
