<?php
declare(strict_types=1);

/**
 * cGui
 *
 * Graphical Console GUI
 *
 * @modul author Stefan Schlombs
 * @modul version 1.0.0
 * @modul versionRequiredSystem 1.0.0
 * @modul hasJavascript
 *
 */
class ContainerFactoryCgui extends Base
{
    protected string $class   = '';
    protected string $command = '';
    protected string $secure  = '';

    public static function cgui():void
    {
        /** @var ContainerFactoryRequest $prepare */
        $prepare = Container::get('ContainerFactoryRequest',
                                  ContainerFactoryRequest::REQUEST_GET,
                                  'prepare');

        /** @var ContainerFactoryRequest $consoleID */
        $consoleID = Container::get('ContainerFactoryRequest',
                                    ContainerFactoryRequest::REQUEST_GET,
                                    'execute');

        if ($prepare->exists()) {
            self::prepare();
        }
        elseif ($consoleID->exists()) {
            self::execute($consoleID->get());
        }
    }

    protected static function prepare():void
    {
        /** @var ContainerFactoryRequest $modul */
        $modul = Container::get('ContainerFactoryRequest',
                                ContainerFactoryRequest::REQUEST_POST,
                                'modul');

        /** @var ContainerFactoryRequest $command */
        $command = Container::get('ContainerFactoryRequest',
                                  ContainerFactoryRequest::REQUEST_POST,
                                  'command');

        /** @var ContainerFactoryRequest $secure */
        $secure = Container::get('ContainerFactoryRequest',
                                 ContainerFactoryRequest::REQUEST_POST,
                                 'secure');

        /** @var ContainerFactoryRequest $parameter */
        $parameter = Container::get('ContainerFactoryRequest',
                                    ContainerFactoryRequest::REQUEST_POST,
                                    'parameter');

        if (
            !$modul->exists() || !$command->exists() || !$secure->exists() || ($secure->get() !== (string)Config::get('/environment/secret/cgui',
                                                                                                                      uniqid()) && !password_verify((string)Config::get('/environment/secret/cgui'),
                                                                                                                                                    $secure->get()))
        ) {
            throw new DetailedException('postDataFail');
        }

        /** @var Console_abstract $console */
        $console = Container::get($modul->get() . '_console',
                                  0,
                                  'prepare',
                                  $command->get(),
                                  $parameter->get());
        $console->setOutputMode(Console_abstract::OUTPUT_MODE_AJAX);
        $console->execute();
        exit();
    }

    protected static function execute(string $consoleID): void
    {
        require_once(CMS_PATH_STORAGE_CACHE . '/class/console/console_' . $consoleID . '.php');
        $className = 'console_' . $consoleID;
        new $className(Console_abstract::OUTPUT_MODE_AJAX);
    }

    public static function getJavaScript():string
    {
        $file = Container::get('ContainerFactoryFile',
                               Core::getRootClass(__CLASS__) . '_script_js');
        $file->load();
        return $file->get();
    }
}
