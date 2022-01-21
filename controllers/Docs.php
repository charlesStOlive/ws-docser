<?php namespace Waka\Docser\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use System\Classes\PluginManager;
use Winter\Storm\Support\Collection;

/**
 * Docs Back-end Controller
 */
class Docs extends Controller
{
    public $layout = 'empty';
    private Collection $docsCollection;
    private Collection $partsCollection;
    private $docsPart;

    public function __construct()
    {
        parent::__construct();
        $this->addCss('/plugins/waka/docser/assets/css/docs.css');
        BackendMenu::setContext('Waka.Docser', 'docs');
        $this->getDocsData();
        $this->vars['groupDocs'] = $this->getDocsNavigation();
    }

    public function index($pageCode = null) {

    }

    public function preview($pageCode = null) {
        trace_log($pageCode);
        $content = $this->renderDoc($pageCode);
        $this->vars['content'] = $content;

    }



    private function getDocsData() {
        $this->docsCollection = new Collection();
        $this->partsCollection = new Collection();
        $plugins = PluginManager::instance()->getPlugins();
        $docCollection = new \Winter\Storm\Support\Collection();
        foreach($plugins as $plugin) {
            $pluginPath = $plugin->getPluginPath();
            $yamlPath = $pluginPath.'/wakadocs.yaml';
            $yamlFile = \File::exists($yamlPath);
            trace_log($yamlPath." :  ".$yamlFile);
            if($yamlFile) {
                $datas = \Yaml::parseFile($yamlPath);
                $files = $datas['files'] ?? [];
                foreach($files as $key=>$file) {
                    $objet = $file;
                    $objet['code'] = $key;
                    $objet['path'] = $pluginPath.'/docs\/'.$key.'.md';
                    $this->docsCollection->put($key, $objet);
                }
                $parts = $datas['parts'] ?? [];
                foreach($parts as $key=>$part) {
                    $objet = $part;
                    $objet['code'] = $key;
                    $objet['path'] = $pluginPath.'/docs/parts/'.$key.'.md';
                    $this->partsCollection->push($objet);
                }
            }
        }
    }

    public function getDocsNavigation() {
        $docsdata = $this->docsCollection->sortBy('group')->groupBy('group');
        return $docsdata->toArray();

    }

    public function getDocFromCode($docId) {
        return $this->docsCollection->get($docId);
    }

    // public function getNav() {
    //     return $this->renderPartial('@nav.htm', [
    //         'navs' => $this->docsConfig,
    //         'currentPage' =>  $this->getCurrentPage()
    //         ]);
    // }


    // public function getCurrentPage() {
    //      $docId = $this->param('doc_id');
    //     if(!$docId) {
    //         $docId = 'utils_install';
    //     }
    //     return $docId;
    // }

    public function renderDoc($docId) {
        if(!$docId) {
            $docId = 'utils_install';
        }
        $docData = $this->getDocFromCode($docId);
        $docPath = $docData['path'];
        $fileContent = \File::get($docPath);
        //Modification des urls des images
        $regex = '/!\[(.*)\]\(([^ ]+)\)/m';
        $basePath = '/plugins/'.$docData['relativePath'];
        $subst = '![$1]('.$basePath.htmlspecialchars('/$2)');
        $result = preg_replace($regex, $subst, $fileContent);
        //
        $result = $this->integrateSubart($result);
        return \Markdown::parse($result);
    }

    public function integrateSubart($text) {
        $regex = '/<!--includepart\[(.*?)\]-->/m';
        return preg_replace_callback($regex, [&$this,'insertpart'], $text);
        
    }

    public function insertpart($matches) {
        $partName = $matches[1] ?? null;
        $partsToReturn = null;
        if($partName) {
            trace_log($partName);
            trace_log($this->partsCollection);
            $parts = $this->partsCollection->where('code', $partName)->toArray();
            $marpartContentkDown = null;
            foreach($parts as $part) {
                $partsToReturn .= \File::get($part['path']);
            }
            trace_log($partsToReturn);
            return $partsToReturn;
        }
        

    }

    // function doMarkdownLinks($s) {
    // return preg_replace_callback('/\[(.*?)\]\((.*?)\)/', function ($matches) {
    //         return '<a href="' . $matches[2] . '">' . $matches[1] . '</a>';
    //     }, htmlspecialchars($s));
    // }
}
