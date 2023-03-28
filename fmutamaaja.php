<?php
include("syskoneksi.php");
include("myunit.php");

session_start();
 
if($_SESSION["status"] !="login"){
    header("location:index.php");
}

$pagetitle = "SAO WSU | Halaman Utama";
$namahalaman = "Dashboard";

?>
<!DOCTYPE html>
<html lang="en">
<?php include("header.php"); ?> 
<body>
    <div class="dashboard-main-wrapper">
        <?php include("navbar.php"); //external link for navigation bar ?>
        <?php include("menu.php"); // external link for side menu ?>
        <div class="dashboard-wrapper">
            <div class="dashboard-ecommerce">
                <div class="container-fluid dashboard-content ">
                    <?php include("contentheader.php"); // external link for content header ?>
                        <!-- ============================ mulai isi content ================================ -->
                        <div class="row">
                            <!-- ============================================================== -->
                            <!-- data table rowgroup  -->
                            <!-- ============================================================== -->
                            
                            
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="card">
                                    <h5 class="card-header">Grafik Penjualan Yearly</h5>
                                    <div class="card-body">
                                        <div id="curve_chart"></div> 
                                    </div>
                                </div>
                            </div>
                            
                            
                            <!-- ============================================================== -->
                            <!-- end data table rowgroup  -->
                            <!-- ============================================================== -->
                        </div>
                        
                        <!--end-->    
                        
                </div>
            </div>
            <?php include("footer.php"); ?>
        </div>
    </div>
    <?php include("script.php"); ?>

    <?php  
    $thnini = date('Y');
    $thnlalu = date('Y', strtotime('-1 year'));
    
    $query5 = sqlsrv_query($conn,"
    select * from (
    select 1 as urut,'Jan' as month,
    (select isnull(sum(x.ttlkj),0) from artrkoninvpelhd x where convert(varchar(15),transdate,112) between '".$thnini."0101' and '".$thnini."0131' ) as data1,
    (select isnull(sum(x.ttlkj),0) from artrkoninvpelhd x where convert(varchar(15),transdate,112) between '".$thnlalu."0101' and '".$thnlalu."0131' ) as data2
    UNION ALL
    select 2 as urut,'Feb' as month,
    (select isnull(sum(x.ttlkj),0) from artrkoninvpelhd x where convert(varchar(15),transdate,112) between '".$thnini."0201' and '".$thnini."0231' ) as data1,
    (select isnull(sum(x.ttlkj),0) from artrkoninvpelhd x where convert(varchar(15),transdate,112) between '".$thnlalu."0201' and '".$thnlalu."0231' ) as data2
    UNION ALL
    select 3 as urut,'Mar' as month,
    (select isnull(sum(x.ttlkj),0) from artrkoninvpelhd x where convert(varchar(15),transdate,112) between '".$thnini."0301' and '".$thnini."0331' ) as data1,
    (select isnull(sum(x.ttlkj),0) from artrkoninvpelhd x where convert(varchar(15),transdate,112) between '".$thnlalu."0301' and '".$thnlalu."0331' ) as data2
    UNION ALL
    select 4 as urut,'Apr' as month,
    (select isnull(sum(x.ttlkj),0) from artrkoninvpelhd x where convert(varchar(15),transdate,112) between '".$thnini."0401' and '".$thnini."0431' ) as data1,
    (select isnull(sum(x.ttlkj),0) from artrkoninvpelhd x where convert(varchar(15),transdate,112) between '".$thnlalu."0401' and '".$thnlalu."0431' ) as data2
    UNION ALL
    select 5 as urut,'Mei' as month,
    (select isnull(sum(x.ttlkj),0) from artrkoninvpelhd x where convert(varchar(15),transdate,112) between '".$thnini."0501' and '".$thnini."0531' ) as data1,
    (select isnull(sum(x.ttlkj),0) from artrkoninvpelhd x where convert(varchar(15),transdate,112) between '".$thnlalu."0501' and '".$thnlalu."0531' ) as data2
    UNION ALL
    select 6 as urut,'Jun' as month,
    (select isnull(sum(x.ttlkj),0) from artrkoninvpelhd x where convert(varchar(15),transdate,112) between '".$thnini."0601' and '".$thnini."0631' ) as data1,
    (select isnull(sum(x.ttlkj),0) from artrkoninvpelhd x where convert(varchar(15),transdate,112) between '".$thnlalu."0601' and '".$thnlalu."0631' ) as data2
    UNION ALL
    select 7 as urut,'Jul' as month,
    (select isnull(sum(x.ttlkj),0) from artrkoninvpelhd x where convert(varchar(15),transdate,112) between '".$thnini."0701' and '".$thnini."0731' ) as data1,
    (select isnull(sum(x.ttlkj),0) from artrkoninvpelhd x where convert(varchar(15),transdate,112) between '".$thnlalu."0701' and '".$thnlalu."0731' ) as data2
    UNION ALL
    select 8 as urut,'Agu' as month,
    (select isnull(sum(x.ttlkj),0) from artrkoninvpelhd x where convert(varchar(15),transdate,112) between '".$thnini."0801' and '".$thnini."0831' ) as data1,
    (select isnull(sum(x.ttlkj),0) from artrkoninvpelhd x where convert(varchar(15),transdate,112) between '".$thnlalu."0801' and '".$thnlalu."0831' ) as data2
    UNION ALL
    select 9 as urut,'Sep' as month,
    (select isnull(sum(x.ttlkj),0) from artrkoninvpelhd x where convert(varchar(15),transdate,112) between '".$thnini."0901' and '".$thnini."0931' ) as data1,
    (select isnull(sum(x.ttlkj),0) from artrkoninvpelhd x where convert(varchar(15),transdate,112) between '".$thnlalu."0901' and '".$thnlalu."0931' ) as data2
    UNION ALL
    select 10 as urut,'Okt' as month,
    (select isnull(sum(x.ttlkj),0) from artrkoninvpelhd x where convert(varchar(15),transdate,112) between '".$thnini."1001' and '".$thnini."1031' ) as data1,
    (select isnull(sum(x.ttlkj),0) from artrkoninvpelhd x where convert(varchar(15),transdate,112) between '".$thnlalu."1001' and '".$thnlalu."1031' ) as data2
    UNION ALL
    select 11 as urut,'Nov' as month,
    (select isnull(sum(x.ttlkj),0) from artrkoninvpelhd x where convert(varchar(15),transdate,112) between '".$thnini."1101' and '".$thnini."1131' ) as data1,
    (select isnull(sum(x.ttlkj),0) from artrkoninvpelhd x where convert(varchar(15),transdate,112) between '".$thnlalu."1101' and '".$thnlalu."1131' ) as data2
    UNION ALL
    select 12 as urut,'Des' as month,
    (select isnull(sum(x.ttlkj),0) from artrkoninvpelhd x where convert(varchar(15),transdate,112) between '".$thnini."1201' and '".$thnini."1231' ) as data1,
    (select isnull(sum(x.ttlkj),0) from artrkoninvpelhd x where convert(varchar(15),transdate,112) between '".$thnlalu."1201' and '".$thnlalu."1231' ) as data2
    ) as K order by K.urut
    ");
    
    echo '<script>';
    echo 'var my_2d = [';
    while($hasil5 = sqlsrv_fetch_array($query5)){
    echo '{"month":"'.$hasil5[1].'","0":"'.$hasil5[1].'","'.$thnini.'":"'.$hasil5[2].'","1":"'.$hasil5[2].'","'.$thnlalu.'":"'.$hasil5[3].'","2":"'.$hasil5[3].'"},';
    } 
    echo ']';
    echo '</script>';                   
    ?>
    <script type="text/javascript">

        google.charts.load('current', {packages: ['corechart']});
        google.charts.setOnLoadCallback(drawChart);
        
        function drawChart() {

            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Month');
            data.addColumn('number', <?php echo '"'.$thnini.'"'; ?>);
            data.addColumn('number', <?php echo '"'.$thnlalu.'"'; ?>);
            for(i = 0; i < my_2d.length; i++)
            data.addRow([my_2d[i][0], parseInt(my_2d[i][1]),parseInt(my_2d[i][2])]);
            var options = {
                curveType: 'function',
                legend: { position: 'bottom' }
            };

            var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));
            chart.draw(data, options);
        }
    </script>
</body>
</html>