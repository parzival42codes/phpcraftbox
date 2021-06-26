<?php

//ini_set('memory_limit',
//        '512M');
//ini_set("max_execution_time",
//        720);

require(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'Library/exception.inc.php');
require(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'Library/config.inc.php');

define('EVENT_DISABLE',
       true);

$consoleStart = 'install';

$installClasses = [
    'Config',
    'ContainerFactoryLanguage',
    'Event',
    //  'Data',
    'ContainerFactoryFile',
    'ContainerFactoryStorage',
    'ContainerFactoryModul',
    'Core',
    'CoreErrorhandler',
    'CorePdo',
    'CoreDebug',
    'CoreDebugDump',
    'CoreDebugDumpObject',
    'ContainerFactoryRouter',
    'Style',
    'Container',
    'ContainerFactoryUser',
    'ContainerFactoryUserGroup',
    'CoreDebugProfiler',
    'CoreIndex',
    'CoreAutoload',
    'ContainerIndexPage',
    'ContainerIndexPageBox',
    'ContainerExtensionTemplateLoad',
    'ContainerExtensionTemplateParseCreateForm',
    'ContainerExtensionTemplateParseCreateFormElement',
    'ContainerExtensionTemplateParseCreateTableTable',
    'ContainerExtensionTemplateParseCreateTableTableYesno',
    'ContainerExtensionAjax',
    'ContainerExtensionTemplateParseHelperDialog',
    'ContainerFactoryMenu',
    'Application',
    'ApplicationIndex',
    'ApplicationAdministrationUserGroup',
    'ApplicationTask',
    'ContainerExternResources',
    'PluginIndexJavascriptJquery',
    'ContainerIndexTab',
    'ContainerFactoryDatabaseQuery',
];

switch ($consoleStart) {
    case 'database':
        $consoleClassName = 'ContainerFactoryDatabase_console';
        $parameter        = [
            0,
            '',
        ];
        break;
    case 'install':
        $consoleClassName = 'ContainerFactoryModulInstall_console';
        $parameter        = array_merge([
                                            0,
                                            '--install'
                                        ],
                                        $installClasses);
        break;
    case 'cache':
        $consoleClassName = 'ContainerExtensionCache_console';
        $parameter        = [
            0,
            '--all',
        ];
        break;
    case 'cacheAll':
        $consoleClassName = 'ContainerExtensionCache_console';
        $parameter        = [
            0,
            '--all',
        ];
        break;
    case 'modul':
        $consoleClassName = 'ContainerFactoryModul_console';
        $parameter        = [
            0,
            '--collect'
        ];
        break;
}

/*
$consoleClassName= 'ContainerExtensionCache_console';

$parameter = [
    0,
    '--all',
];
*/


/** @var Console_abstract $console */
$console = Container::get($consoleClassName,
                           $parameter);
$console->execute();
