<div class="ui-widget ui-form">
    <div class="ui-widget-header ui-corner-top padding5">
        <?php
        	$action = strtoupper($_GET['action']);
			echo 'PASSWORD REQUIRED';			
        ?>
    </div>
    <div class="ui-widget-content ui-corner-bottom">
        <form id="trolndo_pass_form" method="post" action="<?php echo BASE_URL ?>pages/sales_online/trolndo.php?action=process_pass" class="ui-helper-clearfix">
            
			<label for="password" class="ui-helper-reset label-control">Password</label>
            <div class="ui-corner-all form-control">
                <input value="<?php echo $_GET['id'] ?>" type="hidden" id="id" name="id">	
                <input value="" type="password" class="required" id="pass" name="pass">	
            </div>
			
        </form>
    </div>
</div>