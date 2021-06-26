<?php

class ContainerFactorySession
{
    protected static ?SessionHandlerInterface $sessionHandler = null;

    public static function setSessionHandler(?string $handler = null): void
    {
        if ($handler !== null) {
            self::$sessionHandler = Container::get('ContainerFactorySession' . ucfirst($handler));
        }
    }

    public static function check(): bool
    {
        if (session_status() === PHP_SESSION_DISABLED || session_status() === PHP_SESSION_NONE) {
            return false;
        }
        elseif (session_status() === PHP_SESSION_ACTIVE) {
            return true;
        }
        else {
            return false;
        }

    }

    public static function set(string $path, $value): void
    {
        if (self::check() === false) {
            throw new DetailedException('sessionNotActive',
                                        0,
                                        null,
                                        [],
                                        1);
        }
        $_SESSION[$path] = $value;
    }

    public static function get(string $path): ?string
    {
        if (self::check() === false) {
            throw new DetailedException('sessionNotActive',
                                        0,
                                        null,
                                        [],
                                        1);
        }
        return ($_SESSION[$path] ?? null);
    }

    public static function start(bool $forced = false): void
    {
        if (isset($_COOKIE[Config::get('/environment/session/cookie/name')]) === true || $forced === true) {
            if (self::$sessionHandler !== null) {
                session_set_save_handler(self::$sessionHandler,
                                         true);
            }

            session_name(Config::get('/environment/session/cookie/name'));

            if (empty(Config::get('/environment/session/cookie/domain'))) {
                $domain = Config::get('/server/domain');
            }
            else {
                $domain = Config::get('/environment/session/cookie/domain');
            }

            session_set_cookie_params([
                                          'lifetime' => Config::get('/environment/session/cookie/lifetime'),
                                          'path'     => Config::get('/environment/session/cookie/path'),
                                          'domain'   => $domain,
                                          'secure'   => (bool)Config::get('/environment/session/cookie/secure'),
                                          'httponly' => (bool)Config::get('/environment/session/cookie/httponly'),
                                          'samesite' => Config::get('/environment/session/cookie/samesite'),
                                      ]);

            session_start();

        }

    }

    public static function destroy(): void
    {
        $_SESSION = [];
        session_destroy();

        if (empty(Config::get('/environment/session/cookie/domain'))) {
            $domain = Config::get('/server/domain');
        }
        else {
            $domain = Config::get('/environment/session/cookie/domain');
        }

        setcookie(Config::get('/environment/session/cookie/name'),
                  '',
                  [
                      'expires' => time() - 3600,
                      'path'    => Config::get('/environment/session/cookie/path'),
                      'domain'  => $domain,
                  ]);
    }

}
