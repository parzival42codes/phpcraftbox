<?php
declare(strict_types=1);

class ApplicationUserEmailcheck_app extends ApplicationAdministration_abstract
{

    public function setContent(): string
    {
        /** @var ContainerFactoryRouter $router */
        $router = Container::getInstance('ContainerFactoryRouter');

        /** @var ApplicationUserEmailcheck $emailCheck */
        $emailCheck = Container::get('ApplicationUserEmailcheck',
                                     $router->getParameter('id'));

        if ($emailCheck->verify() === true) {
            $result = $emailCheck->action();
            if ($result === true) {
                $emailCheck->remove();
            }

        }
            return '';

    }
}
