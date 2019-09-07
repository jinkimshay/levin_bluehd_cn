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

namespace app\api\logic;

/**
 * 用户留资接口逻辑
 */
class Userinfo extends ApiBase
{
	public function sendUserInfo ($data)
	{
		$result = $this->modelUserinfo->setInfo($data);
		
		return $result ? [RESULT_SUCCESS, '用户存储成功'] : [RESULT_ERROR, $this->modelUserinfo->getError()];
	}
	
	public function userinfo ($where, $data) 
	{
		$result = $this->modelUserinfo->where($where)->update($data);
		
		return $result ? [RESULT_SUCCESS, '用户留资成功'] : [RESULT_ERROR, $this->modelUserinfo->getError()];
	}
	
	public function pv() 
	{
		$this->modelPv->setInfo(['time'=> \time()]);
	}
	
	public function uv($vin) 
	{
		$data = [
			'u_vin' => $vin,
			'create_time' => \time()
		];
		$this->modelUv->setInfo($data);
	}
	
	public function getUserInfo ($where)
	{

		return $this->modelUserinfo->getInfo($where);
	}
		
		public function getUserList($where)
		{
			// 当前月份开始时间戳
			$star_time = mktime(0,0,0,\date('m'),1,\date('Y'));
			// 当前月份结束时间戳
			$end_time = mktime(23,59,59,date('m'),date('t'),date('Y'));
			
			return $this->modelUserinfo->where($where)->where('create_time', 'between', [$star_time, $end_time])->select();
		}
		
				/**
		 * 中奖逻辑
		 */
		public function prize($vin)
		{

			$mon = \date('m', \time()); // 当前月份
			$this->modelUserinfo->where('lv_vin', $vin)->update(['update_time'=> \time()]); // 更新用户中奖时间
			$num = \rand(0, 5953); // 抽奖随机数

			// 九月份前三奖中奖逻辑
			// 一等奖 1 		二等奖 1 	三等奖 1 	四等奖 25 		五等奖 250 	六等奖 3425 	八等奖 	22500
			if($mon == 9)
			{
				/**
				// 判断一等奖是否已中
				$prizeFirst = $this->getUserList(['prize' => 1]);
				if(empty($prizeFirst))
				{
					// 判断用户是否抽中,或者在当月最后一天还没中出一等奖,由第一个进入抽奖的人中一等奖
					if($num == 0 || date('d') == 30)
					{
						return 1;
					}
				}
				// 判断二等奖是否已中
				$prizeSecond = $this->getUserList(['prize' => 2]);
				if(empty($prizeSecond))
				{
					// 判断用户是否抽中,或者在当月最后一天还没中出一等奖,由第一个进入抽奖的人中一等奖
					if($num == 0 || date('d') == 30)
					{
						return 2;
					}
				}
				// 判断三等奖是否已中
				$prizeThird = $this->getUserList(['prize' => 3]);
				if(empty($prizeThird))
				{
					// 判断用户是否抽中,或者在当月最后一天还没中出三等奖,由第一个进入抽奖的人中三等奖
					if($num == 5953 || date('d') == 30)
					{
						return 3;
					}
				}
				*/
				$numth = 1;
			}
			
			// 十月份前三奖中奖逻辑
			// 一等奖 0 		二等奖 1 	三等奖 1 	四等奖 25 		五等奖 250 	六等奖 3425 	八等奖 	22500
			if($mon == 10)
			{
				/**
				// 判断一等奖是否已中
				$prizeFirst = $this->getUserList(['prize' => 1]);
				if(empty($prizeFirst))
				{
					// 判断用户是否抽中,或者在当月最后一天还没中出一等奖,由第一个进入抽奖的人中一等奖
					if($num == 0 || date('d') == 30)
					{
						return 1;
					}
				}
				// 判断二等奖是否已中
				$prizeSecond = $this->getUserList(['prize' => 2]);
				if(\count($prizeSecond) < 2)
				{
					// 判断用户是否抽中,或者在当月最后一天还没中出一等奖,由第一个进入抽奖的人中一等奖
					if($num == 0 || date('d') == 30)
					{
						return 2;
					}
				}
				// 判断三等奖是否已中
				$prizeThird = $this->getUserList(['prize' => 3]);
				if(\count($prizeThird) < 2)
				{
					// 判断用户是否抽中,或者在当月最后一天还没中出三等奖,由第一个进入抽奖的人中三等奖
					if($num == 5953 || date('d') == 30)
					{
						return 3;
					}
				}
				*/
				$numth = 2;
			}
			
			// 十一月份前三奖中奖逻辑
			// 一等奖 0 		二等奖 1 	三等奖 1 	四等奖 25 		五等奖 250 	六等奖 3425 	八等奖 	22500
			if($mon == 11)
			{
				/**
				// 判断一等奖是否已中
				$prizeFirst = $this->getUserList(['prize' => 1]);
				if(empty($prizeFirst))
				{
					// 判断用户是否抽中,或者在当月最后一天还没中出一等奖,由第一个进入抽奖的人中一等奖
					if($num == 0 || date('d') == 30)
					{
						return 1;
					}
				}
				// 判断二等奖是否已中
				$prizeSecond = $this->getUserList(['prize' => 2]);
				if(\count($prizeSecond) < 3)
				{
					// 判断用户是否抽中,或者在当月最后一天还没中出一等奖,由第一个进入抽奖的人中一等奖
					if($num == 0 || date('d') == 30)
					{
						return 2;
					}
				}
				// 判断三等奖是否已中
				$prizeThird = $this->getUserList(['prize' => 3]);
				if(\count($prizeThird) < 3)
				{
					// 判断用户是否抽中,或者在当月最后一天还没中出三等奖,由第一个进入抽奖的人中三等奖
					if($num == 5953 || date('d') == 30)
					{
						return 3;
					}
				}
				*/
				$numth = 3;
			}
			
			// 十二月份前三奖中奖逻辑
			// 一等奖 1 		二等奖 0 	三等奖 2 	四等奖 25 		五等奖 250 	六等奖 3425 	八等奖 	22500
			if($mon == 12)
			{
				/**
				// 判断一等奖是否已中
				$prizeFirst = $this->getUserList(['prize' => 1]);
				if(\count($prizeFirst) < 2)
				{
					// 判断用户是否抽中,或者在当月最后一天还没中出一等奖,由第一个进入抽奖的人中一等奖
					if($num == 0 || date('d') == 30)
					{
						return 1;
					}
				}
				// 判断二等奖是否已中
				$prizeSecond = $this->getUserList(['prize' => 2]);
				if(\count($prizeSecond) < 3)
				{
					// 判断用户是否抽中,或者在当月最后一天还没中出一等奖,由第一个进入抽奖的人中一等奖
					if($num == 0 || date('d') == 30)
					{
						return 2;
					}
				}
				// 判断三等奖是否已中
				$prizeThird = $this->getUserList(['prize' => 3]);
				if(\count($prizeThird) < 5)
				{
					// 判断用户是否抽中,或者在当月最后一天还没中出三等奖,由第一个进入抽奖的人中三等奖
					if($num == 5953 || date('d') == 30)
					{
						return 3;
					}
				}
				*/
				$numth = 4;
			}
			// 抽奖四等奖
			/* $prizeFourth = $this->modelPrize->where(['id' => 4])->find();
			if($prizeFourth['surplus_num'] < 25 * $numth)
			{
				if($num % 238 == 0)
				{
					return 4;
				}
			} */
			// 抽五等奖
			/*$prizeFifth = $this->modelPrize->where(['prize' => 5])->find();
			if($prizeFifth['surplus_num'] < 250 * $numth)
			{
				if($num % 24 == 0)
				{
					return 5;
				}
			}*/
			// 抽六等奖
			/*$prizeSixth = $this->modelPrize->where(['prize' => 6])->find();
			if($prizeSixth['surplus_num'] < 3425 * $numth)
			{
				if($num % 2 == 0 || $num % 425 == 0)
				{
					return 6;
				}
			}*/
			// 以上都不中,返回八等奖
			$prizeEighth = $this->modelPrize->where(['prize' => 8])->find();
			if($prizeEighth['surplus_num'] < 90000)
			{
				return 8;
			}else{
				if($prizeSixth['surplus_num'] < 13700)
				{
					return 6;
				}elseif($prizeFifth['surplus_num'] < 1000){
					
					return 5;
				}elseif($prizeFourth['surplus_num'] < 100){
					
					return 4;
				}
			}
		}
}