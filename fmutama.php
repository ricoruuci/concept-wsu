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
                                    <div id="totals"></div>
                                </div>
                            </div>
                            
                            <!-- ============================================================== -->
                            <!-- end data table rowgroup  -->
                            <!-- ============================================================== -->
                        </div>
                        <div class="row">
                            <!-- ============================================================== -->
                            <!-- data table rowgroup  -->
                            <!-- ============================================================== -->
                                                        
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="card">
                                <h5 class="card-header">Grafik Penjualan Monthly</h5>
                                <div class="card-body">
                                    <div id="chartContainer" style="height: 370px; width: 100%;"></div>
                                </div>
                                </div>
                            </div>
                            
                            <!-- ============================================================== -->
                            <!-- end data table rowgroup  -->
                            <!-- ============================================================== -->
                        </div>
                        <!-- ============================ mulai isi content ================================ -->
                        <?php

                            $query = sqlsrv_query($conn,"
                            SELECT 
                            ISNULL(SUM(CASE WHEN X.Overdue < 0 THEN X.Sisa ELSE 0 END),0) as BlmJth,
                            ISNULL(SUM(CASE WHEN X.Overdue BETWEEN 1 AND 30 THEN X.Sisa ELSE 0 END),0) as '1sd30',
                            ISNULL(SUM(CASE WHEN X.Overdue BETWEEN 31 AND 90 THEN X.Sisa ELSE 0 END),0) as '31sd90',
                            ISNULL(SUM(CASE WHEN X.Overdue > 90 THEN X.Sisa ELSE 0 END),0) as 'over90',
                            ISNULL((select COUNT(*) as Jumlah FROM (
                            select A.POID,A.FgOto from APTrPurchaseOrderHd A UNION ALL
                            select A.GBUID,A.FGOto from ARTrPenawaranHd A 
                            ) as K WHERE ISNULL(K.FgOto,'T')='T'),0) as JumPO,
                            
                            ISNULL((select COUNT(*) from APTrPurchaseRequestHd A inner join ARMsSales B on A.SalesID=B.SalesID 
                            where FgSubmit='Y' AND FgSales='K' AND A.FgOto='T'),0) as JumPR,
                            
                            ISNULL((SELECT COUNT(*) FROM ( 
                            SELECT A.POID,A.TTLSO FROM ARTrPurchaseOrderHd A 
                            where A.FgOto IN ('T','O','L','OL','OB','OLB','LB') 
                            )as K),0) as JumSO,
                            
                            ISNULL((SELECT SUM(K.TTLSO) FROM ( 
                            SELECT A.POID,A.TTLSO FROM ARTrPurchaseOrderHd A 
                            where A.FgOto IN ('T','O','L','OL','OB','OLB','LB') 
                            )as K),0) as TotalSO,
                            
                            ISNULL((
                            SELECT ISNULL(SUM(K.Total-Bayar-Retur),0) as Sisa
                            FROM (  
                            SELECT A.KonsinyasiInvID,A.TglJthTempo,A.SuppID,A.Transdate,ISNULL(A.TTLKs*A.Rate,0) as Total,A.CurrID,A.JatuhTempo,
                            ISNULL((SELECT ISNULL(ROUND(CASE WHEN Z.FgTax='Y' THEN SUM(X.Qty*X.Price)*Z.NilaiPPN*0.01 ELSE SUM(X.Qty*X.Price) END,0),0) as Retur 
                            FROM APTrReturnDt X  INNER JOIN APTrReturnHd Y ON X.ReturnID=Y.ReturnID  INNER JOIN APTrKonsinyasiInvHd Z on X.PurchaseID=Z.KonsinyasiInvID
                            WHERE X.PurchaseID=A.KonsinyasiInvID AND Y.SuppID=A.SuppID GROUP BY Z.FgTax,Z.NilaiPPN),0) as Retur,
                            (SELECT ISNULL(SUM(CASE WHEN L.Jenis='D' THEN L.Amount*Q.Rate ELSE L.Amount*Q.Rate-1 END),0)  FROM CFTrKKBBDt L 
                            INNER JOIN CFTrKKBBHd Q ON L.VoucherID=Q.VoucherID WHERE L.Note=A.KonsinyasiInvID AND Q.FgPayment='T') as Bayar 
                            FROM APTrKonsinyasiInvHd A  
                            ) as K  
                            WHERE CONVERT(VARCHAR(8),K.Transdate,112) <= CONVERT(VARCHAR(8),GETDATE(),112)  AND ISNULL(K.Total-K.Bayar-K.Retur,0)<>0  
                            ),0) as Debt,

                            ISNULL((SELECT COUNT(*) FROM CFTrKKBBHd A WHERE ISNULL(A.FgPayment,'T')='F'),0) as JumFin

                            
                            FROM (
                            SELECT K.KonInvPelID,ISNULL(K.Total-K.Retur-K.Bayar,0) as Sisa,CASE WHEN DATEDIFF(day,K.TglJthTempo,getdate())>0 then DATEDIFF(day,K.TglJthTempo,getdate()) else 0 end as Overdue
                            FROM ( 
                            SELECT A.CustID,A.KonInvPelID,A.Transdate,ISNULL(A.TTLKj,0) as Total,A.TransDate + A.JatuhTempo as TglJthTempo,A.JatuhTempo,A.SalesID,  
                            ISNULL((SELECT ISNULL(ROUND(CASE WHEN Z.FgTax='Y' THEN SUM(X.Qty*X.Price)*Z.Tax*0.01 ELSE SUM(X.Qty*X.Price) END,0),0) as Retur  
                            FROM ARtrReturPenjualanDt X INNER JOIN ARtrReturPenjualanHd Y ON X.ReturnID=Y.ReturnID Inner join artrkoninvpelhd Z on X.SaleID=Z.KonInvpelId  
                            WHERE X.SaleID=A.KonInvPelID AND Y.CustID=A.CustID GROUP BY Z.FgTax,Z.Tax),0) as Retur,A.CurrID,  
                            (SELECT ISNULL(SUM(CASE WHEN L.Jenis='K' THEN L.Amount ELSE L.Amount-1 END),0) FROM CFTrKKBBDt L 
                            INNER JOIN CFTrKKBBHd Q ON L.VoucherID=Q.VoucherID  WHERE L.Note = A.KonInvPelID  AND Q.FgPayment='T' ) as Bayar
                            FROM ARTrKonInvPelHd A 
                            ) as K
                            WHERE CONVERT(VARCHAR(8),K.Transdate,112) <= CONVERT(VARCHAR(8),GETDATE(),112) AND ISNULL(K.Total-K.Retur-K.Bayar,0)<>0 
                            ) as X 
                            ");
                            while($hasil = sqlsrv_fetch_array($query)){	

                        ?>
                        
                        <div class="row">
                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">   
                                    <div class="card border-3 border-top border-top-primary">
                                        <div class="card-body">
                                            <h5 class="text-muted">Account Receivable</h5>
                                            <h5 class="text-muted">< 0</h5>
                                            <div class="metric-value d-inline-block">
                                                <h1 class="mb-1"><?php echo number_format($hasil[0],2,".",","); ?></h1>
                                            </div>
                                            <div class="metric-label d-inline-block float-right text-success font-weight-bold">
                                                <span class="badge-dot badge-success mr-1"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                    <div class="card border-3 border-top border-top-primary">
                                        <div class="card-body">
                                            <h5 class="text-muted">Account Receivable</h5>
                                            <h5 class="text-muted">1 - 30</h5>
                                            <div class="metric-value d-inline-block">
                                                <h1 class="mb-1"><?php echo number_format($hasil[1],2,".",","); ?></h1>
                                            </div>
                                            <div class="metric-label d-inline-block float-right text-success font-weight-bold">
                                                <span class="badge-dot badge-primary mr-1"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                    <div class="card border-3 border-top border-top-primary">
                                        <div class="card-body">
                                        <h5 class="text-muted">Account Receivable</h5>
                                            <h5 class="text-muted">31 - 90</h5>
                                            <div class="metric-value d-inline-block">
                                                <h1 class="mb-1"><?php echo number_format($hasil[2],2,".",","); ?></h1>
                                            </div>
                                            <div class="metric-label d-inline-block float-right text-success font-weight-bold">
                                                <span class="badge-dot badge-warning mr-1"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                    <div class="card border-3 border-top border-top-primary">
                                        <div class="card-body">
                                            <h5 class="text-muted">Account Receivable</h5>
                                            <h5 class="text-muted">> 90</h5>
                                            <div class="metric-value d-inline-block">
                                                <h1 class="mb-1"><?php echo number_format($hasil[3],2,".",","); ?></h1>
                                            </div>
                                            <div class="metric-label d-inline-block float-right text-danger font-weight-bold">
                                                <span class="badge-dot badge-danger mr-1"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>                 
                            </div>

                            <div class="row">
                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">   
                                    <div class="card border-3 border-top border-top-primary">
                                        <div class="card-body">
                                            <h5 class="text-muted">Outstanding Otorisasi SO</h5>
                                            <h5 class="text-muted">Total</h5>
                                            <div class="metric-value d-inline-block">
                                                <h1 class="mb-1"><?php echo number_format($hasil[7],2,".",","); ?></h1>
                                            </div>
                                            <div class="metric-label d-inline-block float-right text-success font-weight-bold">
                                                <span class="badge-dot badge-info mr-1"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                    <div class="card border-3 border-top border-top-primary">
                                        <div class="card-body">
                                            <h5 class="text-muted">Outstanding Otorisasi SO</h5>
                                            <h5 class="text-muted">Count</h5>
                                            <div class="metric-value d-inline-block">
                                                <h1 class="mb-1"><?php echo number_format($hasil[6],2,".",","); ?></h1>
                                            </div>
                                            <div class="metric-label d-inline-block float-right text-success font-weight-bold">
                                                <span class="badge-dot badge-info mr-1"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                    <div class="card border-3 border-top border-top-primary">
                                        <div class="card-body">
                                        <h5 class="text-muted">Outstanding Otorisasi PR</h5>
                                            <h5 class="text-muted">Count</h5>
                                            <div class="metric-value d-inline-block">
                                                <h1 class="mb-1"><?php echo number_format($hasil[5],2,".",","); ?></h1>
                                            </div>
                                            <div class="metric-label d-inline-block float-right text-success font-weight-bold">
                                                <span class="badge-dot badge-info mr-1"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                    <div class="card border-3 border-top border-top-primary">
                                        <div class="card-body">
                                            <h5 class="text-muted">Outstanding Otorisasi PO</h5>
                                            <h5 class="text-muted">Count</h5>
                                            <div class="metric-value d-inline-block">
                                                <h1 class="mb-1"><?php echo number_format($hasil[4],2,".",","); ?></h1>
                                            </div>
                                            <div class="metric-label d-inline-block float-right text-danger font-weight-bold">
                                                <span class="badge-dot badge-info mr-1"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>                 
                            </div>

                            <div class="row">
                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12"> 
                                    <div class="card border-3 border-top border-top-primary">
                                        <div class="card-body">
                                            <h5 class="text-muted">Outstanding Otorisasi Finance</h5>
                                            <h5 class="text-muted">Count</h5>
                                            <div class="metric-value d-inline-block">
                                                <h1 class="mb-1"><?php echo number_format($hasil[9],0,".",","); ?></h1>
                                            </div>
                                            <div class="metric-label d-inline-block float-right text-success font-weight-bold">
                                                <span class="badge-dot badge-secondary mr-1"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">   
                                    <div class="card border-3 border-top border-top-primary">
                                        <div class="card-body">
                                            <h5 class="text-muted">Account Payable</h5>
                                            <h5 class="text-muted">Total</h5>
                                            <div class="metric-value d-inline-block">
                                                <h1 class="mb-1"><?php echo number_format($hasil[8],2,".",","); ?></h1>
                                            </div>
                                            <div class="metric-label d-inline-block float-right text-success font-weight-bold">
                                                <span class="badge-dot badge-secondary mr-1"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                                 
                            </div>

                        <?php 
                        } 
                        
                        ?>
                        
                        <!--end-->    
                        <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                <div class="card">
                                    <h5 class="card-header">Stock Gudang Raw</h5>
                                    <div class="card-body">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Nama Group</th>
                                                    <td scope="col" align='right'><b>Stock</b></td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php  
                                            
                                            $query7 = sqlsrv_query($conn,"
                                            select x.productdesc, sum(k.stock) as total_kg from (
                                            select a.itemid, b.productid, isnull(sum(case when a.fgtrans<50 then a.qty else a.qty*-1 end),0) as stock from allitem a 
                                            inner join inmsitem b on a.itemid=b.itemid 
                                            inner join inmswarehouse c on a.warehouseid=c.warehouseid where c.fgraw='y' and b.fgmaster='rm'
                                            group by a.itemid, b.productid
                                            ) as k inner join inmsproduct x on k.productid=x.productid
                                            group by x.productdesc");

                                            while($hasil7 = sqlsrv_fetch_array($query7)){
                                                echo "<tr>";
                                                echo "<th scope='row'>".$hasil7[0]."</th>";
                                                echo "<td scope='row' align='right'>".number_format($hasil7[1],2,".",",")."</td>";
                                                echo "</tr>";
                                            }                    
                                            ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                <div class="card">
                                    <h5 class="card-header">Stock Gudang</h5>
                                    <div class="card-body">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Nama Gudang</th>
                                                    <td scope="col" align='right'><b>Stock</b></td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php  
                                            
                                            $query8 = sqlsrv_query($conn,"
                                            select x.warehousename, sum(k.stock*k.panjang) as total_meter from (
                                            select a.itemid, a.warehouseid, b.panjang, isnull(sum(case when a.fgtrans<50 then a.qty else a.qty*-1 end),0) as stock from allitem a 
                                            inner join inmsitem b on a.itemid=b.itemid group by a.warehouseid, a.itemid, b.panjang
                                            ) as k inner join inmswarehouse x on k.warehouseid=x.warehouseid where x.fgraw='t'
                                            group by x.warehousename");

                                            while($hasil8 = sqlsrv_fetch_array($query8)){
                                                echo "<tr>";
                                                echo "<th scope='row'>".$hasil8[0]."</th>";
                                                echo "<td scope='row' align='right'>".number_format($hasil8[1],2,".",",")."</td>";
                                                echo "</tr>";
                                            }                    
                                            ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                </div>
            </div>
            <?php include("footer.php"); ?>
        </div>
    </div>
    <?php include("script.php"); ?>

    <?php

    $thnini = date('Y');
    $thnlalu = date('Y', strtotime('-1 year'));

    $query5 = sqlsrv_query($conn, "
        SELECT MONTH(transdate) as month,
            ISNULL(SUM(CASE WHEN YEAR(transdate) = $thnini THEN ttlkj ELSE 0 END),0) AS data1,
            ISNULL(SUM(CASE WHEN YEAR(transdate) = $thnlalu THEN ttlkj ELSE 0 END),0) AS data2
        FROM artrkoninvpelhd
        WHERE YEAR(transdate) IN ($thnini, $thnlalu)
        GROUP BY MONTH(transdate)
        ORDER BY MONTH(transdate)
    ");
    
    ?>
    
    <script type="text/javascript">

    google.charts.load('current', {packages: ['corechart']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        // Data (bulan Januari sampai Desember)
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Month');
        data.addColumn('number', '<?php echo $thnini; ?>');
        data.addColumn('number', '<?php echo $thnlalu; ?>');

        var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        var totals = { '<?php echo $thnini; ?>': 0, '<?php echo $thnlalu; ?>': 0 };

        
        <?php
        $i = 0;
        while($hasil5 = sqlsrv_fetch_array($query5)) { 
        ?>
            data.addRow([months[<?php echo $i; ?>], <?php echo $hasil5[1]; ?>, <?php echo $hasil5[2]; ?>]);
            totals["<?php echo $thnini; ?>"] += <?php echo $hasil5[1]; ?>;
            totals["<?php echo $thnlalu; ?>"] += <?php echo $hasil5[2]; ?>;
            
        <?php
            $i++;
        }
        ?>

        var options = {
            curveType: 'function',
            legend: { position: 'bottom' }
        };

        var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));
        chart.draw(data, options);

        // Display total information
        document.getElementById('totals').innerHTML = 'Total <?php echo $thnini; ?>: ' + totals['<?php echo $thnini; ?>'].toLocaleString() +
                                                        '<br/> Total <?php echo $thnlalu; ?>: ' + totals['<?php echo $thnlalu; ?>'].toLocaleString();
    }
</script>


    <script>
    window.onload = function () {
    
    var chart = new CanvasJS.Chart("chartContainer", {
        animationEnabled: true,
        theme: "light2",
        title:{
            text: "Grafik Penjualan Monthly"
        },
        axisY:{
            includeZero: true
        },
        legend:{
            cursor: "pointer",
            verticalAlign: "center",
            horizontalAlign: "right",
            itemclick: toggleDataSeries
        },
        data: [
            <?php
            $blnini = date('Ym');
            $blnlalu = date('Ym', strtotime('-1 month'));
            $blnlusa = date('Ym', strtotime('-2 month')); 

            /*$query4 = sqlsrv_query($conn,"
            select * from (
            select A.salesid,A.SalesName,
            (select isnull(sum(x.ttlkj),0) from artrkoninvpelhd x where convert(varchar(15),transdate,112) between '".$blnini."01' and '".$blnini."31' and X.salesid=A.salesid ) as data1,
            (select isnull(sum(x.ttlkj),0) from artrkoninvpelhd x where convert(varchar(15),transdate,112) between '".$blnlalu."01' and '".$blnlalu."31' and X.salesid=A.salesid ) as data2,
            (select isnull(sum(x.ttlkj),0) from artrkoninvpelhd x where convert(varchar(15),transdate,112) between '".$blnlusa."01' and '".$blnlusa."31' and X.salesid=A.salesid ) as data3	
            from armssales A
            ) as K where K.data1+K.data2+K.data3<>0 order by K.SalesName
            ");*/

            $query4 = sqlsrv_query($conn,"
            select K.GroupID,L.GroupDesc,SUM(data1) as data1,SUM(data2) as Data2,SUM(data3) as data3 from (
            select 'A' as GroupID,A.salesid,A.SalesName,
            (select isnull(sum(x.ttlkj),0) from artrkoninvpelhd x where convert(varchar(15),transdate,112) between '20230101' and '20230531' and X.salesid=A.salesid ) as data1,
            (select isnull(sum(x.ttlkj),0) from artrkoninvpelhd x where convert(varchar(15),transdate,112) between '20230201' and '20230231' and X.salesid=A.salesid ) as data2,
            (select isnull(sum(x.ttlkj),0) from artrkoninvpelhd x where convert(varchar(15),transdate,112) between '20230301' and '20230331' and X.salesid=A.salesid ) as data3	
            from armssales A
            ) as K 
            --left join armsgroupsales L on K.groupID=L.GroupID
            left join (select 'A' as GroupID,'TEST' as GroupDesc) L on K.groupID=L.GroupID
            where K.data1+K.data2+K.data3<>0 
            GROUP BY K.GroupID,L.GroupDesc
            order by L.GroupDesc
            ");

            $abd = 1;

            while($hasil4 = sqlsrv_fetch_array($query4)){ 

            $dataPoints1 = array(
                array("label"=> $blnini, "y"=> $hasil4[2]),
                array("label"=> $blnlalu, "y"=> $hasil4[3]),
                array("label"=> $blnlusa, "y"=> $hasil4[4]),
            
            );

            echo '{';
            echo 'type: "column",';
            echo 'name: "'.$hasil4[1].'",';
            echo 'indexLabel: "{y}",';
            echo 'yValueFormatString: "#,##0.00",';
            echo 'showInLegend: true,';
            ?>
            dataPoints: <?php echo json_encode($dataPoints1, JSON_NUMERIC_CHECK); ?>
            <?php
            echo '},';
            
            $abd = $abd + 1;

            }
            ?>
        ]
    });
    chart.render();
    
    function toggleDataSeries(e){
        if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
            e.dataSeries.visible = false;
        }
        else{
            e.dataSeries.visible = true;
        }
        chart.render();
    }
    
    }
    </script>
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
</body>
</html>