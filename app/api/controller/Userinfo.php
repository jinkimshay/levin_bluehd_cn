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
 * 用户留资接口控制器
 */
class Userinfo extends ApiBase
{
		/**
		 * 首页进来会员存储
		 */
		public function sendUserInfo ()
		{
			$data = $this->param;
			$username = $data['user'];
			$phone = $data['tel'];
			$vin = $data['vin'];
			$where['lv_vin'] = $vin;
			
			$data = [
				'lv_vin' => $vin,
				'username' => $username,
				'create_time' => \time(),
				'phone' => $phone
			];
			
			$this->logicUserinfo->pv(); // 保存访问量
			
			$re = $this->logicUserinfo->getUserInfo($where);

			if($re == ''){
				return $this->apiReturn($this->logicUserinfo->sendUserInfo($data));
			}else{
				return $this->apiReturn(['msg'=>'会员已存在', 'code'=> 1]);
			}
		}
		
		/**
		 * 用户留资接口
		 */
		public function userinfo()
		{
			$data = $this->param;
			
			$vin = $data['vin'];
			
			$where['lv_vin'] = $vin;
			
			$param = [
				'lv_user_name' => $data['username'],
				'lv_user_tel' => $data['usertel'],
				'lv_user_addr' => $data['useraddr'],
			];
			
			return $this->apiReturn($this->logicUserinfo->userinfo($where, $param));
		}
		
		/**
		 * 查询会员是否已参加游戏
		 * @param {Object} $where
		 */
		public function getUserInfo ()
		{
			$data = $this->param;
			$vin = $data['vin'];
			$where['lv_vin'] = $vin;
			$re = $this->logicUserinfo->getUserInfo($where);
			
			if(!empty($re) && $re['prize'] != 0)
			{// 有数据且已中奖
				$type = 0; // 不需要留资
				if(\in_array($re['prize'], [1,2,3,7]))
				{
					$type = 1; // 需要留资
				}
				if($re['lv_user_tel'] != '')
				{// 已中奖已留资
					return $this->apiReturn(['code'=>1, 'pid'=>$re['prize'], 'type'=>$type]);
					
				}else{// 已中奖未留资
					return $this->apiReturn(['code'=>2, 'pid'=>$re['prize'], 'type'=>$type]);
				}
			}else{
				$this->logicUserinfo->uv($vin); // 保存答题量
			}
			return $this->apiReturn(['code'=>0]);
		}
		
		/**
		 * 优惠券发放
		 */
		public function sendCoupon()
		{
			$data = $this->param;
			$phone = $data['tel'];
			$_type = $data['type'];
			$couponBatchs = '';
			if($_type == 1){
				$couponBatchs = 'A0000020190903182114'; // 100元优惠券
			}
			if($_type == 2){
				$couponBatchs = 'A0000020190903181558'; // 200元优惠券
			}
			if($_type == 3){
				$couponBatchs = 'A0000020190903181245'; // 300元优惠券
			}
			$url = 'https://carapp.gtmc.com.cn/appservice/api/action/CouponInfoAction/SendCouponToUserNotVerify.json'; // 正式环境接口
			// $url = 'https://carapptest.gtmc.com.cn/appservice/api/action/CouponInfoAction/SendCouponToUserNotVerify.json'; // 测试环境接口
			$appId = '195';
          	// $appId = '203';

			$key = '9837bbee26fe4ad8d9ec197357a71ede';
          	// $key = '1aw20f93uy69ui8u4326f10b37412auw';
			$nonce = \rand(0, 9999);
			$timestamp = \time();
			// $phone = '13636683620';
			$numbers = 1;
			$str = $key.$nonce.$timestamp;
			$signature = \sha1($str);
			$signature = \strtoupper($signature);
			$params = [
				'signature' => $signature,
				'appId' => $appId,
				'nonce' => $nonce,
				'timestamp' => $timestamp,
				'phone' => $phone,
				'couponBatchs' => $couponBatchs,
				'numbers' => $numbers
			];
			
			return $this->apiReturn($this->http_post($url, $params));
		}
		
		/**
		 * 站内信发送
		 */
		public function pushMessage()
		{
			$data = $this->param;
			$phone = $data['tel'];
			$msgContent = $data['msg'];
			
			$url = 'https://carapp.gtmc.com.cn/appservice/api/action/UserInfoAction/pushMessageToUser.json'; // 正式环境接口
			// $url = 'https://carapptest.gtmc.com.cn/appservice/api/action/UserInfoAction/pushMessageToUser.json'; // 测试环境接口
			$appId = '195';
			$key = '9837bbee26fe4ad8d9ec197357a71ede';
			$nonce = \rand(0, 9999);
			$timestamp = \time();
			$sendWay = 2;
			// $phone = '13636683620';
			$signature = \sha1($key.$nonce.$timestamp);
			$signature = \strtoupper($signature);

			$params = [
				'signature' => $signature,
				'appId' => $appId,
				'nonce' => $nonce,
				'timestamp' => $timestamp,
				'phone' => $phone,
				'msgContent' => $msgContent,
				'sendWay' => $sendWay
			];
			return $this->apiReturn(json_decode($this->http_post($url,$params), true));
		}
		
		/**
		 * 验证车联网开通状态
		 */
		public function getCarWhetherOpen()
		{
			$data = $this->param;
			$vin = $data['vin'];
			$userId = $data['userId'];
			$url = 'https://carapptest.gtmc.com.cn/api/vhcNet/vhcSync/queryVhcStatus'; // 测试环境接口
			$appId = '206';
			$key = 'uw4l0e14282dd5c10673b36e9b2e8866';
			$nonce = \rand(100000, 999999);
			$timestamp = \time();
			// 加密
			$signature = \sha1($key.$nonce.$timestamp);
			$signature = \strtoupper($signature);
			// 参数组合
			$params = [  
				'signature' => $signature,
				'appId' => $appId,
				'nonce' => $nonce,
				'timestamp' => $timestamp,
				'vin' => $vin,
				'userId' => $userId
			];
			
			// 返回
			return $this->apiReturn(json_decode($this->http_post_header($url,$params), true));
		}
		
		/**
		 * 验证262车主
		 */
		public function getCarWhetherPerson()
		{
			$data = $this->param;
			$vin = $data['vin'];
			$userId = $data['userId'];
			$phone = $data['phone'];
			$url = 'https://carapptest.gtmc.com.cn/api/vhcNet/vhcSync/getCarCodeByVinAndPhone'; // 测试环境接口
			$appId = '206';
			$key = 'uw4l0e14282dd5c10673b36e9b2e8866';
			$nonce = \rand(100000, 999999);
			$timestamp = \time();
			// 加密
			$signature = \sha1($key.$nonce.$timestamp);
			$signature = \strtoupper($signature);
			// 参数组合
			$params = [  
				'signature' => $signature,
				'appId' => $appId,
				'nonce' => $nonce,
				'timestamp' => $timestamp,
				'vin' => $vin,
				'phone' => $phone,
				'userId' => $userId
			];
			
			// 返回
			return $this->apiReturn(json_decode($this->http_post_header($url,$params), true));
		}
		
		/**
		 * 能量发放调用接口
		 * @param {string} $phone 电话号码
		 */
		public function energyGrant()
		{
			$data = $this->param;
			$phone = $data['tel'];
			$vin = $data['vin'];
			
			$appId = '1011';
			$incValue = 2000;
			$staffCode = $phone;
			$taskType = 'hd_yxyl';
			$versionId = 1;
			$key = 'fynl090801yx';
			$iv = 'ndk5849843ief83r';
			$secret_key = '03d031158bb523d2';
			
			$str = 'incValue='.$incValue.'&staffCode='.$staffCode.'&taskType='.$taskType.'&versionId='.$versionId.'&key='.$key;
			$str = \strtolower($str);
			$str = \sha1($str);
			$sign = \strtolower($str);
			$en_str = 'incValue='.\urlencode($incValue).'&staffCode='.\urlencode($staffCode).'&taskType='.\urlencode($taskType).'&versionId='.\urlencode($versionId).'&sign='.\urlencode($sign);
			$aes_str = \openssl_encrypt($en_str, 'AES-128-CBC', $secret_key, 0, $iv);
			$aes_str2 = \urlencode($aes_str);
			$url = 'https://carapp.gtmc.com.cn/api/fyx-energy-api/act/collect'; // 正式环境
			// $url = 'https://carapptest.gtmc.com.cn/api/fyx-energy-api/act/collect'; // 测试环境
			$end_url = $url.'?pack='.$aes_str2.'&appId='.$appId;
			
			$result = $this->http_get($end_url);
			$result = \json_decode($result);
			if($result->success == 'true')
			{
				return $this->apiReturn($result);
			}else{
				
				$where['lv_vin'] = $vin;
				Db::name('userinfo')->where('lv_vin', $vin)->update(['type'=> -1]);
			}
		}
		
		/**
		 * GET方式
		 */
		public function http_get($durl)
		{
			// header传送格式
			$headers = array(
					"token:1111111111111",
					"over_time:22222222222",
			);
			// 初始化
			$curl = curl_init();
			// 设置url路径
			curl_setopt($curl, CURLOPT_URL, $durl);
			// 将 curl_exec()获取的信息以文件流的形式返回，而不是直接输出。
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true) ;
			// 在启用 CURLOPT_RETURNTRANSFER 时候将获取数据返回
			curl_setopt($curl, CURLOPT_BINARYTRANSFER, true) ;
			// 添加头信息
			curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
			// CURLINFO_HEADER_OUT选项可以拿到请求头信息
			curl_setopt($curl, CURLINFO_HEADER_OUT, true);
			// 不验证SSL
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
			// 执行
			$data = curl_exec($curl);
			// 打印请求头信息
//        echo curl_getinfo($curl, CURLINFO_HEADER_OUT);
			// 关闭连接
			curl_close($curl);
			// 返回数据
			return $data;
		}
		
		/**
		 * POST方式
		 */
		public function http_post($durl, $post_data)
		{
			// header传送格式
          $headers = array(
              "token:1111111111111",
              "over_time:22222222222",
          );
          //初始化
          $curl = curl_init();
          //设置抓取的url
          curl_setopt($curl, CURLOPT_URL, $durl);
          //设置头文件的信息作为数据流输出
          curl_setopt($curl, CURLOPT_HEADER, false);
          //设置获取的信息以文件流的形式返回，而不是直接输出。
          curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
          //设置post方式提交
          curl_setopt($curl, CURLOPT_POST, true);
          // 设置post请求参数
          curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
          // 添加头信息
          curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
          // CURLINFO_HEADER_OUT选项可以拿到请求头信息
          curl_setopt($curl, CURLINFO_HEADER_OUT, true);
          // 不验证SSL
          curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
          curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
          //执行命令
          $data = curl_exec($curl);
          // 打印请求头信息
  //        echo curl_getinfo($curl, CURLINFO_HEADER_OUT);
          //关闭URL请求
          curl_close($curl);
          //显示获得的数据
          return $data;
		}
		
		/**
		 * POST方式
		 */
		public function http_post_header($durl, $post_data)
		{
			// header传送格式
          $headers = array(
              "Content-Type: application/json",
							"Host: carapptest.gtmc.com.cn",
							"Cache-Control: no-cache",
          );
          //初始化
          $curl = curl_init();
          //设置抓取的url
          curl_setopt($curl, CURLOPT_URL, $durl);
          //设置头文件的信息作为数据流输出
          curl_setopt($curl, CURLOPT_HEADER, false);
          //设置获取的信息以文件流的形式返回，而不是直接输出。
          curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
          //设置post方式提交
          curl_setopt($curl, CURLOPT_POST, true);
          // 设置post请求参数
          curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
          // 添加头信息
          curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
          // CURLINFO_HEADER_OUT选项可以拿到请求头信息
          curl_setopt($curl, CURLINFO_HEADER_OUT, true);
          // 不验证SSL
          curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
          curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
          //执行命令
          $data = curl_exec($curl);
          // 打印请求头信息
  //        echo curl_getinfo($curl, CURLINFO_HEADER_OUT);
          //关闭URL请求
          curl_close($curl);
          //显示获得的数据
          return $data;
		}

		/**
		 * 用户中奖
		 */
		public function userprize()
		{
			$data = $this->param;
			
			$vin = $data['vin'];
					
			$where['lv_vin'] = $vin;
			
			$res = $this->logicUserinfo->getUserInfo($where);

			if($res['prize'] == 0) 
			{
				$pid = $this->logicUserinfo->prize($vin);
				
			}else{
				
				$pid = $res['prize'];
			}

	 //        
	 //        	$re = [];
			// 
			// if($pid == 1)
			// {
			// 	$re = $this->logicUserinfo->getUserList(['prize' => 1]);
			// }
			// if($pid == 2)
			// {
			// 	$re = $this->logicUserinfo->getUserList(['prize' => 2]);
			// }
			// if($pid == 3)
			// {
			// 	$re = $this->logicUserinfo->getUserList(['prize' => 3]);
			// }
			
			// 启动事务
			Db::startTrans();
			try{
				
				// if(empty($re))
				// {
					$a = Db::name('userinfo')->where('lv_vin', $vin)->update(['prize'=> $pid]);
					$b = Db::name('prize')->where('id', $pid)->setInc('ready_num');
					$c = Db::name('prize')->where('id', $pid)->setDec('surplus_num');
					$d = Db::name('userinfo')->where('lv_vin', $vin)->find();
				// }
				
				if(\in_array($pid, [1, 2, 3, 7]))
				{
					$type = 1; // 实物奖励
				}
				if(\in_array($pid, [4, 5, 6, 8]))
				{
					$type = 2; // 虚物奖励
				}

				// 提交事务
				Db::commit();
								return $this->apiReturn(['pid'=>$pid, 'type'=>$type]);
								// return $this->apiReturn(['pid'=>1, 'type'=>1]);
			} catch (\Exception $e) {
					// 回滚事务
					Db::rollback();
			}
			
		}
}