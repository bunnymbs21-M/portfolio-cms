<?php
session_start();
require_once __DIR__ . '/../config/db.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$pdo = getDBConnection();
$status = '';

// Handle CRUD Actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    // CREATE Project
    if ($action === 'create_project') {
        $title = trim($_POST['title']);
        $desc = trim($_POST['description']);
        $stack = trim($_POST['tech_stack']);
        $gh = trim($_POST['github_url']);
        $live = trim($_POST['live_url']);

        $stmt = $pdo->prepare("INSERT INTO projects (title, description, tech_stack, github_url, live_url) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$title, $desc, $stack, $gh, $live]);
        $status = 'Project added successfully.';
    }

    // UPDATE Project
    if ($action === 'update_project') {
        $id = (int)$_POST['id'];
        $title = trim($_POST['title']);
        $desc = trim($_POST['description']);
        $stack = trim($_POST['tech_stack']);
        $gh = trim($_POST['github_url']);
        $live = trim($_POST['live_url']);

        $stmt = $pdo->prepare("UPDATE projects SET title = ?, description = ?, tech_stack = ?, github_url = ?, live_url = ? WHERE id = ?");
        $stmt->execute([$title, $desc, $stack, $gh, $live, $id]);
        $status = 'Project updated.';
    }

    // DELETE Project
    if ($action === 'delete_project') {
        $id = (int)$_POST['id'];
        $stmt = $pdo->prepare("DELETE FROM projects WHERE id = ?");
        $stmt->execute([$id]);
        $status = 'Project deleted.';
    }

    // DELETE Message
    if ($action === 'delete_message') {
        $id = (int)$_POST['id'];
        $stmt = $pdo->prepare("DELETE FROM messages WHERE id = ?");
        $stmt->execute([$id]);
        $status = 'Message removed.';
    }
}

// Fetch Projects & Messages
$projects = $pdo->query("SELECT * FROM projects ORDER BY created_at DESC")->fetchAll();
$messages = $pdo->query("SELECT * FROM messages ORDER BY created_at DESC")->fetchAll();

// Edit state helper
$edit_project = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM projects WHERE id = ?");
    $stmt->execute([(int)$_GET['edit']]);
    $edit_project = $stmt->fetch();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
  <div class="container" style="max-width: 900px;">
    <nav class="admin-nav">
      <strong>CMS Manager (<?= sanitize($_SESSION['admin_username']) ?>)</strong>
      <div>
        <a href="../index.php" target="_blank">View Site</a> &middot;
        <a href="logout.php">Logout</a>
      </div>
    </nav>

    <?php if ($status): ?>
      <p style="color: var(--text); font-weight: 500; margin-bottom: 20px;"><?= sanitize($status) ?></p>
    <?php endif; ?>

    <!-- Project Form (Create or Update) -->
    <h2><?= $edit_project ? 'Edit Project' : 'Add New Project' ?></h2>
    <form method="POST" action="dashboard.php">
      <input type="hidden" name="action" value="<?= $edit_project ? 'update_project' : 'create_project' ?>">
      <?php if ($edit_project): ?>
        <input type="hidden" name="id" value="<?= $edit_project['id'] ?>">
      <?php endif; ?>

      <div class="form-group">
        <label>Project Title</label>
        <input type="text" name="title" value="<?= $edit_project ? sanitize($edit_project['title']) : '' ?>" required>
      </div>
      <div class="form-group">
        <label>Description</label>
        <textarea name="description" rows="3" required><?= $edit_project ? sanitize($edit_project['description']) : '' ?></textarea>
      </div>
      <div class="form-group">
        <label>Tech Stack (Comma-separated)</label>
        <input type="text" name="tech_stack" value="<?= $edit_project ? sanitize($edit_project['tech_stack']) : '' ?>" required>
      </div>
      <div class="form-group">
        <label>GitHub URL</label>
        <input type="url" name="github_url" value="<?= $edit_project ? sanitize($edit_project['github_url']) : '' ?>">
      </div>
      <div class="form-group">
        <label>Live URL</label>
        <input type="url" name="live_url" value="<?= $edit_project ? sanitize($edit_project['live_url']) : '' ?>">
      </div>
      <button type="submit"><?= $edit_project ? 'Update Project' : 'Save Project' ?></button>
      <?php if ($edit_project): ?>
        <a href="dashboard.php" style="margin-left: 10px; font-size: 0.9rem;">Cancel Edit</a>
      <?php endif; ?>
    </form>

    <!-- Manage Existing Projects -->
    <h2>Existing Projects (CRUD)</h2>
    <table class="admin-table">
      <thead>
        <tr>
          <th>Title</th>
          <th>Tech Stack</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($projects as $p): ?>
          <tr>
            <td><?= sanitize($p['title']) ?></td>
            <td><?= sanitize($p['tech_stack']) ?></td>
            <td>
              <a href="dashboard.php?edit=<?= $p['id'] ?>">Edit</a>
              <form method="POST" style="display:inline;" onsubmit="return confirm('Delete this project?');">
                <input type="hidden" name="action" value="delete_project">
                <input type="hidden" name="id" value="<?= $p['id'] ?>">
                <button type="submit" class="btn-delete">Delete</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <!-- Contact Messages Inbox -->
    <h2>Received Messages</h2>
    <table class="admin-table">
      <thead>
        <tr>
          <th>From</th>
          <th>Subject</th>
          <th>Message</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($messages as $m): ?>
          <tr>
            <td><?= sanitize($m['name']) ?><br><small><?= sanitize($m['email']) ?></small></td>
            <td><?= sanitize($m['subject']) ?></td>
            <td><?= nl2br(sanitize($m['body'])) ?></td>
            <td>
              <form method="POST" style="display:inline;">
                <input type="hidden" name="action" value="delete_message">
                <input type="hidden" name="id" value="<?= $m['id'] ?>">
                <button type="submit" class="btn-delete">Delete</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</body>
</html>