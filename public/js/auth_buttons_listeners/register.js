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

    document.getElementById('registerForm').addEventListener('submit', async function (e) {
        e.preventDefault();

        const errorDiv = document.getElementById('formError');
        const errorText = document.getElementById('errorMessage');

        const formData = {
            first_name: document.getElementById('form__firstname').value,
            last_name: document.getElementById('form__lastname').value,
            email: document.getElementById('form__email').value,
            password: document.getElementById('form__password').value,
            confirm_password: document.getElementById('form__confirm__password').value
        };

        try {
            const response = await fetch('/kim/api/auth/register', {
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
            errorText.innerText = "A server error occurred. Please try again later.";
            errorDiv.style.display = 'flex';
        }
    });
});