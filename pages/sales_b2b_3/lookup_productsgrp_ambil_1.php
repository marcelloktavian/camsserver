<?php
include"../../include/koneksi.php";

$id = $_GET['id'];
$id_cust = $_GET['id_cust'];
//$sql_cmd ="SELECT pc.*,pd.size FROM mst_b2bcustomer_product pc LEFT JOIN mst_b2bproducts pd ON pc.products_id=pd.id WHERE (pc.closed=0) and pc.products_id = '".$id."' and pc.b2bcustomer_id='".$id_cust."' LIMIT 1";
$sql_cmd="SELECT (SELECT gp.id FROM mst_b2bproductsgrp gp WHERE gp.id='".$id."') AS id_product,(SELECT gp.nama FROM mst_b2bproductsgrp gp WHERE gp.id='".$id."') AS product,(SELECT bp.nett_price FROM mst_b2bcustomer_product bp WHERE bp.products_id='".$id."' AND bp.b2bcustomer_id='".$id_cust."') AS harga,(SELECT bp.price FROM mst_b2bcustomer_product bp WHERE bp.products_id='".$id."' AND bp.b2bcustomer_id='".$id_cust."') AS pricelist,(SELECT bp.disc FROM mst_b2bcustomer_product bp WHERE bp.products_id='".$id."' AND bp.b2bcustomer_id='".$id_cust."') AS disc,(SELECT gd.id FROM mst_b2bproductsgrp_detail gd WHERE gd.id_productsgrp='".$id."' AND gd.size=36) AS s36,(SELECT gd.id FROM mst_b2bproductsgrp_detail gd WHERE gd.id_productsgrp='".$id."' AND gd.size=37) AS s37,(SELECT gd.id FROM mst_b2bproductsgrp_detail gd WHERE gd.id_productsgrp='".$id."' AND gd.size=38) AS s38,(SELECT gd.id FROM mst_b2bproductsgrp_detail gd WHERE gd.id_productsgrp='".$id."' AND gd.size=39) AS s39,(SELECT gd.id FROM mst_b2bproductsgrp_detail gd WHERE gd.id_productsgrp='".$id."' AND gd.size=40) AS s40,(SELECT gd.id FROM mst_b2bproductsgrp_detail gd WHERE gd.id_productsgrp='".$id."' AND gd.size=41) AS s41,(SELECT gd.id FROM mst_b2bproductsgrp_detail gd WHERE gd.id_productsgrp='".$id."' AND gd.size=42) AS s42,(SELECT gd.id FROM mst_b2bproductsgrp_detail gd WHERE gd.id_productsgrp='".$id."' AND gd.size=43) AS s43,(SELECT gd.id FROM mst_b2bproductsgrp_detail gd WHERE gd.id_productsgrp='".$id."' AND gd.size=44) AS s44,(SELECT gd.id FROM mst_b2bproductsgrp_detail gd WHERE gd.id_productsgrp='".$id."' AND gd.size=45) AS s45";
 
//var_dump($sql_cmd);  
$sql = mysql_query($sql_cmd);
$row = mysql_fetch_array($sql);
echo json_encode($row);

?>