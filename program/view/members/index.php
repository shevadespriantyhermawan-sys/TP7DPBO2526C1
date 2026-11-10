<?php
require_once '../../config/db.php';
require_once '../../helpers/functions.php';
require_once '../../helpers/AlertHandler.php';
require_once '../../class/Member.php';

$db = (new Database())->getConnection();
$memberObj = new Member($db);

if (isset($_GET['delete'])) {
    $memberObj->delete($_GET['delete']);
    AlertHandler::setAlert("Member deleted successfully!", "danger");
    header("Location: index.php");
    exit;
}

$members = $memberObj->getAll();
include '../layouts/header.php';
?>

<div class="header-action">
    <h2>Member Management</h2>
    <a href="create.php" class="btn">+ Add New Member</a>
</div>

<?php include '../components/alert.php'; ?>

<table class="data-table">
    <thead>
        <tr>
            <th>Photo</th>
            <th>Full Name</th>
            <th>Stage Name</th>
            <th>Position / Instrument</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($members)): ?>
            <?php foreach ($members as $m): ?>
            <tr>
                <td>
                    <?php 
                    // Mengambil nama file dari DB
                    $fileName = !empty($m['photo']) ? trim($m['photo']) : 'default-avatar.png';
                    
                    // Path Absolut Web Server (Langsung menunjuk ke folder htdocs/TP7DPBO2526C1)
                    $webPath = "/TP7DPBO2526C1/program/assets/uploads/members/" . $fileName;
                    ?>
                    <img src="<?= htmlspecialchars($webPath) ?>" 
                         alt="<?= htmlspecialchars($m['stage_name']) ?>" 
                         class="thumb" 
                         style="width: 55px; height: 55px; object-fit: cover; border-radius: 6px;">
                </td>
                <td><?= htmlspecialchars($m['name'] ?? '-') ?></td>
                <td><?= htmlspecialchars($m['stage_name'] ?? '-') ?></td>
                <td><?= htmlspecialchars($m['position'] ?? '-') ?></td>
                <td>
                    <a href="edit.php?id=<?= $m['id'] ?>" class="btn-edit">Edit</a>
                    <a href="index.php?delete=<?= $m['id'] ?>" class="btn-delete" onclick="return confirm('Delete member?')">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="5" style="text-align: center;">No member data found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php include '../layouts/footer.php'; ?>