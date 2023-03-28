<?php 
include("syskoneksi.php");
include("myunit.php");
 
$username = $_POST['username'];
$password = $_POST['password'];
$rotate = 41;
$crypt = "";

for ($i = 0; $i < strlen($password); $i = $i + 1) 
{
	$baru[$i] = substr($password,$i,1);
}

for ( $i = 0; $i < strlen($password); $i = $i + 1 ) 
{
    $asc = ord($baru[$i]);
    $asc = $asc + (($i + 1) * $rotate);
    $kumpul = $asc / 16 + 65;
    $hsl1 = chr($kumpul);
    $kumpul = $asc % 16 + 65;
    $hsl2 = chr($kumpul);
    $crypt = $crypt . $hsl1 . $hsl2;
}

echo $crypt;

$sql = "select * from sysmsuser where userid='".$username."' and passwd='".$crypt."'";

$stmt = sqlsrv_query($conn, $sql);

if ( $stmt === false ) 
{
     die( print_r( sqlsrv_errors(), true));
}

$cek = sqlsrv_has_rows( $stmt );

if ( $cek === true )
{
	session_start();
	$_SESSION["username"] = $username;
	$_SESSION["status"] = "login";

	$query = sqlsrv_query($conn,"select kdgroup from sysmsuser where userid='".$username."' ");
	while($hasildata = sqlsrv_fetch_array($query)){
	$_SESSION["groupuser"] = $hasildata['kdgroup'];
	}
	
	header("location:fmutama.php");
}
else
{

	echo "<script>
	window.location.href='index.php';
	alert('username/password tidak ditemukan!');
	</script>";
} 


?>