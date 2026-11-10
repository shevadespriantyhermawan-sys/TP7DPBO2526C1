<?php
require_once '../../config/db.php';
require_once '../../helpers/functions.php';
require_once '../../helpers/AlertHandler.php';
require_once '../../class/Guitar.php';
require_once '../../class/Category.php';

$db = (new Database())->getConnection();
$guitarObj = new Guitar($db);
$categoryObj = new Category($db);
$categories = $categoryObj->getAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category_id = sanitize($_POST['category_id']);
    $brand = sanitize($_POST['brand']);
    $model = sanitize($_POST['model']);
    $serial_number = sanitize($_POST['serial_number']);
    $status = sanitize($_POST['status']);
    
    $photoName = uploadImage($_FILES['photo'], '../../assets/uploads/guitars/') ?? 'default-guitar.png';

    if ($guitarObj->create($category_id, $brand, $model, $serial_number, $status, $photoName)) {
        AlertHandler::setAlert("Instrument added successfully!");
        header("Location: index.php");
        exit;
    }
}

include '../layouts/header.php';
?>

<h2>Add New Instrument</h2>

<form method="POST" action="create.php" enctype="multipart/form-data" class="form-card-full">
    <div class="form-group">
        <label>Category</label>
        <select name="category_id" required>
            <option value="">-- Select Category --</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group">
        <label>Brand</label>
        <input type="text" name="brand" placeholder="e.g. Fender" required>
    </div>
    <div class="form-group">
        <label>Model</label>
        <input type="text" name="model" placeholder="e.g. Stratocaster" required>
    </div>
    <div class="form-group">
        <label>Serial Number</label>
        <input type="text" name="serial_number" required>
    </div>
    <div class="form-group">
        <label>Status</label>
        <select name="status" required>
            <option value="Available">Available</option>
            <option value="On Loan">On Loan</option>
            <option value="Maintenance">Maintenance</option>
        </select>
    </div>
    <div class="form-group">
        <label>Photo</label>
        <input type="file" name="photo" accept="image/*">
    </div>
    
    <button type="submit" class="btn">Save Instrument</button>
    <a href="index.php" class="btn btn-secondary">Cancel</a>
</form>

<?php include '../layouts/footer.php'; ?>