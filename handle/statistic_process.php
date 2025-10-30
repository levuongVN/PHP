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


// Hàm lấy dữ liệu cho biểu đồ so sánh thu nhập và chi tiêu
function getChartData($conn, $user_id, $months = 6) {
    $chart_data = [
        'labels' => [],
        'income' => [],
        'expense' => []
    ];
    
    for ($i = $months - 1; $i >= 0; $i--) {
        $date = date('Y-m', strtotime("-$i months"));
        $month_label = date('m/Y', strtotime("-$i months"));
        
        $income = getTotalIncomeByMonth($conn, $user_id, $date);
        $expense = getTotalExpenseByMonth($conn, $user_id, $date);
        
        $chart_data['labels'][] = $month_label;
        $chart_data['income'][] = $income;
        $chart_data['expense'][] = $expense;
    }
    
    return $chart_data;
}
$chart_data = getChartData($conn, $user_id, 6);

?>