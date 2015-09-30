<?php
/**
 * 蜘蛛日志记录程序
 * 支持记录的蜘蛛有：
 * 百度
 * 360
 * 谷歌
 * msn
 * 搜狗
 * 搜搜
 * 有道
 * 神马
 * @author  huchao <hu_chao@139.com>
 * @date 2015-09-27
 */

class spiderLog
{

	/**
	 * 蜘蛛规则
	 * @var array
	 */
	public $rules;

	/**
	 * ua
	 * @var tring
	 */
	public $userAgent;

	/**
	 * 自身实例
	 * @var object
	 */
	private static $spiderLog;


	private function __construct(){
		date_default_timezone_set('Asia/Shanghai');
		$this->userAgent = strtolower($_SERVER['HTTP_USER_AGENT']);
		$this->rules = array(
	  		'baiduspider'=>'Baidu',
	  		'baiduspider-mobile'=>'Baidu-Mobile',
	  		'360spider'=>'360',      	
	  		'googlebot'=>'Google',
	  		'msnbot'=>'Msn',
	  		'sogou'=>'Sogou',
	  		'soso'=>'Soso',
	  		'bingbot'=>'bingbot',
	  		'yisouspider'=>'sm',
	  		"yahoo! slurp"=>"Yahoo",
	  		'youdaobot'=>'Youdao'
		);
	}


	/**
	 * 获取蜘蛛名称
	 * @return sting 蜘蛛名称
	 */
	public function getSpiderName(){
		foreach($this->rules as $match_rule=>$display){
	  		if (strpos($this->userAgent, $match_rule) !== false)
	    		return $display;
		}
		return false;
	}


	/**
	 * 记录日志
	 * @param  string $path 日志路径
	 * @return none
	 */
	public static function record($path){

		if( self::$spiderLog instanceof self ) return false;

		self::$spiderLog = new spiderLog();

		if($spider = self::$spiderLog->getSpiderName()){

			$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'NoReferer';
			$robot_ip = $_SERVER['REMOTE_ADDR'];
			$page_url = ( isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://' ) . $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];

			$content = array(
				'robot'		=> $spider,
				'time'		=> date("Y-m-d H:i:s"),
				'spiderIp'	=> $robot_ip,
				'url'		=> $page_url,
				'referer'	=> $referer
				);

			$json_content = json_encode($content);

			//创建文件夹
			if( !file_exists( dirname($path) ) )
				mkdir( dirname($path) );

			//创建一个安全的文件
			if( !file_exists( $path ) )
				file_put_contents($path, "<?php die();?>\n");
			
			file_put_contents($path, $json_content."\n", FILE_APPEND);

		}
	}
	
}

$logname = 'spiderlog-'.date("Y-m").'.php';
spiderLog::record(SA_DIR.'/log/'.$logname);
