<section class="reports-sessions">

    <div class="reports-sessions__header">

        <h2 class="reports-sessions__title">
            Sessions Per Day
        </h2>

        <div class="reports-sessions__actions">

            <button
                    id="exportPng"
                    class="reports-sessions__button"
            >
                PNG
            </button>

            <button
                    id="exportWebp"
                    class="reports-sessions__button"
            >
                WebP
            </button>

            <button
                    id="exportCsv"
                    class="reports-sessions__button"
            >
                CSV
            </button>

            <button
                    id="exportXml"
                    class="reports-sessions__button"
            >
                XML
            </button>

        </div>

    </div>

    <div class="reports-sessions__chart-container">

        <canvas id="sessionsChart"></canvas>

    </div>

</section>

<?php

$chartLabels = [];
$chartValues = [];

$dataMap = [];

foreach($sessionsPerDay as $row){

    $dataMap[$row->day] =
            (int)$row->total;
}

$monday =
        strtotime('monday this week');

for($i = 0; $i < 7; $i++){

    $date =
            date(
                    'Y-m-d',
                    strtotime("+$i day", $monday)
            );

    $chartLabels[] =
            date(
                    'D',
                    strtotime($date)
            );

    $chartValues[] =
            $dataMap[$date] ?? 0;
}
?>

<script>

    const sessionsLabels =
            <?= json_encode($chartLabels) ?>;

    const sessionsValues =
            <?= json_encode($chartValues) ?>;

</script>
