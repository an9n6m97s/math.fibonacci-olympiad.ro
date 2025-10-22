<section class="admin-card">
    <h1>Autentificare administrator</h1>
    <p>Accesează zona de administrare pentru a gestiona înscrierile și setările ediției curente.</p>
    <form id="admin-login-form" novalidate>
        <div class="form-group">
            <label class="form-label text-light mb-2" for="admin-username">Utilizator</label>
            <input type="text" id="admin-username" name="username" placeholder="Utilizator" required>
        </div>
        <div class="form-group">
            <label class="form-label text-light mb-2" for="admin-password">Parolă</label>
            <input type="password" id="admin-password" name="password" placeholder="Parolă" required>
        </div>
        <div class="form-group">
            <button type="submit">Intră în cont</button>
        </div>
        <div id="admin-login-message" class="alert d-none" role="alert"></div>
    </form>
</section>
<script>
  (function() {
    const form = document.getElementById('admin-login-form');
    if (!form) return;
    const message = document.getElementById('admin-login-message');

    const showMessage = (type, text) => {
      message.classList.remove('d-none', 'alert-success', 'alert-danger', 'alert-warning');
      message.classList.add(`alert-${type}`);
      message.textContent = text;
    };

    form.addEventListener('submit', function(e) {
      e.preventDefault();
      const formData = new FormData(form);
      showMessage('warning', 'Se verifică datele…');

      fetch('/backend/api/private/admin/login.php', {
        method: 'POST',
        body: formData
      })
        .then(async (response) => {
          const data = await response.json().catch(() => ({}));
          if (!response.ok) {
            throw new Error(data.message || 'Autentificare eșuată.');
          }
          return data;
        })
        .then(() => {
          showMessage('success', 'Autentificare reușită. Se încarcă…');
          setTimeout(() => {
            window.location.href = '/admin/dashboard';
          }, 600);
        })
        .catch((error) => {
          showMessage('danger', error.message);
        });
    });
  })();
</script>
