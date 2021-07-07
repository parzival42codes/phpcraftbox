<?php

/**
 * User
 *
 * User
 *
 * @author   Stefan Schlombs
 * @version  1.0.0
 * @modul    versionRequiredSystem 1.0.0
 * @modul    language_name_de_DE Request
 * @modul    language_name_en_US Request
 * @modul    language_path_de_DE /Factory
 * @modul    language_path_en_US /Factory
 *
 */

class ContainerFactoryRequest extends Base
{

    const REQUEST_TYPE_POST   = 'POST';
    const REQUEST_TYPE_GET    = 'GET';
    const REQUEST_TYPE_COOKIE = 'COOKIE';


    const REQUEST_UNKNOWN              = 'UNKNOWN';
    const REQUEST_GET                  = 'GET';
    const REQUEST_POST                 = 'POST';
    const REQUEST_PUT                  = 'PUT';
    const REQUEST_DELETE               = 'DELETE';
    const REQUEST_FORM                 = 'FORM';
    const REQUEST_TRANPORT_TYPE_NORMAL = 'NORMAL';
    const REQUEST_TRANPORT_TYPE_AJAX   = 'AJAX';

    protected ?string $request        = '';
    protected ?bool   $requestExists  = false;
    protected ?string $requestPure    = '';
    protected string  $requestType    = '';
    protected         $requestFilter  = '';
    protected         $requestOptions = null;
    protected ?string $requestDefault = '';

    /**
     * ContainerFactoryRequest constructor.
     *
     * @param string      $requestType POST / GET / COOKIE /FORM / FILES / REQUEST / SERVER
     * @param string      $key         Key of the reading Request
     * @param string|null $requestDefault
     * @param string|null $filter
     * @param null        $options
     */
    public function __construct(string $requestType, string $key, ?string $requestDefault = null, ?string $filter = null, $options = null)
    {
        $this->requestType    = strtoupper($requestType);
        $this->requestOptions = $options;
        $this->requestDefault = $requestDefault;

        $this->requestFilter = $filter ?? FILTER_SANITIZE_STRING;

        switch ($this->requestType) {
            case self::REQUEST_TYPE_GET:
                $this->request       = (string)($_GET[$key] ?? $this->requestDefault);
                $this->requestExists = isset($_GET[$key]);
                break;
            case self::REQUEST_TYPE_POST:
                $this->request       = (string)($_POST[$key] ?? $this->requestDefault);
                $this->requestExists = isset($_POST[$key]);
                break;
            case self::REQUEST_TYPE_COOKIE:
                $this->request       = (string)($_COOKIE[$key] ?? $this->requestDefault);
                $this->requestExists = isset($_COOKIE[$key]);
                break;
            case 'REQUEST':
                $this->request       = $_REQUEST[$key] ?? $this->requestDefault;
                $this->requestExists = isset($_REQUEST[$key]);
                break;
            case 'SERVER':
                $this->request       = $key ?? $this->requestDefault;
                $this->requestExists = true;
                break;
            default:
                $this->request       = '???';
                $this->requestExists = false;
            //thrown
        }

        $this->requestPure = $this->request;
        if (!is_array($this->request)) {
            $this->request = $this->filter($this->request);
        }
    }

    public function filter(?string $var, ?string $requestFilter = null, $requestOptions = null): string
    {
        return filter_var($var,
            ($requestFilter ?? $this->requestFilter),
            ($requestOptions ?? $this->requestOptions));
    }

    public static function getRequestTransportType(): string
    {
        if (strtolower(($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '')) == 'xmlhttprequest') {
            return self::REQUEST_TRANPORT_TYPE_AJAX;
        }
        else {
            return self::REQUEST_TRANPORT_TYPE_NORMAL;
        }

    }

    public static function getRequestREST(): string
    {
        $method = strtoupper($_SERVER['REQUEST_METHOD'] ?? null);
        switch ($method) {
            case "GET":
                return self::REQUEST_GET;
            case "POST":
                return self::REQUEST_POST;
            case "PUT":
                return self::REQUEST_PUT;
            case "DELETE":
                return self::REQUEST_DELETE;
            default:
                header('HTTP/1.0 501 Not Implemented');
                die();
        }

    }

    public function exists(): bool
    {
        return $this->requestExists;
    }

    public function get(): string
    {
        return $this->request;
    }

    public function getPure()
    {
        return $this->requestPure;
    }

}
