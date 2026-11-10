<?php
require_once '../../config/db.php';
require_once '../../helpers/functions.php';
require_once '../../helpers/AlertHandler.php';
require_once '../../class/Loan.php';
require_once '../../class/Member.php';
require_once '../../class/Guitar.php';

$db = (new Database())->getConnection();
$loanObj = new Loan($db);
$memberObj = new Member($db);
$guitarObj = new Guitar($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $member_id = sanitize($_POST['member_id']);
    $guitar_id = sanitize($_POST['guitar_id']);
    $loan_date = sanitize($_POST['loan_date']);
    $notes = sanitize($_POST['notes']);

    if ($loanObj->create($member_id, $guitar_id, $loan_date, $notes)) {
        $guitarObj->updateStatus($guitar_id, 'On Loan');
        AlertHandler::setAlert("Equipment loan issued successfully!");
        header("Location: index.php");
        exit;
    }
}

$members = $memberObj->getAll();
$availableGuitars = array_filter($guitarObj->getAllWithCategory(), function($g) {
    return $g['status'] === 'Available';
});

include '../layouts/header.php';
?>

<h2>Issue Equipment Loan</h2>

<form method="POST" action="create.php" class="form-card-full">
    <div class="form-group">
        <label>Borrowing Member</label>
        <select name="member_id" required>
            <option value="">-- Select Member --</option>
            <?php foreach ($members as $m): ?>
                <option value="<?= $m['id'] ?>"><?= htmlspecialchars($m['stage_name']) ?> (<?= $m['position'] ?>)</option>
            <?php endforeach; ?>
        </select>
    </div>
    
    <div class="form-group">
        <label>Select Available Instrument</label>
        <select name="guitar_id" required>
            <option value="">-- Select Instrument --</option>
            <?php foreach ($availableGuitars as $g): ?>
                <option value="<?= $g['id'] ?>"><?= htmlspecialchars($g['brand'] . ' ' . $g['model']) ?> [SN: <?= $g['serial_number'] ?>]</option>
            <?php endforeach; ?>
        </select>
    </div>
    
    <div class="form-group">
        <label>Loan Date</label>
        <input type="date" name="loan_date" value="<?= date('Y-m-d') ?>" required>
    </div>
    
    <div class="form-group">
        <label>Notes / Purpose</label>
        <input type="text" name="notes" placeholder="e.g. Studio Recording Session">
    </div>
    
    <button type="submit" class="btn">Confirm & Issue Loan</button>
    <a href="index.php" class="btn btn-secondary">Cancel</a>
</form>

<?php include '../layouts/footer.php'; ?>