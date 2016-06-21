<?php 
session_start();
?>
<!DOCTYPE html>
<html>
     <head>
          <title>管理...</title>
          <meta charset="utf-8"> 
          <link type="text/css" rel="stylesheet" href="Public/easyui/themes/bootstrap/easyui.css"/>
          <link type="text/css" rel="stylesheet" href="Public/easyui/themes/icon.css"/>	
          <script type="text/javascript" src="Public/easyui/jquery.min.js"></script>
          <script type="text/javascript" src="Public/easyui/jquery.easyui.min.js"></script>
          <script type="text/javascript" src="Public/easyui/locale/easyui-lang-zh_CN.js"></script>
		  <script type="text/javascript" >
          $(function(){
//                $("#tabs").tabs({
//                    fit:true; 
//                })
          });
          function addTabs(url,name){
        	 if($('#tabs').tabs("exists",name)){
            	 //如果当前选项卡已经存在   则选中它
            	 $('#tabs').tabs("select",name);
             }else{
               // 添加一个未选中的选项卡面板
        	   $('#tabs').tabs('add',{
               		title: name,
               		selected: true,
               		closable:true,
               		content:"<iframe name='"+name+"' src='"+url+"' width='100%' height='100%' frameborder='0'  scrolling='no' > </iframe> "
           	   }); 
             }         
          }
		  </script>
     </head>
     
    <body class="easyui-layout"> 
         
        <div data-options="region:'north',title:'栏目',split:true" style="height:100px; margin-left:1200px;">
			    <div style="margin-top:20px; font-size:16px; color:#03458f;">
			    
			       <?php 
			       if (array_key_exists("loginUser",$_SESSION)){
// 			           switch ($_SESSION['loginUser'][3]){
// 			               case 1: "管理员"  break ;
// 			           }
			         echo "欢迎你！".$_SESSION['loginUser'][4]."(".$_SESSION['loginUser'][2].")";
			         echo  "<a href='index.php' style='font-size:15px;margin-left:30px; color:red;'>退出登录</a>";
			       }
			       else{
                        echo "<a  href='index.php' >登录进入 </a>";
                        echo "<a  href='zc.php' style='margin-left:18px; color:red;'>立即注册</a>";
			       }
			       ?>
			       
			   </div>    
        </div> 
       
        <div data-options="region:'west',title:'菜单',split:true" style="width:200px;">
            <ul id="tree" class="easyui-tree"> 
             <?php 
//              echo "<li>";
//              echo "<span>{$menu->getName()}</span>";
//              echo "<ul>";
//              ?>
                <?php 
                if (array_key_exists("secondMenu", $_SESSION)){
                    $secondMenu = $_SESSION["secondMenu"];
                    foreach ($secondMenu as $menu2){
                        echo "<li><span>{$menu2[1]}</span><ul>";  
                        foreach ($menu2[5] as $menu3){
                            echo "<li><span><a href=\"Javascript:addTabs('{$menu3[2]}','{$menu3[1]}');\">{$menu3[1]}</a></span></li>";
                        }                        
                        echo "</ul>";
                        echo "</li>";
                    }
                }
//                 ?>
                <?php 
//                 echo "</ul></li>";
//                 ?>
                
            </ul> 
        </div>   
        <div data-options="region:'center'" style="padding:5px;background:#eee;">
            <div id="tabs" class="easyui-tabs" style="width:500px;height:250px;" data-options="fit:true">   
                <div title="欢迎你" style="padding:20px;" data-options="closable:true">   
                    hello  world!!!  
                </div>   
            </div> 
        </div>   
    </body>
</html>