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

namespace app\api\controller;

use think\Db;

/**
 * 题库接口控制器
 */
class Problem extends ApiBase
{
		
		/**
		 * 题库列表接口
		 */
		public function problemList()
		{
			$arr = \range(1, 18);
			$id_arr = array_rand($arr, 5);

			$one['id'] = $arr[$id_arr[0]];
			$two['id'] = $arr[$id_arr[1]];
			$three['id'] = $arr[$id_arr[2]];
			$four['id'] = $arr[$id_arr[3]];
			$five['id'] = $arr[$id_arr[4]];
			
			$data = [
				0=> $this->logicProblem->getProblemList($one),
				1=> $this->logicProblem->getProblemList($two),
				2=> $this->logicProblem->getProblemList($three),
				3=> $this->logicProblem->getProblemList($four),
				4=> $this->logicProblem->getProblemList($five)
			];
			
			return $this->apiReturn($data);
		}
}
