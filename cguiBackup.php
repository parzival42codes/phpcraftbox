<?php

ini_set('memory_limit',
        '512M');
ini_set("max_execution_time",
        720);

require(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'Library/config.inc.php');

define('EVENT_DISABLE',
       true);

if ($_GET['prepare'] ?? 0) {

    /** @var ContainerFactoryDatabaseQuery $query */
    $query = Container::get('ContainerFactoryDatabaseQuery',
                            __METHOD__,
                            true,
                            ContainerFactoryDatabaseQuery::MODE_OTHER,
                            false);

    $dropTables = [
        'index_router',
        'config',
        'debug_statistic',
        'event_attach',
        'event_trigger',
        'user_group',
        'user_group_access',
        'user_group_to_access',
        'form_response',
        'index_module',
        'language',
        'menu',
        'page_box',
        'storage',
        'task_index',
        'documentation',
        'token',
        'user',
        'log_notification',
        'template_positions',
        'content',
        'content_index',
        'content_history',
        'general_memory',
        'vendor',
    ];


    $queryString = 'DROP TABLE IF EXISTS `' . implode('`,`',
                                                      $dropTables) . '`;';
    $query->query($queryString);
    $query->construct();

    $query->execute();

//   eol(true);

    $dropTables = [
        'cache'   => [
            [
                'cache'
            ]
        ],
        'session' => [
            [
                'session'
            ]
        ],
    ];

    foreach ($dropTables as $dropTablesName => $table) {
        foreach ($table as $dropTablesList) {
            foreach ($dropTablesList as $dropTablesItem) {
                if (
                    ContainerFactoryDatabaseEngineSqlite::tableIsInDatabase($dropTablesName,
                                                                            $dropTablesName) === true
                ) {
                    /** @var ContainerFactoryDatabaseQuery $query */
                    $query       = Container::get('ContainerFactoryDatabaseQuery',
                                                  __METHOD__,
                                                  $dropTablesName,
                                                  ContainerFactoryDatabaseQuery::MODE_OTHER);
                    $queryString = ' DELETE FROM "' . $dropTablesItem . '";';
                    $query->query($queryString);
                    $query->construct();
                    $query->execute();

                }
            }
        }
    }


    $_POST['cguiConsoleClass'] = 'ContainerFactoryModulInstall';

    if (
        !isset($_POST['cguiConsoleClass']) || !isset($_POST['cguiSecureKey']) || Config::get('/environment/secret/cgui',
                                                                                             '') !== $_POST['cguiSecureKey']
    ) {
        throw new DetailedException('secureKeyFail',
                                    0,
                                    null);
    }

    /** @var Console_abstract $console */
    $console = Container::get(($_POST['cguiConsoleClass'] ?? '') . '_console',
                              0,
                              'prepare',
                              'install',
        ($_POST['cguiParameter'] ?? []));
    $console->setOutputMode(Console_abstract::OUTPUT_MODE_AJAX);
    $console->execute();
    exit();
}
elseif ($_GET['execute'] ?? 0) {

    ContainerFactoryDatabaseEngineMysqlTable::setTableIndexCache(false);

    if (
        !isset($_POST['cguiConsoleClass']) || !isset($_POST['cguiSecureKey']) || Config::get('/environment/secret/cgui',
                                                                                             '') !== $_POST['cguiSecureKey']
    ) {
        throw new DetailedException('secureKeyFail',
                                    0,
                                    null);
    }

    /** @var Console_abstract $console */
    $console = Container::get('ContainerFactoryModulInstall_console',
                              $_POST['cguiConsoleId'],
                              'execute',
                              'install',
        ($_POST['cguiParameter'] ?? []));

    $console->setOutputMode(Console_abstract::OUTPUT_MODE_AJAX);

    $console->execute();
    exit();
}
else {

    $body       = file_get_contents(CMS_ROOT . '/Cgui/Body.tpl');
    $bodyJQuery = file_get_contents(CMS_ROOT . '/Cgui/Jquery.3.5.1.min.js') . file_get_contents(CMS_ROOT . '/Cgui/script.js');
    $bodyCss    = file_get_contents(CMS_ROOT . '/Cgui/Style.css');

    /** @var ContainerExtensionTemplate $bodyTemplate */
    $bodyTemplate = Container::get('ContainerExtensionTemplate');
    $bodyTemplate->set($body);
    $bodyTemplate->assign('javascriptHeader',
                          $bodyJQuery);
    $bodyTemplate->assign('headerCss',
                          $bodyCss);

    $bodyTemplate->parse();
    echo $bodyTemplate->get();

}
