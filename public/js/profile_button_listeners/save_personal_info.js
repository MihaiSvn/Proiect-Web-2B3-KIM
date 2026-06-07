
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('updateProfileForm').addEventListener('submit', function (e) {
        e.preventDefault();

        const form = this;
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalBtnText = submitBtn.innerHTML;

        const formData = new FormData(form);

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Saving...';

        fetch('/kim/api/user/update', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(result => {
                const redirectUrl = window.location.pathname + '?' + result.status + '=' + encodeURIComponent(result.message);
                window.location.href = redirectUrl;
            })
            .catch(error => {
                window.location.href = window.location.pathname + '?error=Service unavailable';
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
            });
    });
});