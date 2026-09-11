<?php

namespace Xitara\VoodooBlocks;

use App;
use Backend;
use Event;
use File;
use System\Classes\PluginBase;
use System\Classes\PluginManager;
use Xitara\VoodooBlocks\Models\Blocklist;
use Yaml;

/**
 * DynamicContent Plugin Information File
 */
class Plugin extends PluginBase
{
    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails() : array
    {
        return [
            'name' => 'xitara.voodooblocks::lang.plugin.name',
            'description' => 'xitara.voodooblocks::lang.plugin.description',
            'author' => 'xitara.voodooblocks::lang.plugin.author',
            'icon' => 'xitara.voodooblocks::lang.plugin.icon',
            'iconSvg' => 'xitara.voodooblocks::lang.plugin.iconSvg',
            'homepage' => 'xitara.voodooblocks::lang.plugin.homepage',
        ];
    }

    /**
     * Boot method, called right before the request route.
     *
     * @return array
     */
    public function boot() : void
    {
        /**
         * Check if we are currently in backend module.
         */
        if (!App::runningInBackend()) {
            return;
        }

        Event::listen('backend.page.beforeDisplay', function ($controller) {
            $path = plugins_path('/xitara/voodooblocks/assets');
            // $controller->addCss($path . '/css/backend.css');
            // $controller->addJs($path . '/js/backend.js');
        });

        Event::listen('backend.form.extendFieldsBefore', function ($widget) {
            if (!$widget->model instanceof \Xitara\VoodooBlocks\Models\Block) {
                return;
            }

            foreach (PluginManager::instance()->getRegistrationMethodValues('registerModules') as $plugin => $modules) {
                foreach ($modules as $key => $namespace) {
                    $reflector = new \ReflectionClass($namespace);
                    $dirs = new \DirectoryIterator(dirname($reflector->getFileName()));

                    foreach ($dirs as $dir) {
                        if (!$dir->isDot() && strpos($dir->getFilename(), '.yaml')) {
                            $yaml = Yaml::parse(File::get($dir->getPathname()));
                            $groups[$yaml['group']] = $yaml;
                        }
                    }
                }

                if ($widget->isNested === false && !empty($groups)) {
                    $widget->tabs['fields']['modules'] = [
                        'tab' => 'xitara.voodooblocks::lang.tab.modules',
                        'prompt' => 'xitara.voodooblocks::lang.modules.prompt',
                        'type' => 'repeater',
                        'span' => 'full',
                        'style' => 'accordion',
                        'groups' => $groups,
                    ];
                }
            }
        });
    }

    /**
     * Register method, called when the plugin is first registered.
     *
     * @return void
     */
    public function register() : void
    {
        $this->registerConsoleCommand('voodooblocks.module', 'Xitara\VoodooBlocks\Console\Module');
    }

    /**
     * Registers any front-end components implemented in this plugin.
     *
     * @return array
     */
    public function registerComponents() : array
    {
        return [
            'Xitara\VoodooBlocks\Components\Blocklist' => 'blocklist',
        ];
    }

    public function registerPageSnippets() : array
    {
        return $this->registerComponents();
    }

    /**
     * Registers any back-end permissions used by this plugin.
     *
     * @return array
     */
    public function registerPermissions() : array
    {
        return [
            'xitara.voodooblocks.create' => [
                'tab' => 'Voodoo Blocks',
                'label' => 'Create Blockslists',
            ],
            'xitara.voodooblocks.edit' => [
                'tab' => 'Voodoo Blocks',
                'label' => 'Edit Blockslists',
            ],
        ];
    }

    public function registerMarkupTags() : array
    {
        return [
            'filters' => [
                'renderModules' => [$this, 'renderModules'],
            ],
        ];
    }

    /**
     * Registers back-end navigation items for this plugin.
     *
     * @return array
     */
    public function registerNavigation() : array
    {
        return [
            'voodooblocks' => [
                'label' => 'xitara.voodooblocks::lang.plugin.name',
                'url' => Backend::url('xitara/voodooblocks/blocklists'),
                'icon' => 'icon-leaf',
                'iconSvg' => '/plugins/xitara/voodooblocks/assets/images/voodooblocks-icon.svg',
                'permissions' => ['xitara.voodooblocks.*'],
                'order' => 500,
                'sideMenu' => [
                    'blocklists' => [
                        'label' => 'xitara.voodooblocks::lang.submenu.blocklist',
                        'url' => Backend::url('xitara/voodooblocks/blocklists'),
                        'icon' => 'icon-archive',
                        'permissions' => [
                            'xitara.voodooblocks.create',
                            'xitara.voodooblocks.edit',
                        ],
                        'attributes' => [
                            'group' => 'xitara.voodooblocks::lang.submenu.label',
                            'placeholder' => true,
                        ],
                        'order' => 0,
                    ],
                ],
            ],
        ];
    }

    public static function getBlocklistOptions() : array
    {
        $data = Blocklist::orderBy('name', 'asc')->lists('name', 'slug');
        $data = ['none' => e(trans('xitara.voodooblocks::lang.no_blocklist'))]
            + $data;

        return $data;
    }

    public function renderModules($modules) : string
    {
        $result = [];
        foreach ($modules as $module) {
            $namespace = '\\' . str_replace('-', '\\', $module['_group']);
            unset($module['_group']);

            if (method_exists($namespace, 'renderText')) {
                $result[] = $namespace::renderText($module);
            }
        }

        return join($result);
    }
}
