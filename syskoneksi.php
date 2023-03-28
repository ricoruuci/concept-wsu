<?php 
//$server 	= "192.168.1.232\SAODB";
$server 	= ".";
$database = "WSUData";
$userdb 	= "sa";
$passdb 	= "admin1";
$connectionInfo = array( "Database"=>"$database", "UID"=>"$userdb", "PWD"=>"$passdb" );
$conn = sqlsrv_connect( $server, $connectionInfo );

/*nyalakan untuk cek koneksi */
/*
if( $conn ) {
     echo "Connection established.<br />";
}else{
     echo "Connection could not be established.<br />";
     die( print_r( sqlsrv_errors(), true));
}
*/
?>