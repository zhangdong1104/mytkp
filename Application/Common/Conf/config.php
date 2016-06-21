<?php
//应用配置  全局配置
return array(
	//'配置项'=>'配置值'
	//'SESSION)AUTO_START'=>false,
	//修改默认的模板目录结构为 控制器名称
//     'TMPL_FILE_DEPR'=>'_',
	//数据库PDO配置
// 	"DSN"=>"mysql:host=localhost;dbname=php_xm",
//     "DBUSER"=>"root",
//     "DBPASS"=>"123456",
//     "DBPORT"=>"3306",
    //设置url模式为rewrite模式
//     "URL_MODEL"=>2,
//     "PDOOPTIONS"=>ARRAY(
//        \PDO::ATTR_ERRMODE=>\PDO::ERRMODE_EXCEPTION 
//        ),
    //分页查询相关配置
    "PAGENO"=>1,
    "PAGESIZE"=>10,
    
    //开启路由，全局路由
    'URL_ROUTER_ON'=>TRUE,
    'URL_ROUTE_RULES'=>ARRAY(
        'ttt/:uid/:name'=>"Home/index/index",//静态的规则路由
        
        'login'=>"Home/User/login"
    ),
    'URL_MAP_RULES'=>array(
        'ttt' =>"Home/index/index",
        'login'=>"Home/User/login",
        'aaa'=>array("http://www.dododa.cn",302)
    ),
    'DB_DSN' => "mysql://root:123456@localhost:3306/tb_u#utf8",
    
    //     "DB_ARRAY"=>ARRAY(
        //         'db_type' =>'mysql'
        //         )
    
        'DB_TYPE'               =>  'mysql',     // 数据库类型
        'DB_HOST'               =>  'localhost', // 服务器地址
        'DB_NAME'               =>  'php_xm',          // 数据库名
        'DB_USER'               =>  'root',      // 用户名
        'DB_PWD'                =>  '123456',          // 密码
        'DB_PORT'               =>  '3306',        // 端口
//         'DB_PREFIX'             =>  '',    // 数据库表前缀
        'DB_PARAMS'             =>array(
            \PDO::ATTR_ERRMODE=>\PDO::ERRMODE_EXCEPTION
        ),
    
);





?>