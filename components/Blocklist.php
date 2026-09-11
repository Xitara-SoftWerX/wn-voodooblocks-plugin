<?php

namespace Xitara\VoodooBlocks\Components;

use Cms\Classes\ComponentBase;
use Event;
use File;
use Xitara\VoodooBlocks\Models\Blocklist as BlocklistModel;

class Blocklist extends ComponentBase
{
    public $blocklist;

    public function componentDetails()
    {
        return [
            'name' => 'Blocklist Component',
            'description' => 'No description provided yet...',
        ];
    }

    public function defineProperties()
    {
        return [
            'blocklist' => [
                'title' => 'xitara.voodooblocks::component.blocklist.title',
                'description' => 'xitara.voodooblocks::component.blocklist.description',
                'type' => 'dropdown',
                'required' => true,
            ],
        ];
    }

    public function onRun()
    {
        $this->addJs('assets/js/app.js');
        $this->addCss('assets/css/app.css');
    }

    public function onRender()
    {
        $blocklist = BlocklistModel::find($this->property('blocklist'));

        if ($blocklist === null) {
            return;
        }

        if ($blocklist->is_default_css == 1) {
            $this->addCss('assets/css/default.css');
        }

        // $blocklist->blocks = $this->generateBlocklist($blocklist->blocks);
        $this->blocklist = $this->page['blocklist'] = $blocklist;
    }

    // public function generateBlocklist($blocks)
    // {
    //     $blocklist = [];

    //     // var_dump($blocks);
    //     // exit;

    //     foreach ($blocks as $block) {
    //         $block = $block['block'];

    //         /**
    //          * event to patch block data like parsing placeholder aso
    //          */
    //         // Event::fire('xitara.voodooblocks.beforeProcessBlock', [ & $block]);

    //         if (isset($block['modules'])) {
    //             $block['is_blocked'] = false;

    //             foreach ($block['modules'] as $module) {
    //                 // $class = '\\Xitara\\DynamicContentModules\\Classes\\';
    //                 // $class .= ucfirst(camel_case($module['_group']));

    //                 // \Log::debug($class);

    //                 // if (!class_exists($class)) {
    //                 // continue;
    //                 // }

    //                 /**
    //                  * add heading, excerpt and content if not defined by module
    //                  */
    //                 foreach (['heading', 'subheading', 'excerpt', 'content'] as $type) {
    //                     if (!isset($module[$type])) {
    //                         $module[$type] = trim($block[$type]);
    //                     }
    //                 }

    //                 // $templateName = strtolower(camel_case($module['_group']));
    //                 $templateName = snake_case($module['_group']);

    //                 $template = 'xitara/voodooblocksmodules/views/';
    //                 $template .= $templateName . '.htm';
    //                 // \Log::debug($template);
    //                 // var_dump($template);

    //                 if (!File::exists(plugins_path($template))) {
    //                     $template = null;
    //                 }

    //                 /**
    //                  * inject css and js if exists
    //                  */
    //                 $assets = 'xitara/voodooblocksmodules/assets/';
    //                 $asset  = $assets . 'css/' . $templateName . '.css';

    //                 // var_dump(plugins_path($asset));
    //                 // var_dump(public_path($asset));
    //                 // var_dump(File::exists(plugins_path($asset)));
    //                 // var_dump($asset);

    //                 if (File::exists(plugins_path($asset))) {
    //                     $this->addCss(plugins_url($asset));
    //                 }

    //                 $asset = $assets . 'js/' . $templateName . '.js';
    //                 // var_dump($asset);

    //                 if (File::exists(plugins_path($asset))) {
    //                     $this->addJs(plugins_url($asset));
    //                 }

    //                 /**
    //                  * get dynamic data
    //                  *
    //                  * @var array
    //                  */
    //                 $textlist = [];
    //                 $varlist  = [];

    //                 foreach ($module as $key => $data) {
    //                     // var_dump($key);
    //                     if (strpos($key, '__text_') !== false) {
    //                         $key   = str_replace('__text_', '', $key);
    //                         $_text = Text::find($data);

    //                         // var_dump($key);

    //                         if ($_text !== null) {
    //                             $textlist[$key] = $_text->text;
    //                         }
    //                     } else {
    //                         $varlist[$key] = $data;
    //                     }
    //                 }

    //                 $parsed = $class::getData($template, $textlist, $varlist);
    //                 // var_dump($parsed);
    //                 // var_dump($template);

    //                 if ($parsed === null) {
    //                     $block['is_blocked'] = true;
    //                     // continue;
    //                 }

    //                 /**
    //                  * write config array
    //                  */
    //                 $block['dynamic_config'][$templateName] = $template;
    //                 // var_dump(count($block['modules'] ?? []));

    //                 // var_dump($block['heading']);

    //                 if (count($block['modules'] ?? []) > 1) {
    //                     $block['dynamic_content'][] = '<li>' . $parsed . '</li>';
    //                 } else {
    //                     $block['dynamic_content'][] = $parsed;
    //                 }
    //                 // }
    //             }

    //             unset($block['modules']);
    //             // var_dump($block['dynamic_content']);
    //             // var_dump($block);
    //             // exit;
    //             $block['dynamic_config'] = array_dot($block['dynamic_config'] ?? []);

    //             if (count($block['dynamic_content'] ?? []) > 1 && $block['is_raw_block'] == 0) {
    //                 $dynamicContent = '<ul class="dynamic-content">';
    //                 $dynamicContent .= join($block['dynamic_content'] ?? []);
    //                 $dynamicContent .= '</ul>';
    //                 $block['dynamic_content'] = $dynamicContent;
    //             } else {
    //                 $block['dynamic_content'] = join($block['dynamic_content'] ?? []);
    //             }
    //         }

    //         Event::fire('xitara.voodooblocks.afterProcessBlock', [ & $block]);

    //         if (!isset($block['is_blocked']) || $block['is_blocked'] !== true) {
    //             $blocklist[] = $block;
    //         }
    //     }

    //     return $blocklist;
    // }

    public function getBlocklistOptions()
    {
        return BlocklistModel::orderBy('heading', 'asc')->lists('heading', 'id');
    }
}
