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
 * 访问量控制器
 */
class Pv extends AdminBase
{
	/**
	 * 访问量统计
	 */
	public function page_view()
	{
		
		$count = $this->logicPv->getPageView($this->param);
		
		$this->assign('count', $count);
		
		return $this->fetch('page_view');
	}
}