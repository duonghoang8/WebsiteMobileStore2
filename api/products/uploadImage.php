<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['image_file'])) {
        $uploadDir = '../../uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName = uniqid() . '_' . basename($_FILES['image_file']['name']);
        $targetFile = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['image_file']['tmp_name'], $targetFile)) {
            $response = [
                'success' => true,
                'image_url' => 'uploads/' . $fileName
            ];
        } else {
            $response = [
                'success' => false,
                'message' => 'Lỗi khi tải ảnh lên server.'
            ];
        }
    } else {
        $response = [
            'success' => false,
            'message' => 'Không có file được gửi.'
        ];
    }
} else {
    $response = [
        'success' => false,
        'message' => 'Phương thức không hợp lệ.'
    ];
}

echo json_encode($response);
?>
