<?php
// index.php — Basic entry page for the Dispatch project
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/classes/Auth.php';
$user = Auth::user();
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

          <?php if ($user): ?>
          <li class="nav-item dropdown ms-2">
            <a class="nav-link dropdown-toggle" href="#" id="userMenu" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <?php echo htmlspecialchars($user['email'] ?? $user['name']); ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
              <li><a class="dropdown-item" href="#" id="navLogout">Log out</a></li>
            </ul>
          </li>
          <?php else: ?>
          <li class="nav-item dropdown ms-2">
            <a class="nav-link dropdown-toggle" href="#" id="accountMenu" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Account
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="accountMenu">
              <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#suppliersModal">Supplier Sign In</a></li>
              <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#truckersModal">Trucker Sign In</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#suppliersModal" data-action="open-register">Register Supplier</a></li>
              <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#truckersModal" data-action="open-register">Register Trucker</a></li>
            </ul>
          </li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>

  <main class="container">
    <div class="p-5 mb-4 bg-body rounded-3">
      <h1 class="display-5">Hello from index.php</h1>
      <p class="lead">Server time: <?php echo date('Y-m-d H:i:s'); ?></p>
      <hr class="my-4">
      <p class="small text-muted">Built with Bootstrap (CDN, latest).</p>
      <?php if ($user): ?>
        <div class="alert alert-success mt-3">Logged in as <strong><?php echo htmlspecialchars($user['name'] ?? $user['email']); ?></strong> (<?php echo htmlspecialchars($user['role']); ?>)</div>
      <?php else: ?>
        <div class="alert alert-info mt-3">You are not logged in.</div>
      <?php endif; ?>
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
        const titleEl = modalEl.querySelector('.modal-title');
        const bodyEl = modalEl.querySelector('.modal-body');
        const primaryBtn = modalEl.querySelector('.modal-footer .btn-primary');

        // login and register HTML
        const loginHTML = `
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
              <a href="#" class="small" id="regLink" data-action="open-register">Register</a>
              <a href="#" class="small" data-action="noop">&nbsp;</a>
            </div>
          </form>`;
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
              <a href="#" class="small" id="loginLink" data-action="open-login">Already have an account? Sign In</a>
              <a href="#" class="small" data-action="noop">&nbsp;</a>
            </div>
          </form>`;

        // Helper to set modal state and re-attach listeners
        function setModalState(isRegister) {
          if (isRegister) {
            titleEl.textContent = titleEl.textContent.replace(/Login/i,'Register');
            bodyEl.innerHTML = registerHTML;
            if (primaryBtn) primaryBtn.textContent = 'Register';
          } else {
            titleEl.textContent = titleEl.textContent.replace(/Register/i,'Login');
            bodyEl.innerHTML = loginHTML;
            if (primaryBtn) primaryBtn.textContent = 'Sign In';
          }
          // Attach listeners after content swap
          const regLink = bodyEl.querySelector('#regLink');
          const loginLink = bodyEl.querySelector('#loginLink');
          if (regLink) {
            regLink.onclick = function(e) {
              e.preventDefault();
              setModalState(true);
            };
          }
          if (loginLink) {
            loginLink.onclick = function(e) {
              e.preventDefault();
              setModalState(false);
            };
          }
        }

        // When modal is shown, set state based on triggering element (login by default)
        modalEl.addEventListener('show.bs.modal', function(e){
          const action = e?.relatedTarget?.dataset?.action || null;
          setModalState(action === 'open-register');
        });

        // Initial attach
        setModalState(false);
      });
    })();
    // --- Registration and Login AJAX wiring ---
    // Get base URL from PHP config (inject as JS variable)
    const BASE_URL = <?php echo json_encode(constant('BASE_URL')); ?>;

    function showModalMessage(modalEl, msg, type = 'danger') {
      let msgBox = modalEl.querySelector('.modal-message');
      if (!msgBox) {
        msgBox = document.createElement('div');
        msgBox.className = 'modal-message mt-2';
        modalEl.querySelector('.modal-body').prepend(msgBox);
      }
      msgBox.innerHTML = `<div class="alert alert-${type} py-2">${msg}</div>`;
    }

    function clearModalMessage(modalEl) {
      const msgBox = modalEl.querySelector('.modal-message');
      if (msgBox) msgBox.remove();
    }

    function getFormData(form) {
      const data = {};
      Array.from(form.elements).forEach(el => {
        if (el.name && el.value !== undefined) data[el.name] = el.value;
      });
      return data;
    }

    // Attach submit handlers for both modals (login/register)
    ['truckersModal','suppliersModal'].forEach(modalId => {
      const modalEl = document.getElementById(modalId);
      if (!modalEl) return;
      modalEl.addEventListener('click', function(e){
        // Find the closest button (handles inner elements)
        const btn = e.target.closest('button');
        if (!btn || !btn.classList.contains('btn-primary')) return;
        e.preventDefault();
        clearModalMessage(modalEl);
        const isRegister = btn.textContent.match(/Register/i);
        const form = modalEl.querySelector('form');
        if (!form) return;
        // Find email/password fields
        const email = form.querySelector('input[type="email"]')?.value?.trim();
        const password = form.querySelector('input[type="password"]')?.value;
        if (!email || !password) {
          showModalMessage(modalEl, 'Email and password required');
          return;
        }
        // Determine role
        const role = modalId === 'truckersModal' ? 'trucker' : 'supplier';
          if (isRegister) {
          // Registration AJAX
          fetch(`${BASE_URL}/auth/register.php`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ name: email, email, password, role })
          })
          .then(r => r.json().catch(() => ({ success: false, error: 'Invalid server response' })))
          .then(res => {
            if (res && res.success) {
              showModalMessage(modalEl, 'Registration successful! You can now log in.', 'success');
              setTimeout(() => { modalEl.querySelector('.btn-close').click(); }, 1200);
            } else {
              showModalMessage(modalEl, res.error || (res.errors && res.errors.join('<br>')) || 'Registration failed');
            }
          })
          .catch(err => {
            console.error('Register error', err);
            showModalMessage(modalEl, 'Network error');
          });
          } else {
          // Login AJAX
          fetch(`${BASE_URL}/auth/login.php`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ email, password })
          })
          .then(r => r.json().catch(() => ({ success: false, error: 'Invalid server response' })))
          .then(res => {
            if (res && res.success) {
              showModalMessage(modalEl, 'Login successful!', 'success');
              setTimeout(() => { modalEl.querySelector('.btn-close').click(); }, 1000);
              // Optionally update UI for logged-in user here
              // reload page to show logged-in state
              setTimeout(() => { window.location.reload(); }, 1100);
            } else {
              showModalMessage(modalEl, res.error || 'Login failed');
            }
          })
          .catch(err => {
            console.error('Login error', err);
            showModalMessage(modalEl, 'Network error');
          });
        }
      });
    });

    document.querySelectorAll('.modal').forEach(modal => {
      modal.addEventListener('hidden.bs.modal', function() {
        document.body.focus();
      });
    });

    // Logout handler: call server logout and reload
    document.addEventListener('click', function(e){
      if (!e.target) return;
      if (e.target.id === 'navLogout') {
        e.preventDefault();
        fetch(`${BASE_URL}/auth/logout.php`, { method: 'POST', credentials: 'same-origin' })
          .then(r => r.json().catch(() => ({})))
          .finally(() => { window.location.reload(); });
      }
    });
  </script>
</body>
</html>
