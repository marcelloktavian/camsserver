<?php require_once '../../include/config.php' ?>
<?php
	if(isset($_GET['action']) && strtolower($_GET['action']) == 'json') {
		$page  = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx  = $_GET['sidx'];
        $sord  = $_GET['sord'];

        if(!$sidx) $sidx=1;

		//searching _filter---------------------------------------------------------
       if ($_REQUEST["_search"] == "false") {
       $where = "WHERE a.deleted=0 ";
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
	  $where = sprintf(" where a.deleted=0 AND %s ".$operations[$_REQUEST["searchOper"]], $_REQUEST["searchField"], $value);
	
     }
//--------------end of searching--------------------		
     //   $where = "WHERE deleted=0 ";
        $q = $db->query("SELECT a.*,c.nama as category FROM `mst_expedition` a LEFT JOIN `mst_expeditioncat` c on a.id_expeditioncat=c.id  ".$where);
		$count = $q->rowCount();
        
        $count > 0 ? $total_pages = ceil($count/$limit) : $total_pages = 0;
        if ($page > $total_pages) $page=$total_pages;
        $start = $limit*$page - $limit;
        if($start <0) $start = 0;

        $q = $db->query("SELECT a.*,c.nama as category FROM `mst_expedition` a LEFT JOIN `mst_expeditioncat` c on a.id_expeditioncat=c.id ".$where." ORDER BY `".$sidx."` ".$sord." LIMIT ".$start.", ".$limit);
		$data1 = $q->fetchAll(PDO::FETCH_ASSOC);
        $responce['page'] = $page;
        $responce['total'] = $total_pages;
        $responce['records'] = $count;
        $i=0;
        foreach($data1 as $line) {
        	$allowEdit = array(1,2,3);
			$allowDelete = array(1,2,3);
			if(in_array($_SESSION['user']['access'], $allowEdit))
				$edit = '<a onclick="javascript:popup_form(\''.BASE_URL.'pages/master_online/expedition.php?action=edit&id='.$line['id'].'\',\'table_expedition\')" href="javascript:;">Edit</a>';
			else
				$edit = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">Edit</a>';
        	
			if(in_array($_SESSION['user']['access'], $allowDelete))
				$delete = '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/master_online/expedition.php?action=delete&id='.$line['id'].'\',\'table_expedition\')" href="javascript:;">Delete</a>';
			else
				$delete = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">Delete</a>';
			
            $responce['rows'][$i]['id']   = $line['id'];
            $responce['rows'][$i]['cell'] = array(
                $line['id'],
                $line['kode'],                
                $line['nama'],                
                $line['category'],                
                $line['kode_warna'],                
                $edit,
				$delete,
            );
            $i++;
        }
        echo json_encode($responce);
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'add') {
		include 'expedition_form.php';exit();
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'edit') {
		include 'expedition_form.php';exit();
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'delete') {
		$stmt = $db->prepare("UPDATE mst_expedition SET deleted=? WHERE id=?");
		$stmt->execute(array(1, $_GET['id']));
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
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'process') {
		if(isset($_POST['id'])) {
			$stmt = $db->prepare("UPDATE mst_expedition SET kode=?,nama=?,id_expeditioncat=?,kode_warna=?,user=?, lastmodified = NOW() WHERE id=?");
			$stmt->execute(array($_POST['kode'],strtoupper($_POST['nama']),$_POST['id_expeditioncat'],$_POST['kode_warna'], $_SESSION['user']['username'], $_POST['id']));
			$affected_rows = $stmt->rowCount();
			if($affected_rows > 0) {
				$r['stat'] = 1;
				$r['message'] = 'Success';
			}
			else {
				$r['stat'] = 0;
				$r['message'] = 'Failed';
			}
		}
		else {
			$stmt = $db->prepare("INSERT INTO  mst_expedition(`kode`,`nama`,`id_expeditioncat`,`kode_warna`,`user`,`lastmodified`) VALUES( ?, ?,  ?,  ?,  ?, NOW())");
			//var_dump($stmt);die;
			if($stmt->execute(array($_POST['kode'],strtoupper($_POST['nama']),$_POST['id_expeditioncat'],$_POST['kode_warna'],$_SESSION['user']['username']))) {
				$r['stat'] = 1;
				$r['message'] = 'Success';
			}
			else {
				$r['stat'] = 0;
				$r['message'] = 'Failed';
			}
		}	
		echo json_encode($r);
		exit;
	}
?>
<table id="table_expedition"></table>
<div id="pager_table_expedition"></div>
<div class="btn_box">
<?php
	$allow = array(1,2,3);
	if(in_array($_SESSION['user']['access'], $allow)) {
		echo '<button type="button" onclick="javascript:popup_form(\''.BASE_URL.'pages/master_online/expedition.php?action=add\',\'table_expedition\')" class="btn">Tambah</button>';
	}
?>
</div>
<script type="text/javascript">
    $(document).ready(function(){

        $("#table_expedition").jqGrid({
            url:'<?php echo BASE_URL.'pages/master_online/expedition.php?action=json'; ?>',
            datatype: "json",
            colNames:['ID','Kode','Nama','Kategori','Kode_Warna','Edit','Delete'],
            colModel:[
                {name:'ID',index:'id', width:70, searchoptions: {sopt:['cn']}},
                {name:'Kode',index:'kode', width:70, searchoptions: {sopt:['cn']}},
                {name:'Nama',index:'nama', width:170, searchoptions: {sopt:['cn']}},                
                {name:'Category',index:'category', width:100, searchoptions: {sopt:['cn']}},                
                {name:'Kode_warna',index:'kode_warna', width:70, searchoptions: {sopt:['cn']}},                
                {name:'Edit',index:'edit', align:'center', width:50, sortable: false, search: false},
                {name:'Delete',index:'delete', align:'center', width:50, sortable: false, search: false},
            ],
            rowNum:10,
            rowList:[10,20,30],
            pager: '#pager_table_expedition',
            sortname: 'nama',
            autowidth: true,
            height: '230',
            viewrecords: true,
            rownumbers: true,
            sortorder: "asc",
            caption:"Master Category",
            ondblClickRow: function(rowid) {
                alert(rowid);
            }
        });
        $("#table_expedition").jqGrid('navGrid','#pager_table_expedition',{edit:false,add:false,del:false});
    })
</script>