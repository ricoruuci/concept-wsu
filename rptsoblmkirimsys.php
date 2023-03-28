<?php 
    include_once("syskoneksi.php");

  	if(isset($_REQUEST['action'])){

	  	switch ($_REQUEST['action']){

	  		case "tampil":
	  		?>

				<table id="example" class="table table-striped table-bordered second" style="width:100%">

				<thead>
	                <tr>
	                    <th>Tanggal</th>
                        <th>Nomor SO</th>
                        <th>PO Customer</th>
                        <th>Pelanggan</th>
                        <th>Area</th>
                        <th>Sales</th>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Satuan</th>
                        <th>Jml Mtr</th>
                        <th>Jml SO</th>
                        <th>Jml DO</th>
                        <th>Sisa</th>
                        <th>Harga</th>
                        <th>Total</th>
                    </tr>                              
	            </thead>
	                        		
	            <tbody id="tablebody">
                    <?php
                    $total = 0;

                    $query = sqlsrv_query($conn,"SELECT CONVERT(varchar(10),K.Transdate,111) as Tanggal, K.POID, K.POCust, K.CustName, K.AreaName, K.SalesName, 
                    K. ItemID, K.ItemName, K.UOMID, K.Qty2, K.Jumlah, K.JumDO,K.Jumlah-JumDO as Sisa, K.Price, 
                    ISNULL(K.Jumlah-JumDO,0)*(CASE WHEN K.Qty2=0 THEN 1 ELSE K.Qty2 END)*K.Price as Total FROM ( 
                    select B.Transdate, A.POID, B.POCust, D.CustName, F.AreaName, E.SalesName, A.ItemID, C.ItemName, A.UOMID, A.Qty2, A.Jumlah,
                    ISNULL((SELECT SUM(X.Qty) FROM ARTrKonTransBrgDt X inner join artrkontransbrghd Y on X.KonTransBrgID=Y.KonTransBrgID  
                    WHERE Y.SOID=B.POID AND X.ItemID=A.ItemID AND X.NumAll=A.NUmAll),0) as JumDO, A.Price
                    from artrpurchaseorderdt a  
                    inner join artrpurchaseorderhd b on a.poid=b.poid  
                    inner join inmsitem c on a.itemid=c.itemid  
                    inner join armscustomer d on b.custid=d.custid  
                    inner join armssales e on b.salesid=e.salesid  
                    inner join armsarea f on d.areacust=f.areaid
                    where b.fgoto='y' and b.fgcetak='y'  
                    ) as k  where convert(varchar(8),k.transdate,112) <= convert(varchar(8),getdate(),112)
                    and k.jumlah-jumdo > 0 
                    order by k.transdate,poid,custname");
                    while($hasil = sqlsrv_fetch_array($query)){
                    echo "<tr>";
                    echo "<td>".$hasil[0]."</td>";
                    echo "<td>".$hasil[1]."</td>";
                    echo "<td>".$hasil[2]."</td>";
                    echo "<td>".$hasil[3]."</td>";
                    echo "<td>".$hasil[4]."</td>";
                    echo "<td>".$hasil[5]."</td>";
                    echo "<td>".$hasil[6]."</td>";
                    echo "<td>".$hasil[7]."</td>";
                    echo "<td>".$hasil[8]."</td>";
                    echo "<td align='right'>".number_format($hasil[9],2,".",",")."</td>";
                    echo "<td align='right'>".number_format($hasil[10],2,".",",")."</td>";
                    echo "<td align='right'>".number_format($hasil[11],2,".",",")."</td>";
                    echo "<td align='right'>".number_format($hasil[12],2,".",",")."</td>";
                    echo "<td align='right'>".number_format($hasil[13],0,".",",")."</td>";
                    echo "<td align='right'>".number_format($hasil[14],0,".",",")."</td>";
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
                        <th>Tanggal</th>
                        <th>Nomor SO</th>
                        <th>PO Customer</th>
                        <th>Pelanggan</th>
                        <th>Area</th>
                        <th>Sales</th>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Satuan</th>
                        <th>Jml Mtr</th>
                        <th>Jml SO</th>
                        <th>Jml DO</th>
                        <th>Sisa</th>
                        <th>Harga</th>
                        <th>Total</th>
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