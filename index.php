<?php
require_once __DIR__ . '/config/db.php';
$pdo = getDBConnection();

$stmt = $pdo->query("SELECT * FROM projects ORDER BY created_at DESC");
$projects = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bhanu Madhavarapu | CS Student &amp; Developer</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <div class="container">
    <header>
      <h1>Bhanu Madhavarapu</h1>
      <p class="subtitle">Computer Science Student &amp; Developer</p>
    </header>

    <section id="about">
      <h2>About</h2>
      <p>I focus on backend systems, command-line tooling, and web architectures. I prioritize zero-fluff code, computational efficiency, and robust, reliable applications.</p>
    </section>

    <section id="projects">
      <h2>Projects</h2>
      <div class="project-list">
        <?php if (empty($projects)): ?>
          <p class="project-desc">No projects available at this moment.</p>
        <?php else: ?>
          <?php foreach ($projects as $project): ?>
            <div class="project-item">
              <div class="project-header">
                <span class="project-title"><?= sanitize($project['title']) ?></span>
                <div class="project-links">
                  <?php if (!empty($project['github_url'])): ?>
                    <a href="<?= sanitize($project['github_url']) ?>" target="_blank" rel="noopener">Code</a>
                  <?php endif; ?>
                  <?php if (!empty($project['live_url'])): ?>
                    &middot; <a href="<?= sanitize($project['live_url']) ?>" target="_blank" rel="noopener">Live</a>
                  <?php endif; ?>
                </div>
              </div>
              <p class="project-desc"><?= sanitize($project['description']) ?></p>
              <span class="project-tags"><?= sanitize($project['tech_stack']) ?></span>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </section>

    <section id="contact">
      <h2>Contact</h2>
      <form id="contact-form">
        <div class="form-group">
          <label for="name">Name</label>
          <input type="text" id="name" name="name" required>
        </div>
        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
          <label for="subject">Subject</label>
          <input type="text" id="subject" name="subject" required>
        </div>
        <div class="form-group">
          <label for="body">Message</label>
          <textarea id="body" name="body" rows="4" required></textarea>
        </div>
        <button type="submit" id="submit-btn">Send Message</button>
        <div id="form-feedback" class="feedback"></div>
      </form>
    </section>
  </div>
  <script src="assets/js/main.js"></script>
</body>
</html>