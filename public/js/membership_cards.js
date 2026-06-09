document.addEventListener('DOMContentLoaded', () => {

    console.log('MEMBERSHIP JS LOADED');

    const membershipTabs =
        document.querySelectorAll(
            '.membership-packages__tab'
        );

    const membershipPanels =
        document.querySelectorAll(
            '.membership-packages__panel'
        );

    if(
        membershipTabs.length > 0 &&
        membershipPanels.length > 0
    ){



        function showPanel(index){

            membershipTabs.forEach(tab => {

                tab.classList.remove(
                    'membership-packages__tab--active'
                );

            });

            membershipPanels.forEach(panel => {

                panel.classList.remove(
                    'membership-packages__panel--active'
                );

            });

            membershipTabs[index].classList.add(
                'membership-packages__tab--active'
            );

            membershipPanels[index].classList.add(
                'membership-packages__panel--active'
            );

        }

        membershipTabs.forEach((tab,index) => {

            tab.addEventListener('click', () => {

                showPanel(index);

            });

        });

    }

    const buttons =
        document.querySelectorAll(
            '.membership-card__button'
        );

    console.log('Buttons found:', buttons.length);

    buttons.forEach(button => {

        button.addEventListener('click', async (e) => {
            e.preventDefault();

            if (!button.classList.contains('membership-card__button--active')) {

                buttons.forEach(btn => {
                    btn.textContent = 'Select';
                    btn.classList.remove('membership-card__button--active');
                });

                button.textContent = 'Buy Now';
                button.classList.add('membership-card__button--active');

            } else {

                const subscriptionId =
                    button.dataset.subscriptionId;


                button.style.pointerEvents = 'none';
                button.disabled = true;
                button.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Processing...';


                console.log(subscriptionId);
                try {
                    const response = await fetch('/kim/api/subscription/purchase', {
                        method: 'POST',
                        headers: {'Content-Type': 'application/json'},
                        body: JSON.stringify({subscription_id: subscriptionId})
                    });

                    const result = await response.json();

                    if (result.status === 'success') {
                        const dashboardUrl = new URL('/kim/dashboard', window.location.origin);
                        dashboardUrl.searchParams.set('success', result.message);

                        window.location.href = dashboardUrl.toString();
                    } else {
                        const currentUrl = new URL(window.location.href);

                        currentUrl.searchParams.delete('success');
                        currentUrl.searchParams.set('error', result.message);

                        window.location.href = currentUrl.toString();
                    }
                } catch (err) {
                    const currentUrl = new URL(window.location.href);
                    currentUrl.searchParams.delete('success');
                    currentUrl.searchParams.set('error', 'Service unavailable');

                    window.location.href = currentUrl.toString();
                }
            }

        });

    });

});
