<?php

class ContainerIndexPage_ajax extends ContainerExtensionAjax_abstract
{

    public function execute(): void
    {
        $cookie = new ContainerFactoryHeaderCookie();
        $cookie->setName('cookiebanner');
        $cookie->setValue('1');

        /** @var ContainerFactoryHeader $header */
        $header = Container::getInstance('ContainerFactoryHeader');
        $header->setCookie($cookie);
    }

}
