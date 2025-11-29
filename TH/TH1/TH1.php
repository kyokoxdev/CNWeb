<?php
require_once 'flowers_data.php';
$flowers = $_SESSION['flowers'];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh Sách Các Loài Hoa</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; padding: 20px; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .header h1 { color: #2c3e50; }
        .login-btn {
            background: #3498db;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.3s;
        }
        .login-btn:hover { background: #2980b9; }
        .admin-btn { background: #27ae60; }
        .admin-btn:hover { background: #219a52; }
        .flowers-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }
        .flower-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        .flower-card:hover { transform: translateY(-5px); }
        .flower-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .flower-card .content { padding: 15px; }
        .flower-card h2 { color: #2c3e50; margin-bottom: 10px; font-size: 1.3em; }
        .flower-card p { color: #7f8c8d; line-height: 1.6; font-size: 0.95em; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Danh Sách Các Loài Hoa</h1>
        <?php if (isLoggedIn()): ?>
            <a href="admin.php" class="login-btn admin-btn">Quản trị</a>
        <?php else: ?>
            <a href="login.php" class="login-btn">Đăng nhập Admin</a>
        <?php endif; ?>
    </div>

    <div class="flowers-grid">
        <?php foreach ($flowers as $flower): ?>
            <div class="flower-card">
                <img src="images/<?php echo htmlspecialchars($flower['image']); ?>" 
                     alt="<?php echo htmlspecialchars($flower['name']); ?>"
                     onerror="this.src='https://via.placeholder.com/300x200?text=No+Image'">
                <div class="content">
                    <h2><?php echo htmlspecialchars($flower['name']); ?></h2>
                    <p><?php echo htmlspecialchars($flower['description']); ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>