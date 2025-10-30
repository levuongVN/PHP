<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../functions/dbConnect.php';
require_once __DIR__ . '/../functions/auth.php';
require_once __DIR__ . '/../functions/statistic.php';

$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    header('Location: ../Index.php');
    exit;
}

// Tạo kết nối
$conn = getDBConnection();

$top_categories = getTopSpendingCategoriesAllTime($conn, $user_id);

$color_palette = [
    '#4361ee', // Xanh dương
    '#f72585', // Hồng
    '#4cc9f0', // Xanh nhạt  
    '#f8961e', // Cam
    '#7209b7', // Tím
];

// Gán màu cho từng danh mục
foreach ($top_categories as $index => &$category) {
    $category['display_color'] = $color_palette[$index % count($color_palette)];
}


?>