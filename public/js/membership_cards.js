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

        button.addEventListener('click', (event) => {

            event.preventDefault();

            if(
                button.textContent.trim() ===
                'Select'
            ){

                buttons.forEach(btn => {

                    btn.textContent = 'Select';

                    btn.classList.remove(
                        'membership-card__button--active'
                    );

                });

                button.textContent =
                    'Buy Now';

                button.classList.add(
                    'membership-card__button--active'
                );

            } else {

                const subscriptionId =
                    button.dataset.subscriptionId;

                const formData =
                    new FormData();

                formData.append(
                    'subscription_id',
                    subscriptionId
                );

                fetch(
                    '/kim/api/subscription/purchase',
                    {
                        method: 'POST',
                        body: formData
                    }
                )
                    .then(response => response.json())
                    .then(data => {

                        if(data.status === 'success') {

                            window.location.href =
                                '/kim/dashboard?success=' +
                                encodeURIComponent(
                                    data.message
                                );

                        } else {

                            window.location.href =
                                '/kim/membership?error=' +
                                encodeURIComponent(
                                    data.message
                                );

                        }

                    });

            }

        });

    });

});