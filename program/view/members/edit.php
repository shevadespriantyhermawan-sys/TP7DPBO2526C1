<?php
require_once '../../config/db.php';
require_once '../../helpers/functions.php';
require_once '../../helpers/AlertHandler.php';
require_once '../../class/Member.php';

$db = (new Database())->getConnection();
$memberObj = new Member($db);

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: index.php");
    exit;
}

$member = $memberObj->getById($id);
if (!$member) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name       = $_POST['name'] ?? '';
    $stage_name = $_POST['stage_name'] ?? '';
    $position   = $_POST['position'] ?? '';
    // Pengecekan aman agar tidak ada Undefined array key "photo"
    $photoName  = isset($member['photo']) ? $member['photo'] : 'default-avatar.png'; 

    // Logika Upload Foto Baru (menggunakan move_uploaded_file)
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath   = $_FILES['photo']['tmp_name'];
        $originalName  = $_FILES['photo']['name'];
        $fileExtension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        
        $newFileName   = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $originalName);
        $uploadFileDir = '../../assets/uploads/members/';

        if (!is_dir($uploadFileDir)) {
            mkdir($uploadFileDir, 0777, true);
        }

        $dest_path = $uploadFileDir . $newFileName;
        
        // Memakai fungsi resmi PHP: move_uploaded_file
        if (move_uploaded_file($fileTmpPath, $dest_path)) {
            $photoName = $newFileName;
        }
    }

    // Update Data ke Database
    if ($memberObj->update($id, $name, $stage_name, $position, $photoName)) {
        AlertHandler::setAlert("Member updated successfully!", "success");
        header("Location: index.php");
        exit;
    } else {
        $error = "Failed to update member.";
    }
}

include '../layouts/header.php';
?>

<div class="form-container">
    <h2>Edit Member</h2>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form action="edit.php?id=<?= $member['id'] ?>" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label>Full Name</label>
            <input type="text" name="name" value="<?= htmlspecialchars($member['name'] ?? '') ?>" required class="form-control">
        </div>

        <div class="form-group">
            <label>Stage Name</label>
            <input type="text" name="stage_name" value="<?= htmlspecialchars($member['stage_name'] ?? '') ?>" required class="form-control">
        </div>

        <div class="form-group">
            <label>Position / Instrument</label>
            <input type="text" name="position" value="<?= htmlspecialchars($member['position'] ?? '') ?>" required class="form-control">
        </div>

        <div class="form-group">
            <label>Current Photo</label><br>
            <?php 
            $photoFileName = !empty($member['photo']) ? $member['photo'] : 'default-avatar.png';
            $photoPath = "../../assets/uploads/members/" . $photoFileName;
            ?>
            <img src="<?= htmlspecialchars($photoPath) ?>" alt="Member Photo" style="width: 80px; height: 80px; object-fit: cover; border-radius: 6px; margin-bottom: 10px;">
        </div>

        <div class="form-group">
            <label>Change Photo (Optional)</label>
            <input type="file" name="photo" accept="image/*" class="form-control">
        </div>

        <button type="submit" class="btn">Update Member</button>
        <a href="index.php" class="btn-secondary">Cancel</a>
    </form>
</div>

<?php include '../layouts/footer.php'; ?>