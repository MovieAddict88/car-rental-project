<?php require_once APPROOT . '/views/user/includes/header.php'; ?>

<div class="row">
    <div class="col-md-8 mx-auto">
        <h2 class="text-center">My Profile</h2>

        <!-- Profile Update Form -->
        <div class="card card-body bg-light mt-4">
            <h4>Update Profile Information</h4>
            <form action="<?php echo SITE_URL; ?>/user/profile" method="post">
                <div class="form-group mb-3">
                    <label for="name">Name:</label>
                    <input type="text" name="name" class="form-control <?php echo (!empty($data['name_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['user']->name; ?>">
                    <span class="invalid-feedback"><?php echo $data['name_err'] ?? ''; ?></span>
                </div>
                <div class="form-group mb-3">
                    <label for="email">Email:</label>
                    <input type="email" name="email" class="form-control <?php echo (!empty($data['email_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['user']->email; ?>">
                    <span class="invalid-feedback"><?php echo $data['email_err'] ?? ''; ?></span>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Update Profile</button>
                </div>
            </form>
        </div>

        <!-- Change Password Form -->
        <div class="card card-body bg-light mt-4">
            <h4>Change Password</h4>
            <form action="<?php echo SITE_URL; ?>/user/profile/change_password" method="post">
                <div class="form-group mb-3">
                    <label for="current_password">Current Password:</label>
                    <input type="password" name="current_password" class="form-control <?php echo (!empty($data['current_password_err'])) ? 'is-invalid' : ''; ?>">
                    <span class="invalid-feedback"><?php echo $data['current_password_err'] ?? ''; ?></span>
                </div>
                <div class="form-group mb-3">
                    <label for="new_password">New Password:</label>
                    <input type="password" name="new_password" class="form-control <?php echo (!empty($data['new_password_err'])) ? 'is-invalid' : ''; ?>">
                    <span class="invalid-feedback"><?php echo $data['new_password_err'] ?? ''; ?></span>
                </div>
                <div class="form-group mb-3">
                    <label for="confirm_password">Confirm New Password:</label>
                    <input type="password" name="confirm_password" class="form-control <?php echo (!empty($data['confirm_password_err'])) ? 'is-invalid' : ''; ?>">
                    <span class="invalid-feedback"><?php echo $data['confirm_password_err'] ?? ''; ?></span>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-danger">Change Password</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once APPROOT . '/views/user/includes/footer.php'; ?>