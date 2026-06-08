<section class="reports-analytics">

    <h2 class="reports-analytics__title">
        Analytics Dashboard
    </h2>

    <div class="reports-analytics__cards">

        <div class="reports-analytics__card">

            <div class="reports-analytics__icon">
                <i class="fa-solid fa-users"></i>
            </div>

            <div class="reports-analytics__content">

                <p class="reports-analytics__value">
                    <?= $activeUsers ?>
                </p>

                <p class="reports-analytics__label">
                    Active Users
                </p>

            </div>

        </div>

        <div class="reports-analytics__card">

            <div class="reports-analytics__icon">
                <i class="fa-regular fa-calendar"></i>
            </div>

            <div class="reports-analytics__content">

                <p class="reports-analytics__value">
                    <?= $todaySessions ?>
                </p>

                <p class="reports-analytics__label">
                    Today's Sessions
                </p>

            </div>

        </div>

        <div class="reports-analytics__card">

            <div class="reports-analytics__icon">
                <i class="fa-solid fa-arrow-trend-up"></i>
            </div>

            <div class="reports-analytics__content">

                <p class="reports-analytics__value">
                    <?= $weekSessions ?>
                </p>

                <p class="reports-analytics__label">
                    This Week
                </p>

            </div>

        </div>

        <div class="reports-analytics__card">

            <div class="reports-analytics__icon">
                <i class="fa-solid fa-chart-column"></i>
            </div>

            <div class="reports-analytics__content">

                <p class="reports-analytics__value">
                    <?= $monthSessions ?>
                </p>

                <p class="reports-analytics__label">
                    This Month
                </p>

            </div>

        </div>

    </div>

</section>