<?php
require_once '../../config/db.php';
require_once '../../helpers/functions.php';
require_once '../../helpers/AlertHandler.php';
require_once '../../class/Guitar.php';

$db = (new Database())->getConnection();
$guitarObj = new Guitar($db);

if (isset($_GET['delete'])) {
    $guitarObj->delete($_GET['delete']);
    AlertHandler::setAlert("Guitar deleted successfully!", "danger");
    header("Location: index.php");
    exit;
}

$guitars = $guitarObj->getAllWithCategory();
include '../layouts/header.php';
?>

<div class="header-action">
    <h2>Guitar & Gear Inventory</h2>
    <a href="create.php" class="btn">+ Add New Instrument</a>
</div>

<?php include '../components/alert.php'; ?>

<table class="data-table">
    <thead>
        <tr>
            <th>Photo</th>
            <th>Category</th>
            <th>Brand & Model</th>
            <th>Serial Number</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($guitars)): ?>
            <?php foreach ($guitars as $g): ?>
            <tr>
                <td>
                    <?php 
                    $fileName = !empty($g['photo']) ? trim($g['photo']) : 'default-guitar.png';
                    $webPath = "/TP7DPBO2526C1/program/assets/uploads/guitars/" . $fileName;
                    ?>
                    <img src="<?= htmlspecialchars($webPath) ?>" 
                         alt="Guitar" 
                         class="thumb" 
                         style="width: 55px; height: 55px; object-fit: cover; border-radius: 6px;">
                </td>
                <td><?= htmlspecialchars($g['category_name']) ?></td>
                <td><?= htmlspecialchars($g['brand'] . ' ' . $g['model']) ?></td>
                <td><?= htmlspecialchars($g['serial_number']) ?></td>
                <td><span class="badge status-<?= strtolower(str_replace(' ', '-', $g['status'])) ?>"><?= $g['status'] ?></span></td>
                <td>
                    <a href="edit.php?id=<?= $g['id'] ?>" class="btn-edit">Edit</a>
                    <a href="index.php?delete=<?= $g['id'] ?>" class="btn-delete" onclick="return confirm('Delete instrument?')">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="6" style="text-align: center;">No guitar data found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php include '../layouts/footer.php'; ?>