<script src="../../assets/js/jsbilangan.js" type="text/javascript"></script>
<script type="text/javascript" src="../../assets/js/jquery-1.4.js"></script>
<style type="text/css">
.style9 {
font-size: 9pt; 
font-family:Tahoma;
}
.style9b {color: #000000;
  font-size: 9pt;
  font-weight: bold;
  font-family: Tahoma;
}.style99 {font-size: 13pt; font-family:Tahoma}
.style10 {font-size: 10pt; font-family:Tahoma; text-align:right}
.style19 {font-size: 10pt; font-weight: bold; font-family:Tahoma; font-style:italic}
.style11 {
  color: #000000;
  font-size: 8pt;
  font-weight: normal;
  font-family: MS Reference Sans Serif;
  
}
.style20b {font-size: 8pt;font-weight: bold; font-family:Tahoma}
.style20 {font-size: 8pt; font-family:Tahoma}
.style16 {font-size: 9pt; font-family:Tahoma}
.style21 {color: #000000;
  font-size: 10pt;
  font-weight: bold;
  font-family: Tahoma;
}
.style18 {color: #000000;
  font-size: 9pt;
  font-weight: normal;
  font-family: Tahoma;
}
.style_footer {color: #000000;
  font-size: 11pt;  
  font-family: Tahoma;
  border-top: 1px solid black;
  border-bottom: 1px solid black;
  border-right: 1px solid black;
  border-left: 1px solid black;
  
}
.style19b { color: #000000;
  font-size: 11pt;
  font-weight: bold;
  font-family: Tahoma;
}
.style_title {  color: #000000;
  font-size: 11pt;  
  font-family: Tahoma;
  border-top: 1px solid black;
  border-bottom: 1px solid black;
  border-right: 1px solid black;
  
  
  padding: 3px;
}
.style_title_left { color: #000000;
  font-size: 11pt;  
  font-family: Tahoma;
  border-top: 1px solid black;
  border-bottom: 1px solid black;
  border-right: 1px solid black;
  border-left: 1px solid black;
  
  padding: 3px;
}
.style_detail { color: #000000;
  font-size: 9pt; 
  font-family: Tahoma;
  border-bottom: 1px dashed black;
  border-right: 1px solid black;
  padding: 3px;
}
.style_detail_left {  color: #000000;
  font-size: 9pt; 
  font-family: Tahoma;
  border-bottom: 1px dashed black;
  border-left: 1px solid black;
  border-right: 1px solid black;
  padding: 3px;
}
@page {
        size: A4;
        margin: 15px;
    }
</style>
<?php
error_reporting(0);
  include("../../include/koneksi.php");
  $tglstart=$_GET['start'];
  $st = explode("/", $tglstart);
  $tgl1 = $st[2].'-'.$st[1].'-'.$st[0];
  $tglend=$_GET['end'];
  $st2 = explode("/", $tglend);
  $tgl2 = $st2[2].'-'.$st2[1].'-'.$st[0];
  $type=$_GET['type'];


    $query_total = "SELECT (SUM(total)-SUM(ongkir)) AS total FROM ((SELECT dr.nama, SUM(so.totalqty) AS totalqty, COUNT(so.id_trans) AS jumlah, SUM(so.faktur) AS faktur, SUM(so.exp_fee) AS ongkir,SUM(so.discount_faktur) AS discount_faktur,SUM(so.total) AS total FROM olnso so 
    LEFT JOIN mst_dropshipper dr ON dr.id=so.id_dropshipper
    WHERE so.deleted=0 AND so.state='1' AND DATE(so.lastmodified) BETWEEN STR_TO_DATE('$tglstart','%d/%m/%Y')  AND STR_TO_DATE('$tglend','%d/%m/%Y')
    GROUP BY so.id_dropshipper)
    UNION ALL
    (SELECT cus.nama, SUM(b.totalkirim) AS totalqty, COUNT(b.id_trans) AS jumlah, SUM(b.faktur) AS faktur, SUM(b.exp_fee) AS ongkir,SUM(b.discount_faktur) AS discount_faktur,SUM(b.totalfaktur) AS total FROM b2bdo b
    LEFT JOIN mst_b2bcustomer cus ON b.id_customer=cus.id
    WHERE b.deleted=0 AND DATE(b.tgl_trans) BETWEEN STR_TO_DATE('$tglstart','%d/%m/%Y')  AND STR_TO_DATE('$tglend','%d/%m/%Y')
    GROUP BY b.id_customer)) AS tbl";

$rs=mysql_fetch_array( mysql_query($query_total));
$totalsemua = $rs['total'];

$query_b2b =  "SELECT SUM(total) AS total FROM ((SELECT cus.nama, SUM(b.totalkirim) AS totalqty, COUNT(b.id_trans) AS jumlah, SUM(b.faktur) AS faktur, SUM(b.exp_fee) AS ongkir,SUM(b.discount_faktur) AS discount_faktur,SUM(b.totalfaktur) AS total FROM b2bdo b
    LEFT JOIN mst_b2bcustomer cus ON b.id_customer=cus.id
    WHERE b.deleted=0 AND DATE(b.tgl_trans) BETWEEN STR_TO_DATE('$tglstart','%d/%m/%Y')  AND STR_TO_DATE('$tglend','%d/%m/%Y')
    GROUP BY b.id_customer)) AS tbl";
   $rsb2b=mysql_fetch_array( mysql_query($query_b2b)); 
   $totalb2b = $rsb2b['total'];

   


$query_oln =  "SELECT (SUM(total)-SUM(ongkir)) AS total FROM ((SELECT dr.nama, SUM(so.totalqty) AS totalqty, COUNT(so.id_trans) AS jumlah, SUM(so.faktur) AS faktur, SUM(so.exp_fee) AS ongkir,SUM(so.discount_faktur) AS discount_faktur,SUM(so.total) AS total FROM olnso so 
    LEFT JOIN mst_dropshipper dr ON dr.id=so.id_dropshipper
    WHERE so.deleted=0 AND so.state='1' AND DATE(so.lastmodified) BETWEEN STR_TO_DATE('$tglstart','%d/%m/%Y')  AND STR_TO_DATE('$tglend','%d/%m/%Y')
    GROUP BY so.id_dropshipper)) AS tbl";
   $rsoln=mysql_fetch_array( mysql_query($query_oln)); 
   $totaloln = $rsoln['total'];
?>


<form id="form2" name="form2" action="" method="post"  onSubmit="return validasi(this)">
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td height="123" valign="top"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
          
          <tr>
            <td width="100%" class="style99" colspan="7"><strong>
      OLN + B2B REPORT </strong></td>
      <td style="text-align:right">
                <div id="timestamp">
                <?php
                    date_default_timezone_set('Asia/Jakarta');
                    echo $timestamp = date('d/m/Y H:i:s');
                ?>
                </div>  
                
            </td>
            
          </tr>
          <tr>
            <td width="100%" class="style9b" colspan="7">Periode :
            <?php echo"".$tglstart;?> - <?php echo"".$tglend;?>
            &nbsp;&nbsp;&nbsp;&nbsp;
            Total B2B : <?php echo"".number_format($totalb2b);?> (<?php echo number_format((($totalb2b/$totalsemua)*100),2) ?>%) 
            &nbsp;&nbsp;&nbsp;&nbsp;
            Total OLN : <?php echo"".number_format($totaloln);?> (<?php echo number_format((($totaloln/$totalsemua)*100),2) ?>%)</td>   
      </tr>
                
  </table>  
    
    
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  
        <tr>
            <td colspan="8" class="style9"><hr /></td>
          </tr>
      <tr>
      <th width="3%" class="style_title_left"><div align="center">No</div></td>
      <th width="25%" class="style_title"><div align="center">Dropshipper/Customer</div></td>
      <th width="10%" class="style_title"><div align="center">TotalQty</div></td>
      <th width="5%" class="style_title"><div align="center">Jumlah Order</div></td>
      <th width="15%" class="style_title"><div align="right">Total Faktur</div></td>
    <th width="15%" class="style_title"><div align="right">Total Potongan</div></td>
    <th width="15%" class="style_title"><div align="right">Faktur-Potongan</div></td>
    <th width="5%" class="style_title"><div align="right">Persentase</div></td>
      
    </tr>
    <?
    
        $sql_detail = "(SELECT dr.nama, SUM(so.totalqty) AS totalqty, COUNT(so.id_trans) AS jumlah, SUM(so.faktur) AS faktur, SUM(so.exp_fee) AS ongkir,SUM(so.discount_faktur) AS discount_faktur,SUM(so.total) AS total FROM olnso so 
        LEFT JOIN mst_dropshipper dr ON dr.id=so.id_dropshipper
        WHERE so.deleted=0 AND so.state='1' AND DATE(so.lastmodified) BETWEEN STR_TO_DATE('$tglstart','%d/%m/%Y')  AND STR_TO_DATE('$tglend','%d/%m/%Y')
        GROUP BY so.id_dropshipper)
        UNION ALL
        (SELECT cus.nama, SUM(b.totalkirim) AS totalqty, COUNT(b.id_trans) AS jumlah, SUM(b.faktur) AS faktur, SUM(b.exp_fee) AS ongkir,SUM(b.discount_faktur) AS discount_faktur,SUM(b.totalfaktur) AS total FROM b2bdo b
        LEFT JOIN mst_b2bcustomer cus ON b.id_customer=cus.id
        WHERE b.deleted=0 AND DATE(b.tgl_trans) BETWEEN STR_TO_DATE('$tglstart','%d/%m/%Y')  AND STR_TO_DATE('$tglend','%d/%m/%Y')
        GROUP BY b.id_customer)";
  if($type == 1){
        $order = "ORDER BY nama ASC";
  }else{
        $order = "ORDER BY total DESC";
  }
  
    // var_dump($sql_detail);die;
  $sq2 = mysql_query($sql_detail." ".$order);
  $i=1;
  $nomer=0;
  $grand_qty=0;
  $grand_faktur=0;
  $grand_order=0;
  $grand_ongkir=0;
  $grand_total=0;
  $biaya=0;
  while($rs2=mysql_fetch_array($sq2))
  { 
    $nomer++;

      $persen = ($rs2['total']/$totalsemua)*100;
    
  ?>
    <tr>
      <td class="style_detail_left"><div align="left"><?=$nomer;?>
    </div></td>
    <td class="style_detail"><div align="left"><?=$rs2['nama'];?>
    </div></td>
      <td class="style_detail"><div align="center"><?=number_format($rs2['totalqty']);?></div></td>
      <td class="style_detail"><div align="center"><?=number_format($rs2['jumlah']);?></div></td>
      <td class="style_detail"><div align="right"><?=number_format($rs2['faktur']);?></div></td>
    <td class="style_detail"><div align="right"><?=number_format($rs2['discount_faktur']);?></div></td>
      <td class="style_detail"><div align="right"><?=number_format($rs2['total'] - $rs2['ongkir']);?></div></td>
      <td class="style_detail"><div align="center"><?=number_format($persen,2);?>%</div></td>
    </tr>  <?
  $grand_qty+=$rs2['totalqty'];
  $grand_faktur+=$rs2['faktur'];
  $grand_order+=$rs2['jumlah'];
  $grand_disc+=$rs2['discount_faktur'];
  $grand_total+=$rs2['total'] - $rs2['ongkir'];
    $nett_faktur=$grand_faktur-$grand_disc; 
    $dpp=($nett_faktur/1.11); 
  }
  
  ?>
       <tr>
        <td class="style_footer"></td>
            <td class="style_footer"><div align="right">GrandTotal :</div></td>
            <td class="style_footer"><div align="center"><?=number_format($grand_qty);?></div></td>
            <td class="style_footer"><div align="center"><?=number_format($grand_order);?></div></td>
            <td class="style_footer"><div align="right"><?=number_format($grand_faktur);?></div></td>
            <td class="style_footer"><div align="right"><?=number_format($grand_disc);?></div></td>
            <td class="style_footer"><div align="right"><?=number_format($grand_total);?></div></td>
            <td class="style_footer"><div align="right"></td>
       </tr>
     
     
  
  </table>
   
  
   
  
  <div align="center"></div>
</form>

<script language="javascript">
      $(document).ready(function() {
      setInterval(timestamp, 1000);
});

function timestamp() {
    $.ajax({
        url: '../timestamp.php',
        success: function(data) {
            $('#timestamp').html(data);
        },
    });
}

window.print();
</script>
  <div align="center"><span class="style20">
   
  </span> </div>
