<script type="text/javascript" src="../../assets/js/jquery-1.4.js"></script>
<style type="text/css">
.style9 {
font-size: 9pt; 
font-family:Tahoma;
}
.style9b {
  font-size: 14pt;
  font-weight: bold;
  font-family: Tahoma;
}
.style99 {font-size: 10pt; font-family:Tahoma}
.style10 {font-size: 10pt; font-weight: bold; font-family:Tahoma;}
.style19 {font-size: 13pt; font-weight: bold; font-family:Tahoma;}
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
.style6 {color: #000000;
  font-size: 9pt;
  font-weight: bold;
  font-family: Tahoma;
}
.style19b { color: #000000;
  font-size: 11pt;
  font-weight: bold;
  font-family: Tahoma;
}
.style_title {  color: #000000;
  font-size: 10pt; 
  font-family: Tahoma;
  border-top: 1px solid black;
  border-bottom: 1px solid black;
  border-right: 1px solid black;
  
  
  padding: 3px;
}
.style_title_left { color: #000000;
  font-size: 10pt; 
  font-family: Tahoma;
  border-top: 1px solid black;
  border-bottom: 1px solid black;
  border-right: 1px solid black;
  border-left: 1px solid black;
  
  padding: 3px;
}
.style_detail { color: #000000;
  font-size: 10pt; 
  font-family: Tahoma;
  border-right: 1px solid black;
  padding: 3px;
}
.style_detail2 { color: #000000;
  font-size: 6pt; 
  font-family: Tahoma;
  border-right: 1px solid black;
}
.style_detail3 { color: #000000;
  font-size: 10pt; 
  font-family: Tahoma;
  border-top: 1px solid black;
  border-right: 1px solid black;
  border-bottom: 1px solid black;
}
.style_font {  color: #000000;
  font-size: 10pt; 
  font-family: Tahoma;
}
.style_detail_left {  color: #000000;
  font-size: 10pt; 
  font-family: Tahoma;
  border-left: 1px solid black;
  border-right: 1px solid black;
  padding: 3px;
}
.font{
    font-size: 10pt; 
    font-family: Tahoma;
}
#container{width:100%;}
#timestamp{font-size: 9pt;  
  font-family: Tahoma;}
#left{float:left;width:30%;}
#right{float:right;width:70%;}
#left2{float:left;width:70%;}
#right2{float:right;width:30%;}
#left3{float:left;width:93%;}
#right3{float:right;width:7%;}
@page {
        size: A4;
        margin: 15px;
    }
</style>

<?php
    require "../../include/koneksi.php";
    $startdate = $_GET['start'];
    $st = explode("/",$startdate);
    $enddate = $_GET['end'];
    $en = explode("/",$enddate);
    $filter = $_GET['filter'];

    $date=date_create(date("Y-m-d"));
    $date1=date_create($en[2].'/'.$en[1].'/'.$en[0]);
    $date2=date_create($st[2].'/'.$st[1].'/'.$st[0]);

    $sql = "SELECT det.uraian, det.buktikas, det.no_akun, det.nama_akun, det.debet, det.kredit, mst.total_debet, mst.total_kredit, mst.kode, mst.tanggal
    FROM cashreceipt mst 
    LEFT JOIN cashreceipt_det det ON det.id_parent=mst.id
    WHERE date(mst.tanggal) between '".$st[2].'/'.$st[1].'/'.$st[0]."' and '".$en[2].'/'.$en[1].'/'.$en[0]."' AND mst.deleted=0  ";
    $result= mysql_query($sql);

?>

<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <br><br>
        <td colspan=5 class="style9b" >PT. AGUNG KEMUNINGWIJAYA</td>
    </tr>
    <tr>
        <td colspan=5 class="style99" >Taman Kopo Indah 1 No.6 Bandung - Indonesia</td>
    </tr>
    <tr>
        <td colspan=5 class="style99" >TEL : 022 - 5401972 &nbsp;&nbsp; FAX : 022 - 55407084</td>
    </tr>
    <tr>
        <td colspan=5>&nbsp;</td>
    </tr>
    <tr>
        <td colspan=5 class="style19" align="center">LAPORAN KAS BULANAN</td>
    </tr>
    <tr>
        <td colspan=5 class="style10" align="center"><div style="text-transform:uppercase">TANGGAL : <?=date_format($date2,"d F Y")?> - <?=date_format($date1,"d F Y")?></div></td>
    </tr>
    <tr>
        <td colspan=5>&nbsp;</td>
    </tr>
    <tr>
        <td width='1%'><div class="style_title_left" align='left' >NO</div></td>
        <td width='44%'><div class="style_title" style="border-top: 1px solid black;" align='center' >URAIAN</div></td>
        <td width='25%'><div class="style_title" style="border-top: 1px solid black;" align='center' >BUKTI KAS</div></td>
        <td width='15%'><div class="style_title" style="border-top: 1px solid black;" align='center' >DEBET (Rp.)</div></td>
        <td width='15%'><div class="style_title" style="border-top: 1px solid black;" align='center' >KREDIT (Rp.)</div></td>
    </tr>
    <?php
        $kasbon=0;
        $no=0;
        $jumlah=1;
        $totdebet=0;
        $totdebettnpsaldo=0;
        $totkredit=0;
        $tgl = "";
        $pertama = 0;
        while ($data = mysql_fetch_array($result)){
          $kasbon = $data['kode'];
          
          $tgl2 = $tgl;
          $tgl = $data['tanggal'];

          if($tgl2 != $tgl && $jumlah != '1'){
            $no=0;
            ?>
                <tr>
                <td><div class='style_detail_left' align="right"><font style="color:white;">.</font></div></td>
                <td><div class='style_detail' align="center"><font style="color:white;">.</font></div></td>
                <td><div class='style_detail' align="center"><font style="color:white;">.</font></div></td>
                <td><div class='style_detail' align="center"><font style="color:white;">.</font></div></td>
                <td><div class='style_detail' align="center"><font style="color:white;">.</font></div></td>
                </tr>
                <?php
            }
            ?>
            <tr><?php
              if($jumlah == 1){
                $pertama = $data['debet'];
                ?><td><div class='style_detail_left' align="right"><font style="color:white;">.</font></div></td>
                 <td><div class='style_detail' style='text-decoration: underline;text-underline-position: under;'><?=$data['uraian']?></div></td>
                <?php
              }else{
                  if($tgl2 != $tgl){
                    ?><td><div class='style_detail_left' align="right"><font style="color:white;">.</font></div></td>
                    <td><div class='style_detail' style='text-decoration: underline;text-underline-position: under;'><?=$data['uraian']?></div></td>
                   <?php
                  }else{
                    ?><td><div class='style_detail_left' align="right"><?=$no?></div></td>
                    <td><div class='style_detail'><?=$data['uraian']?></div></td>
                    <?php
                 }
              }
              
              if($data['buktikas'] == ''){
                ?><td><div class='style_detail' align="center"><font style="color:white;">.</font></div></td><?php
              }else{
                ?><td><div class='style_detail' align="center"><?=$data['buktikas']?></div></td><?php
              }

              if($data['debet'] == '' || $data['debet']=='0'){
                ?><td><div class='style_detail' align="right"><font style="color:white;">.</font></div></td><?php
              }else{
                ?><td><div class='style_detail' align='right'><?=number_format($data['debet'],0)?></div></td><?php
                if($tgl2 == $tgl){
                  $totdebettnpsaldo = $totdebettnpsaldo + $data['debet'];
                }
              }

              if($data['kredit'] == '' || $data['kredit']=='0'){
                ?><td><div class='style_detail' align="right"><font style="color:white;">.</font></div></td><?php
              }else{
                ?><td><div class='style_detail' align='right'><?=number_format($data['kredit'],0)?></div></td><?php
              }
            ?>
            </tr>
            <?php
            $no++;
            $jumlah++;
            $totdebet = $totdebet + $data['debet'];
            $totkredit = $totkredit + $data['kredit'];
        }

      for ($i=$jumlah; $i <= 17; $i++) { 
        ?><tr>
                <td><div class='style_detail_left' align="right"><font style="color:white;">.</font></div></td>
                <td><div class='style_detail' align="center"><font style="color:white;">.</font></div></td>
                <td><div class='style_detail' align="center"><font style="color:white;">.</font></div></td>
                <td><div class='style_detail' align="center"><font style="color:white;">.</font></div></td>
                <td><div class='style_detail' align="center"><font style="color:white;">.</font></div></td>
              </tr><?php
      }

        $sql = "SELECT det.uraian, det.buktikas, det.no_akun, det.nama_akun, det.debet, det.kredit, mst.total_debet, mst.total_kredit, mst.kode, mst.tanggal
            FROM cashreceipt mst 
            LEFT JOIN cashreceipt_det det ON det.id_parent=mst.id
            WHERE date(mst.tanggal) = '".$tgl."' AND mst.deleted=0  ";
        $result= mysql_query($sql);
        $totsaldodebet = 0;
        $totsaldokredit = 0;
        $kasbon = 0;
        while ($data = mysql_fetch_array($result)){
            $kasbon = $data['kode'];
            $totsaldodebet = $totsaldodebet + $data['debet'];
            $totsaldokredit = $totsaldokredit + $data['kredit'];
        }


    ?>
    <tr>
        <td><div class='style_detail_left'>&nbsp;</div></td>
        <td><div class='style_detail'>&nbsp;</div></td>
        <td><div class='style_detail'>&nbsp;</div></td>
        <td><div class='style_detail' style='border-top: 1px solid black;' align='right'><?=number_format($totdebettnpsaldo,0)?></div></td>
        <td><div class='style_detail' style='border-top: 1px solid black;' align='right'><?=number_format($totkredit,0)?></div></td>
    </tr>
    <tr>
        <td><div class='style_detail_left'>&nbsp;</div></td>
        <td><div class='style_detail' align='center'>Saldo Per : <?=date_format($date1,"d M Y")?></div></td>
        <td><div class='style_detail'>&nbsp;</div></td>
        <td><div class='style_detail'>&nbsp;</div></td>
        <td><div class='style_detail' align='right'><?=number_format($totsaldodebet - $totsaldokredit,0)?></div></td>
    </tr>
    <tr>
        <td><div class='style_detail_left'></div></td>
        <td><div class='style_detail'></div></td>
        <td><div class='style_detail'></div></td>
        <td><div class='style_detail3'></div></td>
        <td><div class='style_detail3'></div></td>
    </tr>
    <tr>
        <td><div class='style_detail_left' style='border-bottom: 1px solid black;'>&nbsp;</div></td>
        <td><div class='style_detail' align='center' style='border-bottom: 1px solid black;'>J U M L A H :</div></td>
        <td><div class='style_detail' style='border-bottom: 1px solid black;'>&nbsp;</div></td>
        <td><div class='style_detail' style='border-bottom: 1px solid black;' align='right'><?=number_format($totdebettnpsaldo+$pertama,0)?></div></td>
        <!-- <td><div class='style_detail' style='border-bottom: 1px solid black;' align='right'><?=number_format($totkredit+($totdebet-$totkredit),0)?></div></td> -->
        <td><div class='style_detail' style='border-bottom: 1px solid black;' align='right'><?=number_format($totkredit+($totsaldodebet - $totsaldokredit),0)?></div></td>
    </tr>
    <tr>
        <td colspan=5>&nbsp;</td>
    </tr>
    <tr>
        <td colspan=5>&nbsp;</td>
    </tr>
    <tr>
        <td><div class='font'>&nbsp;</div></td>
        <td><div class='font' align='center'>DIKETAHUI OLEH :</div></td>
        <td><div class='font'>&nbsp;</div></td>
        <td colspan=2><div class='font' align='center'>DIBUAT OLEH :</div></td>
    </tr>
    <tr>
        <td colspan=5>&nbsp;</td>
    </tr>
    <tr>
        <td colspan=5>&nbsp;</td>
    </tr>
    <tr>
        <td colspan=5>&nbsp;</td>
    </tr>
    <tr>
        <td><div class='font'>&nbsp;</div></td>
        <td><div class='font' align='center' style='text-decoration: underline;text-underline-position: under;'>ENRICO TJANDRA</div></td>
        <td><div class='font'>&nbsp;</div></td>
        <td colspan=2><div class='font' align='center' style='text-decoration: underline;text-underline-position: under;'>NENDEN N</div></td>
    </tr>
    <tr>
        <td colspan=5>&nbsp;</td>
    </tr>
    <tr>
        <td colspan=5>&nbsp;</td>
    </tr>
    <tr>
        <td colspan=5>&nbsp;</td>
    </tr>
    <tr>
        <td></td>
        <td><div class='font' align='left' style='text-decoration: underline;text-underline-position: under;'>CATATAN SALDO :</div></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td align='right'>1.</td>
        <td><div class='font' align='left'>Uang Tunai</div></td>
        <td align='right'><div class='font'><?=number_format(($totsaldodebet - $totsaldokredit)-$kasbon,0)?></div></td>
        <td style='padding:15px;'></td>
        <td></td>
    </tr>
    <tr>
        <td align='right'>2.</td>
        <td><div class='font' align='left'>Kas Bon</div></td>
        <td align='right'><div class='font'><?=number_format($kasbon,0)?></div></td>
        <td></td>
        <td></td>
    </tr>
</table>
<script>
    window.print();
</script>