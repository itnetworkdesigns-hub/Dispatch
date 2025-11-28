<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/classes/Auth.php';

Auth::startSession();
if (!Auth::check()) {
    header('Location: ' . BASE_URL);
    exit;
}
$user = Auth::user();
if (($user['role'] ?? null) !== 'supplier') {
    http_response_code(403);
    echo 'Forbidden — supplier account required.';
    exit;
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>New Order — Supplier</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@latest/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
  <div class="container">
    <h1 class="mb-3">Create New Transport Order</h1>
    <p>Logged in as <strong><?php echo htmlspecialchars($user['email']); ?></strong></p>

    <div id="alert"></div>

    <form id="orderForm" class="mb-4">
      <div class="mb-3">
        <label class="form-label">Number of cars</label>
        <input type="number" class="form-control" name="num_cars" min="1" value="1" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Pickup point</label>
        <input type="text" class="form-control" name="pickup_point" placeholder="Address or location" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Destination point</label>
        <input type="text" class="form-control" name="destination_point" placeholder="Address or location" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Notes (optional)</label>
        <textarea class="form-control" name="notes" rows="3"></textarea>
      </div>
      <button class="btn btn-primary" type="submit">Create Order</button>
      <a class="btn btn-secondary" href="<?php echo htmlspecialchars(BASE_URL); ?>">Back</a>
    </form>
  </div>

  <script>
    const BASE_URL = <?php echo json_encode(constant('BASE_URL')); ?>;
    const form = document.getElementById('orderForm');
    const alertBox = document.getElementById('alert');
    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      alertBox.innerHTML = '';
      const fd = new FormData(form);
      const data = Object.fromEntries(fd.entries());
      // convert num_cars to number
      data.num_cars = Number(data.num_cars || 0);
      try {
        const res = await fetch(BASE_URL + '/orders/create.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(data),
          credentials: 'same-origin'
        });
        const json = await res.json().catch(() => null);
        if (!res.ok || !json || !json.success) {
          const msg = json && (json.error || (json.errors && json.errors.join('<br>'))) || 'Failed to create order';
          alertBox.innerHTML = `<div class="alert alert-danger">${msg}</div>`;
          return;
        }
        alertBox.innerHTML = `<div class="alert alert-success">Order created (#${json.order_id}).</div>`;
        form.reset();
        form.num_cars.value = 1;
      } catch (err) {
        alertBox.innerHTML = `<div class="alert alert-danger">Network error</div>`;
      }
    });
  </script>
</body>
</html>
