<?php
require_once '../../config/db.php';
require_once '../../helpers/functions.php';
require_once '../../helpers/AlertHandler.php';
require_once '../../class/Guitar.php';
require_once '../../class/Category.php';

$db = (new Database())->getConnection();
$guitarObj = new Guitar($db);
$categoryObj = new Category($db);

$id = $_GET['id'] ?? null;
$guitar = $guitarObj->getById($id);
$categories = $categoryObj->getAll();

if (!$guitar) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category_id = sanitize($_POST['category_id']);
    $brand = sanitize($_POST['brand']);
    $model = sanitize($_POST['model']);
    $serial_number = sanitize($_POST['serial_number']);
    $status = sanitize($_POST['status']);
    
    $photoName = uploadImage($_FILES['photo'], '../../assets/uploads/guitars/');

    if ($guitarObj->update($id, $category_id, $brand, $model, $serial_number, $status, $photoName)) {
        AlertHandler::setAlert("Instrument updated successfully!");
        header("Location: index.php");
        exit;
    }
}

include '../layouts/header.php';
?>

<h2>Edit Instrument</h2>

<form method="POST" action="edit.php?id=<?= $id ?>" enctype="multipart/form-data" class="form-card-full">
    <div class="form-group">
        <label>Category</label>
        <select name="category_id" required>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= $guitar['category_id'] == $cat['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group">
        <label>Brand</label>
        <input type="text" name="brand" value="<?= htmlspecialchars($guitar['brand']) ?>" required>
    </div>
    <div class="form-group">
        <label>Model</label>
        <input type="text" name="model" value="<?= htmlspecialchars($guitar['model']) ?>" required>
    </div>
    <div class="form-group">
        <label>Serial Number</label>
        <input type="text" name="serial_number" value="<?= htmlspecialchars($guitar['serial_number']) ?>" required>
    </div>
    <div class="form-group">
        <label>Status</label>
        <select name="status" required>
            <option value="Available" <?= $guitar['status'] === 'Available' ? 'selected' : '' ?>>Available</option>
            <option value="On Loan" <?= $guitar['status'] === 'On Loan' ? 'selected' : '' ?>>On Loan</option>
            <option value="Maintenance" <?= $guitar['status'] === 'Maintenance' ? 'selected' : '' ?>>Maintenance</option>
        </select>
    </div>
    <div class="form-group">
        <label>Current Photo</label>
        <br>
        <img src="../../assets/uploads/guitars/<?= $guitar['photo'] ?>" class="thumb" onerror="this.src='https://via.placeholder.com/150/1f1f1f/ffffff?text=Guitar'">
    </div>
    <div class="form-group">
        <label>Change Photo (Optional)</label>
        <input type="file" name="photo" accept="image/*">
    </div>
    
    <button type="submit" class="btn">Update Instrument</button>
    <a href="index.php" class="btn btn-secondary">Cancel</a>
</form>

<?php include '../layouts/footer.php'; ?>