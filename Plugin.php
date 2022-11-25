<?php namespace Waka\Docser;

use Backend;
use System\Classes\PluginBase;
use System\Classes\SettingsManager;

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
        return [];
    }

    /**
     * Registers any back-end permissions used by this plugin.
     *
     * @return array
     */
    public function registerPermissions()
    {
        return [
            'waka.docser.admin' => [
                'tab' => 'Waka - docser',
                'label' => 'Administrateur de docser',
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
                'order'       => 1500,
            ],
        ];
    }

    /**
     * Registers back-end navigation items for this plugin.
     *
     * @return array
     */
    public function registerSettings()
    {
        return [
            'appdocs' => [
                'label' => \Lang::get('waka.docser::appdoc.menu_appdocs'),
                'description' => \Lang::get('waka.docser::appdoc.menu_appdocs_description'),
                'category' => SettingsManager::CATEGORY_SYSTEM,
                'icon' => 'icon-book',
                'url' => \Backend::url('waka/docser/appdocs/index/'),
                'permissions' => ['waka.docser.admin.*'],
            ],
        ];
    }
}
