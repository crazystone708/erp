<?php /* Smarty version 2.6.26, created on 2018-12-31 16:41:45
         compiled from sysmanage/menu_show.html */ ?>
<!DOCTYPE html>
<html>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<body class="gray-bg">
<div class="wrapper wrapper-content animated fadeInRight">
  <div class="row">
    <div class="col-sm-12">
      <div class="ibox float-e-margins">
        <div class="ibox-title">
          <h5><i class="fa fa-home"></i> 系统菜单</h5>
          <div class="ibox-tools"> <a href="<?php echo @ACT; ?>
/sysmanage/Menu/menu_add/" class="btn btn-xs btn-primary"><i class="fa fa-plus"></i> 添加</a> <a href="?" class="btn btn-xs btn-danger"><i class="fa fa-refresh"></i>刷新</a> </div>
        </div>
        <div class="ibox-content">
          <div class="treeClassBody"><?php echo $this->_tpl_vars['treeHtml']; ?>
</div>
        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>
<script type="text/javascript">
$(document).ready(function () {

	$('.i-checks').iCheck({
		checkboxClass: 'icheckbox_square-green',
		radioClass: 'iradio_square-green',
	});
	
	//更改启用
	$("body").on("blur", ".treeVisible", function() {
		id =$(this).attr("data-id");
		vis =parseInt($(this).val());
		if(vis>0){
		   visible=1;
		}else{
			visible=0;
		}
		$.ajax({
			type: "POST",
			url: "<?php echo @ACT; ?>
/sysmanage/Menu/menu_modify_visible/",
			data:{"visible":visible,"id":id},
			dataType:"json",
			success: function(data){
				if(data.statusCode=='200'){
					layer.msg('操作成功', {icon: 1});   
				}
				console.log(data.rtnstatus);
			}
		});
	});
	//更改排序
	$("body").on("blur", ".treeSort", function() {
		id =$(this).attr("data-id");
		sort =$(this).val();
		$.ajax({
			type: "POST",
			url: "<?php echo @ACT; ?>
/index.php/sysmanage/Menu/menu_modify_sort/",
			data:{"sort":sort,"id":id},
			dataType:"json",
			success: function(data){
				if(data.statusCode=='200'){
					layer.msg('操作成功', {icon: 1});   
				}
				console.log(data.rtnstatus);
			}
		});
	});
	//更改排序
	$("body").on("blur", ".treeName", function() {
		id =$(this).attr("data-id");
		value =$(this).val();
		$.ajax({
			type: "POST",
			url: "<?php echo @ACT; ?>
/index.php/sysmanage/Menu/menu_modify_name/",
			data:{"name":value,"id":id},
			dataType:"json",
			success: function(data){
				if(data.statusCode=='200'){
					layer.msg('操作成功', {icon: 1});   
				}
				console.log(data.rtnstatus);
			}
		});
	});
	//更改链接地址
	$("body").on("blur", ".treeUrl", function() {
		id =$(this).attr("data-id");
		value =$(this).val();
		$.ajax({
			type: "POST",
			url: "<?php echo @ACT; ?>
/index.php/sysmanage/Menu/menu_modify_url/",
			data:{"url":value,"id":id},
			dataType:"json",
			success: function(data){
				if(data.statusCode=='200'){
					layer.msg('操作成功', {icon: 1});   
				}
				console.log(data.rtnstatus);
			}
		});
	});
	
	$("body").on("click", ".single_operation", function() {
		menu_id =$(this).attr("data-id");
		single_act =$(this).attr("data-act")
		if(single_act=="add"){
			act_url="<?php echo @ACT; ?>
/sysmanage/Menu/menu_add/";
			layer.open({
				type: 2,
				title: '添加菜单',
				shadeClose: true,
				fixed: false, //不固定
				area: ['800px', '500px'],
				content: '<?php echo @ACT; ?>
/sysmanage/Menu/menu_add/id/'+menu_id+'/' //iframe的url
			});			
			return false;	
		}else if(single_act=="modify"){
			layer.open({
				type: 2,
				title: '修改菜单',
				shadeClose: true,
				fixed: false, //不固定
				area: ['800px', '500px'],
				content: '<?php echo @ACT; ?>
/sysmanage/Menu/menu_modify/id/'+menu_id+'/' //iframe的url
			});			
			return false;
		}else if(single_act=="del"){
			act_url="<?php echo @ACT; ?>
/sysmanage/Menu/menu_del/";
		}
		$.ajax({
			type: "POST",
			url: act_url,
			data:{"id":menu_id},
			dataType:"json",
			success: function(data){
				if(data.statusCode=='200'){
					layer.msg('操作成功', {icon: 1}); 
				}
			}
		});
	});
	
});
</script>