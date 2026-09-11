<?php

namespace Xitara\VoodooBlocks\Console;

use File;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;

class Module extends Command
{
    /**
     * target path
     */
    private $targetPath;

    /**
     * @var string The console command name.
     */
    protected $name = 'voodooblocks:module';

    /**
     * @var string The console command description.
     */
    protected $description = 'Generates a plugin, classfile, .yaml, language file(s) and .htm file';

    /**
     * @var mixed The module path.
     * @var mixed The plugin path.
     */
    protected $modulePath = null;
    protected $pluginPath = null;

    // public function __construct()
    // {
    //     parent::__construct();
    // }

    /**
     * Execute the console command.
     * @return void
     */
    public function handle()
    {
        $name = ucfirst($this->option('name'));

        // $this->output->writeln(getcwd());

        if ($name === null || $name == '') {
            $this->output->writeln('Name ist required: php artisan ' . $this->name . ' --name=ModuleName [-y(aml)] [-t(emplate)] [-l(ang) [en] --namespace="Xitara\BlockExtender"]');
            $this->output->writeln('');
            $this->output->writeln('If a namespace is given, a plugin will be generated within this namespace, if this plugin not exists. Without a namespace, the modules will be generated in Xitara\VoodooBlocks namespace');
            exit;
        }

        // var_dump($this->option());
        // \Log::debug($this->modulePath);
        // exit;

        $this->output->writeln('New module: ' . $name);

        /**
         * generate plugin
         */
        if ($this->option('namespace') !== null) {
            $this->generatePlugin($name);
        }

        /**
         * define path for module
         */
        if (isset($this->pluginPath)) {
            $this->modulePath = $this->pluginPath . '/modules/' . strtolower($name);
        } else {
            $this->modulePath = dirname(dirname(__FILE__)) . '/modules/' . strtolower($name);
        }
        // exit;

        /**
         * generate module folder
         */
        if (!File::exists($this->modulePath)) {
            var_dump($this->modulePath);
            File::makeDirectory($this->modulePath, 0777, true);
        }

        /**
         * generate class file
         */
        $this->generateClassFile($name);

        /**
         * generate yaml file for repeater form
         */
        if ($this->option('yaml') === true) {
            $this->generateYamlFile($name);
        }

        /**
         * generate htm template file
         */
        if ($this->option('template') === true) {
            $this->generateTemplateFile($name);
        }

        /**
         * generate language file
         */
        if ($this->option('lang') !== null) {
            $this->generateLanguageFile($name, $this->option('lang'));
        }
    }

    private function generatePlugin($name)
    {
        $namespace = $this->option('namespace');
        $dotted = strtolower(str_replace('\\', '.', $namespace));
        $vendor = explode('\\', $namespace)[0];
        $lowername = strtolower($name);

        if (class_exists($namespace)) {
            $this->output->writeln('Plugin exists: ' . $namespace);

            return;
        }

        $this->pluginPath = plugins_path(strtolower(str_replace('\\', '/', $namespace)));
        if (!File::exists($this->pluginPath)) {
            File::makeDirectory($this->pluginPath, 0777);
        }


        $content = <<< EOF
<?php namespace {$namespace};

use Backend;
use Backend\Models\UserRole;
use System\Classes\PluginBase;
use System\Classes\PluginManager;

/**
 * BlockExtender Plugin Information File
 */
class Plugin extends PluginBase
{
    /**
     * Returns information about this plugin.
     */
    public function pluginDetails(): array
    {
        return [
            'name'        => '{$dotted}::lang.plugin.name',
            'description' => '{$dotted}::lang.plugin.description',
            'author'      => '{$vendor}',
            'icon'        => 'icon-leaf'
        ];
    }

    /**
     * Registers the custom Blocks provided by this plugin
     */
    public function registerModules(): array
    {
        return [
            '{$lowername}' => \\{$namespace}\\Modules\\{$name}\\{$name}::class,
        ];
    }
}
EOF;

        if (!File::exists($this->pluginPath . '/Plugin.php')) {
            File::put($this->pluginPath . '/Plugin.php', $content);
            $this->output->writeln('Plugin generated: ' . $this->pluginPath . '/Plugin.php');
        }
    }

    private function generateClassFile($name)
    {
        $namespace = $this->option('namespace');
        $lowername = strtolower($name);

        $filePath = $this->modulePath . '/' . $name . '.php';

        if (File::exists($filePath)) {
            $this->output->writeln('PHP-File exists: ' . $filePath);
            // return;
        }

        $content = <<< EOF
<?php namespace {$namespace}\\Modules\\{$name};

use File;
use Twig;
use Winter\Storm\Parse\Bracket;

class $name
{
    /**
     * define placeholder for replacement in text fields. Names must the same as in \$vars
     *
     * @var array
     */
    public static \$placeholder = [
        'placeholder_1' => 'Placeholder 1 description',
        'placeholder_2' => 'Placeholder 2 description',
        'placeholder_3' => 'Placeholder 3 description',
        'placeholder_4' => 'Placeholder 4 description',
    ];

    /**
     * pipe placeholders into description in language file
     */
    public static function getPlaceholder(): string
    {
        \$list = [];
        foreach (self::\$placeholder as \$placeholder => \$description) {
            \$list[] = '{' . \$placeholder . '} => ' . \$description;
        }

        return join('<br>', \$list);
    }

    /**
     * render module with parsed twig template
     */
    public static function renderText(\$module): string
    {
        /**
         * define replace texts für placeholders. Names must the same as in \$placeholders
         */
        \$vars = [
            'placeholder_1' => 'Placeholder 1 replaced',
            'placeholder_2' => 'Placeholder 2 replaced',
            'placeholder_3' => 'Placeholder 3 replaced',
            'placeholder_4' => 'Placeholder 4 replaced',
        ];

        /**
         * process form fields
         */
        foreach (\$module as \$name => \$data) {
            /**
             * parse textfields with defined vars
             */
            \$module[\$name] = Bracket::parse(\$data, \$vars);

            /**
             * get dropdown data
             */
            \$method = 'get' . ucfirst(camel_case(\$name)) . 'Options';
            if (method_exists(__CLASS__, \$method)) {
                \$module[\$name . '_options'] = self::\$method();
            }
        }

        /**
         * parse twig template
         */
        \$template = dirname(__FILE__) . '/$lowername.twig';
        if (File::exists(\$template)) {
            \$parsed = Twig::parse(File::get(\$template), \$module);
            return \$parsed;
        }
    }

    /**
     * define dropdown options
     */
    public static function getTestDropdownOptions(\$value = null, \$form = null): array
    {
        return [
            'foo1' => 'Bar1',
            'foo2' => 'Bar2',
            'foo3' => 'Bar3',
            'foo4' => 'Bar4',
            'foo5' => 'Bar5',
        ];
    }
}
EOF;

        File::put($filePath, $content);
        $this->output->writeln('PHP-File generated: ' . $filePath);
    }

    private function generateYamlFile($name)
    {
        $namespace = $this->option('namespace');
        $dotted = strtolower(str_replace('\\', '.', $namespace));
        $group = str_replace('\\', '-', $namespace);
        $lowername = strtolower($name);
        $filePath = $this->modulePath . '/' . $lowername . '.yaml';

        if (File::exists($filePath)) {
            $this->output->writeln('Yaml-File exists: ' . $filePath);

            return;
        }

        $content = <<< EOF
name: {$dotted}::{$lowername}.name
description: {$dotted}::{$lowername}.description
icon: icon-archive
group: {$group}-Modules-{$name}-{$name}
fields:
    _name:
        label: {$dotted}::{$lowername}.name
        comment: {$dotted}::{$lowername}.comment
        type: section
        span: full
    test_text:
        label: {$dotted}::{$lowername}.text.label
        comment: {$dotted}::{$lowername}.text.comment
        type: text
        span: auto
    test_switch:
        label: {$dotted}::{$lowername}.switch.label
        comment: {$dotted}::{$lowername}.switch.comment
        type: switch
        span: auto
    test_dropdown:
        label: xitara.blockextender::voodootest.dropdown.label
        comment: xitara.blockextender::voodootest.dropdown.comment
        type: dropdown
        span: auto
    test_richeditor:
        label: xitara.blockextender::voodootest.richeditor.label
        comment: xitara.blockextender::voodootest.richeditor.comment
        commentHtml: true
        type: richeditor
        span: full
EOF;

        File::put($filePath, $content);
        $this->output->writeln('Yaml-File generated: ' . $filePath);
    }

    private function generateTemplateFile($name)
    {
        $file = strtolower($name);
        $filePath = $this->modulePath . '/' . $file . '.twig';

        if (File::exists($filePath)) {
            $this->output->writeln('Template-File exists: ' . $filePath);

            return;
        }

        $content = <<< EOF
test_text: {{ test_text }} <br>
test_switch: {{ test_switch }} <br>
test_dropdown: {{ test_dropdown }} <br>
test_dropdown_option: {{ test_dropdown_options[test_dropdown] }} <br>
test_richeditor: <br>
{{ test_richeditor|raw }}
EOF;

        File::put($filePath, $content);
        $this->output->writeln('Template-File generated: ' . $filePath);
    }

    private function generateLanguageFile($name, $lang)
    {
        $namespace = $this->option('namespace');

        $this->modulePath .= '/../../lang';

        if ($lang == null) {
            $lang = 'en';
        }

        $this->modulePath .= '/' . $lang;
        if (!File::exists($this->modulePath)) {
            File::makeDirectory($this->modulePath, 0777, true);
        }

        $filePath = $this->modulePath . '/' . strtolower($name) . '.php';

        if (File::exists($filePath)) {
            $this->output->writeln('Language-File exists: ' . $filePath);

            return;
        }

        $content = <<< EOF
<?php

use {$namespace}\\Modules\\{$name}\\{$name};

return [
    'name' => '{$name}',
    'description' => 'Description of {$name}',
    'text' => [
        'label' => 'Text',
        'comment' => 'Comment for text',
    ],
    'dropdown' => [
        'label' => 'Dropdown',
        'comment' => 'Comment for dropdown',
    ],
    'switch' => [
        'label' => 'Switch',
        'comment' => 'Comment for switch',
    ],
    'richeditor' => [
        'label' => 'Text',
        'comment' => 'The following placeholders are possible: <br>' . {$name}::getPlaceholder(),
    ],
];

EOF;

        File::put($filePath, $content);
        $this->output->writeln('Language-File generated: ' . $filePath);
    }

    /**
     * Get the console command options.
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['name', null, InputOption::VALUE_REQUIRED, 'Name of new module', null],
            ['namespace', null, InputOption::VALUE_OPTIONAL, 'Create plugin with this namespace or put module in this namespace if plugin already exists', null],
            ['lang', 'l', InputOption::VALUE_OPTIONAL, 'Create lang-file with given lang', null],
            ['yaml', 'y', InputOption::VALUE_NONE, 'Create yaml', null],
            ['template', 't', InputOption::VALUE_NONE, 'Create twig-template', null],
        ];
    }
}
