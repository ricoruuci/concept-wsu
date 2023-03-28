<?php
include("syskoneksi.php");
include("myunit.php");

session_start();
 
if($_SESSION["status"] !="login"){
    header("location:index.php");
}

$pagetitle = "Master Pelanggan";
$namahalaman = "Master Pelanggan";

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
                                    <h5 class="card-header">Master Pelanggan</h5>
                                
                                    

                                    
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
    

    <!-- ============================ FORM INSERT ================================ -->

    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Tambah Data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" id="ins_rec">
                <div class="modal-body">
                    <!-- Kolom-kolom Data -->

                    <div class="form-group">
                        <label><b>ItemID</b></label>
                        <input type="text" name="ItemID" class="form-control" placeholder="">
                        <span class="error-msg" id="msg_1"></span>
                    </div>
                    <div class="form-group">
                        <label><b>ItemName</b></label>
                        <input type="text" name="ItemName" class="form-control" placeholder="">
                        <span class="error-msg" id="msg_2"></span>
                    </div>
                    <div class="form-group">
                        <label><b>GroupID</b></label>
                        <input type="text" name="GroupID" class="form-control" placeholder="">
                        <span class="error-msg" id="msg_3"></span>
                    </div>
                    <div class="form-group">
                        <label><b>CurrID</b></label>
                        <input type="text" name="CurrID" class="form-control" placeholder="">
                        <span class="error-msg" id="msg_4"></span>
                    </div>
                    <div class="form-group">
                        <span class="success-msg" id="sc_msg"></span>
                    </div>
                    <!-- Akhir Kolom Data -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="close_click" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" >Tambah</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ============================ AKHIR FORM INSERT ================================ -->

    <!-- ============================ FORM UPDATE ================================ -->

    <div class="modal fade" id="updateModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateModalCenterTitle">Ubah Data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" id="updata">
                <div class="modal-body">
                    <!-- Kolom-kolom Data -->

                     <div class="form-group">
                        <label><b>ItemID</b></label>
                        <input type="text" class="form-control" name="ItemID" id="upd_1" class="form-control" placeholder="" readonly="true">
                        <span class="error-msg" id="umsg_1"></span>
                    </div>
                    <div class="form-group">
                        <label><b>ItemName</b></label>
                        <input type="text" class="form-control" name="ItemName" id="upd_2"  class="form-control" placeholder="">
                        <span class="error-msg" id="umsg_2"></span>
                    </div>
                    <div class="form-group">
                        <label><b>GroupID</b></label>
                        <input type="text" class="form-control" name="GroupID" id="upd_3"  class="form-control" placeholder="">
                        <span class="error-msg" id="umsg_3"></span>
                    </div>
                    <div class="form-group">
                        <label><b>CurrID</b></label>
                        <input type="text" class="form-control" name="CurrID" id="upd_4"  class="form-control" placeholder="">
                        <span class="error-msg" id="umsg_4"></span>
                    </div>


                    <!-- Akhir Kolom Data -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" id="up_cancle">Batal</button>
                    <button type="submit" class="btn btn-primary">Ubah</button>
                </div>
                </form>   
            </div>
        </div>
    </div>  

    <!-- ============================ AKHIR FORM UPDATE ================================ -->

    <!-- ============================ FORM DELETE ================================ -->

    <div class="modal fade" id="deleteModalCenter" tabindex="-1" role="dialog" aria-labelledby="deleteModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalCenterTitle">Hapus Data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                     </button>
                </div>
                <div class="modal-body">
                    <p>Apakah kamu yakin menghapus data ini ?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="de_cancle" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="deleterec">Hapus</button>
                </div>
            </div>
        </div>
    </div>  

    <!-- ============================ AKHIR FORM DELETE ================================ -->
  <?php include("script.php"); ?>
    <script>
    var namafile = 'inmsitemsys.php';

    $(document).ready(function (){
    //RELOAD TABLE DATA
    $('#tbl_rec').load(namafile+'?action=tampil');
    //INSERT TABLE DATA
    $('#ins_rec').on("submit", function(e){
        e.preventDefault();
        $.ajax({
            type:'POST',
            url: namafile+'?action=insert',
            data:$(this).serialize(),
            success:function(vardata){
                var json = JSON.parse(vardata);
                if(json.status == 101){
                    console.log(json.msg);
                    $('#tbl_rec').load(namafile+'?action=tampil');
                    $('#ins_rec').trigger('reset');
                    $('#close_click').trigger('click');
                }
                else {
                    $('#sc_msg').text(json.msg);
                    console.log(json.msg);
                }
            }
        });
    });
    //GET DATA
    $(document).on("click", "button.editdata", function(){
        $('#msg_1').text("");
        $('#msg_2').text("");
        $('#msg_3').text("");
        $('#msg_4').text("");
        var check_id = $(this).data('dataid');
            $.getJSON(namafile+"?action=getdata", {checkid : check_id}, function(json){
                if(json.status == 0){
                    $('#upd_1').val(json.ItemID);
                    $('#upd_2').val(json.ItemName);
                    $('#upd_3').val(json.GroupID);
                    $('#upd_4').val(json.CurrID);
                }
                else{
                    console.log(json.msg);
                }
            });
        });
    });
    //UPDATE TABLE DATA

    $('#updata').on("submit", function(e){
        e.preventDefault();
        console.log('a');
        $.ajax({
            type:'POST',
            url: namafile+'?action=update',
            data:$(this).serialize(),
            success:function(vardata){

                var json = JSON.parse(vardata);

                if(json.status == 101){
                    console.log(json.msg);
                    $('#tbl_rec').load(namafile+'?action=tampil');
                    $('#ins_rec').trigger('reset');
                    $('#up_cancle').trigger('click');
                }
                else{
                    console.log(json.msg);
                }

            }

        });

    });
    //DELETE TABLE DATA
    var deleteid;
    $(document).on("click", "button.deletedata", function(){
        deleteid = $(this).data("dataid");
    });
    $('#deleterec').click(function (){
        $.ajax({
            type:'POST',
            url: namafile+'?action=delete',
            data:{delete_id : deleteid},
            success:function(data){
                var json = JSON.parse(data);
                if(json.status == 0){
                    $('#tbl_rec').load(namafile+'?action=tampil');
                    $('#de_cancle').trigger("click");
                    console.log(json.msg);
                }
                else{
                    console.log(json.msg);
                }
            }
        });
    });

</script>

</body>
</html>