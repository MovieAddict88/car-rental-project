<?php
$page_title = "System Settings";
require_once 'includes/header.php';
require_once 'includes/sidebar.php';
require_once 'includes/navbar.php';

// --- Load Settings from Database ---
$settings_result = $conn->query("SELECT * FROM settings");
$settings = [];
while ($row = $settings_result->fetch_assoc()) {
    $settings[$row['setting_key']] = $row['setting_value'];
}

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn->begin_transaction();
    try {
        // Loop through POST data and update settings
        foreach ($_POST as $key => $value) {
            if (isset($settings[$key])) {
                $stmt = $conn->prepare("UPDATE settings SET setting_value = ? WHERE setting_key = ?");
                $stmt->bind_param("ss", $value, $key);
                $stmt->execute();
                $stmt->close();
            }
        }

        // --- Logo Upload Logic ---
        if (isset($_FILES['site_logo']) && $_FILES['site_logo']['error'] == 0) {
            $logo_name = time() . '_' . basename($_FILES["site_logo"]["name"]);
            move_uploaded_file($_FILES['site_logo']['tmp_name'], '../assets/images/' . $logo_name);

            $stmt = $conn->prepare("UPDATE settings SET setting_value = ? WHERE setting_key = 'site_logo'");
            $stmt->bind_param("s", $logo_name);
            $stmt->execute();
            $stmt->close();
        }

        $conn->commit();
        $success = "Settings saved successfully!";
        // Reload settings to display updated values
        $settings_result = $conn->query("SELECT * FROM settings");
        while ($row = $settings_result->fetch_assoc()) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }

    } catch (mysqli_sql_exception $exception) {
        $conn->rollback();
        $errors[] = "Error saving settings.";
    }
}
?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">System Settings</h1>

    <?php if ($success): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>

    <form action="settings.php" method="POST" enctype="multipart/form-data">
        <div class="row">
            <!-- General Settings -->
            <div class="col-lg-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">General Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="site_name" class="form-label">Site Name</label>
                            <input type="text" class="form-control" id="site_name" name="site_name" value="<?php echo htmlspecialchars($settings['site_name']); ?>">
                        </div>
                        <div class="mb-3">
                            <label for="contact_email" class="form-label">Contact Email</label>
                            <input type="email" class="form-control" id="contact_email" name="contact_email" value="<?php echo htmlspecialchars($settings['contact_email']); ?>">
                        </div>
                        <div class="mb-3">
                            <label for="contact_phone" class="form-label">Contact Phone</label>
                            <input type="text" class="form-control" id="contact_phone" name="contact_phone" value="<?php echo htmlspecialchars($settings['contact_phone']); ?>">
                        </div>
                        <div class="mb-3">
                            <label for="site_logo" class="form-label">Site Logo</label>
                            <input class="form-control" type="file" id="site_logo" name="site_logo">
                            <small class="form-text text-muted">Current logo: <?php echo htmlspecialchars($settings['site_logo']); ?></small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Gateway Settings -->
            <div class="col-lg-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Payment Gateway (Stripe)</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="stripe_publishable_key" class="form-label">Publishable Key</label>
                            <input type="text" class="form-control" id="stripe_publishable_key" name="stripe_publishable_key" value="<?php echo htmlspecialchars($settings['stripe_publishable_key']); ?>">
                        </div>
                        <div class="mb-3">
                            <label for="stripe_secret_key" class="form-label">Secret Key</label>
                            <input type="password" class="form-control" id="stripe_secret_key" name="stripe_secret_key" value="<?php echo htmlspecialchars($settings['stripe_secret_key']); ?>">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary btn-lg">Save Settings</button>
    </form>
</div>

<?php
require_once 'includes/footer.php';
?>