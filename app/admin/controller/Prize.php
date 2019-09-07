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

namespace app\admin\controller;

/**
 * 奖品控制器
 */
class Prize extends AdminBase
{

	/**
	 * 奖品列表
	 */
	public function prize_list()
	{
		$this->assign('list', $this->logicPrize->getPrizeList());
		
		return $this->fetch('prize_list');
	}
	
	/**
	 * 中奖用户
	 */
	public function user_list()
	{
		$data = $this->param;
		
		$where = $this->logicPrize->getWhere($this->param);
		
		empty($data['begin_time']) && $data['begin_time'] = '1970-01-01';
		
		empty($data['end_time']) && $data['end_time'] = \date('Y-m-d H:i:s', \time());
		
		$where['u.update_time'] = ['between time', [$data['begin_time'], $data['end_time']]];
		
		$list = $this->logicPrize->getUserList($where);
		
		$prize = 1;
		
		$this->assign('list', $list);
		
		$this->assign('prize', $prize);
		
		$this->assign('count', \count($list));
		
		return $this->fetch('user_list');
	}
	
	/**
	 * 未中奖用户
	 */
	public function user_no_prize()
	{
		$data = $this->param;
		
		$where = $this->logicPrize->getWhere($this->param);
		
		empty($data['begin_time']) && $data['begin_time'] = '1970-01-01';
		
		empty($data['end_time']) && $data['end_time'] = \date('Y-m-d H:i:s', \time());
		
		$where['u.create_time'] = ['between time', [$data['begin_time'], $data['end_time']]];
		
		$list = $this->logicPrize->getNoPrizeList($where);
		
		$prize = 0;
		
		$this->assign('list', $list);
		
		$this->assign('prize', $prize);
		
		$this->assign('count', \count($list));
		
		return $this->fetch('user_list');
	}
	
	/**
	 * 导出中奖用户
	 */
	public function exportUserList()
	{
		$data = $this->param;
		
		// \dump($data);die();
		
		$where = $this->logicPrize->getWhere($this->param);
		
		if($data['prize'] == 0)
		{
			$field = '';
			
			$where['u.prize'] = ['=', 0];
			
			empty($data['begin_time']) && $data['begin_time'] = '1970-01-01';
			
			empty($data['end_time']) && $data['end_time'] = \date('Y-m-d H:i:s', \time());
			
			$where['u.create_time'] = ['between time', [$data['begin_time'], $data['end_time']]];
			
			$list = $this->logicPrize->getNoPrizeList($where, 'u.*, p.prize as prizename', 'u.id DESC', false);
		}
		
		if($data['prize'] == 1)
		{
			$field = 'u.*, p.prize as prizename';
			
			$where['u.prize'] = ['<>', 0];
			
			empty($data['begin_time']) && $data['begin_time'] = '1970-01-01';
			
			empty($data['end_time']) && $data['end_time'] = \date('Y-m-d H:i:s', \time());
			
			$where['u.update_time'] = ['between time', [$data['begin_time'], $data['end_time']]];
			
			$list = $this->logicPrize->getUserList($where, 'u.*, p.prize as prizename', 'u.id DESC', false);
		}

		$this->logicPrize->exportUserList($list);
	}
}