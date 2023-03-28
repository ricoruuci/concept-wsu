<?php 
    include_once("syskoneksi.php");

  	if(isset($_REQUEST['action'])){

	  	switch ($_REQUEST['action']){

	  		case "tampil":
	  		?>

				<table id="example2" class="table table-striped table-bordered" style="width:100%">                                                                   

				    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Invoice</th>
                            <th>Sales</th>
                            <th>Pelanggan</th>
                            <th>Area</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $dari = $_REQUEST['dari'];
                        $smp = $_REQUEST['smp'];

                        $query = sqlsrv_query($conn,"select convert(varchar(10),a.transdate,121) as tanggal, b.salesname, d.custname, isnull(e.areaname,'-') as areaname,
                        a.koninvpelid, a.kontransbrgid, a.soid, a.jatuhtempo, a.stkj, a.discount, a.ppn, a.ttlkj, c.biayaongkir, c.biayaarmada, a.alamatinv 
                        from artrkoninvpelhd a 
                        inner join armssales b on a.salesid=b.salesid
                        inner join artrkontransbrghd c on a.kontransbrgid=c.kontransbrgid 
                        inner join armscustomer d on a.custid=d.custid
                        left join armsarea e on d.areacust=e.areaid
                        where a.transdate between '".$dari."' and '".$smp."'
                        order by salesname");
                        while($hasil = sqlsrv_fetch_array($query)){
                        echo "<tr>";
                        echo "<td>".$hasil[0]."</td>";
                        echo "<td>".$hasil[4]."</td>";
                        echo "<td>".$hasil[1]."</td>";
                        echo "<td>".$hasil[2]."</td>";
                        echo "<td>".$hasil[3]."</td>";
                        echo "<td align='right'>".number_format($hasil[11],2,".",",")."</td>";
                        echo "</tr>";			
                        }	

                        ?>                    
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Tanggal</th>
                            <th>Invoice</th>
                            <th>Sales</th>
                            <th>Pelanggan</th>
                            <th>Area</th>
                            <th>Total</th>
                        </tr>
                    </tfoot>

                </table>
                
                <?php
                echo '<script src="jsku.js"></script>';
                echo '<script src="assets/vendor/datatables/js/dataTables.bootstrap4.min.js"></script>';
                echo '<script src="https://cdn.datatables.net/rowgroup/1.0.4/js/dataTables.rowGroup.min.js"></script>';    
                echo '<script src="assets/vendor/datatables/js/data-table.js"></script>';
			break;

		}
	}
?>