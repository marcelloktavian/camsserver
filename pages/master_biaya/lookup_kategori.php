<?php
include("../../include/koneksi.php");
$q = strtolower($_GET["q"]);
	//var_dump($sql_text);die;
if (!$q) return;
	$sql_text="select * from mst_kategori_biaya where (deleted=0)  and (nama_kategori LIKE '%$q%')";
	$sql = mysql_query($sql_text);
	while($r = mysql_fetch_array($sql)) {
	$nama = $r['id'].":".$r['nama_kategori'];
	echo "$nama \n";
	}
    
?>
