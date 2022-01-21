<?php namespace Waka\Docser\Components;

use Cms\Classes\ComponentBase;
use System\Classes\PluginManager;

class Doc extends ComponentBase
{
    public $docsConfig;

    public function componentDetails()
    {
        return [
            'name'        => 'doc Component',
            'description' => 'No description provided yet...'
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    public function init()
    {
        $this->docsConfig = $this->navDatas();
        trace_log($this->docsConfig);
    }

    public function navDatas() {
        $plugins = PluginManager::instance()->getPlugins();
        $docArray = [];
        foreach($plugins as $plugin) {
            $pluginPath = $plugin->getPluginPath();
            $yamlPath = $pluginPath.'/wakadoc.yaml';
            $yamlFile = \File::exists($yamlPath);
            if($yamlFile) {
                $datas = \Yaml::parseFile($yamlPath);
                foreach($datas as $key=>$datas) {
                    $docArray[$key] = $datas;
                    //On entre l'adresse de chaque sous fichiers
                    $docArray[$key]['path'] = $pluginPath.'/docs\/'.$key.'.md';
                }
            }
        }
        return $docArray;
    }
    public function getnavData($docId) {
        return $this->docsConfig[$docId];
    }

    public function getNav() {
        return $this->renderPartial('@nav.htm', [
            'navs' => $this->docsConfig,
            'currentPage' =>  $this->getCurrentPage()
            ]);
    }


    public function getCurrentPage() {
         $docId = $this->param('doc_id');
        if(!$docId) {
            $docId = 'utils_install';
        }
        return $docId;
    }

    public function getContent() {
        $docId = $this->param('doc_id');
        if(!$docId) {
            $docId = 'utils_install';
        }
        trace_log($docId);
        $docData = $this->getnavData($docId);
        $docPath = $docData['path'];
        $file = \File::get($docPath);
        //Modification des urls des images
        $regex = '/\[(.*)\]\(([^ ]+)\)/m';
        $basePath = url('plugins/'.$docData['path']);

        $subst = '[$1]('.$basePath.'/$2)';
        
        $result = preg_replace($regex, $subst, $file);
        return $result;
    }

}
