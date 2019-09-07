<?php
// +---------------------------------------------------------------------+
// | OneBase    | [ WE CAN DO IT JUST THINK ]                            |
// +---------------------------------------------------------------------+
// | Licensed   | http://www.apache.org/licenses/LICENSE-2.0 )           |
// +---------------------------------------------------------------------+
// | Author     | Bigotry <3162875@qq.com>                               |
// +---------------------------------------------------------------------+
// | Repository | https://gitee.com/Bigotry/OneBase                      |
// +---------------------------------------------------------------------+

namespace app\admin\logic;

/**
 * 奖品逻辑
 */
class Prize extends AdminBase
{
	/**
	 * 奖品列表
	 */
	public function getPrizeList($where = [], $field = '*', $order = '', $paginate = DB_LIST_ROWS)
	{
		return $this->modelPrize->getList($where, $field, $order, $paginate);
	}
	
	/**
	 * 中奖用户
	 */
	public function getUserList($where = [], $field = 'u.*, p.prize as prizename', $order = 'u.id DESC', $paginate = DB_LIST_ROWS)
	{
		$this->modelUserinfo->alias('u');
			
		$where['u.prize'] = ['<>', 0];
        
		$join = [
								[SYS_DB_PREFIX . 'prize p', 'u.prize = p.id', 'LEFT'],
						];
		
		$this->modelUserinfo->join = $join;
		
		return $this->modelUserinfo->getList($where, $field, $order, $paginate);
	}
	
	/**
	 * 未中奖用户
	 */
	public function getNoPrizeList($where = [], $field = 'u.*, p.prize as prizename', $order = 'u.id DESC', $paginate = DB_LIST_ROWS)
	{
		$this->modelUserinfo->alias('u');

		$where['u.prize'] = ['=', 0];

		$join = [
								[SYS_DB_PREFIX . 'prize p', 'u.prize = p.id', 'LEFT'],
						];
		
		$this->modelUserinfo->join = $join;
		
		return $this->modelUserinfo->getList($where, $field, $order, $paginate);
	}
    
	/**
	 * 导出会员列表
	 */
	public function exportUserList($list)
	{
			$titles = "APP会员名,APP会员电话,会员VIN值,留资姓名,留资电话,留资地址,中奖奖品,首次进入时间,中奖时间";
			$keys   = "username,phone,lv_vin,lv_user_name,lv_user_tel,lv_user_addr,prizename,create_time,update_time";
			
			action_log('导出', '导出中奖用户列表');
			
			export_excel($titles, $keys, $list, '中奖用户列表');
	}
	
	/**
	 * 获取会员列表搜索条件
	 */
	public function getWhere($data = [])
	{
			$where = [];
			
			!empty($data['search_data']) && $where['u.phone'] = ['like', '%'.$data['search_data'].'%'];
			
			!empty($data['prize_num']) && $where['p.id'] = $data['prize_num'];
			
			return $where;
	}
	
}