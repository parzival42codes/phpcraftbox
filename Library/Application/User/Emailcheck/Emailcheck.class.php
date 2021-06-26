<?php
declare(strict_types=1);

/**
 * User Email Check
 *
 * User Email Check
 *
 * @author   Stefan Schlombs
 * @version  1.0.0
 * @modul    groupAccess 1,2,3,4
 * @modul    versionRequiredSystem 1.0.0
 * @modul    language_name_de_DE Email Check
 * @modul    language_name_en_US Email Check
 * @modul    language_path_de_DE /Benutzer
 * @modul    language_path_en_US /User
 *
 */
class ApplicationUserEmailcheck extends Base
{

    private ApplicationUserEmailcheck_crud $crud;
    private ?string                        $uuid;

    public function __construct(?string $mailCheckKey = null)
    {
        /** @var ApplicationUserEmailcheck_crud $crud */
        $crud       = Container::get('ApplicationUserEmailcheck_crud');
        $this->crud = $crud;

        if ($mailCheckKey !== null) {
            $this->uuid = $mailCheckKey;
            $this->crud->setCrudId($mailCheckKey);
            $this->crud->findById();
        }

    }


    public function create(string $emailAddress, string $subject, string $content, string $class, string $method, array $parameter = []): void
    {

        $this->crud->setCrudId($this->getUuid());
        $this->crud->setCrudClass($class);
        $this->crud->setCrudMethod($method);
        $this->crud->setCrudParameter(serialize($parameter));
        $this->crud->insert();

        /** @var ContainerFactoryMail $mailer */
        $mailer = Container::get('ContainerFactoryMail');

        $mailer->addAddress($emailAddress);
        $mailer->setSubject($subject);

        /** @var ContainerFactoryRouter $routerLinkRegistermail */
        $routerLinkRegistermail = Container::get('ContainerFactoryRouter');
        $routerLinkRegistermail->setApplication('ApplicationUserEmailcheck');
        $routerLinkRegistermail->setRoute('default');
        $routerLinkRegistermail->setParameter('id',
                                              $this->getUuid());

//        eol();

        $content = strtr($content,
                         [
                             '[[LINK]]' => $routerLinkRegistermail->getUrlReadable(),
                         ]);

        $mailer->setBody($content);

        $mailer->send();

    }

    public function verify(): bool
    {
        return ($this->crud->getDataVariableCreated() !== null);
    }


    public function remove(): void
    {
        $this->crud->delete();
    }

    public function action(): bool
    {
        return call_user_func([
                                  $this->crud->getCrudClass(),
                                  $this->crud->getCrudMethod()
                              ],
                              unserialize($this->crud->getCrudParameter()));
    }

    /**
     * @return ApplicationUserEmailcheck_crud
     */
    public function getCrud(): ApplicationUserEmailcheck_crud
    {
        return $this->crud;
    }

    /**
     * @return string
     */
    public function getUuid(): string
    {
        if ($this->uuid === null) {
            /** @var ContainerFactoryUuid $uuid */
            $uuid       = Container::get('ContainerFactoryUuid');
            $this->uuid = $uuid->create();
        }
        return $this->uuid;
    }

    /**
     * @param string $uuid
     */
    public function setUuid(string $uuid): void
    {
        $this->uuid = $uuid;
    }

}
