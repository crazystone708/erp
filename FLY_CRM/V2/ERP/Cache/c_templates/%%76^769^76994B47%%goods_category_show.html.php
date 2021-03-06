<?php /* Smarty version 2.6.26, created on 2019-02-11 23:11:41
         compiled from goods/goods_category_show.html */ ?>
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
					<h5><i class="fa fa-home"></i> 商品分类</h5>
					<div class="ibox-tools"> <a href="<?php echo @ACT; ?>
/goods/GoodsCategory/goods_category_add/" >
						<button type="button" class="btn btn-xs btn-primary"><i class='fa fa-plus'></i> 添加</button>
						</a> <a href="?">
						<button type="button" class="btn btn-xs btn-danger"> <i class='fa fa-refresh'></i>刷新</button>
						</a> </div>
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
	//更改排序
	$("body").on("blur", ".treeSort", function() {
		id =$(this).attr("data-id");
		sort =$(this).val();
		$.ajax({
			type: "POST",
			url: "<?php echo @ACT; ?>
/index.php/goods/GoodsCategory/goods_category_modify_sort/",
			data:{"sort":sort,"id":id},
			dataType:"json",
			success: function(data){
				if(data.rtnstatus=='success'){
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
/index.php/goods/GoodsCategory/goods_category_modify_name/",
			data:{"name":value,"id":id},
			dataType:"json",
			success: function(data){
				if(data.rtnstatus=='success'){
					layer.msg('操作成功', {icon: 1});   
				}
				console.log(data.rtnstatus);
			}
		});
	});
	//更改排序
	$("body").on("blur", ".treeUrl", function() {
		id =$(this).attr("data-id");
		value =$(this).val();
		$.ajax({
			type: "POST",
			url: "<?php echo @ACT; ?>
/index.php/sysmanage/Dept/dept_modify_url/",
			data:{"url":value,"id":id},
			dataType:"json",
			success: function(data){
				if(data.rtnstatus=='success'){
					layer.msg('操作成功', {icon: 1});   
				}
				console.log(data.rtnstatus);
			}
		});
	});
});
</script>