<?php
include 'config.php';
require_once './public/index.php';
ini_set('date.timezone','Asia/Shanghai');
$loader = new Twig_Loader_Filesystem('./');
$twig = new Twig_Environment($loader, array(
    'cache' => false,
    'autoescape'=>'html',
));
$title = ['douyin'=>'抖音','weibo'=>'微博','toutiao'=>'头条','douban'=>'豆瓣'];
$type = isset($_GET['type']) ? $_GET['type'] : "douyin";
$resource = $type.".log";
if (!isset($title[$type])) {
	echo "没有这个热搜分类";
	exit();
}
$arrContextOptions=array(
    "ssl"=>array(
        "verify_peer"=>false,
        "verify_peer_name"=>false,
    ),
);
$arrContextOptions = stream_context_create($arrContextOptions);
$time = time()-1800;
$data= @file_get_contents($resource,false,$arrContextOptions);
$filetime = @filemtime($resource);
if ($time>$filetime) {
	$data = false;
}
if ((isset($_GET['update'])&&$_GET['update']==1)||!$data) {
	$data = file_get_contents(HOT_URL[$type],false,$arrContextOptions);
	file_put_contents($resource, $data);
}
$data = json_decode($data,true);
foreach ($data as $key => $val) {
	if ($key>29) {
		unset($data[$key]);
	}
}
//var_dump($data,true);
//file_put_contents("douyin.log", $aa);

echo $twig->render('boss.twig', array('data' => $data,'title'=> $title[$type]));