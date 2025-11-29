<?php
session_start();

// Initialize flowers data in session if not exists
if (!isset($_SESSION['flowers'])) {
    $_SESSION['flowers'] = [
        [
            "id" => 1,
            "name" => "Hoa đồng tiền",
            "description" => "Hoa đồng tiền thích hợp để trồng trong mùa xuân và đầu mùa hè, khi mà cường độ ánh sáng chưa quá mạnh.",
            "image" => "hoadongtien.jpg"
        ],
        [
            "id" => 2,
            "name" => "Hoa dạ yến thảo",
            "description" => "Dạ yến thảo là lựa chọn thích hợp cho những ai yêu thích trồng hoa làm đẹp nhà ở.",
            "image" => "hoadayenthao.jpg"
        ],
        [
            "id" => 3,
            "name" => "Hoa giấy",
            "description" => "Hoa giấy có mặt ở hầu khắp mọi nơi trên đất nước ta, thích hợp với nhiều điều kiện sống khác nhau.",
            "image" => "hoagiay.jpg"
        ],
        [
            "id" => 4,
            "name" => "Hoa thanh tú",
            "description" => "Mang dáng hình tao nhã, màu sắc thiên thanh dịu dàng của hoa thanh tú có thể khiến bạn cảm thấy vô cùng nhẹ nhàng.",
            "image" => "hoathanhtu.jpg"
        ],
        [
            "id" => 5,
            "name" => "Hoa đèn lồng",
            "description" => "Giống như tên gọi, hoa đèn lồng có vẻ đẹp giống như chiếc đèn lồng đỏ trên cao.",
            "image" => "hoadenlong.jpg"
        ],
        [
            "id" => 6,
            "name" => "Hoa cẩm chướng",
            "description" => "Cẩm chướng là loại hoa thích hợp trồng vào dịp xuân - hè.",
            "image" => "hoacamchuong.jpg"
        ]
    ];
}

// Admin credentials
$admin_username = "admin";
$admin_password = "123";

// Helper function to check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
}

// Helper function to get next ID
function getNextId() {
    $maxId = 0;
    foreach ($_SESSION['flowers'] as $flower) {
        if ($flower['id'] > $maxId) {
            $maxId = $flower['id'];
        }
    }
    return $maxId + 1;
}
?>