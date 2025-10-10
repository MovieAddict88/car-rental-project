<?php require_once APPROOT . '/views/admin/includes/header.php'; ?>

<h1 class="h3 mb-4 text-gray-800">System Settings</h1>

<div class="card shadow mb-4">
    <div class="card-body">
        <form action="<?php echo SITE_URL; ?>/admin/settings" method="post">
            <div class="mb-3">
                <label for="website_name" class="form-label">Website Name</label>
                <input type="text" name="website_name" class="form-control" value="<?php echo $data['settings']['website_name']; ?>">
            </div>
             <div class="mb-3">
                <label for="contact_email" class="form-label">Contact Email</label>
                <input type="email" name="contact_email" class="form-control" value="<?php echo $data['settings']['contact_email']; ?>">
            </div>
             <div class="mb-3">
                <label for="contact_phone" class="form-label">Contact Phone</label>
                <input type="text" name="contact_phone" class="form-control" value="<?php echo $data['settings']['contact_phone']; ?>">
            </div>

            <hr>
            <h5 class="mb-3">Payment Gateway Keys</h5>
            <div class="mb-3">
                <label for="paypal_client_id" class="form-label">PayPal Client ID</label>
                <input type="text" name="paypal_client_id" class="form-control" value="<?php echo $data['settings']['paypal_client_id']; ?>">
            </div>
            <div class="mb-3">
                <label for="stripe_secret_key" class="form-label">Stripe Secret Key</label>
                <input type="text" name="stripe_secret_key" class="form-control" value="<?php echo $data['settings']['stripe_secret_key']; ?>">
            </div>

            <hr>
            <h5 class="mb-3">SMTP Settings</h5>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="smtp_host" class="form-label">SMTP Host</label>
                    <input type="text" name="smtp_host" class="form-control" value="<?php echo $data['settings']['smtp_host']; ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="smtp_port" class="form-label">SMTP Port</label>
                    <input type="text" name="smtp_port" class="form-control" value="<?php echo $data['settings']['smtp_port']; ?>">
                </div>
            </div>
             <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="smtp_user" class="form-label">SMTP Username</label>
                    <input type="text" name="smtp_user" class="form-control" value="<?php echo $data['settings']['smtp_user']; ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="smtp_pass" class="form-label">SMTP Password</label>
                    <input type="password" name="smtp_pass" class="form-control" value="<?php echo $data['settings']['smtp_pass']; ?>">
                </div>
            </div>
             <div class="mb-3">
                <label for="smtp_secure" class="form-label">SMTP Security</label>
                <select name="smtp_secure" class="form-control">
                    <option value="tls" <?php echo ($data['settings']['smtp_secure'] == 'tls') ? 'selected' : ''; ?>>TLS</option>
                    <option value="ssl" <?php echo ($data['settings']['smtp_secure'] == 'ssl') ? 'selected' : ''; ?>>SSL</option>
                    <option value="" <?php echo ($data['settings']['smtp_secure'] == '') ? 'selected' : ''; ?>>None</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Save Settings</button>
        </form>
    </div>
</div>

<?php require_once APPROOT . '/views/admin/includes/footer.php'; ?>