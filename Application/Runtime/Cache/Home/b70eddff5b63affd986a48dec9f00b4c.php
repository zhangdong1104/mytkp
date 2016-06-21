<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
	<head>
		<title>菜单管理界面</title>
		<meta charset="utf-8">
		<link type="text/css" rel="stylesheet" href="http://localhost:8081/mytkp/Public/easyui/themes/bootstrap/easyui.css"/>
		<link type="text/css" rel="stylesheet" href="http://localhost:8081/mytkp/Public/easyui/themes/icon.css" />
		<script type="text/javascript" src="http://localhost:8081/mytkp/Public/easyui/jquery.min.js"></script>
		<script type="text/javascript" src="http://localhost:8081/mytkp/Public/easyui/jquery.easyui.min.js"></script>
		<script type="text/javascript" src="http://localhost:8081/mytkp/Public/easyui/locale/easyui-lang-zh_CN.js"></script>
		<style type="text/css">
        #formtable{width:60%;margin:auto;margin-top:20px;}
        #formtable tr{height:40px;}
        .in{width:150px;}
		.in2{width:155px;}
		.td1{width:65px;}
        </style>
		<script type="text/javascript">
		$(function(){
			$('#win').window('close');  // close a window 
			$('#dg').datagrid({
			    striped:true,
			    method: "GET",
			    url:'http://localhost:8081/mytkp/index.php/Home/Class/loadClassByPage?pageNo=1&pageSize=10',
			    pagination:true,
			    rownumbers:true,
			    frozenColumns:[[
			        {field:'aaa',checkbox:true}
			    ]],
			    columns:[[
			        {field:'cid',hidden:true},
			        {field:'name',title:'名称',width:60,align:'center'},
			        {field:'classtype',title:'类型',width:70,align:'center',
			        	formatter: function($classtype){
            	            if($classtype == 1){
                	            return "常规班";
                	        }else if($classtype == 2){
                	        	return "快速班";		
                    	    }else if($classtype == 3){
                    	    	return "flash班";
                        	}else{
                        		return "php班";
                            }
    	            	}	
			        },
			        {field:'status',title:'状态',width:140,align:'center',
    	            	formatter: function($status){
            	            if($status == 1){
                	            return "正常";
                	        }else if($status == 2){
                	        	return "休假";		
                    	    }else if($status == 3){
                    	    	return "被合并";
                        	}else{
                        		return "结业";
                            }
    	            	}
        	        },

			        {field:'createtime',title:'创建时间',width:150,align:'center'},
			        {field:'begintime',title:'开班时间',width:150,align:'center'},
			        {field:'endtime',title:'结业时间',width:150,align:'center'},
			        {field:'headername',title:'班主任',width:100,align:'center'},
			        {field:'managername',title:'项目经理',width:100,align:'center'},
			        {field:'stucount',title:'人数',width:50,align:'center'},
			        {field:'remark',title:'备注',width:200,align:'center'}
			    ]],
			    toolbar: '#tb'
			});

			//设置翻页功能
			var pager = $("#dg").datagrid("getPager");
			pager.pagination({
				onSelectPage:function(pageNumber, pageSize){
					refreshData(pageNumber,pageSize);
				}
			});
		});
		function saveOrUpdateMenu(){
			var menuid = $("#menuid").val();
			var name = $("#name").val();
			var url = $("#url").val();
			var parentid = $('#parentid').combo('getValue');
			var isshow = $("#isshow").combo("getValue");
			$.post("http://localhost:8081/mytkp/index.php/Home/Menu/saveOrUpdateMenu",{
				"menuid"	: menuid,
				"name"		: name,
				"url" 		: url,
				"parentid"  : parentid,
				"isshow"	: isshow
			},function(data){
				if(data == "insertok"){
					$.messager.alert('消息','菜单添加成功！','info',function(){
						refreshData(1,10);
						$('#win').window('close');  // close a window
					});
				}else if(data == "updateok"){
					$.messager.alert('消息','菜单修改成功！','info',function(){
						refreshData(1,10);
						$('#win').window('close');  // close a window
					});
				}
			},"text");
		}


		//刷新表格数据
		function refreshData(pageNumber,pageSize){
			$("#dg").datagrid('loading');
			$.getJSON("http://localhost:8081/mytkp/index.php/Home/Class/loadClassByPage?pageNo="+pageNumber+"&pageSize="+pageSize,{},function(result){
				$("#dg").datagrid('loadData',{
					rows: result.rows,
					total: result.total
				});
				var pager = $("#dg").datagrid("getPager");
				pager.pagination({
					pageSize:pageSize,
					pageNumber:pageNumber
				});
				$("#dg").datagrid('loaded');
			});
		}
		
		//搜索班级
		function searchClass(){
			$.post("http://localhost:8081/mytkp/index.php/Home/Class/loadClassByPage",{
				'pageNo'	 :1,
				'pageSize'	 :10,
				'className'  :$("#search-className").val(),
				'createtime1':$("#search-createtime1").combo("getValue"),
				'createtime2':$("#search-createtime2").combo("getValue"),
				'headerName' :$("#search-headerName").val(),
				'begintime1' :$("#search-begintime1").combo("getValue"),
				'begintime2' :$("#search-begintime2").combo("getValue"),
				'managerName':$("#search-managerName").val(),
				'endtime1'   :$("#search-endtime1").combo("getValue"),
				'endtime2'   :$("#search-endtime2").combo("getValue"),
				'status'     :$("#search-status").combo("getValue")
			},function(result){
				$("#dg").datagrid('loadData',{
					rows: result.rows,
					total: result.total
				});
			},"json");
		}
		
		//班级合并
		/*
		至少选两个班级合并 
		状态必须正常
		所选班合并天不能有考试
		*/
		function combineClass(){
			var selectedRows = $("#dg").datagrid("getSelections");
			if(selectedRows.length < 2){
				alert("至少选中两个班级合并！");
				return;
			}
			
			var b =true;
			for(var i=0; i<selectedRows.length;i++){
				if(selectedRows[i].status !=1){                       
					b = false;
					break;
				}
			}
			if(!b){
				alert("所选班级状态必须正常");
				return;
			}
			//获取已选中的班级id
			var cids = new Array();
			var options = new Array();
			options.push({"name":"请指定","cid":"-1"});
			for(var i=0; i<selectedRows.length;i++){
				cids.push(selectedRows[i].cid);
			    options.push({"name":selectedRows[i].name,"cid":selectedRows[i].cid});
			}
			$.post("http://localhost:8081/mytkp/index.php/Home/Class/chechExamToday",{'cids':cids.join(",")},function(data){
				if(data == 1){
					//动态填入合并后班级保存选项 根据已选数据
					$('#combinedClassid').combobox({
						valueField:'cid',
						textField:'name',
						data:options,
						value:'-1'
					});
					//ajax载入
					$('#combinedHeaderid').combobox({    
						    url:'http://localhost:8081/mytkp/index.php/Home/User/loadAllHeader',    
						    valueField:'uid',    
						    textField:'truename',
						    value:'-1'
						});
					//ajax载入
					$('#combinedManagerid').combobox({    
						    url:'http://localhost:8081/mytkp/index.php/Home/User/loadAllManager',    
						    valueField:'uid',    
						    textField:'truename',
						    value:'-1'
						});
					//打开合并界面
					$('#win').window('open');
				}else{
					
				}
			},"text");			
		}
		
		function hebingClasses(){
			//获取已选中班级的id
			var cids = new Array();
			var selectedRows = $("#dg").datagrid("getSelections");
			for(var i=0; i<selectedRows.length;i++){
				cids.push(selectedRows[i].cid);
			}
			$.post("http://localhost:8081/mytkp/index.php/Home/Class/hebingclasses",{
				"cids"				:cids.join(","),
				"combinedClassid"	:$("#combinedClassid").combo("getValue"),
				"combinedHeaderid"  :$("#combinedHeaderid").combo("getValue"),
				"combinedManagerid" :$("#combinedManagerid").combo("getValue")
			},function(result){
				$('#win').window('close'); //关闭窗口
				alert("班级合并成功");
				$("#dg").datagrid('loadData',{
					rows: result.rows,
					total: result.total
				});
			},"json");
		}
		
		</script>
	</head>
	<body>
		<table id="dg"></table>
		<div id="tb">
			<form action="" id="searchForm">
				<table>
					<tr>
						<td class="td1">
							<label>班级名称：</label>
						</td>
						<td>
							<input type="text" class="easyui-validatebox in" placeholder="班级名称模糊查询" id="search-className">
						</td>
						<td colspan="2">
							<label>创建时间：</label>
							<input type="text" class="easyui-datebox in" id="search-createtime1" data-options="editable:false">
							<label>至</label>
							<input type="text" class="easyui-datebox in" id="search-createtime2" data-options="editable:false">
						</td>
					</tr>
					<tr>
						<td class="td1">
							<label>班&nbsp;&nbsp;主&nbsp;任：</label>
						</td>
						<td>
							<input type="text" class="easyui-validatebox in" placeholder="班主任名称模糊查询" id="search-headerName">
						</td>
						<td colspan="2">
							<label>开班时间：</label>
							<input type="text" class="easyui-datebox in" id="search-begintime1" data-options="editable:false">
							<label>至</label>
							<input type="text" class="easyui-datebox in" id="search-begintime2" data-options="editable:false">
						</td>
					</tr>
					<tr>
						<td class="td1">
							<label>项目经理：</label>
						</td>
						<td>
							<input type="text" class="easyui-validatebox in" placeholder="项目经理名称模糊查询" id="search-managerName">
						</td>
						<td colspan="2">
							<label>结业时间：</label>
							<input type="text" class="easyui-datebox in" id="search-endtime1" data-options="editable:false">
							<label>至</label>
							<input type="text" class="easyui-datebox in" id="search-endtime2" data-options="editable:false">
						</td>
					</tr>
					<tr>
						<td class="td1">
							<label>状&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;态：</label>
						</td>
						<td>
							<select class="easyui-combobox in2" id="search-status">
								<option value="-1">状态搜索</option>
								<option value="1">正常</option>
								<option value="2">合并</option>
								<option value="3">结业</option>
								<option value="4">废除</option>
							</select>
						</td>
						<td>
							<a href="javascript:searchClass();" class="easyui-linkbutton" data-options="iconCls:'icon-search',plain:true">搜索</a>
						 	<a href="javascript:combineClass();" class="easyui-linkbutton" data-options="iconCls:'icon-book',plain:true">合并</a>
						 
						</td>
					</tr>
				</table>
			</form>
		</div>
		<div id="win" class="easyui-window" title="合并班级" style="width:500px;height:260px;"   
                data-options="iconCls:'icon-add2',modal:true,collapsible:false,minimizable:false,maximizable:false,resizable:false">   
            <form id="ff" method="post">
            	<!-- <input type="hidden" id="menuid"/>  -->
            	<table id="formtable">
            		
            		<tr>
            			<td align="right"><label for="combinedClassid">合并后班级名称：</label></td>
            			<td>
            				<select id="combinedClassid" style="width:150px;" class="easyui-combobox" ></select>
            			</td>
            		</tr>
            		<tr>
            			<td align="right"><label for="combinedHeaderid">合并后班主任名称：</label></td>
            			<td>
            				<select id="combinedHeaderid" style="width:150px;" class="easyui-combobox" ></select>
            			</td>
            		</tr>
            		<tr>
            			<td align="right"><label for="combinedManagerid">合并后经理名称：</label></td>
            			<td>
            				<select id="combinedManagerid" style="width:150px;" class="easyui-combobox" ></select>
            			</td>
            		</tr>
            		
            		<tr>
            			<td align="center" colspan="2">
            				<a id="btn" href="javascript:hebingClasses();" class="easyui-linkbutton" data-options="iconCls:'icon-submit'">合并班级</a> 
            			</td>
            		</tr>
            	</table>   
            </form>  
        </div> 
	</body>
</html>