document.addEventListener('DOMContentLoaded', function() {

    document.getElementById('changePasswordForm').addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(this);
        const data = Object.fromEntries(formData.entries());

        const submitBtn = document.getElementById("changePassBtn");
        if(submitBtn){
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Processing...';
        }


        fetch('/kim/api/user/change-password', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(data)
        })
            .then(response => response.json())
            .then(result => {

                const redirectUrl = '/kim/profile/settings?' + result.status + '=' + encodeURIComponent(result.message);

                window.location.href = redirectUrl;
            })
            .catch(error => {
                window.location.href = '/kim/profile/settings?error=Service unavailable';
            });
    });
});