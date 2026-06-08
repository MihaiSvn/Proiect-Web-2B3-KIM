<section class="reports-trainers">

    <div class="reports-trainers__header">

        <h2 class="reports-trainers__title">
            Top Trainers & Therapists
        </h2>

        <div class="reports-trainers__actions">

            <button
                    id="exportTrainersPng"
                    class="reports-trainers__button">
                PNG
            </button>

            <button
                    id="exportTrainersWebp"
                    class="reports-trainers__button">
                WebP
            </button>

            <button
                    id="exportTrainersCsv"
                    class="reports-trainers__button">
                CSV
            </button>

            <button
                    id="exportTrainersXml"
                    class="reports-trainers__button">
                XML
            </button>

        </div>

    </div>

    <div class="reports-trainers__chart-container">

        <canvas id="trainersChart"></canvas>

    </div>

</section>

<?php

$trainerLabels = [];
$trainerValues = [];

foreach($topTrainers as $trainer){

    $trainerLabels[] =
            $trainer->trainer_name;

    $trainerValues[] =
            (int)$trainer->total_sessions;
}
?>

<script>

    window.trainerLabels =
            <?= json_encode($trainerLabels) ?>;

    window.trainerValues =
            <?= json_encode($trainerValues) ?>;

</script>