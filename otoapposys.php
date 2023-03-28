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
	                            <th>Jenis PO</th>
								<th>Tanggal</th>
								<th>Nomor PO</th>
								<th>Supplier</th>
								<th>Pajak</th>
								<th>Total PO</th>
								<th>Action</th>
                                    
	                    </thead>
	                        </tr>		
	                    <tbody>

						<?php
						
						$query = sqlsrv_query($conn,"select 
						CASE WHEN K.Urut=1 THEN 'PR BARANG' WHEN K.Urut=2 THEN 'PR JASA' ELSE 'PR UMUM' END as JenisPR,
						CONVERT(VARCHAR(10),K.Transdate,121) as Tanggal,
						K.POID,K.SuppNAme,CASE WHEN K.FgTax='T' THEN 'NON-PPN' ELSE 'PPN' END as Pajak,K.TTLPO 
						FROM (
						select A.Transdate,A.POID,B.SuppName,A.STPO,A.DiscAmount,A.PPN,A.TTLPO,CASE WHEN A.Jenis='S' THEN 'BARANG' ELSE 'JASA' END as Jenis,A.FgOto,1 as Urut, A.Note,A.FgTAx
						from APTrPurchaseOrderHd A inner join APMsSupplier B on A.SuppID=B.SuppID
						UNION ALL
						select A.Transdate,A.GBUID,B.SuppName,A.STGBU,A.Disc,A.PPN,A.TTLGBU,'UMUM',A.FgOto,2, A.Note,A.FgTax from ARTrPenawaranHd A inner join APMsSupplier B on A.CustID=B.SuppID
						) as K
						WHERE ISNULL(K.FgOto,'T')='T' ");
						while($hasil = sqlsrv_fetch_array($query)){
						echo "<tr>";
						echo "<td>".$hasil[0]."</td>";
						echo "<td>".$hasil[1]."</td>";
						echo "<td>".$hasil[2]."</td>";
						echo "<td>".$hasil[3]."</td>";
						echo "<td>".$hasil[4]."</td>";
						echo "<td align='right'>".number_format($hasil[5],2,".",",")."</td>"

						
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
								<th>Jenis PO</th>
								<th>Tanggal</th>
								<th>Nomor PO</th>
								<th>Supplier</th>
								<th>Pajak</th>
								<th>Total PO</th>
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
						<th>Harga</th>
						<th>Total</th>
					</tr>		  
				</thead>
						
				<tbody>

					<?php
					$ItemID = $_GET['checkid'];
					$query = sqlsrv_query($conn,"select B.ItemName,A.Jumlah,A.UOMID as Satuan,A.Price,ISNULL((A.Jumlah*A.Price),0) as Total 
					from APTrPurchaseOrderDt A inner join INMsItem B on A.ItemID=B.ItemID where A.POID='".$ItemID."' ");
					while($hasil = sqlsrv_fetch_array($query)){
					echo "<tr>";
					echo "<td>".$hasil[0]."</td>";
					echo "<td align='right'>".number_format($hasil[1],2,",",".")."</td>";
					echo "<td>".$hasil[2]."</td>";
					echo "<td align='right'>".number_format($hasil[3],2,",",".")."</td>";
					echo "<td align='right'>".number_format($hasil[4],2,",",".")."</td>";
					echo "</tr>";	
					}	
					?>

				</tbody>
				<tfoot>
					<tr>
						<th>Nama Barang</th>
						<th>Jumlah</th>
						<th>Satuan</th>
						<th>Harga</th>
						<th>Total</th>
					</tr>
				</tfoot>
				</table>

			<?php
			echo '<script src="jsku.js"></script>';
			echo '<script src="assets/vendor/datatables/js/dataTables.bootstrap4.min.js"></script>';    
			echo '<script src="assets/vendor/datatables/js/data-table.js"></script>';
			break;

			case "tolak" :

				$query = sqlsrv_query($conn,"update aptrpurchaseorderhd set FGOto='X',otodate=getdate(),otoby='".$_SESSION['username']."' where POID='".$_POST['delete_id']."' ");
	
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

				$query = sqlsrv_query($conn,"update aptrpurchaseorderhd set FGOto='Y',otodate=getdate(),otoby='".$_SESSION['username']."' where POID='".$_POST['delete_id']."' ");
	
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