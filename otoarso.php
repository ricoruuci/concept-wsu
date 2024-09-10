<?php
include("syskoneksi.php");
include("myunit.php");

session_start();
 
if($_SESSION["status"] !="login"){
    header("location:index.php");
}

$pagetitle = "SAO WSU | Otorisasi Sales Order";
$namahalaman = "OTORISASI SALES ORDER";

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
                                    <h5 class="card-header">Sales Order</h5>

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

    <!-- ============================ FORM UPDATE ================================ -->

    <div class="modal fade" id="updateModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateModalCenterTitle">Show Data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" id="updata">
                    <div class="modal-body">
                        <!-- Kolom-kolom Data -->

                        <div class="table-responsive">
                            <div id="employees"></div>
                        </div>
                        <div class="table-responsive">
                            <div id="employeess"></div>
                        </div>

                        <!-- Akhir Kolom Data -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"
                            id="up_cancle">Tutup</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ============================ AKHIR FORM UPDATE ================================ -->

    <!-- ============================ FORM DELETE ================================ -->

    <div class="modal fade" id="deleteModalCenter" tabindex="-1" role="dialog" aria-labelledby="deleteModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalCenterTitle">Action</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Konfirmasi Otorisasi ?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" id="de_cancle" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-secondary" id="deleterec">Void</button>
                    <button type="button" class="btn btn-primary" id="setujurec">Approve</button>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================ AKHIR FORM DELETE ================================ -->
    <?php include("script.php"); ?>
    <script>
    var namafile = 'otoarsosys.php';

    $(document).ready(function() {
        //RELOAD TABLE DATA
        $('#tbl_rec').load(namafile + '?action=tampil');
        //INSERT TABLE DATA
        $('#ins_rec').on("submit", function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: namafile + '?action=insert',
                data: $(this).serialize(),
                success: function(vardata) {
                    var json = JSON.parse(vardata);
                    if (json.status == 101) {
                        console.log(json.msg);
                        $('#tbl_rec').load(namafile + '?action=tampil');
                        $('#ins_rec').trigger('reset');
                        $('#close_click').trigger('click');
                    } else {
                        $('#sc_msg').text(json.msg);
                        console.log(json.msg);
                    }
                }
            });
        });
        //GET DATA
        $(document).on("click", "button.editdata", function() {
            var check_id = $(this).data('dataid');
            $('#employees').load(namafile + "?action=getdata&checkid=" + check_id);
            $('#employeess').load(namafile + "?action=getdataa&checkid=" + check_id);
        });

        //DELETE TABLE DATA
        var deleteid;
        $(document).on("click", "button.deletedata", function() {
            deleteid = $(this).data("dataid");
        });
        $('#deleterec').click(function() {
            $.ajax({
                type: 'POST',
                url: namafile + '?action=tolak',
                data: {
                    delete_id: deleteid
                },
                success: function(data) {
                    var json = JSON.parse(data);
                    if (json.status == 0) {
                        $('#tbl_rec').load(namafile + '?action=tampil');
                        $('#de_cancle').trigger("click");
                        console.log(json.msg);
                    } else {
                        console.log(json.msg);
                    }
                }
            });
        });

        $('#setujurec').click(function() {
            $.ajax({
                type: 'POST',
                url: namafile + '?action=setuju',
                data: {
                    delete_id: deleteid
                },
                success: function(data) {
                    var json = JSON.parse(data);
                    if (json.status == 0) {
                        $('#tbl_rec').load(namafile + '?action=tampil');
                        $('#de_cancle').trigger("click");
                        console.log(json.msg);
                    } else {
                        console.log(json.msg);
                    }
                }
            });
        });

    });
    </script>

</body>

</html>