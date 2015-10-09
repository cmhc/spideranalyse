<?php

/**
 * 蜘蛛日志分析功能
 */
class spiderAnalyse{

	/**
	 * 初始化菜单
	 * 加载脚本
	 */
	public function __construct(){

		add_action('admin_menu', array(&$this,'addMenu') );
		add_action('admin_enqueue_scripts', array(&$this,'loadscript') );

	}

	/**
	 * 添加菜单
	 */
	public function addMenu(){
		add_menu_page('spideranalyse', '蜘蛛日志分析', 'manage_options', 'spideranalyse', array(&$this,'spideranalysePage') );
	}


	/**
	 * 获取蜘蛛的抓取的数量
	 * @param  $date 日志日期，月份 2015-09
	 *
	 * 组合之后的数据为一个大数组，键为蜘蛛名称，值为数量和日期
	 */
	public function getcount($date){
		$loginfo = array();
		$log = SA_DIR.'/log/spiderlog-'.$date.'.php';
		
		if( !file_exists($log) ){
			echo '<h1 style="text-align:center;color:#ccc;margin-top:50px;">未生成蜘蛛文件，等待蜘蛛爬行</h1>';
			return False;
		}

		$fp = fopen($log,'r');
		$i = 0;

		while( !feof($fp) ){
			$line = fgets($fp);
			$i+=1;
			if( $i < 2 ) continue;
			if( empty($line) ) continue;
			$data = json_decode($line,true);
			//print_r($data);
			//转化为y-m-d格式的日期，丢弃时间
			$date = date("Y-m-d",strtotime($data['time']));
			!isset($loginfo[$data['robot']][$date]) && $loginfo[$data['robot']][$date] = 0;
			$loginfo[$data['robot']][$date] += 1;
		}

		fclose($fp);
		//print_r($loginfo);
		return $loginfo;
	}

	/**
	 * 加载脚本
	 * @return none
	 */
	public function loadscript($hook){
		if( $hook != 'toplevel_page_spideranalyse' ) return;
		wp_enqueue_script('echart', SA_URL.'/analyse/js/echarts.js');
	}

	/**
	 * 日志分析菜单所展示页面
	 * @return none
	 */
	public function spideranalysePage(){
		$default_date = !empty($_POST['date']) ? $_POST['date'] : date("Y-m");
		if( !preg_match("/^\d{4}\-\d{2}$/", $default_date) ){
			echo '<h1 style="text-align:center;color:#ccc;margin-top:50px;">日期格式出错，正确格式2015-01</h1>';
			die();
		}
		
		if( ($loginfo = $this->getcount($default_date)) ){
			include( SA_DIR.'/analyse/index.php' );
		}

	}

}

new spiderAnalyse();