<?php
require_once '../config.php';
include 'includes/header.php';

// Fetch all settings
$settings_stmt = $pdo->query("SELECT * FROM settings");
$settings = $settings_stmt->fetchAll(PDO::FETCH_KEY_PAIR);

$success = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    foreach ($_POST['settings'] as $key => $value) {
        $stmt = $pdo->prepare("UPDATE settings SET value = ? WHERE `key` = ?");
        $stmt->execute([$value, $key]);
    }
    $success = 'Settings updated successfully.';
    // Refresh settings
    $settings_stmt = $pdo->query("SELECT * FROM settings");
    $settings = $settings_stmt->fetchAll(PDO::FETCH_KEY_PAIR);
}

// Ensure default settings exist if they don't
$defaults = [
    'stripe_secret_key' => 'YOUR_STRIPE_SECRET_KEY',
    'stripe_publishable_key' => 'YOUR_STRIPE_PUBLISHABLE_KEY',
    'contact_email' => 'contact@carrental.com',
    'company_name' => 'Car Rental Inc.',
    'logo_url' => '../images/logo.png'
];

foreach ($defaults as $key => $value) {
    if (!isset($settings[$key])) {
        $stmt = $pdo->prepare("INSERT INTO settings (`key`, `value`) VALUES (?, ?)");
        $stmt->execute([$key, $value]);
        $settings[$key] = $value;
    }
}

?>

<h2>System Settings</h2>
<?php if ($success): ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
<?php endif; ?>

<form method="POST">
    <div class="card">
        <div class="card-header">Payment Gateway</div>
        <div class="card-body">
            <div class="form-group">
                <label for="stripe_secret_key">Stripe Secret Key</label>
                <input type="text" name="settings[stripe_secret_key]" id="stripe_secret_key" class="form-control" value="<?php echo htmlspecialchars($settings['stripe_secret_key']); ?>">
            </div>
            <div class="form-group">
                <label for="stripe_publishable_key">Stripe Publishable Key</label>
                <input type="text" name="settings[stripe_publishable_key]" id="stripe_publishable_key" class="form-control" value="<?php echo htmlspecialchars($settings['stripe_publishable_key']); ?>">
            </div>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header">Company Information</div>
        <div class="card-body">
            <div class="form-group">
                <label for="company_name">Company Name</label>
                <input type="text" name="settings[company_name]" id="company_name" class="form-control" value="<?php echo htmlspecialchars($settings['company_name']); ?>">
            </div>
            <div class="form-group">
                <label for="contact_email">Contact Email</label>
                <input type="email" name="settings[contact_email]" id="contact_email" class="form-control" value="<?php echo htmlspecialchars($settings['contact_email']); ?>">
            </div>
            <div class="form-group">
                <label for="logo_url">Logo URL</label>
                <input type="text" name="settings[logo_url]" id="logo_url" class="form-control" value="<?php echo htmlspecialchars($settings['logo_url']); ?>">
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-primary mt-3">Save Settings</button>
</form>

<?php include 'includes/footer.php'; ?>