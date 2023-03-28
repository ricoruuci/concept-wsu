<?php 
  include_once("syskoneksi.php");


  	if(isset($_REQUEST['action'])){

	  	switch ($_REQUEST['action']){

	  		case "tampil":
	  		?>

					<table id="example" class="table table-striped table-bordered second" style="width:100%">
						<thead>
	                        <tr>
	                            <th>ItemID</th>
                                 <th>ItemName</th>
                                  <th>GroupID</th>
                                   <th>CurrID</th>
                                    <th>Action</th>
                                    
	                    </thead>
	                        </tr>		
	                    <tbody>

						<?php
						
						$query = sqlsrv_query($conn,"select TOP 10 ItemID,ItemName,GroupID,CurrID from INMSItem");
						while($hasil = sqlsrv_fetch_array($query)){
						echo "<tr>";
						echo "<td>".$hasil[0]."</td>";
						echo "<td>".$hasil[1]."</td>";
						echo "<td>".$hasil[2]."</td>";
						echo "<td>".$hasil[3]."</td>";

						
						?>
						
							<td>
								<button type="button" class="btn btn-brand editdata" 
								data-dataid="<?php echo $hasil['0']; ?>" data-toggle="modal" data-target="#updateModalCenter"><i class="fas fa-edit"></i></button>
                                <button type="button" class="btn btn-danger deletedata" 
                                data-dataid="<?php echo $hasil['0']; ?>" data-toggle="modal" data-target="#deleteModalCenter"><i class="fas fa-trash"></i></button>
							</td>
						</tr>

						<?php	
						
						}	

						?>


						</tbody>
						<tfoot>
	                        <tr>
                            <th>ItemID</th>
                                 <th>ItemName</th>
                                  <th>GroupID</th>
                                   <th>CurrID</th>
                                    <th>Action</th>
	                        </tr>
	                    </tfoot>
					</table>



			<?php
		    echo '<script src="jsku.js"></script>';
		    echo '<script src="assets/vendor/datatables/js/dataTables.bootstrap4.min.js"></script>';    
		    echo '<script src="assets/vendor/datatables/js/data-table.js"></script>';
			break;

			case "insert" :

			$ItemID = $_POST['ItemID'];
            $ItemName = $_POST['ItemName'];
            $GroupID = $_POST['GroupID'];
            $CurrID = $_POST['CurrID'];

		$query 		= sqlsrv_query($conn,"insert INMSItem (ItemID,ItemName,GroupID,CurrID) 
		select '".$ItemID."','".$ItemName."','".$GroupID."','".$CurrID."'");

			if($query){
				$json['status'] = 101;
				$json['msg'] = "Data Berhasil Ditambah";
			} else {
				$json['status'] = 102;
				$json['msg'] = "Gagal Menambah Data";
			}

			echo json_encode($json);
			
			break;

			case "update" :

			$ItemID = $_POST['ItemID'];
            $ItemName = $_POST['ItemName'];
            $GroupID = $_POST['GroupID'];
            $CurrID = $_POST['CurrID'];

			$query = sqlsrv_query($conn,"update INMSItem set ItemName='".$ItemName."',
				GroupID='".$GroupID."' ,CurrID='".$CurrID."'  where ItemID='".$ItemID."'");
			
			if($query){
				$json['status'] = 101;
				$json['msg'] = "Data Berhasil Diubah";
			} else {
				$json['status'] = 102;
				$json['msg'] = "Gagal Mengubah Data";
			}

			echo json_encode($json);
			
			break;

			case "getdata" :

			$ItemID = $_GET['checkid'];

			$query = sqlsrv_query($conn,"select ItemID,ItemName,GroupID,CurrID from INMSItem where ItemID='".$ItemID."' ");

			while($hasil = sqlsrv_fetch_array($query)){

				$json['status'] = 0;
				$json['ItemID'] = $hasil[0];
				$json['ItemName'] = $hasil[1];
				$json['GroupID'] = $hasil[2];
				$json['CurrID'] = $hasil[3];
	
			}

			echo json_encode($json);
			
			break;


			case "delete" :

			$query = sqlsrv_query($conn,"delete from INMSItem where ItemID='".$_POST['delete_id']."' ");

			if($query){
				$json['status'] = 0;
				$json['msg'] = "Berhasil Dihapus";
			}
			else{
				$json['status'] = 1;
				$json['msg'] = "Gagal Hapus";
			}

			echo json_encode($json);
			
			break;

		}
	}

?>