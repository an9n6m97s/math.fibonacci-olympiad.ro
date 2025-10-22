<?php
requireAdminLogin();
?>
<section class="admin-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="text-white mb-0">Participanți înscriși</h1>
        <div class="d-flex gap-2">
            <button class="template-btn btn-style-one" type="button" id="participant-add-btn">
                <span class="btn-wrap">
                    <span class="text-one">Adaugă participant</span>
                    <span class="text-two">Adaugă participant</span>
                </span>
            </button>
            <div class="dropdown">
                <button class="template-btn btn-style-one" type="button" id="participant-export-toggle">
                    <span class="btn-wrap">
                        <span class="text-one">Exportă</span>
                        <span class="text-two">Exportă</span>
                    </span>
                </button>
                <div class="export-menu d-none" id="participant-export-menu">
                    <a href="#" data-format="csv">CSV</a>
                    <a href="#" data-format="json">JSON</a>
                    <a href="#" data-format="list">Listă simplă</a>
                </div>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="admin-table" id="participants-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Elev</th>
                    <th>Email</th>
                    <th>Telefon</th>
                    <th>Oraș</th>
                    <th>Școală</th>
                    <th>Clasa</th>
                    <th>Acțiuni</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <dialog id="participant-modal">
        <form method="dialog" id="participant-form">
            <h2 class="mb-3" id="participant-modal-title">Participant</h2>
            <input type="hidden" name="id" id="participant-id">
            <div class="form-group">
                <label for="participant-first">Prenume</label>
                <input type="text" id="participant-first" name="first_name" required>
            </div>
            <div class="form-group">
                <label for="participant-last">Nume</label>
                <input type="text" id="participant-last" name="last_name" required>
            </div>
            <div class="form-group">
                <label for="participant-email">Email</label>
                <input type="email" id="participant-email" name="email" required>
            </div>
            <div class="form-group">
                <label for="participant-phone">Telefon</label>
                <input type="text" id="participant-phone" name="phone" required>
            </div>
            <div class="form-group">
                <label for="participant-city">Oraș</label>
                <input type="text" id="participant-city" name="city" required>
            </div>
            <div class="form-group">
                <label for="participant-school">Școală</label>
                <input type="text" id="participant-school" name="school" required>
            </div>
            <div class="form-group">
                <label for="participant-grade">Clasa</label>
                <input type="text" id="participant-grade" name="grade" required>
            </div>
            <div class="modal-actions">
                <button type="submit" class="template-btn btn-style-one">
                    <span class="btn-wrap">
                        <span class="text-one">Salvează</span>
                        <span class="text-two">Salvează</span>
                    </span>
                </button>
                <button type="button" class="template-btn btn-style-one" data-close="modal">
                    <span class="btn-wrap">
                        <span class="text-one">Renunță</span>
                        <span class="text-two">Renunță</span>
                    </span>
                </button>
            </div>
            <div id="participant-form-message" class="alert d-none mt-3" role="alert"></div>
        </form>
    </dialog>
</section>
<script>
  (function() {
    const tableBody = document.querySelector('#participants-table tbody');
    const modal = document.getElementById('participant-modal');
    const form = document.getElementById('participant-form');
    const addBtn = document.getElementById('participant-add-btn');
    const exportToggle = document.getElementById('participant-export-toggle');
    const exportMenu = document.getElementById('participant-export-menu');
    const message = document.getElementById('participant-form-message');
    const modalTitle = document.getElementById('participant-modal-title');

    let participants = [];

    const toggleExportMenu = () => {
      exportMenu.classList.toggle('d-none');
    };

    document.addEventListener('click', (event) => {
      if (!exportMenu.contains(event.target) && event.target !== exportToggle) {
        exportMenu.classList.add('d-none');
      }
    });

    exportToggle.addEventListener('click', (event) => {
      event.preventDefault();
      toggleExportMenu();
    });

    exportMenu.querySelectorAll('a').forEach((link) => {
      link.addEventListener('click', (event) => {
        event.preventDefault();
        const format = link.dataset.format;
        window.location.href = `/backend/api/private/participants/export.php?format=${format}`;
        exportMenu.classList.add('d-none');
      });
    });

    const showFormMessage = (type, text) => {
      message.classList.remove('d-none', 'alert-success', 'alert-danger', 'alert-warning');
      message.classList.add(`alert-${type}`);
      message.textContent = text;
    };

    const resetForm = () => {
      form.reset();
      document.getElementById('participant-id').value = '';
      message.classList.add('d-none');
    };

    const openModal = (participant = null) => {
      resetForm();
      if (participant) {
        modalTitle.textContent = 'Editează participant';
        document.getElementById('participant-id').value = participant.id;
        document.getElementById('participant-first').value = participant.first_name;
        document.getElementById('participant-last').value = participant.last_name;
        document.getElementById('participant-email').value = participant.email;
        document.getElementById('participant-phone').value = participant.phone;
        document.getElementById('participant-city').value = participant.city;
        document.getElementById('participant-school').value = participant.school;
        document.getElementById('participant-grade').value = participant.grade_level || '';
      } else {
        modalTitle.textContent = 'Adaugă participant';
      }
      modal.showModal();
    };

    const closeModal = () => {
      modal.close();
    };

    addBtn.addEventListener('click', () => openModal());
    form.querySelector('[data-close="modal"]').addEventListener('click', closeModal);

    const fetchParticipants = () => {
      fetch('/backend/api/private/participants/list.php')
        .then((response) => response.json())
        .then((data) => {
          participants = data.participants || [];
          renderTable();
        });
    };

    const renderTable = () => {
      tableBody.innerHTML = '';
      if (!participants.length) {
        const row = document.createElement('tr');
        const cell = document.createElement('td');
        cell.colSpan = 8;
        cell.textContent = 'Nu există participanți înregistrați.';
        row.appendChild(cell);
        tableBody.appendChild(row);
        return;
      }

      participants.forEach((participant, index) => {
        const row = document.createElement('tr');
        row.innerHTML = `
          <td>${index + 1}</td>
          <td>${participant.first_name} ${participant.last_name}</td>
          <td>${participant.email}</td>
          <td>${participant.phone}</td>
          <td>${participant.city}</td>
          <td>${participant.school ?? ''}</td>
          <td>${participant.grade_level ?? ''}</td>
          <td>
            <button class="btn btn-sm btn-light" data-action="edit" data-id="${participant.id}">Editează</button>
            <button class="btn btn-sm btn-danger" data-action="delete" data-id="${participant.id}">Șterge</button>
          </td>
        `;
        tableBody.appendChild(row);
      });
    };

    tableBody.addEventListener('click', (event) => {
      const target = event.target;
      if (!(target instanceof HTMLElement)) return;
      const id = Number(target.dataset.id);
      if (!id) return;
      const participant = participants.find((item) => Number(item.id) === id);
      if (!participant) return;

      if (target.dataset.action === 'edit') {
        openModal(participant);
      } else if (target.dataset.action === 'delete') {
        if (confirm('Ești sigur că vrei să ștergi acest participant?')) {
          const body = new FormData();
          body.append('id', id);
          fetch('/backend/api/private/participants/delete.php', { method: 'POST', body })
            .then((response) => response.json())
            .then(() => fetchParticipants());
        }
      }
    });

    form.addEventListener('submit', function(event) {
      event.preventDefault();
      const formData = new FormData(form);
      const id = formData.get('id');
      const url = id ? '/backend/api/private/participants/update.php' : '/backend/api/private/participants/create.php';

      showFormMessage('warning', 'Se salvează…');

      fetch(url, {
        method: 'POST',
        body: formData
      })
        .then(async (response) => {
          const data = await response.json().catch(() => ({}));
          if (!response.ok) {
            throw new Error(data.message || 'Operațiunea a eșuat.');
          }
          return data;
        })
        .then(() => {
          showFormMessage('success', 'Salvat cu succes.');
          fetchParticipants();
          setTimeout(() => {
            closeModal();
          }, 400);
        })
        .catch((error) => {
          showFormMessage('danger', error.message);
        });
    });

    fetchParticipants();
  })();
</script>
