<?php /* Smarty version 2.6.26, created on 2019-02-11 23:15:19
         compiled from goods/goods_attr_show.html */ ?>
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
					<h5><i class="fa fa-home"></i> 商品类型</h5>
					<div class="ibox-tools"> <a href="<?php echo @ACT; ?>
/goods/GoodsAttr/goods_attr_add/" >
						<button type="button" class="btn btn-xs btn-primary"><i class='fa fa-plus'></i> 添加</button>
						</a> <a href="?">
						<button type="button" class="btn btn-xs btn-danger"> <i class='fa fa-refresh'></i>刷新</button>
						</a> </div>
				</div>
				<div class="ibox-content">
					<table class="table table-hover sorttable 07fly-table" width="100%">
						<thead>
							<tr>
								<th width="22"><input type="checkbox" group="ids" class="checkboxCtrl"></th>
								<th width="100"><span >类型名称</span></th>
								<th >属性标签</th>
								<th width="100">启用</th>
								<th width="100">排序</th>
								<th width="100">操作</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
						<tfoot class="ibox-content">
							<tr>
								<td colspan="5" align="center"></td>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
</body>
</html>

<script id="tableListTpl" type="text/html">
<% for(var i=0;i<list.length;i++) { %>

	<tr>
		<td><input name="ids" value="<%=list[i]['attr_id']%>" type="checkbox"></td>
		<td><%=list[i]['attr_name']%></td>
		<td><%=list[i]['attr_value_name']%></td>
		<td>
      <div class="onoffswitch">
       		<input type="checkbox" class="onoffswitch-checkbox" data-id="<%=list[i]['attr_id']%>"  id="visible_<%=list[i]['attr_id']%>"  <%if(list[i]['visible']==1){%> checked  <%}%>  >
	   		<label class="onoffswitch-label" for="visible_<%=list[i]['attr_id']%>"> 
	   			<span class="onoffswitch-inner"></span> 
				 <span class="onoffswitch-switch"></span> 
			 </label>
      </div>
		</td>
		<td><input type="text" name="sort" value="<%=list[i]['sort']%>" class="form-control w50 modify_sort" data-id="<%=list[i]['attr_id']%>" >
		</td>
		<td><a href="<?php echo @ACT; ?>
/goods/GoodsAttr/goods_attr_modify/id/<%=list[i]['attr_id']%>/">修改</a> 
			   <a href="<?php echo @ACT; ?>
/goods/GoodsAttr/goods_attr_del/id/<%=list[i]['attr_id']%>/">删除</a>
		</td>
	</tr>
<% } %>
</script> 
<script type="text/javascript">
var ajaxUrl='<?php echo @ACT; ?>
/index.php/goods/GoodsAttr/goods_attr_json/';
//页面加载时初始化分页
$(function() {
  turnPage(1);
	$("body").on("click", ".onoffswitch-checkbox", function() {
		id =$(this).attr("data-id")
		ischecked=$(this).prop('checked');
		if(ischecked){
		   visible='1';
		}else{
			visible='0';
		}
		$.ajax({
			type: "POST",
			url: "<?php echo @ACT; ?>
/index.php/goods/GoodsAttr/goods_attr_modify_visible/",
			data:{"visible":visible,"id":id},
			dataType:"json",
			success: function(data){
				if(data.rtnstatus=='success'){
					layer.msg('操作成功', {icon: 1});   
				}
				console.log(data.rtnstatus);
			}
		});
	});
	$("body").on("blur", ".modify_sort", function() {
		id 	=$(this).attr("data-id")
		sort=$(this).val();
		$.ajax({
			type: "POST",
			url: "<?php echo @ACT; ?>
/index.php/goods/GoodsAttr/goods_attr_modify_sort/",
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
});
</script> 