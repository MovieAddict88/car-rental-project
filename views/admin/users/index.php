<?php require_once APPROOT . '/views/admin/includes/header.php'; ?>

<h1 class="h3 mb-4 text-gray-800">Manage Users</h1>

<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Registered On</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($data['users'] as $user): ?>
                    <tr>
                        <td><?php echo $user->name; ?></td>
                        <td><?php echo $user->email; ?></td>
                        <td><?php echo ucwords($user->role); ?></td>
                        <td>
                            <?php if($user->status): ?>
                                <span class="badge bg-success">Active</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Suspended</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo date('d M Y', strtotime($user->created_at)); ?></td>
                        <td>
                            <a href="<?php echo SITE_URL; ?>/admin/users/toggle_status/<?php echo $user->id; ?>" class="btn btn-sm <?php echo $user->status ? 'btn-secondary' : 'btn-success'; ?>">
                                <?php echo $user->status ? 'Suspend' : 'Activate'; ?>
                            </a>
                            <form action="<?php echo SITE_URL; ?>/admin/users/delete/<?php echo $user->id; ?>" method="post" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once APPROOT . '/views/admin/includes/footer.php'; ?>