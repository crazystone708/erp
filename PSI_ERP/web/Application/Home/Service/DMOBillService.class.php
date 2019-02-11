<?php

namespace Home\Service;

use Home\DAO\DMOBillDAO;
use Home\Common\FIdConst;

/**
 * 成品委托生产订单Service
 *
 * @author 李静波
 */
class DMOBillService extends PSIBaseExService {
	private $LOG_CATEGORY = "成品委托生产订单";

	/**
	 * 获得成品委托生产订单的信息
	 */
	public function dmoBillInfo($params) {
		if ($this->isNotOnline()) {
			return $this->emptyResult();
		}
		
		$params["companyId"] = $this->getCompanyId();
		$params["loginUserId"] = $this->getLoginUserId();
		$params["loginUserName"] = $this->getLoginUserName();
		
		$dao = new DMOBillDAO($this->db());
		return $dao->dmoBillInfo($params);
	}

	/**
	 * 新建或编辑成品委托生产订单
	 */
	public function editDMOBill($json) {
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
			if (! $us->hasPermission(FIdConst::DMO_EDIT)) {
				return $this->bad("您没有编辑成品委托生产订单的权限");
			}
		} else {
			if (! $us->hasPermission(FIdConst::DMO_ADD)) {
				return $this->bad("您没有新建成品委托生产订单的权限");
			}
		}
		
		$db = $this->db();
		
		$db->startTrans();
		
		$dao = new DMOBillDAO($db);
		
		$us = new UserService();
		$bill["companyId"] = $us->getCompanyId();
		$bill["loginUserId"] = $us->getLoginUserId();
		$bill["dataOrg"] = $us->getLoginUserDataOrg();
		
		$log = null;
		if ($id) {
			// 编辑
			
			$rc = $dao->updateDMOBill($bill);
			if ($rc) {
				$db->rollback();
				return $rc;
			}
			
			$ref = $bill["ref"];
			
			$log = "编辑成品委托生产订单，单号：{$ref}";
		} else {
			// 新建
			
			$rc = $dao->addDMOBill($bill);
			if ($rc) {
				$db->rollback();
				return $rc;
			}
			
			$id = $bill["id"];
			$ref = $bill["ref"];
			
			$log = "新建成品委托生产订单，单号：{$ref}";
		}
		
		// 记录业务日志
		$bs = new BizlogService($db);
		$bs->insertBizlog($log, $this->LOG_CATEGORY);
		
		$db->commit();
		
		return $this->ok($id);
	}

	/**
	 * 获得成品委托生产订单主表信息列表
	 */
	public function dmobillList($params) {
		if ($this->isNotOnline()) {
			return $this->emptyResult();
		}
		
		$params["loginUserId"] = $this->getLoginUserId();
		
		$dao = new DMOBillDAO($this->db());
		return $dao->dmobillList($params);
	}

	/**
	 * 获得成品委托生产订单的明细信息
	 */
	public function dmoBillDetailList($params) {
		if ($this->isNotOnline()) {
			return $this->emptyResult();
		}
		
		$params["companyId"] = $this->getCompanyId();
		
		$dao = new DMOBillDAO($this->db());
		return $dao->dmoBillDetailList($params);
	}

	/**
	 * 成品委托生产订单的入库情况列表
	 */
	public function dmoBillDMWBillList($params) {
		if ($this->isNotOnline()) {
			return $this->emptyResult();
		}
		
		$dao = new DMOBillDAO($this->db());
		return $dao->dmoBillDMWBillList($params);
	}

	/**
	 * 删除成品委托生产订单
	 */
	public function deleteDMOBill($params) {
		if ($this->isNotOnline()) {
			return $this->notOnlineError();
		}
		
		$db = $this->db();
		
		$db->startTrans();
		
		$dao = new DMOBillDAO($db);
		
		$rc = $dao->deleteDMOBill($params);
		if ($rc) {
			$db->rollback();
			return $rc;
		}
		
		$ref = $params["ref"];
		$log = "删除成品委托生产订单，单号：{$ref}";
		$bs = new BizlogService($db);
		$bs->insertBizlog($log, $this->LOG_CATEGORY);
		
		$db->commit();
		
		return $this->ok();
	}

	/**
	 * 审核成品委托生产订单
	 */
	public function commitDMOBill($params) {
		if ($this->isNotOnline()) {
			return $this->notOnlineError();
		}
		
		$db = $this->db();
		$db->startTrans();
		
		$dao = new DMOBillDAO($db);
		
		$params["loginUserId"] = $this->getLoginUserId();
		
		$rc = $dao->commitDMOBill($params);
		if ($rc) {
			$db->rollback();
			return $rc;
		}
		
		$ref = $params["ref"];
		$id = $params["id"];
		
		// 记录业务日志
		$log = "审核成品委托生产订单，单号：{$ref}";
		$bs = new BizlogService($db);
		$bs->insertBizlog($log, $this->LOG_CATEGORY);
		
		$db->commit();
		
		return $this->ok($id);
	}

	/**
	 * 取消审核成品委托生产订单
	 */
	public function cancelConfirmDMOBill($params) {
		if ($this->isNotOnline()) {
			return $this->notOnlineError();
		}
		
		$id = $params["id"];
		
		$db = $this->db();
		$db->startTrans();
		
		$dao = new DMOBillDAO($db);
		$rc = $dao->cancelConfirmDMOBill($params);
		if ($rc) {
			$db->rollback();
			return $rc;
		}
		
		$ref = $params["ref"];
		
		// 记录业务日志
		$log = "取消审核成品委托生产订单，单号：{$ref}";
		$bs = new BizlogService($db);
		$bs->insertBizlog($log, $this->LOG_CATEGORY);
		
		$db->commit();
		
		return $this->ok($id);
	}

	/**
	 * 关闭成品委托生产订单
	 */
	public function closeDMOBill($params) {
		if ($this->isNotOnline()) {
			return $this->notOnlineError();
		}
		
		$id = $params["id"];
		
		$db = $this->db();
		$db->startTrans();
		
		$dao = new DMOBillDAO($this->db());
		$rc = $dao->closeDMOBill($params);
		if ($rc) {
			$db->rollback();
			return $rc;
		}
		
		$ref = $params["ref"];
		
		// 记录业务日志
		$log = "关闭成品委托生产订单，单号：{$ref}";
		$bs = new BizlogService($db);
		$bs->insertBizlog($log, $this->LOG_CATEGORY);
		
		$db->commit();
		
		return $this->ok($id);
	}

	/**
	 * 取消关闭成品委托生产订单
	 */
	public function cancelClosedDMOBill($params) {
		if ($this->isNotOnline()) {
			return $this->notOnlineError();
		}
		
		$id = $params["id"];
		
		$db = $this->db();
		$db->startTrans();
		$dao = new DMOBillDAO($db);
		$rc = $dao->cancelClosedDMOBill($params);
		if ($rc) {
			$db->rollback();
			return $rc;
		}
		
		$ref = $params["ref"];
		
		// 记录业务日志
		$log = "取消关闭成品委托生产订单，单号：{$ref}";
		$bs = new BizlogService($db);
		$bs->insertBizlog($log, $this->LOG_CATEGORY);
		
		$db->commit();
		
		return $this->ok($id);
	}
}