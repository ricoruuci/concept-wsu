<?php 
  include_once("syskoneksi.php");
  session_start();

  	if(isset($_REQUEST['action'])){

	  	switch ($_REQUEST['action']){

	  		case "tampil":
	  		?>

						<table class="table table-striped table-bordered first">
						<thead>
	                        <tr>
	                            <th>Jenis PR</th>
								<th>Tanggal</th>
								<th>Nomor PR</th>
								<th>Request By</th>
								<th>Keterangan</th>
								<th>Action</th>
                                    
	                    </thead>
	                        </tr>		
	                    <tbody>

						<?php
						
						$query = sqlsrv_query($conn,"select case when jenis='S' THEN 'Barang' else 'Jasa' end as Jenis,convert(varchar(10),a.transdate,121) as tgl,
						PRID,B.SalesName,ISNULL(A.Note,'') as Note 
						from APTrPurchaseRequestHd A inner join ARMsSales B on A.SalesID=B.SalesID 
					   	where FgSubmit='Y' AND FgSales='K' AND A.FgOto='T' 
						Order By A.Transdate ");
						while($hasil = sqlsrv_fetch_array($query)){
						echo "<tr>";
						echo "<td>".$hasil[0]."</td>";
						echo "<td>".$hasil[1]."</td>";
						echo "<td>".$hasil[2]."</td>";
						echo "<td>".$hasil[3]."</td>";
						echo "<td>".$hasil[4]."</td>";

						
						?>
						
							<td>
								<button type="button" class="btn btn-brand editdata" 
								data-dataid="<?php echo $hasil['2']; ?>" data-toggle="modal" data-target="#updateModalCenter"><i class="fas fa-edit"></i></button>
                                <button type="button" class="btn btn-danger deletedata" 
                                data-dataid="<?php echo $hasil['2']; ?>" data-toggle="modal" data-target="#deleteModalCenter"><i class="fa fa-tasks"></i></button>
							</td>
						</tr>

						<?php	
						
						}	

						?>


						</tbody>
						<tfoot>
	                        <tr>
								<th>Jenis PR</th>
								<th>Tanggal</th>
								<th>Nomor PR</th>
								<th>Request By</th>
								<th>Keterangan</th>
								<th>Action</th>
	                        </tr>
	                    </tfoot>
					</table>



			<?php
		    echo '<script src="jsku.js"></script>';
		    echo '<script src="assets/vendor/datatables/js/dataTables.bootstrap4.min.js"></script>';    
		    echo '<script src="assets/vendor/datatables/js/data-table.js"></script>';
			break;
					
			case "getdata":
			?>
				<table class="table table-striped table-bordered first">
				<thead>
					<tr>
						<th>Nama Barang</th>
						<th>Jumlah</th>
						<th>Satuan</th>
						<th>Tgl Tersedia</th>
					</tr>		  
				</thead>
						
				<tbody>

					<?php
					$ItemID = $_GET['checkid'];
					$query = sqlsrv_query($conn,"select B.ItemName,A.Jumlah,A.UOMID,ISNULL(CONVERT(varchar(10),A.tglTersedia,121),'-') as TglSedia 
					from APTrPurchaseRequestDt A inner join INMsItem B on A.ItemID=B.ItemID WHERE A.PRID='".$ItemID."' ");
					while($hasil = sqlsrv_fetch_array($query)){
					echo "<tr>";
					echo "<td>".$hasil[0]."</td>";
					echo "<td align='right'>".number_format($hasil[1],2,".",",")."</td>";
					echo "<td>".$hasil[2]."</td>";
					echo "<td>".$hasil[3]."</td>";
					echo "</tr>";	
					}	
					?>

				</tbody>
				<tfoot>
					<tr>
						<th>Nama Barang</th>
						<th>Jumlah</th>
						<th>Satuan</th>
						<th>Tgl Tersedia</th>
					</tr>
				</tfoot>
				</table>

			<?php
			echo '<script src="jsku.js"></script>';
			echo '<script src="assets/vendor/datatables/js/dataTables.bootstrap4.min.js"></script>';    
			echo '<script src="assets/vendor/datatables/js/data-table.js"></script>';
			break;

			case "tolak" :

				$query = sqlsrv_query($conn,"update APTrPurchaseRequestHd set FGOto='X',otodate=getdate(),otoby='".$_SESSION['username']."' where PRID='".$_POST['delete_id']."' ");
	
				if($query){
					$json['status'] = 0;
					$json['msg'] = "Berhasil DiVoid";
				}
				else{
					$json['status'] = 1;
					$json['msg'] = "Gagal Tolak";
				}
	
				echo json_encode($json);
				
				break;
			
			case "setuju" :

				$query = sqlsrv_query($conn,"update APTrPurchaseRequestHd set FGOto='Y',otodate=getdate(),otoby='".$_SESSION['username']."' where PRID='".$_POST['delete_id']."' ");
	
				if($query){
					$json['status'] = 0;
					$json['msg'] = "Berhasil DiApprove";
				}
				else{
					$json['status'] = 1;
					$json['msg'] = "Gagal DiApprove";
				}
	
				echo json_encode($json);
				
				break;

		}
	}

?>