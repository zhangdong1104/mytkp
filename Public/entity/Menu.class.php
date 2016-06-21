<?php
namespace entity;
class Menu{
    
    public $menuid;
    
    //展示的菜单名称
    public $name;
    
    //菜单被点击之后要发送的超链接地址 如果此菜单不是最低级则此列的值为null
    public $url;
    
    //parentid表示此菜单父级菜单的主键id 如果此菜单已经是最顶级菜单，则此列的值为-1
    public $parentid;
    
    //isshow表示是否在首页左边的树形菜单中展示 1表示要展示
    public $isshow;
    
    //如果此菜单是二级菜单，则它的所有子菜单放在$children数组中
    //如果此菜单是一个最低级菜单，则$children为null
    private $children;
    public function getMenuid()
    {
        return $this->menuid;
    }
    public function getName()
    {
        return $this->name;
    }
    public function getUrl()
    {
        return $this->url;
    }
    public function getParentid()
    {
        return $this->parentid;
    }
    public function getIsshow()
    {
        return $this->isshow;
    }
    public function setMenuid($menuid)
    {
        $this->menuid = $menuid;
    }
    public function setName($name)
    {
        $this->name = $name;
    }
    public function setUrl($url)
    {
        $this->url = $url;
    }
    public function setParentid($parentid)
    {
        $this->parentid = $parentid;
    }
    public function setIsshow($isshow)
    {
        $this->isshow = $isshow;
    }
    public function getChildren()
    {
        return $this->children;
    }
    public function setChildren(array $children)
    {
        $this->children = $children;
    }


    
    
    
}

?>