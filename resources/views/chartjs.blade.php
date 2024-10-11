<div style="width: 50%; margin: auto;">
    <canvas id="myLineChart"></canvas>
</div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    var ctx = document.getElementById('myLineChart').getContext('2d');
    var myLineChart = new Chart(ctx, {
        type: 'line', // Specify chart type as 'line'
        data: {
            labels: ['S.Y.2122 - 1st Sem', 'S.Y.2122 - 2nd Sem', 'S.Y.2223 - 1st Sem', 'S.Y.2223 - 2nd Sem',
                'S.Y.2324 - 1st Sem', 'S.Y.2324 - 2nd Sem'
            ], // X-axis labels
            datasets: [{
                label: 'GWA',
                data: [1, 2, 1.5, 2.25, 1, 1.25], // Y-axis data points
                fill: false, // Don't fill under the line
                borderColor: 'rgb(75, 192, 192)', // Line color
                tension: 0.1 // Curve tension for smoothness
            }]
        },
        options: {
            scales: {
                x: {
                    beginAtOne: false
                },
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
