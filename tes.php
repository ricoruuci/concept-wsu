<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Line Chart Example</title>
    <!-- Load Google Charts library -->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
</head>
<body>

<!-- Container for the chart -->
<div id="curve_chart" style="width: 900px; height: 500px"></div>

<!-- Container for total information -->
<div id="totals"></div>

<!-- Script to generate chart -->
<script type="text/javascript">
    google.charts.load('current', {packages: ['corechart']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        // Data (bulan Januari sampai Desember)
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Month');
        data.addColumn('number', '2023');
        data.addColumn('number', '2022');

        var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        var totals = { '2023': 0, '2022': 0 };

        // Generate random data for demonstration
        for (var i = 0; i < months.length; i++) {
            var value2023 = Math.floor(Math.random() * 1000);
            var value2022 = Math.floor(Math.random() * 1000);

            data.addRow([months[i], value2023, value2022]);

            totals['2023'] += value2023;
            totals['2022'] += value2022;
        }

        var options = {
            curveType: 'function',
            legend: { position: 'bottom' }
        };

        var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));
        chart.draw(data, options);

        // Display total information
        document.getElementById('totals').innerHTML = 'Total 2023: ' + totals['2023'] +
                                                        ' | Total 2022: ' + totals['2022'];
    }
</script>

</body>
</html>
