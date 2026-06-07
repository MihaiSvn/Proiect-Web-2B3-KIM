document.addEventListener('DOMContentLoaded', function() {

    document.getElementById('logoutLink').addEventListener('click', async function (e) {
    e.preventDefault();

    try {
        const response = await fetch('/kim/api/auth/logout', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'}
        });

        const result = await response.json();

        if (response.ok) {
            window.location.href = result.redirect;
        }
    } catch (err) {
        console.error("Logout failed:", err);
        window.location.href = '/kim/login';
    }
});
});
