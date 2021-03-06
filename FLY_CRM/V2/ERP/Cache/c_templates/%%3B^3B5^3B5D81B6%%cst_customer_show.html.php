<?php /* Smarty version 2.6.26, created on 2018-12-31 16:09:04
         compiled from crm/cst_customer_show.html */ ?>
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
          <h5><i class="fa fa-home"></i> 客户列表</h5>
          <div class="ibox-tools"> 
			  		<a href="?" class="btn btn-xs btn-danger"> <i class='fa fa-refresh'></i> 刷新</a>
			  </div>
        </div>
        <div class="ibox-content table-responsive">
          <div class="row">
            <form id="pagerForm" method="post" class="form-inline">
              <div class="col-sm-3 m-b-xs"> 
				  		
				  		<a href="javascript:void(0)" class="btn btn-info single_operation" data-act="add">添加</a>
                <div class="btn-group">
                  <button data-toggle="dropdown" class="btn btn-danger dropdown-toggle">批量操作 <span class="caret"></span>			</button>
                  <ul class="dropdown-menu">
                    <li><a href="javascript:void(0)" class="batch_operation" data-act="implode">批量导入</a></li>
                    <li><a href="javascript:void(0)" class="batch_operation" data-act="explode">批量导出</a></li>
                    <li class="divider"></li>
                    <li><a href="javascript:void(0)" class="batch_operation" data-act="del">批量删除</a></li>
                  </ul>
                </div>
              </div>
              <div class="col-sm-9 m-b-xs text-right">
                <div class="form-group text-left  pd-b-5">
                  <select data-placeholder="最近联系时间所有..." name="conn_time" class="chosen-select" style="width: 150px;" >
                    <option value="0">最近联系时间所有</option>
                    <option value="3d">最近三天</option>
                    <option value="7d">最近一周</option>
                    <option value="15d">最近半月</option>
                    <option value="1m">最近一月</option>
                    <option value="3m">最近三月</option>
                    <option value="6m">最近六月</option>
                    <option value="12m">最近一年</option>
                  </select>
                </div>
                <div class="form-group text-left pd-b-5">
                  <select data-placeholder="下次联系时间..." name="next_time" class="chosen-select" style="width: 150px;" >
                    <option value="0">下次联系时间所有</option>
                    <option value="3d">最近三天</option>
                    <option value="7d">最近一周</option>
                    <option value="15d">最近半月</option>
                    <option value="1m">最近一月</option>
                    <option value="3m">最近三月</option>
                    <option value="6m">最近六月</option>
                    <option value="12m">最近一年</option>
                  </select>
                </div>
				  		<div class="input-group pd-b-5">
                  <input type="text" name="name" placeholder="请输入客户名称关键词" class="form-control">
				  		</div>
                <div class="btn-group">
                  <button data-toggle="dropdown" class="btn  dropdown-toggle"><span class="caret"></span></button>
                  <div class="dropdown-menu">
                    <div class="ibox-content">
                      <div class="form-group">
                        <label>电话</label>
                        <input type="text" name="tel" placeholder="搜索电话" class="form-control">
                      </div>
                      <div class="form-group">
                        <label>手机号</label>
                        <input type="text" name="mobile" placeholder="搜索手机号" class="form-control">
                      </div>
                      <div class="form-group">
                        <label>联系地址</label>
                        <input type="text" name="address" placeholder="搜索客户联系地址" class="form-control">
                      </div>
                      <div class="form-group">
                        <button type="button" class="btn btn-primary btn-xs btn-block ajaxSearchForm"><i class="fa fa-search"></i> 搜索</button>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="input-group">
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-primary ajaxSearchForm"><i class="fa fa-search"></i> 搜索</button>
                  </span> </div>
              </div>
            </form>
          </div>
          <table class="table table-hover sorttable 07fly-table" width="100%">
            <thead>
              <tr>
                <th width="22"><input type="checkbox" group="ids" class="checkboxCtrl"></th>
                <th orderField="by_customer" class="sort-filed"><span>客户名称</span></th>
				  		<th width="150" orderField="by_connbdt" class="sort-filed"><span>最近沟通时间/内容</span></th>
				  		<th width="120" orderField="by_nextbdt" class="sort-filed"><span>下次联系</span></th>
                <th width="150"><span>联系方式</span></th>
                <th width="80"><span>来源</span></th>
                <th width="80"><span>等级</span></th>
                <th width="80">操作</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
				<table class="table 07fly-table"><tfoot class="ibox-content"><tr><td align="center" class="pagestring"></td></tr></tfoot></table>
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
		<td><input name="customer_id" class="checkboxCtrlId" value="<%=list[i]['customer_id']%>" type="checkbox"></td>
		<td>
			<a href="<?php echo @ACT; ?>
/crm/CstCustomer/cst_customer_detail/customer_id/<%=list[i]['customer_id']%>/"><%=list[i]['name']%></a>
			<p><small>创建于：<%=list[i]['create_time']%></small></p>
		</td>
		<td>
			<p class="overflow-td">
			<%=list[i]['conn_time']%><br>
			<%=list[i]['conn_body']%></p>
		</td>
		<td><%=list[i]['next_time']%></td>
		<td><p class="overflow-td">
			<i class="fa fa-male"></i>:<%=list[i]['linkman']%><br>
			<i class="fa fa-mobile"></i>:<%=list[i]['mobile']%><br>
			</p>
		</td>
		<td><%=list[i]['source_name']%></td>
		<td><%=list[i]['level_name']%></td>
		
		<td width='10'>
			<div class="btn-group">
				<button data-toggle="dropdown" class="btn btn-info dropdown-toggle">操作 <span class="caret"></span></button>
				<ul class="dropdown-menu">
					<li><a href="javascript:void(0)" class="single_operation" data-id="<%=list[i]['customer_id']%>" data-act="linkman">添加联系人</a></li>
					<li><a href="javascript:void(0)" class="single_operation" data-id="<%=list[i]['customer_id']%>" data-act="trace">添加沟通记录</a></li>
					<li class="divider"></li>
					<li><a href="javascript:void(0)" class="single_operation" data-id="<%=list[i]['customer_id']%>" data-act="service">添加服务记录</a></li>
					<li><a href="javascript:void(0)" class="single_operation" data-id="<%=list[i]['customer_id']%>" data-act="chance">添加销售机会</a></li>
					<li class="divider"></li>
					<li><a href="javascript:void(0)" class="single_operation" data-id="<%=list[i]['customer_id']%>" data-act="detail">详细</a></li>
					<li><a href="javascript:void(0)" class="single_operation" data-id="<%=list[i]['customer_id']%>" data-act="modify">修改</a></li>
					<li><a href="javascript:void(0)" class="single_operation" data-id="<%=list[i]['customer_id']%>" data-act="del">删除</a></li>
				</ul>
			</div>
		</td>
	</tr>
<% } %>
</script>
<script src="<?php echo @APP; ?>
/View/template/js/content.js?v=1.0.0"></script>
<script type="text/javascript">
var ajaxUrl='<?php echo @ACT; ?>
/index.php/crm/CstCustomer/cst_customer_json/';
$(document).ready(function () {
	 
	turnPage(1);//页面加载时初始化分页
	$('.chosen-select').chosen({search_contains: true});
	$('.chosen-select').chosen().change(function(){
		ajaxSearchFormData = $("form").serialize();
		turnPage(1);
	});
	$("body").on("click", ".batch_operation", function() {
		batch_act =$(this).attr("data-act")
		var chk_value =[]; 
    	$("tbody input[class='checkboxCtrlId']:checked").each(function(){ 
        chk_value.push($(this).val()); 
		}); 
		customer_id_txt=chk_value.join(",");
		if(batch_act=="recommend"){
		   act_url="<?php echo @ACT; ?>
/index.php/crm/CstCustomer/cst_customer_modify_recommend/";
		}else if(batch_act=="recommend_off"){
			act_url="<?php echo @ACT; ?>
/index.php/crm/CstCustomer/cst_customer_modify_recommend_off/";
		}else if(batch_act=="del"){
			act_url="<?php echo @ACT; ?>
/index.php/crm/CstCustomer/cst_customer_del/";
			$.ajax({
				type: "POST",
				url: act_url,
				data:{"customer_id":customer_id_txt},
				dataType:"json",
				async: false,
				success: function(data){
					if(data.statusCode=='200'){
						layer.msg('操作成功', {icon: 1}); 
						setTimeout(function(){
							turnPage(1);
						 },800);
					}
				}
			});
			return false;
		}else if(batch_act=="online"){
			act_url="<?php echo @ACT; ?>
/index.php/crm/CstCustomer/cst_customer_modify_online/";
		}else if(batch_act=="offline"){
			act_url="<?php echo @ACT; ?>
/index.php/crm/CstCustomer/cst_customer_modify_offline/";
		}
	});
	
	$("body").on("click", ".single_operation", function() {
		customer_id =$(this).attr("data-id");
		single_act =$(this).attr("data-act")
		if(single_act=="add"){
			layer.open({
				type: 2,
				title: '添加客户信息',
				shadeClose: true,
				fixed: false, //不固定
				area: ['90%', '90%'],
				content: '<?php echo @ACT; ?>
/index.php/crm/CstCustomer/cst_customer_add/'
			});			
			return false;	
		}else if(single_act=="modify"){
			act_url="<?php echo @ACT; ?>
/index.php/crm/CstCustomer/cst_customer_modify/";
			layer.open({
				type: 2,
				title: '修改客户信息',
				shadeClose: true,
				fixed: false, //不固定
				area: ['90%', '90%'],
				content: '<?php echo @ACT; ?>
/index.php/crm/CstCustomer/cst_customer_modify/customer_id/'+customer_id+'/' //iframe的url
			});			
			return false;	
		}else if(single_act=="linkman"){
			act_url="<?php echo @ACT; ?>
/index.php/crm/CstLinkman/cst_linkman_add/";
			layer.open({
				type: 2,
				title: '添加联系人',
				shadeClose: true,
				fixed: false, //不固定
				area: ['800px', '500px'],
				content: '<?php echo @ACT; ?>
/index.php/crm/CstLinkman/cst_linkman_add/customer_id/'+customer_id+'/' //iframe的url
			});			
			return false;	
		}else if(single_act=="service"){
			layer.open({
				type: 2,
				title: '添加服务记录',
				shadeClose: true,
				fixed: false, //不固定
				area: ['800px', '500px'],
				content: '<?php echo @ACT; ?>
/index.php/crm/CstService/cst_service_add/customer_id/'+customer_id+'/' //iframe的url
			});			
			return false;
		}else if(single_act=="chance"){
			layer.open({
				type: 2,
				title: '添加销售机会',
				shadeClose: true,
				fixed: false, //不固定
				area: ['800px', '500px'],
				content: '<?php echo @ACT; ?>
/index.php/crm/CstChance/cst_chance_add/customer_id/'+customer_id+'/' //iframe的url
			});			
			return false;
		}else if(single_act=="trace"){
			layer.open({
				type: 2,
				title: '添加沟通记录',
				shadeClose: true,
				fixed: false, //不固定
				area: ['800px', '500px'],
				content: '<?php echo @ACT; ?>
/index.php/crm/CstTrace/cst_trace_add/customer_id/'+customer_id+'/' //iframe的url
			});			
			return false;
		}else if(single_act=="del"){
			act_url="<?php echo @ACT; ?>
/index.php/crm/CstCustomer/cst_customer_del/";
			$.ajax({
				type: "POST",
				url: act_url,
				data:{"customer_id":customer_id},
				dataType:"json",
				async: false,
				success: function(data){
					if(data.statusCode=='200'){
						layer.msg('操作成功', {icon: 1}); 
						setTimeout(function(){
							turnPage(1);
						 },800);
					}
				}
			});
			return false;
		}else if(single_act=="detail"){
			act_url="<?php echo @ACT; ?>
/crm/CstCustomer/cst_customer_detail/customer_id/"+customer_id+"/";
			window.location.href=act_url;
		}else if(single_act=="offline"){
			act_url="<?php echo @ACT; ?>
/index.php/crm/CstCustomer/cst_customer_modify_offline/";
		}
	});
	
	
});
</script>