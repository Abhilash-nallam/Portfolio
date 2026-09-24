/* LeadDesk Mini - Admin Dashboard Scripts */
(function () {
    'use strict';

    const loadingOverlay = document.getElementById('loadingOverlay');
    const toastContainer = document.getElementById('toastContainer');

    function showLoading(show) {
        if (!loadingOverlay) return;
        loadingOverlay.classList.toggle('d-none', !show);
    }

    function showToast(message, type = 'success') {
        if (!toastContainer) return;
        const icon = type === 'success' ? 'fa-circle-check' : 'fa-circle-exclamation';
        const bg = type === 'success' ? 'text-bg-success' : 'text-bg-danger';

        const toastEl = document.createElement('div');
        toastEl.className = `toast align-items-center ${bg} border-0`;
        toastEl.setAttribute('role', 'alert');
        toastEl.innerHTML = `
            <div class="d-flex">
                <div class="toast-body"><i class="fa-solid ${icon} me-2"></i>${message}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>`;
        toastContainer.appendChild(toastEl);
        const toast = new bootstrap.Toast(toastEl, { delay: 3500 });
        toast.show();
        toastEl.addEventListener('hidden.bs.toast', () => toastEl.remove());
    }

    // ---- Status change (AJAX, no reload) ----
    document.querySelectorAll('.status-select').forEach((select) => {
        const applyBadgeClass = (el) => {
            el.classList.remove('badge-status-new', 'badge-status-contacted', 'badge-status-closed');
            const map = { New: 'badge-status-new', Contacted: 'badge-status-contacted', Closed: 'badge-status-closed' };
            el.classList.add(map[el.value] || 'badge-status-new');
        };
        applyBadgeClass(select);

        select.addEventListener('change', async function () {
            const leadId = this.dataset.leadId;
            const status = this.value;
            showLoading(true);
            try {
                const res = await fetch('../api/update_status.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: new URLSearchParams({ lead_id: leadId, status, csrf_token: CSRF_TOKEN }),
                });
                const data = await res.json();
                if (data.success) {
                    applyBadgeClass(this);
                    showToast(data.message, 'success');
                } else {
                    showToast(data.message || 'Could not update status.', 'error');
                }
            } catch (err) {
                showToast('Network error. Please try again.', 'error');
            } finally {
                showLoading(false);
            }
        });
    });

    // ---- Delete lead (AJAX, no reload) ----
    document.querySelectorAll('.delete-lead-btn').forEach((btn) => {
        btn.addEventListener('click', async function () {
            const leadId = this.dataset.leadId;
            if (!confirm('Delete this lead permanently? This cannot be undone.')) return;

            const row = this.closest('tr');
            showLoading(true);
            try {
                const res = await fetch('../api/delete_lead.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: new URLSearchParams({ lead_id: leadId, csrf_token: CSRF_TOKEN }),
                });
                const data = await res.json();
                if (data.success) {
                    row.style.transition = 'opacity .25s ease';
                    row.style.opacity = '0';
                    setTimeout(() => row.remove(), 250);
                    showToast(data.message, 'success');
                } else {
                    showToast(data.message || 'Could not delete lead.', 'error');
                }
            } catch (err) {
                showToast('Network error. Please try again.', 'error');
            } finally {
                showLoading(false);
            }
        });
    });

    // ---- View lead modal population ----
    document.querySelectorAll('.view-lead-btn').forEach((btn) => {
        btn.addEventListener('click', function () {
            document.getElementById('mdName').textContent = this.dataset.name;
            document.getElementById('mdEmail').textContent = this.dataset.email;
            document.getElementById('mdBudget').textContent = this.dataset.budget;
            document.getElementById('mdMessage').textContent = this.dataset.message;
            document.getElementById('mdDate').textContent = this.dataset.date;
        });
    });

    // ---- Mobile sidebar toggle ----
    const sidebarToggle = document.getElementById('sidebarToggle');
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', () => {
            document.querySelector('.admin-sidebar').classList.toggle('sidebar-open');
        });
    }
})();
