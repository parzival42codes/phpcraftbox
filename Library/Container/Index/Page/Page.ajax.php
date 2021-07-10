<?php

class ContainerIndexPage_ajax extends ContainerExtensionAjax_abstract
{

    protected array $postData = [];

    public function execute(): void
    {
        $cookie = new ContainerFactoryHeaderCookie();
        $cookie->setName('cookiebanner');
        $cookie->setValue('1');

        /** @var ContainerFactoryHeader $header */
        $this->header->setCookie($cookie);
    }

}
