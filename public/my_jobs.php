<?php require __DIR__ . '/partials/header.php';
require_role('employer');
global $mysqli;
$user = current_user(); ?>
<main class="container py-4 flex-grow-1 mt-auto">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="m-0">My Jobs</h3>
    <a class="btn btn-gradient btn-sm" href="<?= url('post_job.php') ?>">Post a Job</a>
  </div>
  <div class="table-responsive">
    <table class="table align-middle table-hover table-rounded">
      <thead>
        <tr>
          <th>Title</th>
          <th>Category</th>
          <th>Location</th>
          <th>Salary</th>
          <th>Created</th>
          <th class="text-end">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $stmt = $mysqli->prepare("SELECT id, title, category, location, salary, created_at FROM jobs WHERE employer_id=? ORDER BY created_at DESC");
        $stmt->bind_param('i', $user['id']);
        $stmt->execute();
        $res = $stmt->get_result();
        while ($j = $res->fetch_assoc()): ?>
          <tr class="bg-transparent">
            <td><?= e($j['title']) ?></td>
            <td><span class="badge bg-secondary"><?= e($j['category']) ?></span></td>
            <td><?= e($j['location']) ?></td>
            <td><?= e($j['salary']) ?></td>
            <td><span class="text-muted-2 small"><?= e($j['created_at']) ?></span></td>
            <td class="text-end">
              <a class="btn btn-sm btn-outline-light" href="<?= url('edit_job.php') ?>?id=<?= (int) $j['id'] ?>">Edit</a>
              <form class="d-inline" method="post" action="<?= url('actions/job_delete.php') ?>"
                onsubmit="return confirm('Delete this job?')">
                <?php csrf_input(); ?>
                <input type="hidden" name="id" value="<?= (int) $j['id'] ?>">
                <button class="btn btn-sm btn-outline-danger">Delete</button>
              </form>
            </td>
          </tr>
        <?php endwhile;
        $stmt->close(); ?>
      </tbody>
    </table>
  </div>
</main>
<?php require __DIR__ . '/partials/footer.php'; ?>