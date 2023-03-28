<?php 
    include_once("syskoneksi.php");

  	if(isset($_REQUEST['action'])){

	  	switch ($_REQUEST['action']){

	  		case "tampil":
	  		?>

				<table id="example" class="table table-striped table-bordered second" style="width:100%">

				<thead>
	                <tr>
	                    <th>Gudang</th>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Lot Number</th>
                        <th>Stock</th>
                        <th>Satuan</th>
                        <th>Harga</th>
                        <th>Total</th>
                        <th>Stock in Mtr</th>
                    </tr>                              
	            </thead>
	                        		
	            <tbody>
                    <?php			
                    $query = sqlsrv_query($conn,"SELECT K.*,L.ItemName,J.WarehouseName,ISNULL(K.Stock*L.Panjang,0) as Total_Meter,L.UOMID as Satuan FROM (  
                        select A.WarehouseID,A.ItemID,A.SNID,A.Price,ISNULL(SUM(CASE WHEN A.FgTrans<50 THEN A.Qty ELSE A.Qty-1 END),0) as Stock,  
                        ISNULL(SUM(CASE WHEN A.FgTrans<50 THEN A.Qty*A.Price ELSE A.Qty*A.Price*-1 END),0) Total_Modal  from AllLootNumber A 
                        Where CONVERT(VARCHAR(8),A.TransDate,112)<='20221215'  GROUP BY A.WarehouseID,A.ItemID,A.SNID,A.Price  
                        ) as K INNER JOIN INMSItem L ON K.ItemID=L.ItemID   
                        INNER JOIN INMsWarehouse J ON K.WarehouseID=J.WarehouseID  
                        ORDER BY L.ItemName,J.WarehouseName");
                    while($hasil = sqlsrv_fetch_array($query)){
                    echo "<tr>";
                    echo "<td>".$hasil[7]."</td>";
                    echo "<td>".$hasil[1]."</td>";
                    echo "<td>".$hasil[6]."</td>";
                    echo "<td>".$hasil[2]."</td>";
                    echo "<td align='right'>".number_format($hasil[4],2,".",",")."</td>";
                    echo "<td>".$hasil[9]."</td>";
                    echo "<td align='right'>".number_format($hasil[3],2,".",",")."</td>";
                    echo "<td align='right'>".number_format($hasil[5],2,".",",")."</td>";
                    echo "<td align='right'>".number_format($hasil[8],2,".",",")."</td>";
                    echo "</tr>";			
					}	

					?>
				</tbody>
				
                <tfoot>
	                <tr>
                        <th>Gudang</th>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Lot Number</th>
                        <th>Stock</th>
                        <th>Satuan</th>
                        <th>Harga</th>
                        <th>Total</th>
                        <th>Stock in Mtr</th>
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