<?php
include("syskoneksi.php");
include("myunit.php");

session_start();
 
if($_SESSION["status"] !="login"){
    header("location:index.php");
}

$pagetitle = "SAO WSU | Laporan Hutang Supplier";
$namahalaman = "REKAP HUTANG SUPPLIER";

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
                            <!-- ============================ mulai isi content ================================ -->
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="card">
                                    <h5 class="card-header">Laporan Hutang Supplier</h5>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <div id="tbl_rec"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- ============================ akhir isi content ================================ -->
                        </div>
                    </div>
                </div>
            </div>
            <?php include("footer.php"); ?>
        </div>
    </div>

    <?php include("script.php"); ?>

    <script>
    var namafile = 'rptaprekaphutangsys.php';

    $(document).ready(function (){
    //RELOAD TABLE DATA
    $('#tbl_rec').load(namafile+'?action=tampil');
    });
    </script>

</body>
</html>