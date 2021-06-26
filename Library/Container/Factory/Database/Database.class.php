<?php

class ContainerFactoryDatabase
{

    private static int   $databaseAllCounter = 0;
    private static array $databaseConnection = [];
    private int          $dataBaseCounter    = 0;

    public function __construct()
    {
    }

    /**
     * @param string $databaseConnection
     * @param string $type
     * @param string $dsn
     * @param string $user
     * @param string $pass
     * @param array  $options
     */
    public function set(string $databaseConnection, string $type, string $dsn, string $user = '', string $pass = '', array $options = []): void
    {

        $dsnString = '';
        if ($type === 'mysql') {
            $dsnString = 'mysql:' . $dsn;
        }
        elseif ($type === 'sqlite') {
            $dsnString = 'sqlite:' . CMS_ROOT . $dsn;
        }

        self::$databaseConnection[$databaseConnection] = new ePDO($dsnString,
                                                                  $user,
                                                                  $pass,
                                                                  $options);
        self::$databaseConnection[$databaseConnection]->setAttribute(PDO::ATTR_ERRMODE,
                                                                     PDO::ERRMODE_EXCEPTION);
    }

    public function get(string $databaseConnection = 'primary'): ePDO
    {
        if (isset(self::$databaseConnection[$databaseConnection])) {
            return self::$databaseConnection[$databaseConnection];
        }
        else {
            throw new DetailedException('databaseConnectionNotFound',
                                        0,
                                        null,
                                        [
                                            'debug' => [
                                                'databaseConnection'     => $databaseConnection,
                                                'databaseConnectionList' => array_keys(self::$databaseConnection),
                                            ]
                                        ]);

        }
    }

    public function has(string $databaseConnection): bool
    {
        return isset(self::$databaseConnection[$databaseConnection]);
    }

    public function getDbname(?string $databaseConnection = null): string
    {
        if ($databaseConnection === null) {
            $databaseConnection = 'primary';
        }

        return (string)Config::get('/environment/database/' . $databaseConnection . '/dbname');
    }

    public function close(string $databaseConfigConstants = 'primary'): void
    {
        self::$databaseConnection[$databaseConfigConstants] = null;
    }

}

//Database::_construct($Database);

