document.addEventListener("DOMContentLoaded", () => {
    const roleSelect = document.getElementById('role');
    const specSelect = document.getElementById('specialization');

    if (roleSelect && specSelect) {
        // div ul care contine label ul si select ul ca sa l pot sterge
        const specGroup = specSelect.closest('.form__group');

        function toggleSpecialization() {
            if (roleSelect.value === 'trainer') {
                specGroup.style.display = 'flex';
                specSelect.required = true;
            } else {
                specGroup.style.display = 'none';
                specSelect.required = false;
                specSelect.value = '';
            }
        }

        roleSelect.addEventListener('change', toggleSpecialization);
        toggleSpecialization();

    }

    const roleFilter = document.getElementById('roleFilter');

    //iau toti userii din body
    const tableRows = document.querySelectorAll('.management__table tbody tr');

    if (roleFilter) {
        roleFilter.addEventListener('change', function() {

            // iau valaorea de member/all/trainer
            const selectedRole = this.value;

            tableRows.forEach(row => {

                if (selectedRole === 'all' || row.classList.contains('table_' + selectedRole)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });

        });
    }


    //PENTRU POPUP URI

    const allForms = document.querySelectorAll('.js-api-form');

    allForms.forEach(form => {
        const roleSelect = form.querySelector('select[name="role"]');
        const specSelect = form.querySelector('select[name="specialization"]');

        if (roleSelect && specSelect) {
            const specGroup = specSelect.closest('.form__group');

            function updateVisibility() {
                if (roleSelect.value === 'trainer') {
                    specGroup.style.display = 'flex';
                    specSelect.required = true;
                } else {
                    specGroup.style.display = 'none';
                    specSelect.required = false;
                }
            }

            updateVisibility();

            roleSelect.addEventListener('change', updateVisibility);
        }
    });
});
