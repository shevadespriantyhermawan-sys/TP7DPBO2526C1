<?php
require_once '../../config/db.php';
require_once '../../helpers/functions.php';
require_once '../../class/Loan.php';

$db = (new Database())->getConnection();
$loanObj = new Loan($db);

$id = $_GET['id'] ?? null;
$loan = $loanObj->getByIdDetailed($id);

if (!$loan) {
    header("Location: index.php");
    exit;
}

include '../layouts/header.php';
?>

<h2>Loan Transaction Detail</h2>

<div class="form-card-full" style="display: flex; gap: 30px; align-items: flex-start;">
    <div>
        <img src="../../assets/uploads/guitars/<?= $loan['guitar_photo'] ?>" style="width:200px; border-radius:8px;" onerror="this.src='https://via.placeholder.com/200/1f1f1f/ffffff?text=Guitar'">
    </div>
    <div>
        <h3><?= htmlspecialchars($loan['guitar_brand'] . ' ' . $loan['guitar_model']) ?></h3>
        <p><strong>Serial Number:</strong> <?= htmlspecialchars($loan['serial_number']) ?></p>
        <p><strong>Borrowed By:</strong> <?= htmlspecialchars($loan['member_name']) ?> (<?= htmlspecialchars($loan['member_fullname']) ?>)</p>
        <p><strong>Position:</strong> <?= htmlspecialchars($loan['position']) ?></p>
        <p><strong>Loan Date:</strong> <?= formatDate($loan['loan_date']) ?></p>
        <p><strong>Return Date:</strong> <?= formatDate($loan['return_date']) ?></p>
        <p><strong>Status:</strong> <span class="badge status-<?= strtolower($loan['status']) ?>"><?= $loan['status'] ?></span></p>
        <p><strong>Notes:</strong> <?= htmlspecialchars($loan['notes']) ?></p>
        <br>
        <a href="index.php" class="btn btn-secondary">&laquo; Back to Loans List</a>
    </div>
</div>

<?php include '../layouts/footer.php'; ?>