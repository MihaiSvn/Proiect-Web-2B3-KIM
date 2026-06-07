document.addEventListener('DOMContentLoaded', function() {

    // gasim toate formularele de dismiss
    const dismissForms = document.querySelectorAll('.js-dismiss-form');

    dismissForms.forEach(form => {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();

            const notificationId = this.querySelector('input[name="notification_id"]').value;
            const submitBtn = this.querySelector('button[type="submit"]');

            submitBtn.disabled = true;

            try {
                const response = await fetch('/kim/api/notifications/dismiss', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ notification_id: notificationId })
                });

                const result = await response.json();

                if (result.status === 'success') {
                    const currentUrl = new URL(window.location.href);

                    currentUrl.searchParams.delete('error');
                    currentUrl.searchParams.set('success', result.message);

                    window.location.href = currentUrl.toString();
                } else {
                    const currentUrl = new URL(window.location.href);

                    currentUrl.searchParams.delete('success');
                    currentUrl.searchParams.set('error', result.message);

                    window.location.href = currentUrl.toString();
                    submitBtn.disabled = false;
                }
            } catch (err) {
                const currentUrl = new URL(window.location.href);
                currentUrl.searchParams.delete('success');
                currentUrl.searchParams.set('error', 'Service unavailable');

                window.location.href = currentUrl.toString();
                submitBtn.disabled = false;
            }
        });
    });

    const dismissAllForm = document.querySelector('.js-dismiss-all-form');

    if (dismissAllForm) {
        dismissAllForm.addEventListener('submit', async function(e) {
            e.preventDefault();

            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.disabled = true;

            try {

                const response = await fetch('/kim/api/notifications/dismiss-all', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' }
                });

                const result = await response.json();

                if (result.status === 'success') {
                    const currentUrl = new URL(window.location.href);

                    currentUrl.searchParams.delete('error');
                    currentUrl.searchParams.set('success', result.message);

                    window.location.href = currentUrl.toString();
                } else {
                    const currentUrl = new URL(window.location.href);

                    currentUrl.searchParams.delete('success');
                    currentUrl.searchParams.set('error', result.message);

                    window.location.href = currentUrl.toString();
                    submitBtn.disabled = false;
                }
            } catch (err) {
                const currentUrl = new URL(window.location.href);
                currentUrl.searchParams.delete('success');
                currentUrl.searchParams.set('error', 'Service unavailable');

                window.location.href = currentUrl.toString();
                submitBtn.disabled = false;
            }
        });
    }
});