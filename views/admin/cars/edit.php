<?php require_once APPROOT . '/views/admin/includes/header.php'; ?>

<h1 class="h3 mb-4 text-gray-800">Edit Car</h1>

<div class="card shadow mb-4">
    <div class="card-body">
        <form action="<?php echo SITE_URL; ?>/admin/cars/edit/<?php echo $data['car']->id; ?>" method="post">
            <div class="mb-3">
                <label for="brand" class="form-label">Brand</label>
                <input type="text" name="brand" class="form-control <?php echo (!empty($data['brand_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['car']->brand; ?>">
                <span class="invalid-feedback"><?php echo $data['brand_err']; ?></span>
            </div>
            <div class="mb-3">
                <label for="model" class="form-label">Model</label>
                <input type="text" name="model" class="form-control <?php echo (!empty($data['model_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['car']->model; ?>">
                <span class="invalid-feedback"><?php echo $data['model_err']; ?></span>
            </div>
            <div class="mb-3">
                <label for="type" class="form-label">Type</label>
                <select name="type" class="form-control">
                    <option value="Sedan" <?php echo ($data['car']->type == 'Sedan') ? 'selected' : ''; ?>>Sedan</option>
                    <option value="SUV" <?php echo ($data['car']->type == 'SUV') ? 'selected' : ''; ?>>SUV</option>
                    <option value="Van" <?php echo ($data['car']->type == 'Van') ? 'selected' : ''; ?>>Van</option>
                    <option value="Sports Car" <?php echo ($data['car']->type == 'Sports Car') ? 'selected' : ''; ?>>Sports Car</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="price_per_day" class="form-label">Price Per Day</label>
                <input type="number" step="0.01" name="price_per_day" class="form-control <?php echo (!empty($data['price_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['car']->price_per_day; ?>">
                <span class="invalid-feedback"><?php echo $data['price_err']; ?></span>
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" name="availability" class="form-check-input" id="availability" <?php echo ($data['car']->availability) ? 'checked' : ''; ?>>
                <label class="form-check-label" for="availability">Available</label>
            </div>
            <button type="submit" class="btn btn-primary">Update Car</button>
            <a href="<?php echo SITE_URL; ?>/admin/cars" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

<?php require_once APPROOT . '/views/admin/includes/footer.php'; ?>