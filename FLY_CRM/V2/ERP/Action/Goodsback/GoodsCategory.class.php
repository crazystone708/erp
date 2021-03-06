<?php
  /*
 *
 * admin.GoodsCategory 商品分类
 *
 * =========================================================
 * 零起飞网络 - 专注于网站建设服务和行业系统开发
 * 以质量求生存，以服务谋发展，以信誉创品牌 !
 * ----------------------------------------------
 * @copyright	Copyright (C) 2017-2018 07FLY Network Technology Co,LTD (www.07FLY.com) All rights reserved.
 * @license    For licensing, see LICENSE.html or http://www.07fly.top/crm/license
 * @author ：kfrs <goodkfrs@QQ.com> 574249366
 * @version ：1.0
 * @link ：http://www.07fly.top 
 */	
class GoodsCategory extends Action {

	private $cacheDir = ''; //缓存目录
	private $auth;
	public function __construct() {
		$this->auth = _instance( 'Action/sysmanage/Auth' );
	}

	public function goods_category() {
		$sql	= "select *,category_name as text,category_id as tags from fly_goods_category order by sort asc;";
		$list 	= $this->C( $this->cacheDir )->findAll( $sql );
		return $list;
	}
	
	//得到数形参数
	function getTree( $data, $pId=0,$level=0) {
		$tree = '';
		foreach ( $data as $k => $v ) {
			if ( $v[ 'parent_id' ] == $pId ) { //父亲找到儿子
				$v[ 'children' ] = $this->getTree( $data, $v[ 'category_id' ], $level + 1);
				$v[ 'level' ] =  $level + 1;
				$tree[] = $v;
			}
		}
		return $tree;
	}
	
	//输出树形参数
	function getTreeHtml($tree) {
		$html = '';
		foreach ( $tree as $t ) {
			$kg="";
			//$fx=($t['level']>1)?"|——":"";
			for($x=1;$x<$t['level'];$x++) {
				$kg .="<i class='fly-fl'>|—</i>";
			}
			if ( $t[ 'children' ] == '' ) {
				$html .= "<li><div class='fly-row lines'>
								<i class='fly-fl'>&nbsp;</i>
								<div  class='fly-col-5'>".$kg."<input type='text' name='name[]'  data-id='".$t['category_id']."' value='".$t['category_name']."' class='form-control w150 treeName'/></div>
								
								<div  class='fly-col-2 fly-fr fly-tr'>
									<a href='".ACT."/goods/GoodsCategory/goods_category_add/pid/".$t['category_id']."/'>增加下级</a> 
									<a href='".ACT."/goods/GoodsCategory/goods_category_modify/id/".$t['category_id']."/'>修改</a> 
									<a href='".ACT."/goods/GoodsCategory/goods_category_del/id/".$t['category_id']."/'>删除</a>
								</div>
								<div  class='fly-col-2  fly-fr fly-tr'><input type='text' name='sort[]'  data-id='".$t['category_id']."' value='".$t['sort']."' class='form-control w100 treeSort'/></div>
							</div>
						  </li>";
			} else {
				$html .= "<li><div class='fly-row lines'>
								<lable class='fly-col-1'>[+]</lable>
								<div  class='fly-col-5'>".$kg."<input type='text' name='name[]'  data-id='".$t['category_id']."' value='".$t['category_name']."' class='form-control w150 treeName'/></div>
								<div  class='fly-col-2  fly-fr fly-tr'>
									<a href='".ACT."/goods/GoodsCategory/goods_category_add/pid/".$t['category_id']."/'>增加下级</a> 
									<a href='".ACT."/goods/GoodsCategory/goods_category_modify/id/".$t['category_id']."/'>修改</a> 
									<a href='".ACT."/goods/GoodsCategory/goods_category_del/id/".$t['category_id']."/'>删除</a>
								</div>
								<div class='fly-col-2  fly-fr fly-tr'><input type='text' name='sort[]'  data-id='".$t['category_id']."' value='".$t['sort']."' class='form-control w100 treeSort'/></div>
							</div>
							";
				$html .= $this->getTreeHtml( $t[ 'children' ] );
				$html .= "</li>";
			}
		}
		return $html ? '<ul>' . $html . '</ul>': $html;
	}

	public function selectTree($param, $pid = 0, $lvl = 0)
	{
		static $res =array();
		foreach ($param as $key => $vo) {
			if ($pid == $vo['parent_id']) {
				$vo['category_name'] = str_repeat('&nbsp;&nbsp;', $lvl) . '|--' . $vo['category_name'];
				$res[] = $vo;
				$temp = $lvl + 1;
				$this->selectTree($param, $vo['category_id'], $temp);
			}
		}
		return $res;
	}
	
	//得到数形参数
	function leftTree( $data, $pId=0,$level=0) {
		$tree = '';
		foreach ( $data as $k => $v ) {
			if ( $v[ 'parent_id' ] == $pId ) { //父亲找到儿子
				$v[ 'nodes' ] = $this->getTree( $data, $v[ 'category_id' ], $level + 1);
				$v[ 'level' ] = $level + 1;
				$tree[] = $v;
			}
			
		}
		return $tree;
	}
	//boot tree格式输出
	public function goods_category_left_json(){
		$list =$this->goods_category();
		$list=$this->leftTree($list);	
		echo json_encode($list);
	}
	
	//得到下拉选择html
	public function goods_category_select($tags=null,$sid=null){
		$list =$this->goods_category();
		$list=$this->selectTree($list);
		$rtn  = "<select name='".$tags."' id='".$tags."' class=\"form-control m-b\">";
		$rtn  .= "<option value='0' >--选择分类--</option>";
		foreach($list as $key=>$row){
			$selected=($row['category_id']==$sid)?'selected':'';
			$rtn .="<option value=".$row['category_id']." ".$selected." >".$row['category_name']."</option>";
		}
		$rtn .="</select>";
		return $rtn;
	}

	
	public
	function goods_category_show() {
		$list =$this->goods_category();
		$tree =$this->getTree($list, 0 );
		$treeHtml=$this->getTreeHtml($tree);
		$smarty = $this->setSmarty();
		$smarty->assign( array( "treeHtml" => $treeHtml) );
		$smarty->display( 'goods/goods_category_show.html' );
	}

	public function goods_category_add() {
		if ( empty( $_POST ) ) {
			$pid=$this->_REQUEST('pid');
			$parent_id = $this->goods_category_select('parent_id',$pid);
			$smarty = $this->setSmarty();
			$smarty->assign( array( "parent_id" => $parent_id ) );
			$smarty->display( 'goods/goods_category_add.html' );
		} else {
			$parent_id = $this->_REQUEST( "parent_id" );
			$sort = $this->_REQUEST( "sort" );
			$visible = $this->_REQUEST( "visible" );
			$keywords = $this->_REQUEST( "keywords" );
			$description = $this->_REQUEST( "description" );
			
			$data=array(
					'category_name'=>$this->_REQUEST( "category_name" ),
					'short_name'=>$this->_REQUEST( "short_name" ),
					'short_name'=>$this->_REQUEST( "short_name" ),
					'parent_id'=>$parent_id,
					'sort'=>$sort,
					'visible'=>$visible,
					'keywords'=>$keywords,
					'description'=>$description
				 );
			$this->C( $this->cacheDir )->insert('fly_goods_category',$data );
			$this->location( "操作成功", "/goods/GoodsCategory/goods_category_show/" );
		}
	}
	
	public
	function goods_category_modify() {
		$id = $this->_REQUEST( "id" );
		if ( empty( $_POST ) ) {
			$sql = "select * from fly_goods_category where category_id='$id'";
			$one = $this->C( $this->cacheDir )->findOne( $sql );
			$parent_id = $this->goods_category_select( "parent_id", $one[ "parent_id" ] );
			$smarty = $this->setSmarty();
			$smarty->assign( array( "one" => $one, "parent_id" => $parent_id ) );
			$smarty->display( 'goods/goods_category_modify.html' );
		} else {

			$parent_id = $this->_REQUEST( "parent_id" );
			$sort = $this->_REQUEST( "sort" );
			$visible = $this->_REQUEST( "visible" );
			$keywords = $this->_REQUEST( "keywords" );
			$description = $this->_REQUEST( "description" );

			$data=array(
					'category_name'=>$this->_REQUEST( "category_name" ),
					'short_name'=>$this->_REQUEST( "short_name" ),
					'short_name'=>$this->_REQUEST( "short_name" ),
					'parent_id'=>$parent_id,
					'sort'=>$sort,
					'visible'=>$visible,
					'keywords'=>$keywords,
					'description'=>$description
				 );
			$this->C( $this->cacheDir )->modify('fly_goods_category',$data,"category_id='$id'",true);
			$this->location( "操作成功", "/goods/GoodsCategory/goods_category_show/" );
		}
	}
	
	public
	function goods_category_del() {
		$id = $this->_REQUEST( "id" );
		$sql = "delete from fly_goods_category where category_id='$id'";
		$this->C( $this->cacheDir )->update( $sql );
		$this->location( "操作成功", "/goods/GoodsCategory/goods_category_show/" );
	}
	//排序
	public
	function goods_category_modify_sort() {
		$id		=$this->_REQUEST('id');	
		$sort	=$this->_REQUEST('sort');	
		$upt_data=array(
					'sort'=>$this->_REQUEST( "sort" )
				 );
		$this->C( $this->cacheDir )->modify('fly_goods_category',$upt_data,"category_id='$id'",true);
		$rtnArr=array('rtnstatus'=>'success','msg'=>'');
		echo json_encode($rtnArr);
	}
	//修改名称
	public
	function goods_category_modify_name() {
		$id		=$this->_REQUEST('id');	
		$name	=$this->_REQUEST('name');	
		$upt_data=array(
					'category_name'=>$this->_REQUEST( "name" )
				 );
		$this->C( $this->cacheDir )->modify('fly_sys_position',$upt_data,"category_id='$id'",true);
		$rtnArr=array('rtnstatus'=>'success','msg'=>'');
		echo json_encode($rtnArr);
	}

	//递归获取所有的子分类的ID
	function get_all_child($array,$id){
		$arr = array();
		foreach($array as $v){
			if($v['parent_id'] == $id){
				$arr[] = $v['category_id'];
				$arr = array_merge($arr,$this->get_all_child($array,$v['category_id']));
			};
		};
		return $arr;
	}
	//获得所有子类id,通过数组形式返回
	public
	function goods_category_all_child($pid){
		$data =$this->goods_category();
		$child=$this->get_all_child($data,$pid);
		return $child;
	}
	
} //
?>