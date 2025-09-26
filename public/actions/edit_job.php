<?php
require __DIR__ . '/../config/init.php';
require_role('employer');
$id = (int) ($_GET['id'] ?? 0);

$stmt = $mysqli->prepare("SELECT * FROM jobs WHERE id=? AND employer_id=? LIMIT 1");
$stmt->bind_param('ii', $id, $_SESSION['user_id']);
$stmt->execute();
$job = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$job) {
    flash('Job not found', 'danger');
    header('Location: ' . url('my_jobs.php'));
    exit;
}

$cats = ['Software', 'Design', 'Marketing', 'Finance'];
require __DIR__ . '/partials/header.php';
?>
<main class="container py-4 flex-grow-1" style="max-width:720px">
    <h3 class="mb-3">Edit Job</h3>
    <div class="card card-glass">
        <div class="card-body">
            <form method="post" action="<?= url('actions/job_update.php') ?>">
                <?php csrf_input(); ?>
                <input type="hidden" name="id" value="<?= (int) $job['id'] ?>">

                <div class="mb-3">
                    <label class="form-label">Title</label>
                    <input class="form-control" name="title" required maxlength="150" value="<?= e($job['title']) ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea class="form-control" name="description" rows="6"
                        required><?= e($job['description']) ?></textarea>
                </div>

                <div class="row g-2">
                    <div class="col-md-4">
                        <label class="form-label">Category</label>
                        <select class="form-select" name="category" required>
                            <?php foreach ($cats as $c): ?>
                                <option <?= $job['category'] === $c ? 'selected' : '' ?>><?= e($c) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Location</label>
                        <input class="form-control" name="location" required value="<?= e($job['location']) ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Salary</label>
                        <input class="form-control" name="salary" value="<?= e($job['salary']) ?>">
                    </div>
                </div>

                <button class="btn btn-gradient mt-3">Save changes</button>
                <a href="<?= url('my_jobs.php') ?>" class="btn btn-outline-light mt-3">Cancel</a>
            </form>
        </div>
    </div>
</main>
<?php require __DIR__ . '/partials/footer.php'; ?>