<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container">
    <a class="navbar-brand" href="<?= baseUrl(); ?>">Simple PHP App</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="<?= baseUrl(); ?>">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?= baseUrl('about.php'); ?>">About</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?= baseUrl('gallery.php'); ?>">Gallery</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?= baseUrl('contact.php'); ?>">Contact</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?= baseUrl('auth/login.php'); ?>">Login</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?= baseUrl('auth/register.php'); ?>">Register</a>
        </li>

      </ul>
    </div>
  </div>
</nav>
<div class="container">
  <?php if (isset($_SESSION['success']) && !empty($_SESSION['success'])) : ?>
    <div class="alert alert-success" role="alert">
      <?php
      echo $_SESSION['success'];
      unset($_SESSION['success']);
      ?>
    </div>
  <?php endif ?>
  <?php if (isset($_SESSION['error']) && !empty($_SESSION['error'])) : ?>
    <div class="alert alert-danger" role="alert">
      <?php
      echo $_SESSION['error'];
      unset($_SESSION['error']);
      ?>
    </div>
  <?php endif ?>
</div>