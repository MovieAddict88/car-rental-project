<?php require_once APPROOT . '/views/admin/includes/header.php'; ?>

<h1 class="h3 mb-4 text-gray-800"><?php echo $data['title']; ?></h1>

<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Message</th>
                        <th>Submitted On</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($data['feedback'])): ?>
                        <?php foreach($data['feedback'] as $item): ?>
                        <tr>
                            <td>
                                <?php echo $item->user_name; ?><br>
                                <small><?php echo $item->user_email; ?></small>
                            </td>
                            <td><?php echo nl2br(htmlspecialchars($item->message)); ?></td>
                            <td><?php echo date('d M Y, H:i', strtotime($item->created_at)); ?></td>
                            <td>
                                <form action="<?php echo SITE_URL; ?>/admin/feedback/delete/<?php echo $item->id; ?>" method="post" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this feedback?');">
                                    <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center">No feedback submitted yet.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once APPROOT . '/views/admin/includes/footer.php'; ?>