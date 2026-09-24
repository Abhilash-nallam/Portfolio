/* LeadDesk Mini - Landing Page Scripts */
(function () {
    'use strict';

    // ---- Sticky navbar background on scroll ----
    const navbar = document.getElementById('ldNavbar');
    const onScroll = () => {
        if (!navbar) return;
        navbar.classList.toggle('scrolled', window.scrollY > 40);
    };
    window.addEventListener('scroll', onScroll);
    onScroll();

    // ---- Scroll reveal animation ----
    const revealEls = document.querySelectorAll('.reveal');
    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.15 });
        revealEls.forEach((el) => observer.observe(el));
    } else {
        revealEls.forEach((el) => el.classList.add('is-visible'));
    }

    // ---- Pipeline stage highlight rotation (hero signature element) ----
    const stages = document.querySelectorAll('.pipeline-stage');
    if (stages.length) {
        let idx = 0;
        setInterval(() => {
            stages.forEach((s) => s.classList.remove('is-active'));
            idx = (idx + 1) % stages.length;
            stages[idx].classList.add('is-active');
        }, 2000);
    }

    // ---- Lead form: client validation + AJAX submit ----
    const form = document.getElementById('leadForm');
    if (!form) return;

    const alertBox = document.getElementById('formAlert');
    const submitBtn = document.getElementById('submitLeadBtn');
    const btnText = submitBtn.querySelector('.btn-text');
    const btnSpinner = submitBtn.querySelector('.btn-spinner');

    function setLoading(isLoading) {
        submitBtn.disabled = isLoading;
        btnText.classList.toggle('d-none', isLoading);
        btnSpinner.classList.toggle('d-none', !isLoading);
    }

    function showAlert(message, type) {
        alertBox.className = `alert form-alert alert-${type}`;
        alertBox.innerHTML = message;
        alertBox.classList.remove('d-none');
        alertBox.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    function clearFieldErrors() {
        form.querySelectorAll('.is-invalid').forEach((el) => el.classList.remove('is-invalid'));
    }

    function markFieldError(fieldName, message) {
        const field = form.querySelector(`[name="${fieldName}"]`);
        if (!field) return;
        field.classList.add('is-invalid');
        const feedback = field.parentElement.querySelector('.invalid-feedback') || field.closest('.mb-3, .mb-4')?.querySelector('.invalid-feedback');
        if (feedback && message) feedback.textContent = message;
    }

    // Basic prevention of duplicate empty submissions: disable rapid re-submits
    let lastSubmitTime = 0;

    form.addEventListener('submit', async function (e) {
        e.preventDefault();
        clearFieldErrors();
        alertBox.classList.add('d-none');

        const name = form.name.value.trim();
        const email = form.email.value.trim();
        const budget = form.budget.value.trim();
        const message = form.message.value.trim();

        // ---- Client-side validation ----
        let hasError = false;
        if (name.length < 2) { markFieldError('name'); hasError = true; }
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) { markFieldError('email'); hasError = true; }
        if (!budget) { markFieldError('budget'); hasError = true; }
        if (message.length < 10) { markFieldError('message'); hasError = true; }

        if (hasError) {
            showAlert('Please correct the highlighted fields below.', 'danger');
            return;
        }

        // Prevent double-submit spam within 3 seconds
        const now = Date.now();
        if (now - lastSubmitTime < 3000) return;
        lastSubmitTime = now;

        setLoading(true);

        try {
            const formData = new FormData(form);
            const res = await fetch('api/submit_lead.php', { method: 'POST', body: formData });
            const data = await res.json();

            if (data.success) {
                showAlert(`<i class="fa-solid fa-circle-check me-2"></i>${data.message}`, 'success');
                form.reset();
            } else {
                if (data.errors) {
                    Object.entries(data.errors).forEach(([field, msg]) => markFieldError(field, msg));
                }
                showAlert(`<i class="fa-solid fa-circle-exclamation me-2"></i>${data.message}`, 'danger');
            }
        } catch (err) {
            showAlert('<i class="fa-solid fa-triangle-exclamation me-2"></i>Network error. Please check your connection and try again.', 'danger');
        } finally {
            setLoading(false);
        }
    });
})();
