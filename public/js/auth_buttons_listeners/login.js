document.addEventListener('DOMContentLoaded', function() {

//VEDEM DACA AVEM UN URL EROARE
    const queryString = window.location.search;

    const urlParams = new URLSearchParams(queryString);

    if (urlParams.has('error')) {
        const errorMessage = urlParams.get('error');

        const errorDiv = document.getElementById('formError');
        const errorText = document.getElementById('errorMessage');

        errorText.innerText = errorMessage;
        errorDiv.style.display = 'flex';
    }

    document.getElementById('loginForm').addEventListener('submit', async function (e) {
        e.preventDefault();

        const errorDiv = document.getElementById('formError');
        const errorText = document.getElementById('errorMessage');

        const formData = {
            email: document.getElementById('email').value,
            password: document.getElementById('password').value
        };


        try {
            const response = await fetch('/kim/api/auth/login', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify(formData)
            });

            const result = await response.json();

            if (response.ok) {
                window.location.href = result.redirect;
            } else {
                errorText.innerText = result.message;
                errorDiv.style.display = 'flex';
            }
        } catch (err) {
            errorText.innerText = "Connection error. Please try again.";
            errorDiv.style.display = 'flex';
        }
    });
});