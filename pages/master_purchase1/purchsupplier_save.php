<?php
include "../../include/koneksi.php";

// general variable -------------------------
$row      = $_GET['row'];

// master data processing -------------------
if (isset($_GET['id'])){
  $id_supplier = $_GET['id'];
}
else {
  $id_supplier = 0;
}
$supplier = strtoupper($_POST['supplier']);
$pic      = strtoupper($_POST['pic']);
$alamat   = strtoupper($_POST['alamat']);
$contact  = $_POST['contact'];
$email    = $_POST['email'];
$ktp      = $_POST['ktp'];
$npwp     = $_POST['npwp'];
$pkp      = $_GET['pkp'];
$totalqty = $_POST['totalqty'];

$check_master = "SELECT * FROM `mst_supplier` WHERE id='$id_supplier' LIMIT 1";
$check_val = mysql_query($check_master);

if($check_val){
  $sql_master = "UPDATE `mst_supplier` SET `vendor`='$supplier', `pic`='$pic', `alamat`='$alamat', `telp`='$contact', `email`='$email', `ktp`='$ktp', `npwp`='$npwp' , `pkp`='$pkp', `item`='$totalqty', `lastmodified`=NOW() WHERE id='$id_supplier'";
}
else{
  $sql_master = "INSERT INTO `mst_supplier` (vendor, pic, alamat, telp, email, ktp, npwp, pkp, item, lastmodified) VALUES ('$supplier', '$pic', '$alamat', '$contact', '$email', '$ktp', '$npwp', '$pkp', '$totalqty', NOW())";
}

$sql = mysql_query($sql_master);

// detail data processing -----------------
$id_supplier = "SELECT `id` FROM `mst_supplier` WHERE `vendor`='".$supplier."' AND `pic`='".$pic."' AND `ktp`='".$ktp."' LIMIT 1";
$sql = mysql_query($id_supplier);

$id_supplier = mysql_fetch_array($sql);

foreach($id_supplier as $id_supplier){
  $id_par = $id_supplier[0];
}

$sql_reset = "UPDATE `mst_produk` SET `id_supplier`='0' WHERE `id_supplier`='".$id_par."'";

for($i=1; $i<$row; $i++){
  $id_detail = $_POST['id'.$i];

  $sql_detail = "UPDATE `mst_produk` SET `id_supplier`='".$id_par."' WHERE `id`='".$id_detail."'";
  $sql = mysql_query($sql_detail);
}
?>

<script language="javascript">
  window.close();
</script>