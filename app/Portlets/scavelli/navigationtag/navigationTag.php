<?php

namespace app\Portlets\scavelli\navigationtag;

use App\Portlets\abstractPortlet as Portlet;
use App\Libraries\navigation;
use App\Portlets\scavelli\navigationtag\Controllers\tagController;

class navigationTag extends Portlet {

    private $menu;

    public function init() {
        $this->rp->setModel('App\Models\Content\Tag');

        $this->theme->addExCss($this->getPath().'css/menutag.css');
        $this->theme->addExJs($this->getPath().'js/menutag.js');
    }

    public function getContent() {

        // ordered
        if (!empty($this->config['ord'])) {
            $ord = ['id','name','created_at','updated_at'];
            $dir = ['asc','desc'];
            if (!isset($this->config['dir'])) $this->config['dir'] = 0;
            $this->rp->orderBy($ord[$this->config['ord']], $dir[$this->config['dir']]);
        }

        $tags = $this->rp->get();
        $nav = new navigation();
        foreach($tags as $tag) {
            $this->menu = [$tag->name=>['class'=>'treeview','icon'=>'fa-laptop']];
            $url = (!empty($this->config['inpage'])) ?  url($this->config['inpage']) : url()->current();
            $this->menu[$tag->name]['url'] = $url.'?'.http_build_query(['tag'=>$tag->id]);
            $nav->add($this->menu);
        }
        //dd($nav->getNav());
        $title = $this->config['title'];
        return view('navigationtag::navTagGrey')->with(compact('nav','title'));
    }

    public function configPortlet($portlet) {
        return (new tagController($this->rp))->configPortlet($portlet, $this->request);
    }
}