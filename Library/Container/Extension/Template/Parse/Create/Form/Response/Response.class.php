<?php declare(strict_types=1);

class ContainerExtensionTemplateParseCreateFormResponse extends Base
{
    protected       $formId              = '';
    protected bool  $hasResponse         = false;
    protected array $response            = [];
    protected array $responseFile        = [];
    protected array $responseError       = [];
    protected       $responseRequestData = [];
    protected       $metaData            = [];

    public function __construct(string $formId)
    {
        $this->formId = $formId;

        /** @var ContainerFactoryRequest $requestIdent */
        $requestIdent = Container::get('ContainerFactoryRequest',
                                       ContainerFactoryRequest::REQUEST_POST,
                                       '_ident');

        /** @var ContainerFactoryRequest $requestModify */
        $requestModify = Container::get('ContainerFactoryRequest',
                                        ContainerFactoryRequest::REQUEST_POST,
                                        '_modify');

        /** @var ContainerFactoryRequest $requestForm */
        $requestForm = Container::get('ContainerFactoryRequest',
                                      ContainerFactoryRequest::REQUEST_GET,
                                      '_form');

        if ($requestIdent->exists() && $requestIdent->get() === $formId) {

            $uniqid = uniqid();
            /** @var ContainerExtensionTemplateParseCreateFormResponse_crud $crud */
            $crud = Container::get('ContainerExtensionTemplateParseCreateFormResponse_crud');
            $crud->setCrudUniqid($uniqid);

            $formPostData = [
                'post'  => $_POST,
                'files' => $_FILES,
            ];

            $crud->setCrudData(serialize($formPostData));
            $crud->setCrudModify(($requestModify->get() ?? ''));
            $crud->insert();

            /** @var ContainerFactoryRouter $router */
            $router = clone Container::getInstance('ContainerFactoryRouter');

            $router->setQuery('_form',
                              $uniqid);

            $router->setQuery('_notification',
                              null);

            $router->redirect();
        }
        elseif ($requestForm->exists()) {

            /** @var ContainerExtensionTemplateParseCreateFormResponse_crud $crud */
            $crud = Container::get('ContainerExtensionTemplateParseCreateFormResponse_crud');
            $crud->setCrudUniqid($requestForm->get());
            $crud->findById();

            if ($crud->getCrudData() !== null) {
                $crudData = unserialize($crud->getCrudData());

                $crudDataIdent = ($crudData['post']['_ident'] ?? null);

                if ($crudDataIdent !== $this->formId) {
                    $this->hasResponse = false;
                    return;
                }

                $this->formId = $crudDataIdent;

                /** @var ContainerFactoryCrypt $crypt */
                $crypt = Container::get('ContainerFactoryCrypt');
                $crypt->setText($crud->getCrudModify());
                $crypt->setKey((string)Config::get('/environment/secret/form'));

                $modifyMetaData = unserialize($crypt->getDeCrypt());

                CoreDebug::setRawDebugData(__CLASS__,
                                           [
                                               'formId'         => $formId,
                                               'crudData'       => var_export($crudData,
                                                                              true),
                                               'modifyMetaData' => var_export($modifyMetaData,
                                                                              true),
                                               'unique'         => $crud->getCrudUniqid(),
                                           ]);

                try {

                    $this->responseError = [];
                    foreach ($crudData['post'] as $postKey => $postValue) {
                        $this->response[$postKey] = $postValue;

                        if (isset($modifyMetaData['modify'][$postKey])) {

                            if (is_array($modifyMetaData['modify'][$postKey])) {

                                foreach ($modifyMetaData['modify'][$postKey] as $modifierItem) {

                                    if (is_callable($modifierItem)) {
                                        call_user_func($modifierItem,
                                                       $this,
                                                       null,
                                                       null,
                                                       $postKey,
                                                       $postValue);
                                    }
                                    elseif (is_array($modifierItem)) {
                                        if (class_exists($modifierItem[0])) {
                                            /** @var ContainerExtensionTemplateParseCreateFormModify_abstract $classObject */
                                            $classObject = Container::get($modifierItem[0],
                                                                          $this,
                                                                          null,
                                                                          $modifierItem[1],
                                                                          $postKey,
                                                                          $postValue);
                                            $classObject->modify();
                                        }
                                        elseif (is_callable($modifierItem[0])) {
                                            call_user_func($modifierItem[0],
                                                           $this,
                                                           null,
                                                           $modifierItem[1],
                                                           $postKey,
                                                           $postValue);
                                        }

                                    }
                                    else {
                                        if (!empty($modifierItem)) {
                                            /** @var ContainerExtensionTemplateParseCreateFormModify_abstract $validatorObject */
                                            $validatorObject = Container::get($modifierItem,
                                                                              $this,
                                                                              null,
                                                                              null,
                                                                              $postKey,
                                                                              $postValue);
                                            $validatorObject->modify();
                                        }
                                    }

                                }
                            }
                        }
                    }
                } catch (Throwable $e) {
                    d($e);
                    eol();
                }

                foreach ($crudData['files'] as $postKey => $postValue) {
                    $this->responseFile[$postKey] = $postValue;
                    $this->response[$postKey]     = $postValue['name'];
                }

                $this->hasResponse         = true;
                $this->metaData            = $modifyMetaData['metaData'];
                $this->responseRequestData = $modifyMetaData['requestData'];

                /** @var ContainerExtensionTemplateParseCreateFormResponse_crud $crud */
                $crud = Container::get('ContainerExtensionTemplateParseCreateFormResponse_crud');
                $crud->setCrudUniqid($requestForm->get());
                $crud->findById();
                $crud->delete();
            }
            else {
                $this->hasResponse = false;
            }

        }

    }

    /**
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        if (
        array_key_exists($key,
                         $this->response)
        ) {
            return $this->response[$key];
        }
        else {
            return $default;
        }
    }

    /**
     * @return
     */
    public function getAll()
    {
        return $this->response;
    }

    /**
     * @param array $value
     *
     * @return void
     */
    public function setAll(array $value): void
    {
        $this->response = $value;
    }

    /**
     * @return string
     */
    public function getFile(string $key)
    {
        return ($this->responseFile[$key] ?? null);
    }

    /**
     * @return bool
     */
    public function isHasResponse(): bool
    {
        return $this->hasResponse;
    }

    /**
     * @param string $key
     *
     * @return array
     */
    public function getError(string $key): array
    {
        return ($this->responseError[$key] ?? []);
    }

    /**
     * @param string $key
     * @param string $value
     *
     * @return void
     */
    public function setError(string $key, string $value): void
    {
        $this->responseError[$key][] = $value;
    }

    /**
     * @return bool
     */
    public function hasError(): bool
    {
        return (bool)count($this->responseError);
    }

    /**
     * @return array
     */
    public function getMetaData(): array
    {
        return $this->metaData;
    }

    /**
     * @return array
     */
    public function getResponseRequestData(): array
    {
        return $this->responseRequestData;
    }

}
