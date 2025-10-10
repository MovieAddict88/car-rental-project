<?php
include('includes/header.php');

$message = '';

// Fetch all settings from the database
$stmt = $pdo->query("SELECT * FROM settings");
$settings_array = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Convert array to a more accessible key-value format
$settings = [];
foreach ($settings_array as $setting) {
    $settings[$setting['setting_key']] = $setting['setting_value'];
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pdo->beginTransaction();
    try {
        // Loop through all posted data
        foreach ($_POST as $key => $value) {
            // Check if the setting exists
            if (array_key_exists($key, $settings)) {
                $stmt = $pdo->prepare("UPDATE settings SET setting_value = ? WHERE setting_key = ?");
                $stmt->execute([$value, $key]);
            }
        }

        // Handle file upload for the logo
        if (isset($_FILES['website_logo']) && $_FILES['website_logo']['error'] == 0) {
            $target_dir = "../assets/images/";
            $logo_name = "logo.png"; // Force a standard name for easy reference
            $target_file = $target_dir . $logo_name;
            if (move_uploaded_file($_FILES["website_logo"]["tmp_name"], $target_file)) {
                $stmt = $pdo->prepare("UPDATE settings SET setting_value = ? WHERE setting_key = 'website_logo'");
                $stmt->execute([$logo_name]);
            } else {
                 throw new Exception("Failed to upload logo.");
            }
        }

        $pdo->commit();
        $message = '<div class="alert alert-success">Settings updated successfully.</div>';

        // Re-fetch settings to display updated values
        $stmt = $pdo->query("SELECT * FROM settings");
        $settings_array = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($settings_array as $setting) {
            $settings[$setting['setting_key']] = $setting['setting_value'];
        }

    } catch (Exception $e) {
        $pdo->rollBack();
        $message = '<div class="alert alert-danger">Failed to update settings: ' . $e->getMessage() . '</div>';
    }
}

echo $message;
?>

<h1 class="mt-4">System Settings</h1>
<div class="card mb-4">
    <div class="card-header">Configure System Parameters</div>
    <div class="card-body">
        <form method="POST" enctype="multipart/form-data">

            <!-- General Settings -->
            <fieldset class="mb-4">
                <legend>General Settings</legend>
                <div class="mb-3">
                    <label for="contact_email" class="form-label">Contact Email</label>
                    <input type="email" class="form-control" id="contact_email" name="contact_email" value="<?php echo htmlspecialchars($settings['contact_email'] ?? ''); ?>">
                </div>
                <div class="mb-3">
                    <label for="website_logo" class="form-label">Website Logo</label>
                    <input type="file" class="form-control" id="website_logo" name="website_logo">
                    <?php if (!empty($settings['website_logo'])): ?>
                        <div class="mt-2">
                            <small>Current Logo:</small>
                            <img src="../assets/images/<?php echo htmlspecialchars($settings['website_logo']); ?>" height="50" alt="Current Logo">
                        </div>
                    <?php endif; ?>
                </div>
            </fieldset>

            <!-- Payment Gateway Settings -->
            <fieldset class="mb-4">
                <legend>Payment Gateway Keys (Sandbox)</legend>
                 <div class="mb-3">
                    <label for="paypal_client_id" class="form-label">PayPal Client ID</label>
                    <input type="text" class="form-control" id="paypal_client_id" name="paypal_client_id" value="<?php echo htmlspecialchars($settings['paypal_client_id'] ?? ''); ?>">
                </div>
                <div class="mb-3">
                    <label for="stripe_secret_key" class="form-label">Stripe Secret Key</label>
                    <input type="text" class="form-control" id="stripe_secret_key" name="stripe_secret_key" value="<?php echo htmlspecialchars($settings['stripe_secret_key'] ?? ''); ?>">
                </div>
            </fieldset>

            <button type="submit" class="btn btn-primary">Save Settings</button>
        </form>
    </div>
</div>

<?php include('includes/footer.php'); ?>