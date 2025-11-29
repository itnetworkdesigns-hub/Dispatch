<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/classes/Auth.php';

Auth::startSession();
Auth::requireAdmin();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Admin — User Approvals</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@latest/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
  <div class="container">
    <h1 class="mb-3">Pending User Approvals</h1>
    <div id="alert"></div>
    <div class="table-responsive">
      <table class="table table-sm table-bordered" id="pendingUsersTable">
        <thead>
          <tr><th>#</th><th>Name</th><th>Email</th><th>Role</th><th>Created</th><th>Action</th></tr>
        </thead>
        <tbody>
          <tr><td colspan="6">Loading...</td></tr>
        </tbody>
      </table>
    </div>
  </div>

  <script>
    async function loadPending() {
      const t = document.getElementById('pendingUsersTable');
      const tbody = t.querySelector('tbody');
      tbody.innerHTML = '<tr><td colspan="6">Loading...</td></tr>';
      try {
        const res = await fetch('admin/users/pending.php', { credentials: 'same-origin' });
        const json = await res.json().catch(() => null);
        if (!res.ok || !json || !json.success) {
          tbody.innerHTML = '<tr><td colspan="6">Failed to load</td></tr>';
          return;
        }
        const users = json.users || [];
        if (!users.length) {
          tbody.innerHTML = '<tr><td colspan="6">No pending users</td></tr>';
          return;
        }
        tbody.innerHTML = users.map(u => `
          <tr>
            <td>${u.id}</td>
            <td>${escapeHtml(u.name)}</td>
            <td>${escapeHtml(u.email)}</td>
            <td>${escapeHtml(u.role)}</td>
            <td>${escapeHtml(u.created_at)}</td>
            <td><button class="btn btn-sm btn-success approve" data-id="${u.id}">Approve</button></td>
          </tr>
        `).join('');
        t.querySelectorAll('.approve').forEach(b => b.addEventListener('click', async function(){
          const id = this.dataset.id;
          if (!confirm('Approve user #' + id + '?')) return;
          const res = await fetch('admin/users/approve.php', { method: 'POST', headers: {'Content-Type':'application/json'}, credentials: 'same-origin', body: JSON.stringify({ user_id: Number(id) }) });
          const j = await res.json().catch(() => null);
          if (!res.ok || !j || !j.success) { alert((j && j.error) || 'Approve failed'); return; }
          loadPending();
        }));
      } catch (err) {
        tbody.innerHTML = '<tr><td colspan="6">Network error</td></tr>';
      }
    }
    function escapeHtml(s) { if (s === null || s === undefined) return ''; return String(s).replace(/[&<>"']/g, function(m){ return ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":"&#39;"})[m]; }); }
    document.addEventListener('DOMContentLoaded', loadPending);
  </script>
</body>
</html>
