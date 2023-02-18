<?php require_once '../../include/config.php' ?>
<?php
$group_acess = unserialize(file_get_contents("../../GROUP_ACCESS_CACHE".$_SESSION['user']['group_id']));
$allow_delete = is_show_menu(DELETE_POLICY, TroubleOrder, $group_acess);

	if(isset($_GET['action']) && strtolower($_GET['action']) == 'json') {
		$page  = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx  = $_GET['sidx'];
        $sord  = $_GET['sord'];

        if(!$sidx) $sidx=1;
               if ($_REQUEST["_search"] == "false") {
       $where = " WHERE m.totalqty <> (SELECT SUM(jumlah_beli) FROM olnsodetail AS d WHERE d.id_trans=m.id_trans) ";
       } else {
       $operations = array(
        'eq' => "= '%s'",            // Equal
        'ne' => "<> '%s'",           // Not equal
        'lt' => "< '%s'",            // Less than
        'le' => "<= '%s'",           // Less than or equal
        'gt' => "> '%s'",            // Greater than
        'ge' => ">= '%s'",           // Greater or equal
        'bw' => "like '%s%%'",       // Begins With
        'bn' => "not like '%s%%'",   // Does not begin with
        'in' => "in ('%s')",         // In
        'ni' => "not in ('%s')",     // Not in
        'ew' => "like '%%%s'",       // Ends with
        'en' => "not like '%%%s'",   // Does not end with
        'cn' => "like '%%%s%%'",     // Contains
        'nc' => "not like '%%%s%%'", // Does not contain
        'nu' => "is null",           // Is null
        'nn' => "is not null"        // Is not null
		); 
	
      $value = $_REQUEST["searchString"];
	  $where = sprintf(" WHERE m.totalqty <> (SELECT SUM(jumlah_beli) FROM olnsodetail AS d WHERE d.id_trans=m.id_trans) AND %s ".$operations[$_REQUEST["searchOper"]], $_REQUEST["searchField"], $value);
	 //echo"<script>alert('where=$where')</script>";
     }
        $sql_trouble = "SELECT m.*,j.nama as dropshipper,e.nama as expedition FROM `olnso` m Left Join `mst_dropshipper` j on (m.id_dropshipper=j.id) Left Join `mst_expedition` e on (m.id_expedition=e.id) ".$where;
        // var_dump($sql_trouble);die;
		$q = $db->query($sql_trouble);
        $count = $q->rowCount();
        
        $count > 0 ? $total_pages = ceil($count/$limit) : $total_pages = 0;
        if ($page > $total_pages) $page=$total_pages;
        $start = $limit*$page - $limit;
        if($start <0) $start = 0;

        $q = $db->query($sql_trouble." ORDER BY `".$sidx."` ".$sord."
							 LIMIT ".$start.", ".$limit);
		$data1 = $q->fetchAll(PDO::FETCH_ASSOC);
        $responce['page'] = $page;
        $responce['total'] = $total_pages;
        $responce['records'] = $count;
        $i=0;
        foreach($data1 as $line) {
        	
			// $allowEdit = array(1,2,3);
			// $allowDelete = array(1,2,3);

		 //    if(in_array($_SESSION['user']['access'], $allowEdit)){
			// $edit = '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/report_online/rpt_trouble.php?action=posting&id='.$line['id_trans'].'\',\'table_trouble\')" href="javascript:;">Posting</a>';
			// }
			// else
			// 	$edit = '<a onclick="javascript:custom_alert(\'Tidak Boleh Print Nota\')" href="javascript:;">Edit</a>';
			
			if($allow_delete)
				$delete = '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/report_online/rpt_trouble.php?action=delete&id='.$line['id_trans'].'\',\'table_trouble\')" href="javascript:;">Cancel</a>';
			else
				$delete = '<a onclick="javascript:custom_alert(\'Tidak Boleh dibatalkan\')" href="javascript:;">Delete</a>';
			
			$responce['rows'][$i]['id']   = $line['id_trans'];
            $responce['rows'][$i]['cell'] = array(
                $line['id_trans'],
                $line['dropshipper'],                
                $line['tgl_trans'],
                number_format($line['totalqty'],0),
				number_format($line['faktur'],0),
				number_format($line['exp_fee'],0),
				number_format($line['total'],0),
				$line['nama'],
				$line['expedition'],
				number_format($line['tunai'],0),
				number_format($line['transfer'],0),
				
				$delete,
            );
            $i++;
        }
        echo json_encode($responce);
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'posting') {
		//update olnso agar jadi 1 krn siap kirim,tapi statenya dikasih string='1' krn tipe datanya enum dan tgl_transnya diupdate jadi sekarang
		$stmt = $db->prepare("Update olnso set state='1',tgl_trans=now() WHERE id_trans=?");
		$stmt->execute(array($_GET['id']));
		//var_dump($stmt);
		
		$affected_rows = $stmt->rowCount();
		if($affected_rows > 0) {
			$r['stat'] = 1;
			$r['message'] = 'Success';
		}
		else {
			$r['stat'] = 0;
			$r['message'] = 'Failed';
		}
		echo json_encode($r);
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'delete') {
		//delete olndeposit krn void invoice		
		$stmt = $db->prepare("delete from olndeposit WHERE id_trans=?");
		$stmt->execute(array($_GET['id']));
		//update trjual agar jadi nol krn void invoice
		$stmt = $db->prepare("Update olnso set total=0,exp_fee=0,faktur=0,totalqty=0,tunai=0,transfer=0,deposit=0,piutang=0,pelunasan=0,deleted=1 WHERE id_trans=?");
		$stmt->execute(array($_GET['id']));
		//var_dump($stmt);die;
		//update trjual_detail agar jadi nol krn void invoice
		$stmt = $db->prepare("update olnsodetail set jumlah_beli=0,harga_satuan=0,subtotal=0 WHERE id_trans=?");
		$stmt->execute(array($_GET['id']));
		//var_dump($stmt);die;
		
		$affected_rows = $stmt->rowCount();
		if($affected_rows > 0) {
			$r['stat'] = 1;
			$r['message'] = 'Success';
		}
		else {
			$r['stat'] = 0;
			$r['message'] = 'Failed';
		}
		echo json_encode($r);
		exit;
	}
	else if(isset($_GET['action']) && strtolower($_GET['action']) == 'delete_detail') {
		//delete olnso utk data yang bermasalah		
		$stmt = $db->prepare("delete from olnsodetail WHERE id_so_d=?");
		$stmt->execute(array($_GET['id']));
		
		$affected_rows = $stmt->rowCount();
		if($affected_rows > 0) {
			$r['stat'] = 1;
			$r['message'] = 'Success';
		}
		else {
			$r['stat'] = 0;
			$r['message'] = 'Failed';
		}
		echo json_encode($r);
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'json_sub') {
	
		$id = $_GET['id'];
		
		$where = "WHERE pd.id_trans = '".$id."' ";
        $sql_data="SELECT pd.* FROM `olnsodetail` pd ".$where;
		//var_dump($sql_data); die;
		$q = $db->query($sql_data);
		$count = $q->rowCount();
		$data1 = $q->fetchAll(PDO::FETCH_ASSOC);
		
        $i=0;
        $responce = '';
        foreach($data1 as $line){
		$delete_detail = '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/report_online/rpt_trouble.php?action=delete_detail&id='.$line['id_so_d'].'\',\'table_trouble\')" href="javascript:;">Delete</a>';
            $responce->rows[$i]['id']   = $line['id_trans'];
            $responce->rows[$i]['cell'] = array(
                $i+1,
                $line['id_so_d'],
                $line['id_product'],
                $line['namabrg'],
                $line['size'],
                 number_format($line['harga_satuan'],0),
                 number_format($line['jumlah_beli'],0),                
                 number_format($line['subtotal'],0),                
                 $delete_detail,                
            );
            $i++;
        }
        echo json_encode($responce);
		exit;
	}
	
?>
<table id="table_trouble"></table>
<div id="pager_table_trouble"></div>

<script type="text/javascript">
    $(document).ready(function(){

        $("#table_trouble").jqGrid({
            url:'<?php echo BASE_URL.'pages/report_online/rpt_trouble.php?action=json'; ?>',
            datatype: "json",
            colNames:['ID','Dropshipper','Tanggal Transaksi','Qty','Faktur','Ongkir','Total Faktur','Expedisi','Penerima','Tunai','Transfer','Delete'],
            colModel:[
                {name:'id_trans',index:'id_trans', width:80, search:true, stype:'text', searchoptions:{sopt:['cn']}},
                {name:'dropshipper',index:'j.nama', width:100, searchoptions: {sopt:['cn']}},                
                {name:'tgl_trans',index:'tgl_trans', width:80, searchoptions: {sopt:['cn']},formatter:"date", formatoptions:{srcformat:"Y-m-d", newformat:"d/m/Y"}, align:'center'},
                {name:'totalqty',index:'totalqty', align:'right', width:40, searchoptions: {sopt:['cn']}},
                {name:'faktur',index:'faktur', align:'right', width:80, searchoptions: {sopt:['cn']}},              
				{name:'exp_fee',index:'exp_fee', align:'right', width:60, searchoptions: {sopt:['cn']}},
                {name:'totalfaktur',index:'totalfaktur', align:'right', width:80, searchoptions: {sopt:['cn']}},
                {name:'expedition',index:'expedition', align:'left', width:80, searchoptions: {sopt:['cn']}},
				{name:'nama',index:'nama', align:'left', width:80, searchoptions: {sopt:['cn']}},
				{name:'tunai',index:'tunai', align:'right', width:80, searchoptions: {sopt:['cn']}},
                {name:'transfer',index:'transfer', align:'right', width:80, searchoptions: {sopt:['cn']}},
                
                {name:'Delete',index:'delete', align:'center', width:50, sortable: false, search: false},
            ],
            rowNum:20,
            rowList:[10,20,30],
            pager: '#pager_table_trouble',
            sortname: 'id_trans',
            autowidth: true,
            height: '400',
            viewrecords: true,
            rownumbers: true,
            sortorder: "asc",
            caption:"Trouble Order",
            ondblClickRow: function(rowid) {
                alert(rowid);
            },
            subGrid : true,
            subGridUrl : '<?php echo BASE_URL.'pages/report_online/rpt_trouble.php?action=json_sub'; ?>',
            subGridModel: [
			            	{ 
			            		name : ['No','ID_SO_Det','Kode','Barang','Size','Harga','Qty(pcs)','Subtotal','Action'], 
			            		width : [40,40,40,40,300,50,30,50,50],
			            		align : ['right','right','center','left','center','right','right','right','center'],
			            	} 
			            ],
						
            
        });
        $("#table_trouble").jqGrid('navGrid','#pager_table_trouble',{edit:false,add:false,del:false});
    })
</script>