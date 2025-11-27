<?php
// index.php — Basic entry page for the Dispatch project
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Dispatch</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@latest/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    /* Full-screen hero */
    .hero {
      height: 100vh;
      min-height: 480px;
      position: relative;
    }
    .hero img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: block;
    }
    .hero .overlay {
      background: rgba(0,0,0,0.55);
      z-index: 1;
    }
    .hero .hero-content { z-index: 2; }
    /* Navbar overlay styles so it sits above the hero */
    .navbar-overlay{
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      z-index: 4;
      background: rgba(0,0,0,0.45);
    }
    /* Ensure body content starts after hero visually if needed */
    @media (max-width: 991px){
      .hero { min-height: 60vh; }
    }
  </style>
</head>
<body>
  <header class="container-fluid px-0 mb-4">
    <div class="hero">
      <img src="assets/imgs/dispatch_header.jpg" alt="Dispatch header">

      <!-- Dark overlay -->
      <div class="overlay position-absolute top-0 start-0 w-100 h-100"></div>

      <!-- Centered title + buttons -->
      <div class="hero-content position-absolute top-50 start-50 translate-middle text-center text-white px-3">
        <h1 class="display-4 fw-bold mb-3">Dispatch Auto Carrier</h1>
        <div class="d-flex gap-2 justify-content-center">
          <button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#truckersModal">Truckers</button>
          <button type="button" class="btn btn-outline-light btn-lg" data-bs-toggle="modal" data-bs-target="#suppliersModal">Suppliers</button>
        </div>
      </div>
    </div>
  </header>

  <nav class="navbar navbar-expand-lg navbar-dark navbar-overlay">
    <div class="container">
      <a class="navbar-brand" href="#">Dispatch</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain" aria-controls="navMain" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navMain">
        <ul class="navbar-nav ms-auto align-items-lg-center">
          <li class="nav-item d-none d-lg-block">
            <a class="nav-link active" aria-current="page" href="#">Home</a>
          </li>
          <li class="nav-item d-none d-lg-block">
            <a class="nav-link" href="#">Features</a>
          </li>
          <li class="nav-item d-none d-lg-block">
            <a class="nav-link" href="#">Contact</a>
          </li>

          <!-- Client dropdown -->
          <li class="nav-item dropdown ms-2">
            <a class="nav-link dropdown-toggle" href="#" id="clientMenu" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Client
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="clientMenu">
              <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#suppliersModal">Sign In</a></li>
              <li><a class="dropdown-item" href="register_client.php">Register</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item" href="logout_client.php">Logout</a></li>
            </ul>
          </li>

          <!-- Trucker dropdown -->
          <li class="nav-item dropdown ms-2">
            <a class="nav-link dropdown-toggle" href="#" id="truckerMenu" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Trucker
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="truckerMenu">
              <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#truckersModal">Sign In</a></li>
              <li><a class="dropdown-item" href="register_trucker.php">Register</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item" href="logout_trucker.php">Logout</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <main class="container">
    <div class="p-5 mb-4 bg-body rounded-3">
      <h1 class="display-5"><?php echo 'Hello from index.php'; ?></h1>
      <p class="lead">Server time: <?php echo date('Y-m-d H:i:s'); ?></p>
      <hr class="my-4">
      <p class="small text-muted">Built with Bootstrap (CDN, latest).</p>
    </div>
  </main>

  <!-- Truckers Modal -->
  <div class="modal fade" id="truckersModal" tabindex="-1" aria-labelledby="truckersModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="truckersModalLabel">Truckers Login</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form>
            <div class="mb-3">
              <label class="form-label">Email</label>
              <input type="email" class="form-control" placeholder="you@example.com">
            </div>
            <div class="mb-3">
              <label class="form-label">Password</label>
              <input type="password" class="form-control" placeholder="Password">
            </div>
            <div class="d-flex justify-content-between align-items-center">
              <a href="#" class="small" data-action="open-register">Register</a>
              <a href="forgot_trucker.php" class="small">Forgot password?</a>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Sign In</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Suppliers Modal -->
  <div class="modal fade" id="suppliersModal" tabindex="-1" aria-labelledby="suppliersModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="suppliersModalLabel">Suppliers Login</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form>
            <div class="mb-3">
              <label class="form-label">Email</label>
              <input type="email" class="form-control" placeholder="you@example.com">
            </div>
            <div class="mb-3">
              <label class="form-label">Password</label>
              <input type="password" class="form-control" placeholder="Password">
            </div>
            <div class="d-flex justify-content-between align-items-center">
              <a href="#" class="small" data-action="open-register">Register</a>
              <a href="forgot_supplier.php" class="small">Forgot password?</a>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Sign In</button>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@latest/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Modal state toggling: switch between login and register form inside modals
    (function(){
      const modalIds = ['truckersModal','suppliersModal'];

      modalIds.forEach(id => {
        const modalEl = document.getElementById(id);
        if (!modalEl) return;

        const modalDialog = modalEl.querySelector('.modal-dialog');
        const titleEl = modalEl.querySelector('.modal-title');
        const bodyEl = modalEl.querySelector('.modal-body');
        const primaryBtn = modalEl.querySelector('.modal-footer .btn-primary');

        // store original login HTML to restore later
        const loginHTML = bodyEl.innerHTML;

        // create register HTML (same fields for now)
        const registerHTML = `
          <form>
            <div class="mb-3">
              <label class="form-label">Email</label>
              <input type="email" class="form-control" placeholder="you@example.com">
            </div>
            <div class="mb-3">
              <label class="form-label">Password</label>
              <input type="password" class="form-control" placeholder="Password">
            </div>
            <div class="d-flex justify-content-between align-items-center">
              <a href="#" class="small" data-action="open-login">Already have an account? Sign In</a>
              <a href="#" class="small" data-action="noop">&nbsp;</a>
            </div>
          </form>`;

        // event delegation for clicks inside modal body (register link) and navbar modal triggers
        modalEl.addEventListener('click', function(e){
          const act = e.target && e.target.getAttribute && e.target.getAttribute('data-action');
          if (!act) return;
          e.preventDefault();
          if (act === 'open-register') {
            titleEl.textContent = titleEl.textContent.replace(/Login/i,'Register');
            bodyEl.innerHTML = registerHTML;
            if (primaryBtn) primaryBtn.textContent = 'Register';
          } else if (act === 'open-login') {
            // restore login
            titleEl.textContent = titleEl.textContent.replace(/Register/i,'Login');
            bodyEl.innerHTML = loginHTML;
            if (primaryBtn) primaryBtn.textContent = 'Sign In';
          }
        });

        // When modal is shown, ensure it's in login state (restore)
        modalEl.addEventListener('show.bs.modal', function(){
          titleEl.textContent = titleEl.textContent.replace(/Register/i,'Login');
          bodyEl.innerHTML = loginHTML;
          if (primaryBtn) primaryBtn.textContent = 'Sign In';
        });
      });
    })();
  </script>
</body>
</html>
