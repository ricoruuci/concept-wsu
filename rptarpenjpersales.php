<?php
include("syskoneksi.php");
include("myunit.php");

session_start();
 
if($_SESSION["status"] !="login"){
    header("location:index.php");
}

$pagetitle = "SAO WSU | Laporan Penjualan Per Sales";
$namahalaman = "LAPORAN PENJUALAN PER SALES ";

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
                    <div class="ecommerce-widget">
                        <div class="row">
                            <!-- ============================================================== -->
                            <!-- data table rowgroup  -->
                            <!-- ============================================================== -->
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0">Laporan Penjualan Per Sales</h5>
                                        <div class="form-group row">
                                            <div class="col-5 col-lg-3">
                                                <input id="tgldari" type="date" value="<?php echo date('Y-m-d'); ?>" placeholder="dd/mm/yyyy" class="form-control">
                                            </div>
                                            <label class="form-label">s/d</label>
                                            <div class="col-5 col-lg-3">
                                                <input id="tglsmp" type="date" value="<?php echo date('Y-m-d'); ?>" placeholder="dd/mm/yyyy" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                                <div id="tbl_rec"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- ============================================================== -->
                            <!-- end data table rowgroup  -->
                            <!-- ============================================================== -->
                        </div>
                    </div>
                </div>
            </div>
            <?php include("footer.php"); ?>
        </div>
    </div>

    <?php include("script.php"); ?>

    <script>
    var namafile = 'rptarpenjpersalessys.php';

    $(document).ready(function (){
    //RELOAD TABLE DATA
    var dari = document.getElementById('tgldari').value;
    var smpe = document.getElementById('tglsmp').value;

    $('#tbl_rec').load(namafile+'?action=tampil&dari='+dari+'&smp='+smpe);

    });

    $(document).change(function (){
    //RELOAD TABLE DATA
    var dari = document.getElementById('tgldari').value;
    var smpe = document.getElementById('tglsmp').value;

    $('#tbl_rec').load(namafile+'?action=tampil&dari='+dari+'&smp='+smpe);

    });
            
    </script>
    
</body>
</html>