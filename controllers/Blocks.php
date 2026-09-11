<?php

namespace Xitara\VoodooBlocks\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

/**
 * Blocks Backend Controller
 */
class Blocks extends Controller
{
    /**
     * @var array Behaviors that are implemented by this controller.
     */
    public $implement = [
        \Backend\Behaviors\FormController::class,
        \Backend\Behaviors\ListController::class,
    ];

    public function __construct()
    {
        \Log::debug(__METHOD__);

        parent::__construct();

        BackendMenu::setContext('Xitara.VoodooBlocks', 'voodooblocks', 'blocks');
    }
}
