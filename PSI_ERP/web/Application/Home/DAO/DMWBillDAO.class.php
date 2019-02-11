<?php

namespace Home\DAO;

use Home\Common\FIdConst;

/**
 * 成品委托生产入库单 DAO
 *
 * @author 李静波
 */
class DMWBillDAO extends PSIBaseExDAO {

	/**
	 * 单据状态标志转化为文字
	 *
	 * @param int $code        	
	 * @return string
	 */
	private function billStatusCodeToName($code) {
		switch ($code) {
			case 0 :
				return "待入库";
			case 1000 :
				return "已入库";
			case 2000 :
				return "已退货";
			default :
				return "";
		}
	}

	/**
	 * 生成新的成品委托生产入库单单号
	 *
	 * @param string $companyId        	
	 * @return string
	 */
	private function genNewBillRef($companyId) {
		$db = $this->db;
		
		$bs = new BizConfigDAO($db);
		$pre = $bs->getDMWBillRefPre($companyId);
		
		$mid = date("Ymd");
		
		$sql = "select ref from t_dmw_bill where ref like '%s' order by ref desc limit 1";
		$data = $db->query($sql, $pre . $mid . "%");
		$sufLength = 3;
		$suf = str_pad("1", $sufLength, "0", STR_PAD_LEFT);
		if ($data) {
			$ref = $data[0]["ref"];
			$nextNumber = intval(substr($ref, strlen($pre . $mid))) + 1;
			$suf = str_pad($nextNumber, $sufLength, "0", STR_PAD_LEFT);
		}
		
		return $pre . $mid . $suf;
	}

	public function getDMWBillById($id) {
		$db = $this->db;
		
		$sql = "select ref, bill_status, data_org, company_id
				from t_dmw_bill where id = '%s' ";
		$data = $db->query($sql, $id);
		if (! $data) {
			return null;
		} else {
			return [
					"ref" => $data[0]["ref"],
					"billStatus" => $data[0]["bill_status"],
					"dataOrg" => $data[0]["data_org"],
					"companyId" => $data[0]["company_id"]
			];
		}
	}

	/**
	 * 成品委托生产入库单 - 单据详情
	 */
	public function dmwBillInfo($params) {
		$db = $this->db;
		
		// 公司id
		$companyId = $params["companyId"];
		if ($this->companyIdNotExists($companyId)) {
			return $this->emptyResult();
		}
		
		$bcDAO = new BizConfigDAO($db);
		$dataScale = $bcDAO->getGoodsCountDecNumber($companyId);
		$fmt = "decimal(19, " . $dataScale . ")";
		
		// id: 成品委托生产入库单id
		$id = $params["id"];
		// dmobillRef: 成品委托生产订单单号，可以为空，为空表示直接录入入库单；不为空表示是从成品委托生产订单生成入库单
		$dmobillRef = $params["dmobillRef"];
		
		$result = [
				"id" => $id
		];
		
		$sql = "select p.ref, p.bill_status, p.factory_id, f.name as factory_name,
					p.warehouse_id, w.name as  warehouse_name,
					p.biz_user_id, u.name as biz_user_name, p.biz_dt, p.payment_type,
					p.bill_memo
				from t_dmw_bill p, t_factory f, t_warehouse w, t_user u
				where p.id = '%s' and p.factory_id = f.id and p.warehouse_id = w.id
				  and p.biz_user_id = u.id";
		$data = $db->query($sql, $id);
		if ($data) {
			$v = $data[0];
			$result["ref"] = $v["ref"];
			$result["billStatus"] = $v["bill_status"];
			$result["factoryId"] = $v["factory_id"];
			$result["factoryName"] = $v["factory_name"];
			$result["warehouseId"] = $v["warehouse_id"];
			$result["warehouseName"] = $v["warehouse_name"];
			$result["bizUserId"] = $v["biz_user_id"];
			$result["bizUserName"] = $v["biz_user_name"];
			$result["bizDT"] = $this->toYMD($v["biz_dt"]);
			$result["paymentType"] = $v["payment_type"];
			$result["billMemo"] = $v["bill_memo"];
			
			// 商品明细
			$items = [];
			$sql = "select p.id, p.goods_id, g.code, g.name, g.spec, u.name as unit_name,
						convert(p.goods_count, $fmt) as goods_count, p.goods_price, p.goods_money, p.memo,
						p.dmobilldetail_id
					from t_dmw_bill_detail p, t_goods g, t_goods_unit u
					where p.goods_Id = g.id and g.unit_id = u.id and p.dmwbill_id = '%s'
					order by p.show_order";
			$data = $db->query($sql, $id);
			foreach ( $data as $v ) {
				$items[] = [
						"id" => $v["id"],
						"goodsId" => $v["goods_id"],
						"goodsCode" => $v["code"],
						"goodsName" => $v["name"],
						"goodsSpec" => $v["spec"],
						"unitName" => $v["unit_name"],
						"goodsCount" => $v["goods_count"],
						"goodsPrice" => $v["goods_price"],
						"goodsMoney" => $v["goods_money"],
						"memo" => $v["memo"],
						"dmoBillDetailId" => $v["dmobilldetail_id"]
				];
			}
			
			$result["items"] = $items;
			
			// 查询该单据是否是由成品委托生产订单生成的
			$sql = "select dmo_id from t_dmo_dmw where dmw_id = '%s' ";
			$data = $db->query($sql, $id);
			if ($data) {
				$result["genBill"] = true;
			} else {
				$result["genBill"] = false;
			}
		} else {
			// 新建成品委托生产入库单
			$result["bizUserId"] = $params["loginUserId"];
			$result["bizUserName"] = $params["loginUserName"];
			
			if ($dmobillRef) {
				// 由成品委托生产订单生成入库单
				$sql = "select p.id, p.factory_id, f.name as factory_name, p.deal_date,
							p.payment_type, p.bill_memo
						from t_dmo_bill p, t_factory f
						where p.ref = '%s' and p.factory_id = f.id ";
				$data = $db->query($sql, $dmobillRef);
				if ($data) {
					$v = $data[0];
					$result["factoryId"] = $v["factory_id"];
					$result["factoryName"] = $v["factory_name"];
					$result["dealDate"] = $this->toYMD($v["deal_date"]);
					$result["paymentType"] = $v["payment_type"];
					$result["billMemo"] = $v["bill_memo"];
					
					$dmobillId = $v["id"];
					// 明细
					$items = [];
					$sql = "select p.id, p.goods_id, g.code, g.name, g.spec, u.name as unit_name,
								convert(p.goods_count, $fmt) as goods_count,
								p.goods_price, p.goods_money,
								convert(p.left_count, $fmt) as left_count, p.memo
							from t_dmo_bill_detail p, t_goods g, t_goods_unit u
							where p.dmobill_id = '%s' and p.goods_id = g.id and g.unit_id = u.id
							order by p.show_order ";
					$data = $db->query($sql, $dmobillId);
					foreach ( $data as $v ) {
						$items[] = [
								"id" => $v["id"],
								"dmoBillDetailId" => $v["id"],
								"goodsId" => $v["goods_id"],
								"goodsCode" => $v["code"],
								"goodsName" => $v["name"],
								"goodsSpec" => $v["spec"],
								"unitName" => $v["unit_name"],
								"goodsCount" => $v["left_count"],
								"goodsPrice" => $v["goods_price"],
								"goodsMoney" => $v["left_count"] * $v["goods_price"],
								"memo" => $v["memo"]
						];
					}
					
					$result["items"] = $items;
				}
			}
		}
		
		return $result;
	}

	/**
	 * 新建成品委托生产入库单
	 *
	 * @param array $bill        	
	 * @return array|null
	 */
	public function addDMWBill(& $bill) {
		$db = $this->db;
		
		// 业务日期
		$bizDT = $bill["bizDT"];
		
		// 仓库id
		$warehouseId = $bill["warehouseId"];
		
		// 工厂id
		$factoryId = $bill["factoryId"];
		
		// 业务员id
		$bizUserId = $bill["bizUserId"];
		
		// 付款方式
		// 0 - 应付账款
		$paymentType = 0;
		
		// 单据备注
		$billMemo = $bill["billMemo"];
		
		// 成品委托生产订单单号
		$dmobillRef = $bill["dmobillRef"];
		
		$warehouseDAO = new WarehouseDAO($db);
		$warehouse = $warehouseDAO->getWarehouseById($warehouseId);
		if (! $warehouse) {
			return $this->bad("入库仓库不存在");
		}
		
		$factoryDAO = new FactoryDAO($db);
		$factory = $factoryDAO->getFactoryById($factoryId);
		if (! $factory) {
			return $this->bad("工厂不存在");
		}
		
		$userDAO = new UserDAO($db);
		$user = $userDAO->getUserById($bizUserId);
		if (! $user) {
			return $this->bad("业务人员不存在");
		}
		
		// 检查业务日期
		if (! $this->dateIsValid($bizDT)) {
			return $this->bad("业务日期不正确");
		}
		
		$loginUserId = $bill["loginUserId"];
		if ($this->loginUserIdNotExists($loginUserId)) {
			return $this->badParam("loginUserId");
		}
		
		$dataOrg = $bill["dataOrg"];
		if ($this->dataOrgNotExists($dataOrg)) {
			return $this->badParam("dataOrg");
		}
		
		$companyId = $bill["companyId"];
		if ($this->companyIdNotExists($companyId)) {
			return $this->badParam("companyId");
		}
		
		$bcDAO = new BizConfigDAO($db);
		$dataScale = $bcDAO->getGoodsCountDecNumber($companyId);
		$fmt = "decimal(19, " . $dataScale . ")";
		
		$ref = $this->genNewBillRef($companyId);
		
		$id = $this->newId();
		
		// 主表
		$sql = "insert into t_dmw_bill (id, ref, factory_id, warehouse_id, biz_dt,
					biz_user_id, bill_status, date_created, goods_money, input_user_id, payment_type,
					data_org, company_id, bill_memo)
				values ('%s', '%s', '%s', '%s', '%s', '%s', 0, now(), 0, '%s', %d, '%s', '%s', '%s')";
		
		$rc = $db->execute($sql, $id, $ref, $factoryId, $warehouseId, $bizDT, $bizUserId, 
				$loginUserId, $paymentType, $dataOrg, $companyId, $billMemo);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}
		
		$goodsDAO = new GoodsDAO($db);
		
		// 明细记录
		$items = $bill["items"];
		foreach ( $items as $i => $item ) {
			// 商品id
			$goodsId = $item["goodsId"];
			if ($goodsId == null) {
				continue;
			}
			
			// 检查商品是否存在
			$goods = $goodsDAO->getGoodsById($goodsId);
			if (! $goods) {
				return $this->bad("选择的商品不存在");
			}
			
			// 关于入库数量为什么允许填写0：
			// 当由订单生成入库单的时候，订单中有多种商品，但是是部分到货
			// 那么就存在有些商品的数量是0的情形。
			$goodsCount = $item["goodsCount"];
			if ($goodsCount < 0) {
				return $this->bad("入库数量不能是负数");
			}
			
			// 入库单明细记录的备注
			$memo = $item["memo"];
			
			// 采购单价
			$goodsPrice = $item["goodsPrice"];
			
			// 采购金额
			$goodsMoney = $item["goodsMoney"];
			
			// 采购订单明细记录id
			$dmoBillDetailId = $item["dmoBillDetailId"];
			
			$sql = "insert into t_dmw_bill_detail
						(id, date_created, goods_id, goods_count, goods_price,
						goods_money,  dmwbill_id, show_order, data_org, memo, company_id,
						dmobilldetail_id)
					values ('%s', now(), '%s', convert(%f, $fmt), %f, %f, '%s', %d, '%s', '%s', '%s', '%s')";
			$rc = $db->execute($sql, $this->newId(), $goodsId, $goodsCount, $goodsPrice, 
					$goodsMoney, $id, $i, $dataOrg, $memo, $companyId, $dmoBillDetailId);
			if ($rc === false) {
				return $this->sqlError(__METHOD__, __LINE__);
			}
		}
		
		// 同步入库单主表中的采购金额合计值
		$sql = "select sum(goods_money) as goods_money from t_dmw_bill_detail
				where dmwbill_id = '%s' ";
		$data = $db->query($sql, $id);
		$totalMoney = $data[0]["goods_money"];
		if (! $totalMoney) {
			$totalMoney = 0;
		}
		$sql = "update t_dmw_bill
				set goods_money = %f
				where id = '%s' ";
		$rc = $db->execute($sql, $totalMoney, $id);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}
		
		if ($dmobillRef) {
			// 从订单生成入库单
			$sql = "select id, company_id from t_dmo_bill where ref = '%s' ";
			$data = $db->query($sql, $dmobillRef);
			if (! $data) {
				// 传入了不存在的采购订单单号
				return $this->sqlError(__METHOD__, __LINE__);
			}
			$dmobillId = $data[0]["id"];
			$companyId = $data[0]["company_id"];
			
			$sql = "update t_dmw_bill
					set company_id = '%s'
					where id = '%s' ";
			$rc = $db->execute($sql, $companyId, $id);
			if ($rc === false) {
				return $this->sqlError(__METHOD__, __LINE__);
			}
			
			// 关联成品委托生产订单和成品委托生产入库单
			$sql = "insert into t_dmo_dmw(dmo_id, dmw_id) values('%s', '%s')";
			$rc = $db->execute($sql, $dmobillId, $id);
			if (! $rc) {
				return $this->sqlError(__METHOD__, __LINE__);
			}
		}
		
		$bill["id"] = $id;
		$bill["ref"] = $ref;
		
		// 操作成功
		return null;
	}

	/**
	 * 编辑成品委托生产入库单
	 *
	 * @param array $bill        	
	 * @return array|null
	 */
	public function updateDMWBill(& $bill) {
		$db = $this->db;
		
		// 成品委托生产入库单id
		$id = $bill["id"];
		
		// 业务日期
		$bizDT = $bill["bizDT"];
		
		// 仓库id
		$warehouseId = $bill["warehouseId"];
		
		// 工厂id
		$factoryId = $bill["factoryId"];
		
		// 业务员id
		$bizUserId = $bill["bizUserId"];
		
		// 付款方式
		$paymentType = $bill["paymentType"];
		
		// 备注
		$billMemo = $bill["billMemo"];
		
		$warehouseDAO = new WarehouseDAO($db);
		$warehouse = $warehouseDAO->getWarehouseById($warehouseId);
		if (! $warehouse) {
			return $this->bad("入库仓库不存在");
		}
		
		$factoryDAO = new FactoryDAO($db);
		$factory = $factoryDAO->getFactoryById($factoryId);
		if (! $factory) {
			return $this->bad("工厂不存在");
		}
		
		$userDAO = new UserDAO($db);
		$user = $userDAO->getUserById($bizUserId);
		if (! $user) {
			return $this->bad("业务人员不存在");
		}
		
		// 检查业务日期
		if (! $this->dateIsValid($bizDT)) {
			return $this->bad("业务日期不正确");
		}
		
		$oldBill = $this->getDMWBillById($id);
		if (! $oldBill) {
			return $this->bad("要编辑的成品委托生产入库单不存在");
		}
		$dataOrg = $oldBill["dataOrg"];
		$billStatus = $oldBill["billStatus"];
		$companyId = $oldBill["companyId"];
		if ($this->companyIdNotExists($companyId)) {
			return $this->badParam("companyId");
		}
		
		$bcDAO = new BizConfigDAO($db);
		$dataScale = $bcDAO->getGoodsCountDecNumber($companyId);
		$fmt = "decimal(19, " . $dataScale . ")";
		
		$ref = $oldBill["ref"];
		if ($billStatus != 0) {
			return $this->bad("当前成品委托生产入库单已经提交入库，不能再编辑");
		}
		$bill["ref"] = $ref;
		
		// 编辑单据的时候，先删除原来的明细记录，再新增明细记录
		$sql = "delete from t_dmw_bill_detail where dmwbill_id = '%s' ";
		$rc = $db->execute($sql, $id);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}
		
		$goodsDAO = new GoodsDAO($db);
		
		// 明细记录
		$items = $bill["items"];
		foreach ( $items as $i => $item ) {
			// 商品id
			$goodsId = $item["goodsId"];
			
			if ($goodsId == null) {
				continue;
			}
			
			$goods = $goodsDAO->getGoodsById($goodsId);
			if (! $goods) {
				return $this->bad("选择的商品不存在");
			}
			
			// 关于入库数量为什么允许填写0：
			// 当由成品委托生产订单生成入库单的时候，订单中有多种商品，但是是部分到货
			// 那么就存在有些商品的数量是0的情形。
			$goodsCount = $item["goodsCount"];
			if ($goodsCount < 0) {
				return $this->bad("入库数量不能是负数");
			}
			
			// 入库明细记录的备注
			$memo = $item["memo"];
			
			// 单价
			$goodsPrice = $item["goodsPrice"];
			
			// 金额
			$goodsMoney = $item["goodsMoney"];
			
			// 成品委托生产订单明细记录id
			$dmoBillDetailId = $item["dmoBillDetailId"];
			
			$sql = "insert into t_dmw_bill_detail (id, date_created, goods_id, goods_count, goods_price,
						goods_money,  dmwbill_id, show_order, data_org, memo, company_id, dmobilldetail_id)
					values ('%s', now(), '%s', convert(%f, $fmt), %f, %f, '%s', %d, '%s', '%s', '%s', '%s')";
			$rc = $db->execute($sql, $this->newId(), $goodsId, $goodsCount, $goodsPrice, 
					$goodsMoney, $id, $i, $dataOrg, $memo, $companyId, $dmoBillDetailId);
			if ($rc === false) {
				return $this->sqlError(__METHOD__, __LINE__);
			}
		}
		
		// 同步主表数据
		$sql = "select sum(goods_money) as goods_money from t_dmw_bill_detail
				where dmwbill_id = '%s' ";
		$data = $db->query($sql, $id);
		$totalMoney = $data[0]["goods_money"];
		if (! $totalMoney) {
			$totalMoney = 0;
		}
		$sql = "update t_dmw_bill
				set goods_money = %f, warehouse_id = '%s',
					factory_id = '%s', biz_dt = '%s',
					biz_user_id = '%s', payment_type = %d,
					bill_memo = '%s'
				where id = '%s' ";
		$rc = $db->execute($sql, $totalMoney, $warehouseId, $factoryId, $bizDT, $bizUserId, 
				$paymentType, $billMemo, $id);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}
		
		// 操作成功
		return null;
	}

	/**
	 * 获得成品委托生产入库单主表列表
	 */
	public function dmwbillList($params) {
		$db = $this->db;
		
		$start = $params["start"];
		$limit = $params["limit"];
		
		// 订单状态
		$billStatus = $params["billStatus"];
		
		// 单号
		$ref = $params["ref"];
		
		// 业务日期 -起
		$fromDT = $params["fromDT"];
		// 业务日期-止
		$toDT = $params["toDT"];
		
		// 仓库id
		$warehouseId = $params["warehouseId"];
		
		// 工厂id
		$factoryId = $params["factoryId"];
		
		$loginUserId = $params["loginUserId"];
		if ($this->loginUserIdNotExists($loginUserId)) {
			return $this->emptyResult();
		}
		
		$queryParams = [];
		$sql = "select d.id, d.bill_status, d.ref, d.biz_dt, u1.name as biz_user_name, u2.name as input_user_name,
					d.goods_money, w.name as warehouse_name, f.name as factory_name,
					d.date_created, d.payment_type, d.bill_memo
				from t_dmw_bill d, t_warehouse w, t_factory f, t_user u1, t_user u2
				where (d.warehouse_id = w.id) and (d.factory_id = f.id)
				and (d.biz_user_id = u1.id) and (d.input_user_id = u2.id) ";
		
		$ds = new DataOrgDAO($db);
		// 构建数据域SQL
		$rs = $ds->buildSQL(FIdConst::DMW, "d", $loginUserId);
		if ($rs) {
			$sql .= " and " . $rs[0];
			$queryParams = $rs[1];
		}
		
		if ($billStatus != - 1) {
			$sql .= " and (d.bill_status = %d) ";
			$queryParams[] = $billStatus;
		}
		if ($ref) {
			$sql .= " and (d.ref like '%s') ";
			$queryParams[] = "%{$ref}%";
		}
		if ($fromDT) {
			$sql .= " and (d.biz_dt >= '%s') ";
			$queryParams[] = $fromDT;
		}
		if ($toDT) {
			$sql .= " and (d.biz_dt <= '%s') ";
			$queryParams[] = $toDT;
		}
		if ($factoryId) {
			$sql .= " and (d.factory_id = '%s') ";
			$queryParams[] = $factoryId;
		}
		if ($warehouseId) {
			$sql .= " and (d.warehouse_id = '%s') ";
			$queryParams[] = $warehouseId;
		}
		
		$sql .= " order by d.biz_dt desc, d.ref desc
				limit %d, %d";
		$queryParams[] = $start;
		$queryParams[] = $limit;
		$data = $db->query($sql, $queryParams);
		$result = [];
		
		foreach ( $data as $v ) {
			$result[] = [
					"id" => $v["id"],
					"ref" => $v["ref"],
					"bizDate" => $this->toYMD($v["biz_dt"]),
					"factoryName" => $v["factory_name"],
					"warehouseName" => $v["warehouse_name"],
					"inputUserName" => $v["input_user_name"],
					"bizUserName" => $v["biz_user_name"],
					"billStatus" => $this->billStatusCodeToName($v["bill_status"]),
					"amount" => $v["goods_money"],
					"dateCreated" => $v["date_created"],
					"paymentType" => $v["payment_type"],
					"billMemo" => $v["bill_memo"]
			];
		}
		
		$sql = "select count(*) as cnt
				from t_dmw_bill d, t_warehouse w, t_factory f, t_user u1, t_user u2
				where (d.warehouse_id = w.id) and (d.factory_id = f.id)
				and (d.biz_user_id = u1.id) and (d.input_user_id = u2.id)";
		$queryParams = [];
		$ds = new DataOrgDAO($db);
		$rs = $ds->buildSQL(FIdConst::DMW, "d", $loginUserId);
		if ($rs) {
			$sql .= " and " . $rs[0];
			$queryParams = $rs[1];
		}
		if ($billStatus != - 1) {
			$sql .= " and (d.bill_status = %d) ";
			$queryParams[] = $billStatus;
		}
		if ($ref) {
			$sql .= " and (d.ref like '%s') ";
			$queryParams[] = "%{$ref}%";
		}
		if ($fromDT) {
			$sql .= " and (d.biz_dt >= '%s') ";
			$queryParams[] = $fromDT;
		}
		if ($toDT) {
			$sql .= " and (d.biz_dt <= '%s') ";
			$queryParams[] = $toDT;
		}
		if ($factoryId) {
			$sql .= " and (d.factory_id = '%s') ";
			$queryParams[] = $factoryId;
		}
		if ($warehouseId) {
			$sql .= " and (d.warehouse_id = '%s') ";
			$queryParams[] = $warehouseId;
		}
		
		$data = $db->query($sql, $queryParams);
		$cnt = $data[0]["cnt"];
		
		return [
				"dataList" => $result,
				"totalCount" => $cnt
		];
	}

	/**
	 * 获得成品委托生产入库单的明细记录
	 */
	public function dmwBillDetailList($params) {
		$db = $this->db;
		
		$companyId = $params["companyId"];
		if ($this->companyIdNotExists($companyId)) {
			return $this->emptyResult();
		}
		
		$bcDAO = new BizConfigDAO($db);
		$dataScale = $bcDAO->getGoodsCountDecNumber($companyId);
		$fmt = "decimal(19, " . $dataScale . ")";
		
		// 成品委托生产入库单id
		$id = $params["id"];
		
		$sql = "select p.id, g.code, g.name, g.spec, u.name as unit_name,
					convert(p.goods_count, $fmt) as goods_count, p.goods_price,
					p.goods_money, p.memo
				from t_dmw_bill_detail p, t_goods g, t_goods_unit u
				where p.dmwbill_id = '%s' and p.goods_id = g.id and g.unit_id = u.id
				order by p.show_order ";
		$data = $db->query($sql, $id);
		$result = [];
		
		foreach ( $data as $v ) {
			$result[] = [
					"id" => $v["id"],
					"goodsCode" => $v["code"],
					"goodsName" => $v["name"],
					"goodsSpec" => $v["spec"],
					"unitName" => $v["unit_name"],
					"goodsCount" => $v["goods_count"],
					"goodsMoney" => $v["goods_money"],
					"goodsPrice" => $v["goods_price"],
					"memo" => $v["memo"]
			];
		}
		
		return $result;
	}

	/**
	 * 删除成品委托生产入库单
	 */
	public function deleteDMWBill(& $params) {
		$db = $this->db;
		
		// 成品委托生产入库单id
		$id = $params["id"];
		
		$bill = $this->getDMWBillById($id);
		if (! $bill) {
			return $this->bad("要删除的成品委托生产入库单不存在");
		}
		
		// 单号
		$ref = $bill["ref"];
		
		// 单据状态
		$billStatus = $bill["billStatus"];
		if ($billStatus != 0) {
			return $this->bad("当前采购入库单已经提交入库，不能删除");
		}
		
		// 先删除明细记录
		$sql = "delete from t_dmw_bill_detail where dmwbill_id = '%s' ";
		$rc = $db->execute($sql, $id);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}
		
		// 再删除主表
		$sql = "delete from t_dmw_bill where id = '%s' ";
		$rc = $db->execute($sql, $id);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}
		
		// 删除从成品委托生产订单生成的记录
		$sql = "delete from t_dmo_dmw where dmw_id = '%s' ";
		$rc = $db->execute($sql, $id);
		if ($rc === false) {
			return $this->sqlError(__METHOD__, __LINE__);
		}
		
		// 操作成功
		$params["ref"] = $ref;
		return null;
	}
}