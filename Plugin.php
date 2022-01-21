<?php namespace Waka\Docser;

use Backend;
use System\Classes\PluginBase;

/**
 * docser Plugin Information File
 */
class Plugin extends PluginBase
{
    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'docser',
            'description' => 'No description provided yet...',
            'author'      => 'waka',
            'icon'        => ' icon-book'
        ];
    }

    /**
     * Register method, called when the plugin is first registered.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Boot method, called right before the request route.
     *
     * @return array
     */
    public function boot()
    {

    }

    /**
     * Registers any front-end components implemented in this plugin.
     *
     * @return array
     */
    public function registerComponents()
    {
        return [
            'Waka\Docser\Components\Doc' => 'doc_component',
        ];
    }

    /**
     * Registers any back-end permissions used by this plugin.
     *
     * @return array
     */
    public function registerPermissions()
    {
        return []; // Remove this line to activate

        return [
            'waka.docser.some_permission' => [
                'tab' => 'docser',
                'label' => 'Some permission'
            ],
        ];
    }

    /**
     * Registers back-end navigation items for this plugin.
     *
     * @return array
     */
    public function registerNavigation()
    {

        return [
            'docser' => [
                'label'       => 'Documentation',
                'url'         => Backend::url('waka/docser/docs'),
                'icon'        => 'icon-book',
                'permissions' => ['waka.docser.*'],
                'order'       => 1500,
            ],
        ];
    }
}
