<?php
/**
 * 展示图表的文件
 * 已经定义的变量
 * $default_date
 */

for($i=1;$i<32;$i++){
    if( $i<10 ){
        $i = '0'.$i;
    }
    $date[] = $default_date.'-'.$i;
}

$series = array();

foreach( $loginfo as $spider=>$content ){
    $count = array();
    foreach($date as $d){
        isset( $content[$d] ) ? $count[] = $content[$d] : $count[] = 0;
    }
    $series[] = array(
        "type"=>"line",
        "smooth"=>true,
        "name"=>$spider,
        "data"=>$count
    );
}

?>
    <h2>查询以往月份</h2>
    <form action="" method="post">
        <input type="text" name="date" value="<?php echo isset($_GET['date']) ? $_GET['date'] : date("Y-m")?>">
        <input type="submit" value="查询" class="button button-primary">
    </form>
    <!--Step:1 Prepare a dom for ECharts which (must) has size (width & hight)-->
    <!--Step:1 为ECharts准备一个具备大小（宽高）的Dom-->
    <h2>蜘蛛爬行趋势</h2>
    <div id="main" style="height:500px;border:1px solid #ccc;padding:10px;"></div>
    <!--Step:2 Import echarts.js-->
    <!--Step:2 引入echarts.js wp_enqueue_script-->
    <script type="text/javascript">
    //conifg ECharts's path, link to echarts.js from current page.
    //模块加载器配置echarts的路径，从当前页面链接到echarts.js，定义所需图表路径
    require.config({
        paths: {
            echarts: '<?php echo SA_URL;?>/analyse/js'
        }
    });
    
    // Step:4 require echarts and use it in the callback.
    // Step:4 动态加载echarts然后在回调函数中开始使用，注意保持按需加载结构定义图表路径
    require(
        [
            'echarts',
            'echarts/chart/bar',
            'echarts/chart/line',
            'echarts/chart/map',
            'echarts/chart/pie'
        ],
        function (ec) {
            //--- 折柱 ---
            var myChart = ec.init(document.getElementById('main'));

            //根据蜘蛛的种类展示抓取的状态
            myChart.setOption({
                tooltip : {
                    trigger: 'axis'
                },
                legend: {
                    data:<?php echo json_encode( array_keys($loginfo) ); ?>
                },
                toolbox: {
                    show : true,
                    feature : {
                        mark : {show: true},
                        dataView : {show: true, readOnly: false},
                        magicType : {
                            show: true,
                            type:  ['line', 'bar', 'stack', 'tiled']
                        },
                        restore : {show: true},
                        saveAsImage : {show: true}
                    }
                },
                calculable : true,
                xAxis : [
                    {
                        type : 'category',
                        data : <?php echo json_encode($date);?>
                    }
                ],
                yAxis : [
                    {
                        type : 'value',
                        splitArea : {show : true}
                    }
                ],
                series : <?php echo json_encode($series);?>
            });


            
        }
    );
    </script>