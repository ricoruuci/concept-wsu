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
	                            <th>Tanggal</th>
								<th>Nomor SO</th>
								<th>Pelanggan</th>
								<th>Biaya Tambahan</th>
								<th>Total SO</th>
								<th>Sales</th>
								<th>Area</th>
								<th>PO Customer</th>
								<th>Status</th>
								<th>Limit</th>
								<th>Sisa Piutang</th>
								<th>Sisa Limit</th>
								<th>Action</th>
                                    
	                    </thead>
	                        </tr>		
	                    <tbody>

						<?php
						
						$query = sqlsrv_query($conn,"SELECT CONVERT(VARCHAR(10),K.Transdate,121) as Tanggal,K.POID,K.CustName,K.Adm,K.TTLSO,
						K.SalesName,K.POCust,K.StatusOto,K.LimitPiutang,K.SisaP,K.LimitPiutang-K.SisaP as SisaL,ISNULL(K.AreaName,'-') as AreaName FROM ( 
						SELECT A.Transdate,A.POID,B.CustName,A.FgTax,ISNULL(A.Administrasi+A.Ongkir+A.Repacking,0) as Adm, 
						A.TTLSO,C.SalesName,A.POCust, 
						case when A.FgOto='Y' THEN 'APPROVED' 
						when A.FgOto='X' THEN 'REJECTED' 
						when A.FgOto='O' THEN 'OVERDUE' 
						when A.Fgoto='L' THEN 'OVERLIMIT' 
						when A.FgOto='T' THEN 'TIDAK ADA' 
						when A.FgOto='OL' THEN 'OVERDUE & OVERLIMIT' 
						when A.FgOto='OB' THEN 'OVERDUE & BOTTOM PRICE' 
						when A.FgOto='OLB' THEN 'OVERDUE, OVERLIMIT & BOTTOM PRICE' 
						when A.FgOto='LB' THEN 'OVERLIMIT & BOTTOM PRICE' 
						END as StatusOto, B.LimitPiutang, 
						(SELECT ISNULL(SUM(K.TTLKJ-K.Bayar),0) as Sisa FROM ( 
						SELECT ISNULL(Y.TTLKJ,0) as TTLKJ, ISNULL((SELECT SUM(X.Amount) FROM CFTrKKBBDt X WHERE X.Note=Y.KonInvPelID AND X.RekeningID=Y.RekeningU),0) as Bayar 
						FROM ARTrKonInvPelHd Y WHERE Y.CustID=A.CustID) as K ) as SisaP,D.AreaName
						FROM ARTrPurchaseOrderHd A 
						INNER JOIN ARMsCustomer B on A.CustID=B.CustId 
						INNER JOIN ARMsSales C on A.SalesID=C.SalesID 
						LEFT JOIN ARMsArea D on B.AreaCust=D.AreaID
						where A.FgOto IN ('T','O','L','OL','OB','OLB','LB') 
						)as K Order BY K.Transdate ");
						while($hasil = sqlsrv_fetch_array($query)){
						echo "<tr>";
						echo "<td>".$hasil[0]."</td>";
						echo "<td>".$hasil[1]."</td>";
						echo "<td>".$hasil[2]."</td>";
						echo "<td align='right'>".number_format($hasil[3],2,".",",")."</td>";
						echo "<td align='right'>".number_format($hasil[4],2,".",",")."</td>";
						echo "<td>".$hasil[5]."</td>";
						echo "<td>".$hasil[11]."</td>";
						echo "<td>".$hasil[6]."</td>";
						echo "<td>".$hasil[7]."</td>";
						echo "<td align='right'>".number_format($hasil[8],2,".",",")."</td>";
						echo "<td align='right'>".number_format($hasil[9],2,".",",")."</td>";
						echo "<td align='right'>".number_format($hasil[10],2,".",",")."</td>";

						
						?>
						
							<td>
								<button type="button" class="btn btn-brand editdata" 
								data-dataid="<?php echo $hasil['1']; ?>" data-toggle="modal" data-target="#updateModalCenter"><i class="fas fa-edit"></i></button>
                                <button type="button" class="btn btn-danger deletedata" 
                                data-dataid="<?php echo $hasil['1']; ?>" data-toggle="modal" data-target="#deleteModalCenter"><i class="fa fa-tasks"></i></button>
							</td>
						</tr>

						<?php	
						
						}	

						?>


						</tbody>
						<tfoot>
	                        <tr>
								<th>Tanggal</th>
								<th>Nomor SO</th>
								<th>Pelanggan</th>
								<th>Biaya Tambahan</th>
								<th>Total SO</th>
								<th>Sales</th>
								<th>Area</th>
								<th>PO Customer</th>
								<th>Status</th>
								<th>Limit</th>
								<th>Sisa Piutang</th>
								<th>Sisa Limit</th>
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
					$query = sqlsrv_query($conn,"select B.ItemName,A.Jumlah,A.UOMID,A.Price,ISNULL(A.jumlah*A.Price,0) as Total 
					from ARTrPurchaseOrderDt A inner join INMsItem B on A.ItemID=B.ItemID where A.POID='".$ItemID."' ");
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

				$query = sqlsrv_query($conn,"update ARTrPurchaseOrderHD set FGOto='X',otodate=getdate(),otoby='".$_SESSION['username']."' where POID='".$_POST['delete_id']."' ");
	
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

				$query = sqlsrv_query($conn,"update ARTrPurchaseOrderHD set FGOto='Y',otodate=getdate(),otoby='".$_SESSION['username']."' where POID='".$_POST['delete_id']."' ");
	
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