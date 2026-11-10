<?php
require_once '../../config/db.php';
require_once '../../helpers/functions.php';
require_once '../../helpers/AlertHandler.php';
require_once '../../class/Loan.php';
require_once '../../class/Guitar.php';

$db = (new Database())->getConnection();
$loanObj = new Loan($db);
$guitarObj = new Guitar($db);

if (isset($_GET['return'])) {
    $loanId = $_GET['return'];
    $loanData = $loanObj->getById($loanId);
    if ($loanData) {
        $loanObj->returnGuitar($loanId, date('Y-m-d'));
        $guitarObj->updateStatus($loanData['guitar_id'], 'Available');
        AlertHandler::setAlert("Equipment marked as returned!");
    }
    header("Location: index.php");
    exit;
}

if (isset($_GET['delete'])) {
    $loanObj->delete($_GET['delete']);
    AlertHandler::setAlert("Loan record deleted!", "danger");
    header("Location: index.php");
    exit;
}

$loans = $loanObj->getAllDetailed();
include '../layouts/header.php';
?>

<div class="header-action">
    <h2>Studio Loan Operations</h2>
    <a href="create.php" class="btn">+ Issue New Loan</a>
</div>

<?php include '../components/alert.php'; ?>

<table class="data-table">
    <thead>
        <tr>
            <th>Member</th>
            <th>Equipment</th>
            <th>Loan Date</th>
            <th>Return Date</th>
            <th>Status</th>
            <th>Notes</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($loans as $l): ?>
        <tr>
            <td><?= htmlspecialchars($l['member_name']) ?></td>
            <td><?= htmlspecialchars($l['guitar_brand'] . ' ' . $l['guitar_model']) ?></td>
            <td><?= formatDate($l['loan_date']) ?></td>
            <td><?= formatDate($l['return_date']) ?></td>
            <td><span class="badge status-<?= strtolower($l['status']) ?>"><?= $l['status'] ?></span></td>
            <td><?= htmlspecialchars($l['notes']) ?></td>
            <td>
                <a href="detail.php?id=<?= $l['id'] ?>" class="btn-edit" style="color:#a5d6a7;">Detail</a>
                <?php if ($l['status'] === 'Borrowed'): ?>
                    <a href="index.php?return=<?= $l['id'] ?>" class="btn-edit">Return</a>
                <?php endif; ?>
                <a href="index.php?delete=<?= $l['id'] ?>" class="btn-delete" onclick="return confirm('Delete loan record?')">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include '../layouts/footer.php'; ?>