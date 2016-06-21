<?php
namespace Home\Controller;
use Think\Controller;
use Home\Model\MenuModel;
use Think\Model;
class MenuController extends Controller {
    
    private $menuModel;
    
    public function __construct(){
        parent::__construct();
        $this->menuModel= M("menu");
//         $this->menuModel = new MenuModel();
    }
    
    public function menuManage(){
//         $this->assign("root",ROOT);
        $this->display();
    }
    
    
    
    /**
     * 分页查询菜单列表
     * @param number $pageNo  参数绑定
     * @param number $pageSize参数绑定
     */
    public function loadMenuByPage($pageNo=1, $pageSize=10,$name=null,$url=null,$parentid=null){
        $queryConditions =array();
        if (null !=$name){
            $queryConditions["m.name"]=" like %$name%";
        }
        if (null !=$url){
            $queryConditions["m.url"]=" like '%$url%'";
        }
        if ($parentid>0){
            $queryConditions["m.parentid"]="$parentid";
        }
//         $queryConditions["m.name"]="班级管理";
        $count = $this->menuModel->field(" count(*) as mc ")->table("menu m")->where($queryConditions)->find()["mc"];
        $page["total"]=$count;
        
        $begin =($pageNo-1)*$pageSize;
        $rows= $this->menuModel->field("m.menuid,m.name,m.url,m.parentid,m.isshow")
        ->table("menu m")->where($queryConditions)
        ->limit($begin,$pageSize)->select();
//         print_r($rows);
//         exit();
        $page["rows"] =$rows;
//         print_r($page);
//         exit();
        $this->ajaxReturn($page);
    }
    public function loadClassByPage($pageNo=1,$pageSize=10,$className=null,
        $createtime1=null,$createtime2=null,$headerName=null,
        $begintime1=null,$begintime2=null,$manageName=null,$endtime1=null,$endtime2=null,$status=-1){
            $sql = " from class c,tb_user u1,tb_user u2 where c.headerid=u1.uid and c.manageid=u2.uid ";
            //         $queryConditions =array("c.headerid"=>"u1.uid","c.manageid"=>"u2.uid ");
    
            //第一行
            if (null != $className){
                //             $queryConditions["c.name"]="like '%$className%'";
                $sql .=" and c.name like '%$headerName%'";
                //             $this->classModel->where("name like '%$className%'");
            }
            if (null != $createtime1){
                $sql .=" and c.createTime >= '".$createtime1."'";
                $queryConditions["c.createTime"]=array("EGT","'".$createtime1."'");
                //             $this->classModel->where("createTime >= '%s'",$createtime1);
            }
            if (null != $createtime2){
                $sql .=" and c.createTime <= '".$createtime2."'";
                //             $this->classModel->where("createTime <= '%s'",$createtime2);
            }
    
            //第二行
            if (null != $headerName){
                $sql .=" and u1.trueName like '%$headerName%'";
                //             $queryConditions["c.trueName"]="like '%$headerName%'";
                //             $this->classModel->where("u1.trueName like '%$headerName%'");
            }
            if ($status > 0){
                $sql .=" and c.status = $status";
            }
            //         $count = $this->classModel->field("count(*) as cc")->table("class c,tb_user u1,tb_user u2")->where("c.headerid=u1.uid and c.manageid=u2.uid")->find()["cc"];
            $count = $this->classModel->query("select count(*) as cc".$sql)[0]["cc"];
            $page["total"] =$count;
    
            //         $rows =$this->classModel->field("c.cid,c.name,c.classtype,c.status,c.createtime,c.begintime,c.endtime,u1.truename as headername,u1.truename as managename,c.stucount,c.remark")
            $begin = ($pageNo-1)*$pageSize;
    
            $rows =$this->classModel->query("select c.cid,c.name,c.classtype,c.status,c.createtime,c.begintime,c.endtime,u1.truename as headername,
            u1.truename as managename,c.stucount,c.remark".$sql."limit $begin,$pageSize");
            $page["rows"] =$rows;
            $this->ajaxReturn($page);
            //         ->table("class c,tb_user u1,tb_user u2")
            //         ->where("c.headerid=u1.uid and c.manageid=u2.uid")
            //         ->page($pageNo,$pageSize)->select();
             
            //         $page  = $this->menuModel->loadMenuByPage($pageNo,$pageSize);
    
    }
    
    
    
    
    /**
     * 添加或者修改菜单时，父级菜单下拉列表加载数据
     */
    public function load12Menu(){
        $menu12 = $this->menuModel->load12Menu();
        $this->ajaxReturn($menu12);
    }
    
    /**
     * 添加或者修改菜单
     * @param unknown $menuid       参数绑定
     * @param unknown $name         参数绑定
     * @param unknown $url          参数绑定
     * @param unknown $parentid     参数绑定
     * @param unknown $isshow       参数绑定
     */
    public function saveOrUpdateMenu($menuid,$name,$url,$parentid,$isshow) {
        if($menuid == ""){
            $this->menuModel->saveOrUpdateMenu($name, $url, $parentid, $isshow, 0);
            $this->ajaxReturn("insertok","eval");
        }else{
            $this->menuModel->saveOrUpdateMenu($name, $url, $parentid, $isshow, (int)$menuid);
            $this->ajaxReturn("updateok","eval");
        }
    }
    
    /**
     * 通过主键id加载菜单对象数据
     * @param unknown $menuid
     */
    public function loadMenuByID($menuid){
        $menu = $this->menuModel->loadMenuByID($menuid);
        $this->ajaxReturn($menu);
    }
    
    /**
     * 删除菜单
     * @param unknown $menuids
     */
    public function deleteMenus($menuids) {
        $this->menuModel->deleteMenus($menuids);
        $this->ajaxReturn("OK","eval");
    }
    
    
}