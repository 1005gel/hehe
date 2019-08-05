<?php
require_once __DIR__.'/public/index.php';
include 'conn.php';
//global $conn;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7;
//echo 123;
// var_dump(openssl_get_cert_locations());
// 性别 select count(*),whos,gender from resend group by whos,gender;
//var_dump($ids);
//采集某页面所有的图片
// SELECT elt(INTERVAL(user_follow_count, 0, 50, 60, 70, 80, 90, 100), '<50', '50-60', '60-70', '70-80', '80-90', '90-100', '>=100') as score_level, count(`text`) as counts FROM resend where whos='央视新闻' GROUP BY elt(INTERVAL(user_follow_count, 0, 50, 60, 70, 80, 90, 100), '<50', '50-60', '60-70', '70-80', '80-90', '90-100', '>=100');
//https://m.weibo.cn/detail/4356344180389556
//4350151499683345
//SELECT elt(INTERVAL(user_followers_count, 0, 50, 60, 70, 80, 90, 100), '<50', '50-60', '60-70', '70-80', '80-90', '90-100', '>=100') as score_level, count(name) as counts FROM resend GROUP BY elt(INTERVAL(user_followers_count, 0, 50, 60, 70, 80, 90, 100), '<50', '50-60', '60-70', '70-80', '80-90', '90-100', '>=100');
$url = "https://m.weibo.cn/api/statuses/repostTimeline?id=4361403424508672&page=";
$page = 1;
function gowb($url,$page)
{

	global $conn;
	sleep(1);
	
	$client = new GuzzleHttp\Client();
	$togo = $url.$page;
	//$res = $client->request('GET', $togo);
	try {
		$res = $client->request('GET', $togo);
	    //$client->request('GET', 'https://github.com/_abc_123_404');
	} catch (ClientException $e) {
		//echo "123333";
		//
	    echo Psr7\str($e->getRequest());
	    echo Psr7\str($e->getResponse());
	    sleep(100);
	    return 0;
	}
	//var_dump($res);
	//$html = (string)$res->getBody();
	// try {
	//     //$res = $client->request('GET', $togo);
	//     $res = $client->get($togo);
	// 	$response = $res->send();
	    

	// 	$statuscode = $response->getStatusCode();
	// 	exit();
	// 	var_dump($statuscode);

	// } catch (Guzzle\Http\Exception\BadResponseException $e) {
	//     echo 'Uh oh! ' . $e->getMessage();
	// }
	$s= (string)$res->getBody();
	file_put_contents("cc.log", $s,FILE_APPEND);
	$s = json_decode($s,true);
	//var_dump($s);
	$date = date("Ymd H:i:s");
	file_put_contents("bb.log", $date."----".$togo."-----".$s['ok']."-----".$s['msg']."\r\n",FILE_APPEND);
	$idlog = 'ids.log';
	$ids = file_get_contents($idlog);
	$ids = explode(",", $ids);
	if ($s['ok']=='1') {
		if (isset($s['data']['data'])) {
			foreach ($s['data']['data'] as $k => $v) {
						$i = [];
						$i['source'] = $v['source'];
						$i['created_at'] = $v['created_at'];
						$i['user_avatar_hd'] = $v['user']['avatar_hd'];
						$i['text'] = mb_substr($v['text'], 0,200);
						if (in_array($v['id'], $ids)) {
							continue;
						}else{
							file_put_contents($idlog, ",".$v['id'],FILE_APPEND);
						}
						$i['wbid'] = $v['id'];
						$i['user_screen_name'] = $v['user']['screen_name'];
						$i['user_id'] = $v['user']['id'];
						$i['gender'] = ($v['user']['gender']=='m')?1:0;
						$i['user_followers_count'] = $v['user']['followers_count'];
						$i['user_follow_count'] = $v['user']['follow_count'];
						$i['user_verified_type'] = $v['user']['verified_type'];
						var_dump($v['user']['verified_type']);
						$i['user_close_blue_v'] = $v['user']['close_blue_v']?0:1;
						$i['whos'] = '央视新闻';
						$i['weiboid'] = '4361403424508672';
						var_dump($v['user']['verified_type']);
						$conn->insert('resend', $i);
				file_put_contents("aa.log", $i['user_screen_name']."\r\n",FILE_APPEND);
			}
		}
	}
}
// $s= file_get_contents("cc.log");
// 	$s = json_decode($s,true);
// 	foreach ($s['data']['data'] as $k => $v) {
// 		$i = [];
// 		$i['source'] = $v['source'];
// 		$i['created_at'] = $v['created_at'];
// 		$i['user_avatar_hd'] = $v['user']['avatar_hd'];
// 		$i['text'] = $v['text'];
// 		$i['wbid'] = $v['id'];
// 		$i['user_screen_name'] = $v['user']['screen_name'];
// 		$i['user_id'] = $v['user']['id'];
// 		$i['gender'] = ($v['user']['gender']=='m')?1:0;
// 		$i['user_followers_count'] = $v['user']['followers_count'];
// 		$i['user_follow_count'] = $v['user']['follow_count'];
// 		$i['user_verified_type'] = $v['user']['verified_type'];
// 		$i['user_close_blue_v'] = $v['user']['close_blue_v']?0:1;
// 		$i['whos'] = '蔡徐坤';
// 		$i['weiboid'] = '4350151499683345';
// 		$conn->insert('resend', $i);
// 	}
//gowb($url,1);
for ($i=1; $i <300 ; $i++) { 
	echo $i."---";
	gowb($url,$i);
}
//$data = QueryList::html($html)->find('h3')->texts();

//采集某页面所有的超链接和超链接文本内容
//可以先手动获取要采集的页面源码
// $html = file_get_contents('http://cms.querylist.cc/google/list_1.html');
// //然后可以把页面源码或者HTML片段传给QueryList
// $data = QueryList::html($html)->rules([  //设置采集规则
//     // 采集所有a标签的href属性
//     'link' => ['a','href'],
//     // 采集所有a标签的文本内容
//     'text' => ['a','text']
// ])->query()->getData();
// //打印结果
// print_r($data->all());
