<?php

namespace Home\Service;

use Home\DAO\DMWBillDAO;
use Home\Common\FIdConst;

/**
 * 成品委托生产入库单Service
 *
 * @author 李静波
 */
class DMWBillService extends PSIBaseExService {
	private $LOG_CATEGORY = "成品委托生产入库";

	/**
	 * 成品委托生产入库单 - 单据详情
	 */
	public function dmwBillInfo($params) {
		if ($this->isNotOnline()) {
			return $this->emptyResult();
		}
		
		$params["companyId"] = $this->getCompanyId();
		$params["loginUserId"] = $this->getLoginUserId();
		$params["loginUserName"] = $this->getLoginUserName();
		
		$dao = new DMWBillDAO($this->db());
		return $dao->dmwBillInfo($params);
	}

	/**
	 * 新建或编辑成品委托生产入库单
	 */
	public function editDMWBill($json) {
		if ($this->isNotOnline()) {
			return $this->notOnlineError();
		}
		
		$bill = json_decode(html_entity_decode($json), true);
		if ($bill == null) {
			return $this->bad("传入的参数错误，不是正确的JSON格式");
		}
		
		$id = $bill["id"];
		
		// 判断权限
		$us = new UserService();
		if ($id) {
			if (! $us->hasPermission(FIdConst::DMW_EDIT)) {
				return $this->bad("您没有编辑成品委托生产入库单的权限");
			}
		} else {
			if (! $us->hasPermission(FIdConst::DMW_ADD)) {
				return $this->bad("您没有新建成品委托生产入库单的权限");
			}
		}
		
		$db = $this->db();
		
		$db->startTrans();
		
		$dao = new DMWBillDAO($db);
		
		$us = new UserService();
		$bill["companyId"] = $us->getCompanyId();
		$bill["loginUserId"] = $us->getLoginUserId();
		$bill["dataOrg"] = $us->getLoginUserDataOrg();
		
		$log = null;
		if ($id) {
			// 编辑
			
			$rc = $dao->updateDMWBill($bill);
			if ($rc) {
				$db->rollback();
				return $rc;
			}
			
			$ref = $bill["ref"];
			
			$log = "编辑成品委托生产入库单，单号：{$ref}";
		} else {
			// 新建
			
			$rc = $dao->addDMWBill($bill);
			if ($rc) {
				$db->rollback();
				return $rc;
			}
			
			$id = $bill["id"];
			$ref = $bill["ref"];
			
			$log = "新建成品委托生产入库单，单号：{$ref}";
		}
		
		// 记录业务日志
		$bs = new BizlogService($db);
		$bs->insertBizlog($log, $this->LOG_CATEGORY);
		
		$db->commit();
		
		return $this->ok($id);
	}

	/**
	 * 获得成品委托生产入库单主表列表
	 */
	public function dmwbillList($params) {
		if ($this->isNotOnline()) {
			return $this->emptyResult();
		}
		
		$params["loginUserId"] = $this->getLoginUserId();
		
		$dao = new DMWBillDAO($this->db());
		return $dao->dmwbillList($params);
	}

	/**
	 * 获得成品委托生产入库单的明细记录
	 */
	public function dmwBillDetailList($params) {
		if ($this->isNotOnline()) {
			return $this->emptyResult();
		}
		
		$params["companyId"] = $this->getCompanyId();
		
		$dao = new DMWBillDAO($this->db());
		return $dao->dmwBillDetailList($params);
	}

	/**
	 * 删除成品委托生产入库单
	 */
	public function deleteDMWBill($params) {
		if ($this->isNotOnline()) {
			return $this->notOnlineError();
		}
		
		$db = $this->db();
		$db->startTrans();
		
		$dao = new DMWBillDAO($db);
		$rc = $dao->deleteDMWBill($params);
		if ($rc) {
			$db->rollback();
			return $rc;
		}
		
		// 记录业务日志
		$ref = $params["ref"];
		$log = "删除成品委托生产入库单: 单号 = {$ref}";
		$bs = new BizlogService($db);
		$bs->insertBizlog($log, $this->LOG_CATEGORY);
		
		$db->commit();
		
		return $this->ok();
	}
}