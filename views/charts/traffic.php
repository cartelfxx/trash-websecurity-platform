<canvas id="trafficChart"></canvas>
<script>
var ctx = document.getElementById('trafficChart').getContext('2d');
var trafficChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?= json_encode($labels) ?>,
        datasets: [{
            label: 'Traffic',
            data: <?= json_encode($data) ?>,
            borderColor: 'rgba(54, 162, 235, 1)',
            fill: false
        }]
    }
});
</script>
