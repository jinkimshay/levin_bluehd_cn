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

use think\Db;

/**
 * 访问量逻辑
 */
class Pv extends AdminBase
{
	
	/**
	 * 获取访问量
	 */
	public function getPageView($data)
	{
		$begin = 0;
		$end = 0;
		
		!empty($data['begin_time']) && $begin = \strtotime($data['begin_time']);
		
		!empty($data['end_time']) ? $end = \strtotime($data['end_time']) : $end = time();
		
		$pv = \count(DB::name('pv')->where('create_time', '>', $begin)->where('create_time', '<', $end)->select());
		
		$uv = \count(DB::name('userinfo')->where('update_time', '>', $begin)->where('update_time', '<', $end)->select());
		
		return ['pv' => $pv, 'uv' => $uv];
	}

	
	
	/**
	 * 获取搜索条件
	 */
	public function getWhere($data = [])
	{
			$where = [];
			
			\dump(\strtotime($data['begin_time']));
			
			!empty($data['begin_time']) && $where['u.lv_user_tel'] = ['like', \strtotime($data['begin_time'])];
			
			!empty($data['end_time']) && $where['u.lv_user_tel'] = ['like', \strtotime($data['begin_time'])];

			return $where;
	}
}