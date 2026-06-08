<section class="reports-distribution">

    <div class="reports-distribution__header">

        <h2 class="reports-distribution__title">
            Membership Distribution
        </h2>

        <div class="reports-distribution__actions">

            <button
                    id="distributionExportPng"
                    class="reports-distribution__button">
                PNG
            </button>

            <button
                    id="distributionExportWebp"
                    class="reports-distribution__button">
                WebP
            </button>

            <button
                    id="distributionExportCsv"
                    class="reports-distribution__button">
                CSV
            </button>

            <button
                    id="distributionExportXml"
                    class="reports-distribution__button">
                XML
            </button>

        </div>

    </div>

    <div class="reports-distribution__chart-container">

        <canvas id="distributionChart"></canvas>

    </div>

</section>

<?php

$distributionLabels = [];
$distributionValues = [];

foreach($subscriptionStats as $row){

    $distributionLabels[] =
            $row->type;

    $distributionValues[] =
            (int)$row->total;
}
?>

<script>

    const distributionLabels =
            <?= json_encode($distributionLabels) ?>;

    const distributionValues =
            <?= json_encode($distributionValues) ?>;

</script>