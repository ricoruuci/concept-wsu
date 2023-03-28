<?php 
    include_once("syskoneksi.php");

  	if(isset($_REQUEST['action'])){

	  	switch ($_REQUEST['action']){

	  		case "tampil":
	  		?>

				<table id="example" class="table table-striped table-bordered second" style="width:100%">

				<thead>
	                <tr>
	                    <th>Nama Pelanggan</th>
                        <th>Tgl Invoice</th>
                        <th>No Invoice</th>
                        <th>Total</th>
                        <th>Bayar</th>
                        <th>Retur</th>
                        <th>Sisa</th>
                        <th>Term</th>
                        <th>Jatuh Tempo</th>
                        <th>Overdue</th>
                        <th>Nama Sales</th>
                        <th>Area</th>
                        <th>Kode Pelanggan</th>
                    </tr>                              
	            </thead>
	                        		
	            <tbody id="tablebody">
                    <?php
                    $total = 0;

                    $query = sqlsrv_query($conn,"SELECT K.CustID,L.CustName,City,K.KonInvPelID,CONVERT(VARCHAR(10),K.TransDate,121) as Tanggal,
                    ISNULL(SUM(K.Total),0) as Total,ISNULL(SUM(K.Bayar),0) as Bayar,ISNULL(SUM(K.Retur),0) as Retur,ISNULL(SUM(K.Total-Retur-Bayar),0) as Sisa,K.JatuhTempo,
                    CONVERT(VARCHAR(10),K.TglJthTempo,121) as JthTempo,
                    CASE WHEN DATEDIFF(day,K.TglJthTempo,getdate())>0 then DATEDIFF(day,K.TglJthTempo,getdate()) else 0 end as Overdue,
                    J.SalesName,ISNULL(M.AreaName,'-') as AreaName 
                    FROM ( 
                    SELECT A.CustID,A.KonInvPelID,A.Transdate,ISNULL(A.TTLKj,0) as Total,A.TransDate + A.JatuhTempo as TglJthTempo,A.JatuhTempo,A.SalesID,  
                    ISNULL((SELECT ISNULL(ROUND(CASE WHEN Z.FgTax='Y' THEN SUM(X.Qty*X.Price)*Z.Tax*0.01 ELSE SUM(X.Qty*X.Price) END,0),0) as Retur  
                    FROM ARtrReturPenjualanDt X INNER JOIN ARtrReturPenjualanHd Y ON X.ReturnID=Y.ReturnID Inner join artrkoninvpelhd Z on X.SaleID=Z.KonInvpelId  
                    WHERE X.SaleID=A.KonInvPelID AND Y.CustID=A.CustID GROUP BY Z.FgTax,Z.Tax),0) as Retur,A.CurrID,  
                    (SELECT ISNULL(SUM(CASE WHEN L.Jenis='K' THEN L.Amount ELSE L.Amount-1 END),0) FROM CFTrKKBBDt L 
                    INNER JOIN CFTrKKBBHd Q ON L.VoucherID=Q.VoucherID  WHERE L.Note = A.KonInvPelID  AND Q.FgPayment='T' ) as Bayar  
                    FROM ARTrKonInvPelHd A  
                    ) as K 
                    INNER JOIN ARMsCustomer L ON K.CustID=L.CustID  
                    LEFT JOIN ARMsSales J ON K.SalesID=J.SalesID 
                    LEFT JOIN ARMsArea M on L.AreaCust=M.AreaID
                    WHERE CONVERT(VARCHAR(8),K.Transdate,112) <= CONVERT(VARCHAR(8),GETDATE(),112) AND ISNULL(K.Total-Retur-Bayar,0)<>0 
                    GROUP BY K.CustID,L.CustName,City,K.TglJthTempo,K.KonInvPelID,K.TransDate,K.JatuhTempo,J.SalesName,M.AreaName
                    ORDER BY L.CustName, K.KonInvPelID");
                    while($hasil = sqlsrv_fetch_array($query)){
                    echo "<tr>";
                    echo "<td>".$hasil[1]."</td>";
                    echo "<td>".$hasil[4]."</td>";
                    echo "<td>".$hasil[3]."</td>";
                    echo "<td align='right'>".number_format($hasil[5],2,".",",")."</td>";
                    $total = $total+$hasil[5];
                    echo "<td align='right'>".number_format($hasil[6],2,".",",")."</td>";
                    echo "<td align='right'>".number_format($hasil[7],2,".",",")."</td>";
                    echo "<td align='right'>".number_format($hasil[8],2,".",",")."</td>";
                    echo "<td align='right'>".number_format($hasil[9],0,".",",")."</td>";
                    echo "<td>".$hasil[10]."</td>";
                    echo "<td align='right'>".number_format($hasil[11],0,".",",")."</td>";
                    echo "<td>".$hasil[12]."</td>";
                    echo "<td>".$hasil[13]."</td>";
                    echo "<td>".$hasil[0]."</td>";
                    echo "</tr>";			
					}	

					?>
                    
				</tbody>
				
                <tfoot>
                    <!--<tr>
                        <td align="right" colspan=3>Total :</th>
                        <td align="right"><b><span id="valst"></span></b></td>
                        <th></th>
                        <th></th>
                        <td align="right"><b><span id="valgt"></span></b></td>
                        <th colspan=5></th>
                    </tr>-->
	                <tr>
                        <th>Nama Pelanggan</th>
                        <th>Tgl Invoice</th>
                        <th>No Invoice</th>
                        <th>Total</th>
                        <th>Bayar</th>
                        <th>Retur</th>
                        <th>Sisa</th>
                        <th>Term</th>
                        <th>Jatuh Tempo</th>
                        <th>Overdue</th>
                        <th>Nama Sales</th>
                        <th>Area</th>
                        <th>Kode Pelanggan</th>
                    </tr>
                </tfoot>

                </table>
                
                <?php
                echo '<script src="jsku.js"></script>';
                echo '<script src="assets/vendor/datatables/js/dataTables.bootstrap4.min.js"></script>';    
                echo '<script src="assets/vendor/datatables/js/data-table.js"></script>';
                echo '<script>
                      var table = document.getElementById("tablebody");
                      var GT=0.00, ST=0.00;    
                      var rowCount = table.rows.length; 

                      for (var i =0; i < rowCount; i++) {
                        ST = ST + parseFloat((table.rows[i].cells[3].innerHTML.replace(".","")).replace(",","."));
                        GT = GT + parseFloat((table.rows[i].cells[6].innerHTML.replace(".","")).replace(",","."));
                      }   
                        
                      document.getElementById("valst").innerHTML = ST.toLocaleString("de-DE", { maximumFractionDigits: 2, minimumFractionDigits: 2 });
                      document.getElementById("valgt").innerHTML = GT.toLocaleString("de-DE", { maximumFractionDigits: 2, minimumFractionDigits: 2 });
                      </script>';
			break;

		}
	}
?>