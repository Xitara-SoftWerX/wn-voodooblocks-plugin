<?php

namespace Xitara\VoodooBlocks\Controllers;

use Backend\Classes\Controller;
use BackendMenu;
use Event;

/**
 * Block Lists Back-end Controller
 */
class Blocklists extends Controller
{
    /**
     * @var array Behaviors that are implemented by this controller.
     */
    public $implement = [
        \Backend\Behaviors\FormController::class,
        \Backend\Behaviors\ListController::class,
        \Backend\Behaviors\RelationController::class,
    ];

    public function __construct()
    {
        // \Log::debug(__METHOD__);

        parent::__construct();
        BackendMenu::setContext('Xitara.VoodooBlocks', 'voodooblocks', 'nexus.blocklists');
        // }

        // public function formExtendFields($form)
        // {
        // \Log::debug(__METHOD__);

        // Event::fire('xitara.voodooblocks.dynamicblocks', [&$modules]);

        // \Log::debug($modules);

        // Event::fire('xitara.voodooblocks.dynamicblocks', function ($modules) {
        // \Log::debug('xitara.voodooblocks.dynamicblocks');

        // foreach ($modules as $module) {
        // \Log::debug($module);

        // if ($form->isNested === false) {
        //     $form->fields['blocks']['form']['fields']['block']['form']['tabs']['fields']['dynamic_blocks'] = [
        //         'tab'    => 'xitara.voodooblocks::lang.tab.dynamic_blocks',
        //         'prompt' => 'xitara.voodooblocks::lang.dynamic_blocks.prompt',
        //         'type'   => 'repeater',
        //         'span'   => 'full',
        //         'style'  => 'accordion',
        //         // 'groups' => $block,
        //     ];
        // }
        // }
        // });
    }

    // public function update($id = null)
    // {
    //     if ($id != null) {
    //         BackendMenu::setContext(
    //             'Xitara.VoodooBlocks',
    //             'voodooblocks',
    //             'nexus.blocklist.' . $id
    //         );
    //     }

    //     $this->asExtension('FormController')->update($id);
    // }
}
