<?php
require_once '../../config/db.php';
require_once '../../helpers/functions.php';
require_once '../../helpers/AlertHandler.php';
require_once '../../class/Member.php';

$db = (new Database())->getConnection();
$memberObj = new Member($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name']);
    $stage_name = sanitize($_POST['stage_name']);
    $position = sanitize($_POST['position']);
    
    $photoName = uploadImage($_FILES['photo'], '../../assets/uploads/members/') ?? 'default-avatar.png';

    if ($memberObj->create($name, $stage_name, $position, $photoName)) {
        AlertHandler::setAlert("Member created successfully!");
        header("Location: index.php");
        exit;
    }
}

include '../layouts/header.php';
?>

<h2>Add New Member</h2>

<form method="POST" action="create.php" enctype="multipart/form-data" class="form-card-full">
    <div class="form-group">
        <label>Full Name</label>
        <input type="text" name="name" required>
    </div>
    <div class="form-group">
        <label>Stage Name</label>
        <input type="text" name="stage_name" required>
    </div>
    <div class="form-group">
        <label>Position / Instrument</label>
        <input type="text" name="position" required>
    </div>
    <div class="form-group">
        <label>Photo</label>
        <input type="file" name="photo" accept="image/*">
    </div>
    
    <button type="submit" class="btn">Save Member</button>
    <a href="index.php" class="btn btn-secondary">Cancel</a>
</form>

<?php include '../layouts/footer.php'; ?>