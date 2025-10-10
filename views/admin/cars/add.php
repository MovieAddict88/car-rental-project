<?php require_once APPROOT . '/views/admin/includes/header.php'; ?>

<h1 class="h3 mb-4 text-gray-800">Add New Car</h1>

<div class="card shadow mb-4">
    <div class="card-body">
        <form action="<?php echo SITE_URL; ?>/admin/cars/add" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="brand" class="form-label">Brand</label>
                <input type="text" name="brand" class="form-control <?php echo (!empty($data['brand_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['brand']; ?>">
                <span class="invalid-feedback"><?php echo $data['brand_err']; ?></span>
            </div>
            <div class="mb-3">
                <label for="model" class="form-label">Model</label>
                <input type="text" name="model" class="form-control <?php echo (!empty($data['model_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['model']; ?>">
                <span class="invalid-feedback"><?php echo $data['model_err']; ?></span>
            </div>
            <div class="mb-3">
                <label for="type" class="form-label">Type</label>
                <select name="type" class="form-control">
                    <option value="Sedan">Sedan</option>
                    <option value="SUV">SUV</option>
                    <option value="Van">Van</option>
                    <option value="Sports Car">Sports Car</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="price_per_day" class="form-label">Price Per Day</label>
                <input type="number" step="0.01" name="price_per_day" class="form-control <?php echo (!empty($data['price_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['price_per_day']; ?>">
                <span class="invalid-feedback"><?php echo $data['price_err']; ?></span>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Image</label>
                <input type="file" name="image" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Add Car</button>
            <a href="<?php echo SITE_URL; ?>/admin/cars" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

<?php require_once APPROOT . '/views/admin/includes/footer.php'; ?>