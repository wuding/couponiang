<?php
namespace app\module\item\controller;

use app\model\AlimamaChoiceExcel;

class Index extends \Astro\Controller
{
	
	# public $exit = 'so'; //执行后退出
	public $destruct = 2;# 
	public $disableView = true;
	
	public function __construct($action = '', $method = '', $vars = [])
	{
		# $vars['custom'] = 'value';
		parent::__construct($action, $method, $vars);
		# print_r([__METHOD__, __LINE__, __FILE__]); 
	}
	
	/* 缺省动作 */
	public function _action()
	{
		print_r([__METHOD__, __LINE__, __FILE__]); # 
	}
	
	/* HTTP方法动作 */
	public function _get__action()
	{
		print_r([__METHOD__, __LINE__, __FILE__]); # 
	}
	
	/* HTTP映射动作 */
	public function _get_()
	{
		print_r([__METHOD__, __LINE__, __FILE__]); # 
	}
	
	public function index()
	{
		global $PHP;
		$Excel = new AlimamaChoiceExcel;
		
		$uri = $PHP->requestUri;
		$arr = explode('/', $uri);
		$id = $arr[2];
		
		# $where = ['list_id' => $id];
		$row = $Excel->find($id, ['taobaoke', 'promotion', 'group']);
		$url = '/notfound';
		if ($row) {
			$url = $row->group ? $row->taobaoke : $row->promotion;
			/*
				检测优惠券状态
				$query = parse_url($item->link, PHP_URL_QUERY);
				parse_str($query, $STR);
				$data = [
					'e' => $STR['e'],
					'pid' => $STR['pid'],
				];
				$json = json_encode($data);
				$data = urlencode($json);
				$t = $this->getMillisecond();
				$s = "7cff1f56233e0cd140f7b04f9db6fdd2&$t&12574478&$json";
				$sign = md5($s);
				$u = "https://acs.m.taobao.com/h5/mtop.alimama.union.hsf.coupon.get/1.0/?jsv=2.4.0&appKey=12574478&t=$t&sign=$sign&api=mtop.alimama.union.hsf.coupon.get&v=1.0&AntiCreep=true&AntiFlood=true&type=json&dataType=jsonp&callback=mtopjsonp1&data=$data";
				$http_header = ['X-HTTP-Method-Override: GET'];
				$http_header[] = 'Cookie: miid=650096401599522383; thw=cn; ctoken=f5EC22xW3TW6afSAgz0orhllor; linezing_session=HqXbEI4bMBlyTkfx8lKqI6D1_15300818506014Rh5_3; l=AmNjVb9HEdtwAj9iSbB0x50rc60NV/ea; tkmb=e=tbaY6rsafyZZtj5oaVakX4kRZkzVkD88qIJTGAqZwEW6D55PZXfJQPHI_ndYw2bUEnhaIUvse2UYUArK2G1gopEDqAH21s8JofIf1ZOnPVIJy48kueKZfjW9frDQf84k764lNHEcp-ujUc4CozaSKGbMf5jtpNPpLcHZmmM4-HNmyzTds8SNBhf3f6tUB60xjGu14-4jmPGgY1ndzJdnCnCJibQqM6B0dn7DR0xxXxMebPfUai-sGXtTSMjrFOgjk6l1RWAJS50db6j1uGDJWDhac0zv795E1m1UR_MnIOTGDmntuH4VtA&iv=0&et=1530277113&tk_cps_param=33543472&tkFlag=0; hng=CN%7Czh-CN%7CCNY%7C156; v=0; ockeqeudmj=uBQxopM%3D; WAPFDFDTGFG=%2B4cMKKP%2B8PI%2BOEZgdyxi%2FK0PQlJPjkE%3D; _w_app_lg=0; t=403425f4339c4f43a4885ed2c436e7af; skt=917efba1c2071af9; cookie2=1c7bcff18163151c69335cc96aa9189b; publishItemObj=Ng%3D%3D; csg=0839d12c; uc3=vt3=F8dBzrmTdgHPtzCaQSI%3D&id2=VyyY5Y4mje4%3D&nk2=DeJER0v%2FpQ%3D%3D&lg2=WqG3DMC9VAQiUQ%3D%3D; existShop=MTUzMzExNzE0Nw%3D%3D; tracknick=netjoin; lgc=netjoin; _cc_=U%2BGCWk%2F7og%3D%3D; dnk=netjoin; tg=0; whl=-1%260%261520687785115%261533122697791; _tb_token_=cd519c3eb7170; UM_distinctid=16510b1c6de184-0935bfab6f5a9b-4e47052e-140000-16510b1c6e9209; x=e%3D1%26p%3D*%26s%3D0%26c%3D0%26f%3D0%26g%3D0%26t%3D0%26__ll%3D-1%26_ato%3D0; uc1=cookie16=Vq8l%2BKCLySLZMFWHxqs8fwqnEw%3D%3D&cookie21=VT5L2FSpdeCsOSyjpv%2FIyw%3D%3D&cookie15=Vq8l%2BKCLz3%2F65A%3D%3D&existShop=false&pas=0&cookie14=UoTfKLACoYD9jA%3D%3D&cart_m=0&tag=10&lng=zh_CN; _m_h5_tk=7cff1f56233e0cd140f7b04f9db6fdd2_1533765070342; _m_h5_tk_enc=1754f48f069ea1943e1c6d503972e6e9; cna=971tEXyscUICAXB7lnewRL9z; enc=tQ%2BlPcbb1DMjF9DUQqjQGEQ%2ByS5PjcDWvzYJv5iai5fDp1p3wm5pP9WamQbfc1tR7QRwOnvolzuIcL%2FFxf1LtQ%3D%3D; isg=BIuL3st8VDldfogzXjMWfgtNGi-1iPumjSivFv2IZ0ohHKt-hfAv8imd8hzyPPea';
		
				$content = $this->curl_download($u, $http_header);
				if (!$content) {
					$url = $item->url;
				} else {
					$obj = json_decode($content);
					if ($obj->data->result->retStatus) {
						$url = $item->url;
					}
				}
				*/
			
		}
		header('Location: ' . $url);exit;
		print_r([$url, __METHOD__, __LINE__, __FILE__]); # 
	}
	
	private function getMillisecond()
	{
		list($t1, $t2) = explode(' ', microtime());
		return (float) sprintf('%.0f', (floatval($t1) + floatval($t2)) * 1000);
	}
	
	public function curl_download($url, $http_header = null)
	{
		$ch = curl_init();//初始化一个cURL会话
		curl_setopt($ch,CURLOPT_URL,$url);//抓取url
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);//是否显示头信息
		# curl_setopt($ch,CURLOPT_SSLVERSION,3);//传递一个包含SSL版本的长参数
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		if ($http_header) {
			curl_setopt($ch, CURLOPT_HTTPHEADER, $http_header);
		}
		$data = curl_exec($ch);// 执行一个cURL会话
		$error = curl_error($ch);//返回一条最近一次cURL操作明确的文本的错误信息。
		curl_close($ch);//关闭一个cURL会话并且释放所有资源

		#echo $data;exit;
		/*
		$destination = './1.xls';
		$file = fopen($destination,"w+");
		fputs($file,$data);//写入文件
		fclose($file);
		*/
		
		return $data;
	}
}
