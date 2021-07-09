<?php

abstract class ContainerExtensionAjax_abstract extends Base
{
    protected array                  $postData = [];
    protected ContainerFactoryHeader $header;

    protected string $language = '';
    protected string $class    = '';
    protected array  $data     = [];
    protected array  $trigger  = [];
    private array    $output
                               = [
            'error'       => [],
            'errorSystem' => [],
            'content'     => null,
            'meta'        => [],
            'debug'       => [],
            'trigger'     => [],
        ];

    public function __construct()
    {
        $this->header = new ContainerFactoryHeader();

        try {

            foreach ($this->postData as $postData) {
                $request               = new ContainerFactoryRequest(ContainerFactoryRequest::REQUEST_TYPE_POST,
                                                                     $postData);
                $this->data[$postData] = $request->get();
            }

            $this->execute();

            /** @var ContainerFactoryHeader $header */
            $header = Container::getInstance('ContainerFactoryHeader');
            $header->send();

        } catch (Throwable $e) {
            simpleDebugDump($e);
            simpleDebugDump($e->getTrace());
        }

        $this->header->set('content-type','application/json; charset=utf-8');
        $this->header->send();

//
//        $exceptionCatch = Container::get('ContainerFactoryExceptioncatch',
//            function () {
//                $this->execute();
//            });
//
//        if ($exceptionCatch->hasException()) {
//
//        }

//        if ($language === false) {
//            $this->language = \Config::get('/environment/config/iso_language_code');
//        } else {
//            $this->language = $language;
//        }
//
//        $this->class = $class;
//
//        /*
//        $dataFormular = $data['formular'];
//
//        if (is_array($dataFormular) && isset($dataFormular[0]['name']) && isset($dataFormular[0]['value'])) {
//            $dataReplace = [];
//            foreach ($dataFormular as $dataValue) {
//
//                if (strpos($dataValue['name'],
//                        '[') !== false) {
//                    if (strpos($dataValue['name'],
//                            '[]') === false) {
//
//                        preg_match('@(.*)\[(.*)\]@si',
//                            $dataValue['name'],
//                            $matches);
//
//                        if (isset($matches[2])) {
//                            $dataReplace[$matches[1]][$matches[2]] = $dataValue['value'];
//                        } else {
//                            $dataReplace[$matches[1]][$dataValue['name']] = $dataValue['value'];
//                        }
//                    } else {
//
//                        preg_match('@(.*)\[(.*)\]\[\]@si',
//                            $dataValue['name'],
//                            $matches);
//
//                        if (isset($matches[2])) {
//
//                            if (!isset($dataReplace[$matches[1]][$matches[2]])) {
//                                $dataReplace[$matches[1]][$matches[2]] = [];
//                            }
//                            $dataReplace[$matches[1]][$matches[2]][] = $dataValue['value'];
//                        } else {
//                            if (!isset($dataValue['name'])) {
//                                $dataValue['name'] = [];
//                            }
//                            $dataReplace[$matches[1]][$dataValue['name']][] = $dataValue['value'];
//                        }
//                    }
//                } else {
//                    $dataReplace[$dataValue['name']] = $dataValue['value'];
//                }
//            }
//
//            $this->data['trigger']  = $data['trigger'];
//            $this->data['formular'] = $dataReplace;
//            unset($dataReplace);
//        } else {
//            $this->data['formular'] = null;
//        }
//
//        */
//
//
//        $this->trigger = [
//            'GET'  => [],
//            'POST' => [],
//        ];
//
//        foreach (array_keys($_GET) as $getItem) {
//            $request                        = Container::get('ContainerFactoryRequest',
//                                                              ContainerFactoryRequest::REQUEST_POST,
//                                                              $getItem);
//            $this->trigger['GET'][$getItem] = $request->get();
//        }
//
//        /** @var ContainerFactoryRequest $request */
//        foreach (array_keys($_POST) as $postItem) {
//            $request                          = Container::get('ContainerFactoryRequest',
//                                                                ContainerFactoryRequest::REQUEST_POST,
//                                                                $postItem);
//            $this->trigger['POST'][$postItem] = $request->get();
//        }
//
//        $this->output['trigger'] = $this->trigger;
//
//        if (\Config::get('/debug/status',CMS_DEBUG_ACTIVE) === true) {
//            $this->output['debug']['data'] = $this->data;
//            $this->output['debug']['dump'] = self::$debug;
//        }
//        self::$debug = [];
//
//        $exceptioncatch = Container::get('ContainerFactoryExceptioncatch',
//            function () {
//                \Event::trigger(__CLASS__,
//                                __FUNCTION__,
//                                'execute',
//                                $this,
//                                $scope);
//                $this->execute();
//            });
//
//        if ($exceptioncatch->hasException()) {
//            $eData = $exceptioncatch->getException();
//
//            Container::getInstance('ContainerFactoryHeader')
//                      ->set('#',
//                            'HTTP/1.1 500 Internal Server Error');
//
//            \CoreErrorhandler::trigger('index',
//                                       'ajax',
//                                       [
//                                           'class'     => get_class($eData),
//                                           'message'   => $eData->getMessage(),
//                                           'parameter' => (method_exists($eData,
//                                                                         'getParameter') ? $eData->getParameter() : ''),
//                                           'file'      => $eData->getFile(),
//                                           'line'      => $eData->getLine(),
//                                           'backtrace' => $eData->getTrace(),
//                                       ]);
//        }
//
//        $this->output['errorSystem'] = \Container::callStatic('CoreDebug',
//                                                              'getRawDebugData',
//                                                              'CoreErrorhandler');
    }

    abstract function execute(): void;

    public function get(): string
    {
        $jsonEncoded = json_encode($this->output);
        if ($jsonEncoded !== false) {
            return $jsonEncoded;
        }
        else {
            return '{}';
        }
    }

    public function getData()
    {
        return $this->data;
    }

    public function getTrigger(): array
    {
        return $this->trigger;
    }


    protected function setContent(string $content): void
    {
        $this->output['content'] = $content;
    }

}
