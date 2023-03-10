<?php require_once '../../include/config.php' ?>
<?php
$group_acess = unserialize(file_get_contents("../../GROUP_ACCESS_CACHE".$_SESSION['user']['group_id']));
$allow_add = is_show_menu(ADD_POLICY, B2BSalesman, $group_acess);
$allow_edit = is_show_menu(EDIT_POLICY, B2BSalesman, $group_acess);
$allow_delete = is_show_menu(DELETE_POLICY, B2BSalesman, $group_acess);

	if(isset($_GET['action']) && strtolower($_GET['action']) == 'json') {
		$page  = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx  = $_GET['sidx'];
        $sord  = $_GET['sord'];

        if(!$sidx) $sidx=1;

		//searching _filter---------------------------------------------------------
       if ($_REQUEST["_search"] == "false") {
       $where = "WHERE deleted=0 ";
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
	  $where = sprintf(" where deleted=0 AND %s ".$operations[$_REQUEST["searchOper"]], $_REQUEST["searchField"], $value);
	
     }
//--------------end of searching--------------------		
     //   $where = "WHERE deleted=0 ";
        $q = $db->query("SELECT * FROM `mst_b2bsalesman` a ".$where);
		$count = $q->rowCount();
        
        $count > 0 ? $total_pages = ceil($count/$limit) : $total_pages = 0;
        if ($page > $total_pages) $page=$total_pages;
        $start = $limit*$page - $limit;
        if($start <0) $start = 0;

        $q = $db->query("SELECT a.*  
							 FROM `mst_b2bsalesman` a
							 ".$where."
							 ORDER BY `".$sidx."` ".$sord."
							 LIMIT ".$start.", ".$limit);
		$data1 = $q->fetchAll(PDO::FETCH_ASSOC);

        $statusToko = '';
        $getStat = $db->prepare("SELECT * FROM tbl_status LIMIT 1");
        $getStat->execute();
        $stat = $getStat->fetchAll();
        foreach ($stat as $stats) {
            // $id = $stats['id'];
            $statusToko = $stats['status'];
        }

        $responce['page'] = $page;
        $responce['total'] = $total_pages;
        $responce['records'] = $count;
        $i=0;
        foreach($data1 as $line) {
        	// $allowEdit = array(1,2,3);
			// $allowDelete = array(1,2,3);
            if ($statusToko == 'Tutup') {
                $edit = '<a onclick="javascript:custom_alert(\'Maaf, Toko Sudah Tutup\')" href="javascript:;">Edit</a>';
                $delete = '<a onclick="javascript:custom_alert(\'Maaf, Toko Sudah Tutup\')" href="javascript:;">Delete</a>';
            } else {
			if($allow_edit)
				$edit = '<a onclick="javascript:popup_form(\''.BASE_URL.'pages/master_b2b/b2bsalesman.php?action=edit&id='.$line['id'].'\',\'table_b2bsalesman\')" href="javascript:;">Edit</a>';
			else
				$edit = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">Edit</a>';
        	
			if($allow_delete)
				$delete = '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/master_b2b/b2bsalesman.php?action=delete&id='.$line['id'].'\',\'table_b2bsalesman\')" href="javascript:;">Delete</a>';
			else
				$delete = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">Delete</a>';
			}

            $category = '';
            $ex = explode(",", $line['category']);
                for ($a=0; $a < COUNT($ex); $a++) { 
                    $getcat = $db->prepare("SELECT * FROM mst_b2bcategory_sale WHERE deleted=0 AND id='".$ex[$a]."' ");
                    $getcat->execute();
                    $statcat = $getcat->fetchAll();
                    foreach ($statcat as $cat) {
                        if ($a==0) {
                            $category .= $cat['nama'];
                        } else {
                            $category .= ', '.$cat['nama'];
                        }
    
                    }
                }

            $responce['rows'][$i]['id']   = $line['id'];
            $responce['rows'][$i]['cell'] = array(
                $line['id'],
				$line['nama'],
                $line['alamat'],                
                $line['no_telp'],                
                $line['disc'],                
                $line['type'],                
                $category,                
				$edit,
				$delete,
            );
            $i++;
        }
        echo json_encode($responce);
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'add') {
		include 'b2bsalesman_form.php';exit();
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'edit') {
		include 'b2bsalesman_form.php';exit();
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'delete') {
		$stmt = $db->prepare("UPDATE mst_b2bsalesman SET deleted=? WHERE id=?");
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
            $category = '';
            if(isset($_POST['category'])){
                for ($i=0; $i < COUNT($_POST['category']); $i++) { 
                    if ($i == 0) {
                        $category .= $_POST['category'][$i];
                    } else {
                        $category .= ','.$_POST['category'][$i];
                    }
                    
                }
            }

			$stmt = $db->prepare("UPDATE mst_b2bsalesman SET nama=?,alamat=?,no_telp=?,disc=?,type=?,category=?,user=?, lastmodified = NOW() WHERE id=?");
			$stmt->execute(array($_POST['nama'],$_POST['alamat'],$_POST['no_telp'],$_POST['disc'],$_POST['tipe'], $category,$_SESSION['user']['username'], $_POST['id']));
			
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
            $category = '';
            if(isset($_POST['category'])){
                for ($i=0; $i < COUNT($_POST['category']); $i++) { 
                    if ($i == 0) {
                        $category .= $_POST['category'][$i];
                    } else {
                        $category .= ','.$_POST['category'][$i];
                    }
                    
                }
            }

			$stmt = $db->prepare("INSERT INTO  mst_b2bsalesman(`nama`,`alamat`,`no_telp`,`disc`,`type`,`category`,`user`,`lastmodified`) VALUES(?, ?, ?, ?, ?, ?,NOW())");
			if($stmt->execute(array($_POST['nama'],$_POST['alamat'], $_POST['no_telp'], $_POST['disc'], $_POST['tipe'],$category,$_SESSION['user']['username']))) {
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
<table id="table_b2bsalesman"></table>
<div id="pager_table_b2bsalesman"></div>
<div class="btn_box">
<!--
<a href="javascript: void(0)" 
   onclick="window.open('pages/kirim/sodetail.php');">
   <button class="btn btn-success">Tambah</button></a>
-->
<?php
	
	// $allow = array(1,2,3);
    $statusToko = '';
    $getStat = $db->prepare("SELECT * FROM tbl_status LIMIT 1");
    $getStat->execute();
    $stat = $getStat->fetchAll();
    foreach ($stat as $stats) {
        $statusToko = $stats['status'];
    }
    
    if ($statusToko == 'Tutup') {
        echo '<button type="button" onclick="javascript:custom_alert(\'Maaf, Toko Sudah Tutup\')" class="btn">Tambah</button>';
    }else{
	if($allow_add) {
		echo '<button type="button" onclick="javascript:popup_form(\''.BASE_URL.'pages/master_b2b/b2bsalesman.php?action=add\',\'table_b2bsalesman\')" class="btn">Tambah</button>';
	}}
	
?>
</div>
<script type="text/javascript">
    $(document).ready(function(){

        $("#table_b2bsalesman").jqGrid({
            url:'<?php echo BASE_URL.'pages/master_b2b/b2bsalesman.php?action=json'; ?>',
            /*postData: {
                'title': function() {return $('#sJudul').val(); },
                'sales_id': function() {return $('#sSales_id').val(); },
                'Name': function() {return $('#sCustomer').val(); },
                'summary_status': function() {return $('#sStatus').val(); },
            },*/
            datatype: "json",
            colNames:['ID','Name','Address','Phone','Disc','Type','Category Sale','Edit','Delete'],
            colModel:[
                {name:'id',index:'id', align:'right',width:30, searchoptions: {sopt:['cn']}},
                {name:'nama',index:'nama', width:300, searchoptions: {sopt:['cn']}},                
                {name:'alamat',index:'alamat', width:170, searchoptions: {sopt:['cn']}},                
                {name:'no_telp',index:'no_telp', align:'center', width:30, searchoptions: {sopt:['cn']}},                
                {name:'disc',index:'disc', align:'right', width:30, searchoptions: {sopt:['cn']}},                
                {name:'type',index:'type', align:'center', width:30, searchoptions: {sopt:['cn']}},                
                {name:'category',index:'category', align:'center', width:75, searchoptions: {sopt:['cn']}},                
                {name:'Edit',index:'edit', align:'center', width:50, sortable: false, search: false},
                {name:'Delete',index:'delete', align:'center', width:50, sortable: false, search: false},
            ],
            rowNum:20,
            rowList:[10,20,30],
            pager: '#pager_table_b2bsalesman',
            sortname: 'nama',
            autowidth: true,
            height: '460',
            viewrecords: true,
            rownumbers: true,
            sortorder: "asc",
            caption:"Master B2B Salesman",
            ondblClickRow: function(rowid) {
                alert(rowid);
            }
        });
        $("#table_b2bsalesman").jqGrid('navGrid','#pager_table_b2bsalesman',{edit:false,add:false,del:false});
    })
</script>