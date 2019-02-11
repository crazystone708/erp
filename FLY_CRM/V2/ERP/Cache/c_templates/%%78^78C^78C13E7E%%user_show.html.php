<?php /* Smarty version 2.6.26, created on 2018-12-31 17:12:08
         compiled from sysmanage/user_show.html */ ?>
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
					<h5><i class="fa fa-home"></i>员工管理</h5>
					<div class="ibox-tools"> <a href="<?php echo @ACT; ?>
/sysmanage/User/user_add/" class="btn btn-xs btn-primary"><i class="fa fa-plus"></i> 添加</a> <a href="?" class="btn btn-xs btn-danger"><i class="fa fa-refresh"></i>刷新</a> </div>
				</div>
				<div class="ibox-content">
					<table class="table sorttable 07fly-table" width="100%">
						<thead>
							<tr>
								<th width="22"><input type="checkbox" group="ids" class="checkboxCtrl"></th>
								<th width="120">帐号</th>
								<th width="80">姓名</th>
								<th width="60">性别</th>
								<th width="120">手机</th>
								<th width="120">QQ</th>
								<th width="150">邮箱</th>
								<th width="120">部门</th>
								<th width="120">职务</th>
								<th width="120">角色</th>
								<th>操作</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
						<tfoot class="ibox-content">
							<tr>
								<td colspan="11" align="center"></td>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<script id="tableListTpl" type="text/html">
<% for(var i=0;i<list.length;i++) { %>
	<tr>
		<td><input name="ids" value="<%=list[i]['id']%>" type="checkbox"></td>
		<td><%=list[i]['account']%></td>
		<td><%=list[i]['name']%></td>
		<td>
			<% if(list[i]['gender']==1){%>
				男
			<%}else{%>
				女
			<%}%>			
		</td>
		<td><%=list[i]['mobile']%></td>
		<td><%=list[i]['qicq']%></td>
		<td><%=list[i]['email']%></td>
		<td><%=list[i]['dept_name']%></td>
		<td><%=list[i]['position_name']%></td>
		<td><%=list[i]['role_name']%></td>
		<td>
			   <a href="<?php echo @ACT; ?>
/sysmanage/User/user_modify/id/<%=list[i]['id']%>/" class="btn btn-info btn-xs"><i class="fa fa-paste"></i> 修改</a> 
			   <a href="<?php echo @ACT; ?>
/sysmanage/User/user_del/id/<%=list[i]['id']%>/" class="btn btn-danger btn-xs"><i class="fa fa-remove"></i> 删除</a>
		</td>
	</tr>
<% } %>
</script> 
<script type="text/javascript">
var ajaxUrl='<?php echo @ACT; ?>
/sysmanage/User/user_show_json/';

//页面加载时初始化分页
$(function() {
  turnPage(1);
});
</script> 
</body>

<!-- Mirrored from www.upfine.cn/theme/hplus/table_basic.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 20 Jan 2016 14:20:01 GMT -->
</html>