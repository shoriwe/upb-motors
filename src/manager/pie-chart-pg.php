<?php
require_once '../ensure_login.php';
require_once '../navbar.php';
require_once 'menu-informes.php';

$caja = $_SESSION['caja'];
$banco = $_SESSION['banco'];
$credito = $_SESSION['credito'];
$costos = $_SESSION['costos'];
$gastos = $_SESSION['gastos'];
$dividendos = $_SESSION['dividendos'];
$utilidad_retenida = $_SESSION['utilidad_retenida'];
$impuesto = $_SESSION['impuesto'];
?>
<html>
<div class="centered-container" style="margin-top: 10vh">
    <h1 class="purple-text" style="margin-top: 0.5%;">Graficas</h1>
</div>

<div class="centered-container" style="margin-top: 10vh">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {

            var data = google.visualization.arrayToDataTable([
                ['Razon', 'Valor'],
                ['Ventas',     <?php echo $banco+$caja+$credito;?>],
                ['Costos',      <?php echo $costos;?>],
                ['Gastos',  <?php echo $gastos;?>],
                ['Impuesto', <?php echo $impuesto;?>],
                ['Dividendos',    <?php echo $dividendos;?>]
            ]);

            var options = {
                title: 'Estado de Perdidas y ganancias'
            };

            var chart = new google.visualization.PieChart(document.getElementById('piechart'));

            chart.draw(data, options);
        }
    </script>
    <div id="piechart" style="width: 900px; height: 500px;"></div>
</div>

<div class="centered-container" style="margin-top: 10vh">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load('current', {'packages':['bar']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = google.visualization.arrayToDataTable([
                ['Año', 'Ganancias', 'Perdidas', 'Utilidad Retenida'],
                ['2022', <?php echo $banco+$caja+$credito;?>, <?php echo $costos+$gastos+$impuesto+$dividendos;?>, <?php echo $utilidad_retenida;?>],
            ]);

            var options = {
                chart: {
                    title: 'Estado de Perdidas y ganancias',
                    subtitle: 'Desde 2022',
                }
            };

            var chart = new google.charts.Bar(document.getElementById('columnchart_material'));

            chart.draw(data, google.charts.Bar.convertOptions(options));
        }
    </script>
    <div id="columnchart_material" style="width: 800px; height: 500px;"></div>
</div>

</html>
