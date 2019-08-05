<?php
require_once './public/index.php';
ini_set('date.timezone','Asia/Shanghai');
$loader = new Twig_Loader_Filesystem('./');
$twig = new Twig_Environment($loader, array(
    'cache' => false,
    'autoescape'=>'html',
));

$data= file_get_contents("douyin.log");
$data = json_decode($data,true);
//var_dump($data,true);
//file_put_contents("douyin.log", $aa);

echo $twig->render('boss.twig', array('data' => $data));