<?php /* Smarty version 2.6.26, created on 2018-12-31 16:34:29
         compiled from erp/pos_contract_show.html */ ?>
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
          <h5><i class="fa fa-home"></i>采购合同列表</h5>
          <div class="ibox-tools"><a href="?">
            <button type="button" class="btn btn-xs btn-danger"> <i class="fa fa-refresh"></i>刷新</button>
            </a> </div>
        </div>
        <div class="ibox-content table-responsive">
          <div class="row">
            <form id="pagerForm" method="post" class="form-inline">
              <div class="col-sm-3 m-b-xs"><a class="btn btn-info single_operation" data-act="add" href="javascript:void(0)"><i class="fa fa-plus"></i>添加</a>
                <div class="btn-group">
                  <button data-toggle="dropdown" class="btn btn-default dropdown-toggle">批量操作 <span class="caret"></span></button>
                  <ul class="dropdown-menu">
                    <li><a href="javascript:void(0)" class="batch_operation" data-act="online">批量发短信</a></li>
                    <li><a href="javascript:void(0)" class="batch_operation" data-act="offline">批量发邮件</a></li>
                    <li class="divider"></li>
                    <!--<li><a href="javascript:void(0)" class="batch_operation" data-act="delete">批量删除</a></li>-->
                  </ul>
                </div>
              </div>
              <div class="col-sm-9 m-b-xs text-right"> 
                <div class="form-group text-left  pd-b-5">
                  <select data-placeholder="有效期起始..." name="start_date" class="chosen-select" style="width: 150px;" >
                    <option value="0">有效期起始所有</option>
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
                  <select data-placeholder="有效期结束..." name="end_date" class="chosen-select" style="width: 150px;" >
                    <option value="0">有效期结束所有</option>
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
                  <input type="text" name="name" placeholder="输入主题关键字搜索" class="form-control">
                </div>
                <div class="btn-group">
                  <button data-toggle="dropdown" class="btn dropdown-toggle"><span class="caret"></span></button>
                  <div class="dropdown-menu">
                    <div class="ibox-content">
                      <div class="form-group">
                        <label>供应商名称</label>
                        <input type="text" name="supplier_name" placeholder="搜索供应商名称" class="form-control">
                      </div>
                      <div class="form-group">
                        <label>备注内容</label>
                        <input type="text" name="intro" placeholder="搜索备注内容" class="form-control">
                      </div>
                      <div class="form-group">
                        <button type="button" class="btn btn-primary btn-xs btn-block ajaxSearchForm"><i class="fa fa-search"></i> 搜索</button>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="input-group"> <span class="input-group-btn">
                  <button type="button" class="btn btn-primary ajaxSearchForm"><i class="fa fa-search"></i> 搜索</button>
                  </span> </div>
              </div>
            </form>
          </div>
          <table class="table  table-hover sorttable 07fly-table" width="100%">
            <thead>
              <tr>
                <th width="22"><input type="checkbox" group="ids" class="checkboxCtrl"></th>
                <th width="150" ><span>合同主题</span></th>
                <th width="120" orderField="by_customer" class="sort-filed"><span>供应商名称</span></th>
                <th width="100" orderField="by_money" class="sort-filed"><span>合同金额</span></th>
                <th width="120" orderField="by_backmoney" class="sort-filed"><span>付款/金额/状态</span></th>
                <th width="120" orderField="by_owemoney" class="sort-filed"><span>收票/金额/状态</span></th>
                <th width="100" orderField="by_startdate" class="sort-filed"><span>采购时间</span></th>
                <th width="100" orderField="by_enddate" class="sort-filed"><span>预计到货</span></th>
                <th width="80"><span>合同状态</span></th>
                <th width="80"><span>收货状态</span></th>
                <th width="80" class="text-center">操作</th>
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
		<td><input name="contract_id" class="checkboxCtrlId" value="<%=list[i]['contract_id']%>" type="checkbox"></td>
		<td>
			<%=list[i]['title']%>
			<br><small>(创建：<%=list[i]['create_time']%>)</small>
		</td>
		<td>
			<%=list[i]['supplier_name']%>
			<br><small>(代表：<%=list[i]['linkman']['name']%>)</small>
		</td>
		<td><%=list[i]['money']%></td>
		<td>
			
			付款：<%=list[i]['pay_money']%><br>
			去零：<%=list[i]['zero_money']%><br>
			欠款：<%=list[i]['owe_money']%><br>
			状态：<font color="<%=list[i]['pay_status_arr']['color']%>"><%=list[i]['pay_status_arr']['status_name']%></font>
	
		</td>
		<td>
			收票：<%=list[i]['invoice_money']%><br>
			状态：<font color="<%=list[i]['invoice_status_arr']['color']%>"><%=list[i]['invoice_status_arr']['status_name']%></font>	
		</td>
		<td><%=list[i]['start_date']%></td>
		<td><%=list[i]['end_date']%></td>
		<td><%:=list[i]['status_arr']['status_name_html']%></td>	
		<td>
			<p><%:=list[i]['rece_status_arr']['status_name_html']%></p>
			<% for(var j=0;j<list[i]['rece_status_arr']['status_operation'].length;j++) { %>
			<p><a href="javascript:void(0)" class="single_operation" data-id="<%=list[i]['contract_id']%>" data-act="<%=list[i]['rece_status_arr']['status_operation'][j]['act']%>"><font color="<%=list[i]['rece_status_arr']['status_operation'][j]['color']%>"><%=list[i]['rece_status_arr']['status_operation'][j]['name']%></font></a></p>
			<% } %>
		</td>	
		<td align="center">
			<% for(var j=0;j<list[i]['status_arr']['status_operation'].length;j++) { %>
			<p><a href="javascript:void(0)" class="single_operation" data-id="<%=list[i]['contract_id']%>" data-act="<%=list[i]['status_arr']['status_operation'][j]['act']%>"><font color="<%=list[i]['status_arr']['status_operation'][j]['color']%>"><%=list[i]['status_arr']['status_operation'][j]['name']%></font></a></p>
			<% } %>
		</td>
	</tr>
<% } %>
  <tr>
	<td colspan="10" align="left">
		合同金额合计：<span class="text-danger">&yen;<%=countMoney['total_money']%></span>&nbsp;&nbsp;
		去零金额合计：<span class="text-danger">&yen;<%=countMoney['total_zero_money']%></span>&nbsp;&nbsp;
		付款金额合计：<span class="text-danger">&yen;<%=countMoney['total_pay_money']%></span>&nbsp;&nbsp;
		欠款金额合计：<span class="text-danger">&yen;<%=countMoney['total_owe_money']%></span>&nbsp;&nbsp;
	</td>
  </tr>
</script>
<script src="<?php echo @APP; ?>
/View/template/js/content.js?v=1.0.0"></script>
<script type="text/javascript">
var ajaxUrl='<?php echo @ACT; ?>
/erp/PosContract/pos_contract_json/';
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
		contract_id_txt=chk_value.join(",");
		if(batch_act=="recommend"){
		   act_url="<?php echo @ACT; ?>
/erp/PosContract/pos_contract_modify_recommend/";
		}else if(batch_act=="delete"){
			act_url="<?php echo @ACT; ?>
/erp/PosContract/pos_contract_del/";
		}
    //alert(chk_value.length==0 ?'你还没有选择任何内容！':chk_value); 
		$.ajax({
			type: "POST",
			url: act_url,
			data:{"contract_id":contract_id_txt},
			dataType:"json",
			success: function(data){
				if(data.statusCode=='200'){
					layer.msg('操作成功', {icon: 1}); 
				}
			},
			complete: function () {//完成响应
			}
		});
	});
	
	$("body").on("click", ".single_operation", function() {
		contract_id =$(this).attr("data-id");
		single_act =$(this).attr("data-act")
		if(single_act=="add"){
			layer.open({
				type: 2,
				title: '添加采购合同',
				shadeClose: true,
				fixed: false, //不固定
				area: ['90%', '90%'],
				content: '<?php echo @ACT; ?>
/erp/PosContract/pos_contract_add/',
				end: function () {
					turnPage(1);//页面加载时初始化分页
				}
			});			
			return false;	
		}else if(single_act=="modify"){
			layer.open({
				type: 2,
				title: '修改采购合同',
				shadeClose: true,
				fixed: false, //不固定
				area: ['90%', '90%'],
				content: '<?php echo @ACT; ?>
/erp/PosContract/pos_contract_modify/contract_id/'+contract_id+'/' //iframe的url
			});			
			return false;	
		}else if(single_act=="list_add"){
			//window.location.href='<?php echo @ACT; ?>
/erp/PosContractList/pos_contract_list_add/contract_id/'+contract_id+'/';
			layer.open({
				type: 2,
				title: '采购合同录入明细',
				shadeClose: true,
				fixed: false, //不固定
				area: ['100%', '100%'],
				content: '<?php echo @ACT; ?>
/erp/PosContractList/pos_contract_list_add/contract_id/'+contract_id+'/',
				end: function () {
					turnPage(1);//页面加载时初始化分页
				}
			});			
			return false;	
		}else if(single_act=="delete"){
			act_url="<?php echo @ACT; ?>
/erp/PosContract/pos_contract_del/";
		}else if(single_act=="list_del"){
			act_url="<?php echo @ACT; ?>
/erp/PosContractList/pos_contract_list_del/";
		}else if(single_act=="detail"){
			window.location.href='<?php echo @ACT; ?>
/erp/PosContract/pos_contract_detail/contract_id/'+contract_id+'/';
		}
		$.ajax({
			type: "POST",
			url: act_url,
			data:{"contract_id":contract_id},
			dataType:"json",
			success: function(data){
				if(data.statusCode=='200'){
					layer.msg('操作成功', {icon: 1});
					turnPage(1);
				}
			}
		});
	});
	
	
});
</script>