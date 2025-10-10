<?php
require_once 'includes/header.php';

$error = '';
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo->beginTransaction();

        // Loop through all posted data
        foreach ($_POST as $key => $value) {
            // Sanitize the value
            $sanitized_value = sanitize_input($value);

            // Prepare and execute the update statement for each setting
            $stmt = $pdo->prepare("UPDATE settings SET setting_value = ? WHERE setting_key = ?");
            $stmt->execute([$sanitized_value, $key]);
        }

        // Handle logo upload
        if (isset($_FILES['system_logo']) && $_FILES['system_logo']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = __DIR__ . '/../assets/images/';
            $logo_name = 'logo.' . pathinfo($_FILES['system_logo']['name'], PATHINFO_EXTENSION);
            $target_file = $upload_dir . $logo_name;

            $check = getimagesize($_FILES['system_logo']['tmp_name']);
            if ($check !== false) {
                 if (move_uploaded_file($_FILES['system_logo']['tmp_name'], $target_file)) {
                    $stmt = $pdo->prepare("UPDATE settings SET setting_value = ? WHERE setting_key = 'system_logo'");
                    $stmt->execute([$logo_name]);
                } else {
                    throw new Exception("Sorry, there was an error uploading your logo.");
                }
            } else {
                 throw new Exception("File is not a valid image.");
            }
        }

        $pdo->commit();
        $success = "Settings updated successfully!";
    } catch (Exception $e) {
        $pdo->rollBack();
        $error = "An error occurred: " . $e->getMessage();
    }
}


// Fetch current settings from the database
try {
    $stmt = $pdo->query("SELECT * FROM settings");
    $settings_array = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
} catch (PDOException $e) {
    $error = "Could not fetch system settings.";
    $settings_array = [];
}
?>

<h1 class="h2">System Settings</h1>

<?php if ($error): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>
<?php if ($success): ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <h5>General and Payment Gateway Settings</h5>
    </div>
    <div class="card-body">
        <form action="system_settings.php" method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="system_name" class="form-label">System Name</label>
                    <input type="text" class="form-control" id="system_name" name="system_name" value="<?php echo htmlspecialchars($settings_array['system_name'] ?? ''); ?>" required>
                </div>
                 <div class="col-md-6 mb-3">
                    <label for="contact_email" class="form-label">Contact Email</label>
                    <input type="email" class="form-control" id="contact_email" name="contact_email" value="<?php echo htmlspecialchars($settings_array['contact_email'] ?? ''); ?>" required>
                </div>
            </div>
             <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="contact_phone" class="form-label">Contact Phone</label>
                    <input type="text" class="form-control" id="contact_phone" name="contact_phone" value="<?php echo htmlspecialchars($settings_array['contact_phone'] ?? ''); ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="system_logo" class="form-label">System Logo</label>
                    <input class="form-control" type="file" id="system_logo" name="system_logo">
                     <?php if (!empty($settings_array['system_logo'])): ?>
                        <small class="form-text text-muted">Current logo:</small>
                        <img src="<?php echo URL_ROOT . '/assets/images/' . $settings_array['system_logo']; ?>" alt="System Logo" class="img-thumbnail mt-2" style="max-height: 50px;">
                    <?php endif; ?>
                </div>
            </div>

            <hr>
            <h5>Payment Gateway API Keys</h5>
            <p class="text-muted">Enter your sandbox or production keys for payment processing.</p>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="paypal_client_id" class="form-label">PayPal Client ID</label>
                    <input type="text" class="form-control" id="paypal_client_id" name="paypal_client_id" value="<?php echo htmlspecialchars($settings_array['paypal_client_id'] ?? ''); ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="stripe_secret_key" class="form-label">Stripe Secret Key</label>
                    <input type="text" class="form-control" id="stripe_secret_key" name="stripe_secret_key" value="<?php echo htmlspecialchars($settings_array['stripe_secret_key'] ?? ''); ?>">
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Save Settings</button>
        </form>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>