document.addEventListener('DOMContentLoaded', () => {

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

    buttons.forEach(button => {

        button.addEventListener('click', () => {

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

            }else{

                window.location.href =
                    '/kim/dashboard?success=' +
                    encodeURIComponent(
                        'Membership purchased successfully!'
                    );
            }

        });

    });

});