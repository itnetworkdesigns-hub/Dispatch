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
          <a href="#" class="small" data-action="open-register">Register</a>
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
          <a href="#" class="small" data-action="open-login">Already have an account? Sign In</a>
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
      attachModalLinks();
    }

    // Attach event listeners for register/login links after every content swap
    function attachModalLinks() {
      const regLink = bodyEl.querySelector('[data-action="open-register"]');
      const loginLink = bodyEl.querySelector('[data-action="open-login"]');
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

    // When modal is shown, ensure it's in login state (restore)
    modalEl.addEventListener('show.bs.modal', function(){
      setModalState(false);
    });

    // Initial attach
    setModalState(false);
  });
})();