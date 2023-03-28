<?php 
    include_once("syskoneksi.php");

  	if(isset($_REQUEST['action'])){

	  	switch ($_REQUEST['action']){

	  		case "tampil":
	  		?>

				<table id="example" class="table table-striped table-bordered second" style="width:100%">

				<thead>
	                <tr>
	                    <th>Nama Supplier</th>
                        <th>Tgl Invoice</th>
                        <th>No Invoice</th>
                        <th>Total</th>
                        <th>Bayar</th>
                        <th>Retur</th>
                        <th>Sisa</th>
                        <th>Term</th>
                        <th>Jatuh Tempo</th>
                        <th>Overdue</th>
                        <th>Group Supplier</th>
                        <th>Kota</th>
                        <th>Kode Supplier</th>
                    </tr>                              
	            </thead>
	                        		
	            <tbody>
                    <?php			
                    $query = sqlsrv_query($conn,"SELECT K.SuppID,L.SuppName,F.CustGroupName,City,K.KonsinyasiInvID,CONVERT(VARCHAR(10),K.TransDate,121) AS Tanggal, 
                    ISNULL(SUM(K.Total),0) as NilaiHutang,ISNULL(SUM(K.Bayar),0) as Bayar,ISNULL(SUM(K.Retur),0) as Retur,ISNULL(SUM(K.Total-Bayar-Retur),0) as Sisa,
                    K.JatuhTempo,CONVERT(VARCHAR(10),K.TglJthTempo,121) as TglJthTempo,
                    CASE WHEN DATEDIFF(day,K.TglJthTempo,getdate())>0 THEN DATEDIFF(day,K.TglJthTempo,getdate()) ELSE 0 END as Overdue  FROM (  
                    SELECT A.KonsinyasiInvID,A.TglJthTempo,A.SuppID,A.Transdate,ISNULL(A.TTLKs*A.Rate,0) as Total,A.CurrID,A.JatuhTempo,
                    ISNULL((SELECT ISNULL(ROUND(CASE WHEN Z.FgTax='Y' THEN SUM(X.Qty*X.Price)*Z.NilaiPPN*0.01 ELSE SUM(X.Qty*X.Price) END,0),0) as Retur 
                    FROM APTrReturnDt X  INNER JOIN APTrReturnHd Y ON X.ReturnID=Y.ReturnID  INNER JOIN APTrKonsinyasiInvHd Z on X.PurchaseID=Z.KonsinyasiInvID
                    WHERE X.PurchaseID=A.KonsinyasiInvID AND Y.SuppID=A.SuppID GROUP BY Z.FgTax,Z.NilaiPPN),0) as Retur,
                    (SELECT ISNULL(SUM(CASE WHEN L.Jenis='D' THEN L.Amount*Q.Rate ELSE L.Amount*Q.Rate-1 END),0)  FROM CFTrKKBBDt L 
                    INNER JOIN CFTrKKBBHd Q ON L.VoucherID=Q.VoucherID WHERE L.Note=A.KonsinyasiInvID AND Q.FgPayment='T') as Bayar 
                    FROM APTrKonsinyasiInvHd A  
                    ) as K  
                    INNER JOIN APMsSupplier L ON K.SuppID=L.SuppID  
                    INNER JOIN ARMsGroupCust F ON F.CustCode=L.GroupSupp
                    WHERE CONVERT(VARCHAR(8),K.Transdate,112) <= CONVERT(VARCHAR(8),GETDATE(),112)  AND ISNULL(K.Total-K.Bayar-K.Retur,0)<>0  
                    GROUP BY K.SuppID,F.CustGroupName,L.SuppName,L.City,K.TglJthTempo,K.KonsinyasiInvID,K.TransDate,K.JatuhTempo 
                    ORDER BY L.SuppName,K.TransDate,K.KonsinyasiInvID");
                    while($hasil = sqlsrv_fetch_array($query)){
                    echo "<tr>";
                    echo "<td>".$hasil[1]."</td>";
                    echo "<td>".$hasil[5]."</td>";
                    echo "<td>".$hasil[4]."</td>";
                    echo "<td align='right'>".number_format($hasil[6],2,".",",")."</td>";
                    echo "<td align='right'>".number_format($hasil[7],2,".",",")."</td>";
                    echo "<td align='right'>".number_format($hasil[8],2,".",",")."</td>";
                    echo "<td align='right'>".number_format($hasil[9],0,".",",")."</td>";
                    echo "<td align='right'>".number_format($hasil[10],2,".",",")."</td>";
                    echo "<td>".$hasil[11]."</td>";
                    echo "<td align='right'>".number_format($hasil[12],0,".",",")."</td>";
                    echo "<td>".$hasil[2]."</td>";
                    echo "<td>".$hasil[3]."</td>";
                    echo "<td>".$hasil[0]."</td>";
                    echo "</tr>";			
					}	

					?>
				</tbody>
				
                <tfoot>
	                <tr>
                        <th>Nama Supplier</th>
                        <th>Tgl Invoice</th>
                        <th>No Invoice</th>
                        <th>Total</th>
                        <th>Bayar</th>
                        <th>Retur</th>
                        <th>Sisa</th>
                        <th>Term</th>
                        <th>Jatuh Tempo</th>
                        <th>Overdue</th>
                        <th>Group Supplier</th>
                        <th>Kota</th>
                        <th>Kode Supplier</th>
                    </tr>
                </tfoot>

                </table>

                <?php
                echo '<script src="jsku.js"></script>';
                echo '<script src="assets/vendor/datatables/js/dataTables.bootstrap4.min.js"></script>';    
                echo '<script src="assets/vendor/datatables/js/data-table.js"></script>';

			break;

		}
	}
?>