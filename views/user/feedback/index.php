<?php require_once APPROOT . '/views/user/includes/header.php'; ?>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card card-body bg-light mt-5">
            <h2 class="text-center">Submit Feedback</h2>
            <p>We would love to hear your thoughts, concerns, or problems with anything so we can improve!</p>
            <?php flash('feedback_success'); ?>
            <form action="<?php echo SITE_URL; ?>/user/feedback" method="post">
                <div class="form-group mb-3">
                    <label for="message">Message: <sup>*</sup></label>
                    <textarea name="message" class="form-control form-control-lg <?php echo (!empty($data['message_err'])) ? 'is-invalid' : ''; ?>"><?php echo $data['message']; ?></textarea>
                    <span class="invalid-feedback"><?php echo $data['message_err']; ?></span>
                </div>
                <div class="d-grid">
                    <input type="submit" value="Submit" class="btn btn-success">
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once APPROOT . '/views/user/includes/footer.php'; ?>