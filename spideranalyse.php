<?php
/*
	Plugin Name: spideranalyse
	Plugin URI: http://imhuchao.com/tag/spideranalyse
	Description: record and analyse spider log, include google,baidu,360... 记录并分析蜘蛛日志，包含的蜘蛛有谷歌，百度，360等等
	Version: 0.0.1
	Author: huchao
	Author URI: http://imhuchao.com
	License: GPLv2 or later
*/

//增加蜘蛛日志记录
define( 'SA_URL', plugin_dir_url( __FILE__ ) );
define( 'SA_DIR', plugin_dir_path( __FILE__ ) );
require(SA_DIR.'/class/spiderLog.class.php');
require(SA_DIR.'/class/spiderAnalyse.class.php');