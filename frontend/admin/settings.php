<?php
requireAdminLogin();
?>
<section class="admin-section">
    <h1 class="text-white mb-4">Setări generale</h1>
    <div class="principles-sidebar">
        <form id="admin-settings-form" class="row g-4">
            <div class="col-lg-6">
                <label class="form-label text-light mb-2" for="registration_open">Deschidere înscrieri</label>
                <input type="datetime-local" id="registration_open" name="registration_open" value="<?= date('Y-m-d\TH:i', strtotime($settings['registration_open'])) ?>" required>
            </div>
            <div class="col-lg-6">
                <label class="form-label text-light mb-2" for="registration_close">Închidere înscrieri</label>
                <input type="datetime-local" id="registration_close" name="registration_close" value="<?= date('Y-m-d\TH:i', strtotime($settings['registration_close'])) ?>" required>
            </div>
            <div class="col-lg-6">
                <label class="form-label text-light mb-2" for="comp_start">Început olimpiadă</label>
                <input type="date" id="comp_start" name="comp_start" value="<?= date('Y-m-d', strtotime($settings['comp_start'])) ?>" required>
            </div>
            <div class="col-lg-6">
                <label class="form-label text-light mb-2" for="comp_end">Sfârșit olimpiadă</label>
                <input type="date" id="comp_end" name="comp_end" value="<?= date('Y-m-d', strtotime($settings['comp_end'])) ?>" required>
            </div>
            <div class="col-12">
                <button type="submit" class="template-btn btn-style-one">
                    <span class="btn-wrap">
                        <span class="text-one">Salvează setările</span>
                        <span class="text-two">Salvează setările</span>
                    </span>
                </button>
            </div>
            <div class="col-12">
                <div id="admin-settings-message" class="alert d-none" role="alert"></div>
            </div>
        </form>
    </div>
</section>
<script>
  (function() {
    const form = document.getElementById('admin-settings-form');
    if (!form) return;
    const message = document.getElementById('admin-settings-message');

    const showMessage = (type, text) => {
      message.classList.remove('d-none', 'alert-success', 'alert-danger', 'alert-warning');
      message.classList.add(`alert-${type}`);
      message.textContent = text;
    };

    form.addEventListener('submit', function(e) {
      e.preventDefault();
      const formData = new FormData(form);
      showMessage('warning', 'Se salvează modificările…');

      fetch('/backend/api/private/admin/update-settings.php', {
        method: 'POST',
        body: formData
      })
        .then(async (response) => {
          const data = await response.json().catch(() => ({}));
          if (!response.ok) {
            throw new Error(data.message || 'Nu am putut salva setările.');
          }
          return data;
        })
        .then((data) => {
          showMessage('success', data.message || 'Setări salvate.');
        })
        .catch((error) => {
          showMessage('danger', error.message);
        });
    });
  })();
</script>
