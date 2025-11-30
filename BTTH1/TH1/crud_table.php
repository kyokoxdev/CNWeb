<?php
require_once 'flowers_data.php';

// Check if logged in
if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$message = "";
$editFlower = null;

// Handle CRUD operations
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? '';

    // CREATE
    if ($action === 'create') {
        $newFlower = [
            "id" => getNextId(),
            "name" => $_POST['name'],
            "description" => $_POST['description'],
            "image" => $_POST['image']
        ];
        $_SESSION['flowers'][] = $newFlower;
        $message = "✅ Thêm hoa thành công!";
    }

    // UPDATE
    if ($action === 'update') {
        $id = (int)$_POST['id'];
        foreach ($_SESSION['flowers'] as &$flower) {
            if ($flower['id'] === $id) {
                $flower['name'] = $_POST['name'];
                $flower['description'] = $_POST['description'];
                $flower['image'] = $_POST['image'];
                break;
            }
        }
        unset($flower);
        $message = "✅ Cập nhật thành công!";
    }

    // DELETE
    if ($action === 'delete') {
        $id = (int)$_POST['id'];
        $_SESSION['flowers'] = array_values(array_filter($_SESSION['flowers'], function($f) use ($id) {
            return $f['id'] !== $id;
        }));
        $message = "✅ Xóa thành công!";
    }
}

// Handle Edit request (GET)
if (isset($_GET['edit'])) {
    $editId = (int)$_GET['edit'];
    foreach ($_SESSION['flowers'] as $flower) {
        if ($flower['id'] === $editId) {
            $editFlower = $flower;
            break;
        }
    }
}

$flowers = $_SESSION['flowers'];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản Trị - Danh Sách Hoa</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; padding: 20px; }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header h1 { color: #2c3e50; }
        .header-buttons { display: flex; gap: 10px; }
        .btn {
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            font-size: 14px;
            transition: opacity 0.3s;
        }
        .btn:hover { opacity: 0.8; }
        .btn-primary { background: #3498db; color: white; }
        .btn-success { background: #27ae60; color: white; }
        .btn-warning { background: #f39c12; color: white; }
        .btn-danger { background: #e74c3c; color: white; }
        .btn-secondary { background: #95a5a6; color: white; }

        .message {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .form-container {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        .form-container h2 { margin-bottom: 20px; color: #2c3e50; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; color: #555; }
        .form-group input, .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }
        .form-group textarea { resize: vertical; min-height: 80px; }
        .form-group input:focus, .form-group textarea:focus {
            outline: none;
            border-color: #3498db;
        }

        .table-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #eee; }
        th { background: #2c3e50; color: white; }
        tr:hover { background: #f8f9fa; }
        .actions { display: flex; gap: 5px; }
        .flower-img { width: 60px; height: 60px; object-fit: cover; border-radius: 5px; }
        .desc-cell { max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    </style>
</head>
<body>
    <div class="header">
        <h1>⚙️ Quản Trị Danh Sách Hoa</h1>
        <div class="header-buttons">
            <a href="TH1.php" class="btn btn-primary">🏠 Trang chủ</a>
            <a href="logout.php" class="btn btn-danger">🚪 Đăng xuất</a>
        </div>
    </div>

    <?php if ($message): ?>
        <div class="message"><?php echo $message; ?></div>
    <?php endif; ?>

    <!-- Add/Edit Form -->
    <div class="form-container">
        <h2><?php echo $editFlower ? '✏️ Sửa Thông Tin Hoa' : '➕ Thêm Hoa Mới'; ?></h2>
        <form method="POST" action="admin.php">
            <input type="hidden" name="action" value="<?php echo $editFlower ? 'update' : 'create'; ?>">
            <?php if ($editFlower): ?>
                <input type="hidden" name="id" value="<?php echo $editFlower['id']; ?>">
            <?php endif; ?>

            <div class="form-group">
                <label for="name">Tên hoa:</label>
                <input type="text" id="name" name="name" required 
                       value="<?php echo $editFlower ? htmlspecialchars($editFlower['name']) : ''; ?>"
                       placeholder="Nhập tên hoa">
            </div>

            <div class="form-group">
                <label for="description">Mô tả:</label>
                <textarea id="description" name="description" required 
                          placeholder="Nhập mô tả về loài hoa"><?php echo $editFlower ? htmlspecialchars($editFlower['description']) : ''; ?></textarea>
            </div>

            <div class="form-group">
                <label for="image">Tên file ảnh:</label>
                <input type="text" id="image" name="image" required 
                       value="<?php echo $editFlower ? htmlspecialchars($editFlower['image']) : ''; ?>"
                       placeholder="vd: rose.jpg">
            </div>

            <button type="submit" class="btn btn-success">
                <?php echo $editFlower ? '💾 Cập Nhật' : '➕ Thêm Mới'; ?>
            </button>
            <?php if ($editFlower): ?>
                <a href="admin.php" class="btn btn-secondary">❌ Hủy</a>
            <?php endif; ?>
        </form>
    </div>

    <!-- Flowers Table -->
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Ảnh</th>
                    <th>Tên hoa</th>
                    <th>Mô tả</th>
                    <th>File ảnh</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($flowers as $flower): ?>
                    <tr>
                        <td><?php echo $flower['id']; ?></td>
                        <td>
                            <img src="images/<?php echo htmlspecialchars($flower['image']); ?>" 
                                 alt="<?php echo htmlspecialchars($flower['name']); ?>"
                                 class="flower-img"
                                 onerror="this.src='https://via.placeholder.com/60x60?text=?'">
                        </td>
                        <td><?php echo htmlspecialchars($flower['name']); ?></td>
                        <td class="desc-cell" title="<?php echo htmlspecialchars($flower['description']); ?>">
                            <?php echo htmlspecialchars($flower['description']); ?>
                        </td>
                        <td><?php echo htmlspecialchars($flower['image']); ?></td>
                        <td class="actions">
                            <a href="admin.php?edit=<?php echo $flower['id']; ?>" class="btn btn-warning">✏️ Sửa</a>
                            <form method="POST" action="admin.php" style="display:inline;" 
                                  onsubmit="return confirm('Bạn có chắc muốn xóa hoa này?');">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?php echo $flower['id']; ?>">
                                <button type="submit" class="btn btn-danger">🗑️ Xóa</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>