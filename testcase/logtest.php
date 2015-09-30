<?php
if( PHP_SAPI != 'cli' ) die();
//测试蜘蛛文件生成,百度spider测试
$baiduua = array(
	"http"=>array(
			"user_agent"=>"Mozilla/5.0 (compatible; Baiduspider/2.0; +http://www.baidu.com/search/spider.html)"
		)
	);
$context = stream_context_create($baiduua);
file_get_contents("http://localhost/wordpress",'',$context);
//读取日志文件，查看是否正常
$log = file_get_contents( '../log/spiderlog-'.date("Y-m").'.php' );
echo $log;
?>