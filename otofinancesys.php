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
                        <th>No Voucher</th>
                        <th>Tgl Transaksi</th>
                        <th>Tgl Efektif</th>
                        <th>Atas Nama</th>
                        <th>Total</th>
                        <th>Bank</th>
                        <th>Jenis</th>
                        <th>Keterangan</th>
                        <th>Action</th>
                    </tr>
                </thead>		
                <tbody>

                <?php
                
                $query = sqlsrv_query($conn,"SELECT A.VoucherID,CONVERT(VARCHAR(10),A.Transdate1,111) as TglT,CONVERT(VARCHAR(10),A.Transdate,111) as TglE,A.JumlahD,A.CurrID,A.BankID,A.KodeApproval,
                isnull(CASE WHEN A.FlagKKBB IN ('APK','APB','APC','APGC') THEN (SELECT K.SuppName FROM (SELECT SuppID,SuppName From APMsSupplier ) as K WHERE K.SuppID=Actor)
                            WHEN A.FlagKKBB IN ('ARK','ARB','ARC','ARGC') THEN (SELECT K.CustName FROM (SELECT CustID,CustName From ARMsCustomer ) as K WHERE K.CustID=Actor) 
                            ELSE Actor END,Actor) as Actor,
                CASE WHEN FlagKKBB IN ('APK','APB','APGC') THEN 'Payable'      
                        WHEN FlagKKBB IN ('ARK','ARB','ARGC') THEN 'Receiveable'      
                        WHEN FlagKKBB IN ('KM','BM') THEN 'Pemasukan Kas/Bank'      
                        WHEN FlagKKBB IN ('KK','BK') THEN 'Pengeluaran Kas/Bank'      
                        ELSE 'Jurnal Umum' END as Flag,Note,   
                CASE WHEN A.FgPayment='F' THEN 'WAITING'  
                        WHEN A.FgPayment='T' THEN 'APPROVED'  
                        WHEN A.FgPayment='X' THEN 'REJECTED'  
                        ELSE 'PENDING' END as StatusOto 
                FROM CFTrKKBBHd A 
                WHERE ISNULL(A.FgPayment,'T')='F' 
                ORDER BY A.Transdate1 ");
                
                while($hasil = sqlsrv_fetch_array($query)){
                echo "<tr>";
                echo "<td>".$hasil[0]."</td>";
                echo "<td>".$hasil[1]."</td>";
                echo "<td>".$hasil[2]."</td>";
                echo "<td>".$hasil[7]."</td>";
                echo "<td align='right'>".number_format($hasil[3],2,",",".")."</td>";
                echo "<td>".$hasil[5]."</td>";
                echo "<td>".$hasil[8]."</td>";
                echo "<td>".$hasil[9]."</td>";
                
                ?>
                
                    <td>
                        <button type="button" class="btn btn-brand editdata" 
                        data-dataid="<?php echo $hasil['0']; ?>" data-toggle="modal" data-target="#updateModalCenter"><i class="fas fa-edit"></i></button>
                        <button type="button" class="btn btn-danger deletedata" 
                        data-dataid="<?php echo $hasil['0']; ?>" data-toggle="modal" data-target="#deleteModalCenter"><i class="fa fa-tasks"></i></button>
                    </td>
                </tr>

                <?php	
                
                }	

                ?>


                </tbody>
                <tfoot>
                    <tr>
                        <th>No Voucher</th>
                        <th>Tgl Transaksi</th>
                        <th>Tgl Efektif</th>
                        <th>Atas Nama</th>
                        <th>Total</th>
                        <th>Bank</th>
                        <th>Jenis</th>
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
						<th>Kode Ledger</th>
						<th>Nama Ledger</th>
						<th>Keterangan</th>
						<th>Debet</th>
						<th>Kredit</th>
					</tr>		  
				</thead>
						
				<tbody>

					<?php
					$ItemID = $_GET['checkid'];

					$query = sqlsrv_query($conn,"SELECT K.RekeningID,L.RekeningName,K.Note,
                    CASE WHEN K.CurrID='IDR' THEN (CASE WHEN K.Jenis='D' THEN K.Amount ELSE 0 END) ELSE (CASE WHEN K.Jenis='D' THEN K.Amount*K.Rate ELSE 0 END) END as Debet,
                    CASE WHEN K.CurrID='IDR' THEN (CASE WHEN K.Jenis='K' THEN K.Amount ELSE 0 END) ELSE (CASE WHEN K.Jenis='K' THEN K.Amount*K.Rate ELSE 0 END) END as Kredit
                    FROM (
                    SELECT A.RekeningID,A.Jenis,ISNULL(A.Amount,0) as Amount,
                    ISNULL((CASE WHEN B.FlagKKBB IN ('BK','APB','APC') THEN B.KodeApproval +' - ' ELSE '' END),'')+A.Note as Note,
                    CASE WHEN B.FlagKKBB IN ('BK','BM','GC','APB','ARB','ARC','APC','SA','JU') THEN B.VoucherNo ELSE A.VoucherID END as VoucherID, 
                    B.FgPayment,B.CurrID,B.Rate FROM CFTrKKBBDt A INNER JOIN CFTrKKBBHd B ON A.VoucherID=B.VoucherID 
                    UNION ALL 
                    SELECT B.RekeningID,CASE WHEN A.FlagKKBB IN ('BM','ARB','ARC','KM','ARK') THEN 'D' ELSE 'K' END,
                    ISNULL(CASE WHEN A.FlagKKBB IN ('BM','ARB','ARC','KM','ARK') THEN JumlahD ELSE JumlahK END,0),
                    ISNULL((CASE WHEN A.FlagKKBB IN ('BM','ARB','ARC','KM','ARK') THEN A.KodeApproval +' - ' ELSE '' END),'')+A.Note,
                    A.VoucherID,A.FgPayment,A.CurrID,A.Rate FROM CFTrKKBBHd A INNER JOIN CFMsBank B ON A.BankID=B.BankID 
                    WHERE A.FlagKKBB IN ('BM','BK','ARB','ARC','APB','APC','KM','KK','ARK','APK','ARGC','APGC')
                    ) as K 
                    LEFT JOIN CFMsRekening L on K.RekeningID=L.RekeningID
                    WHERE K.FgPayment<>'T' and K.Amount<>0 and K.VoucherID='".$ItemID."' ORDER BY K.Jenis,K.RekeningID ");

					while($hasil = sqlsrv_fetch_array($query)){
					echo "<tr>";
					echo "<td>".$hasil[0]."</td>";
                    echo "<td>".$hasil[1]."</td>";
                    echo "<td>".$hasil[2]."</td>";
					echo "<td align='right'>".number_format($hasil[3],2,".",",")."</td>";
                    echo "<td align='right'>".number_format($hasil[4],2,".",",")."</td>";
					echo "</tr>";	
					}	
					?>

				</tbody>
				<tfoot>
					<tr>
                        <th>Kode Ledger</th>
						<th>Nama Ledger</th>
						<th>Keterangan</th>
						<th>Debet</th>
						<th>Kredit</th>
					</tr>
				</tfoot>
				</table>

			<?php
			echo '<script src="jsku.js"></script>';
			echo '<script src="assets/vendor/datatables/js/dataTables.bootstrap4.min.js"></script>';    
			echo '<script src="assets/vendor/datatables/js/data-table.js"></script>';
			break;
			
			case "setuju" :

				$query = sqlsrv_query($conn,"UPDATE CFTrKKBBHd SET FgPayment='T',otoby='".$_SESSION['username']."',OtoDate=getdate() WHERE VoucherID='".$_POST['delete_id']."' ");
	
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