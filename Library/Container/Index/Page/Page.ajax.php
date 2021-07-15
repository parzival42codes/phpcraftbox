<?php

class ContainerIndexPage_ajax extends ContainerExtensionAjax_abstract
{

    protected array $postData = [
        'value'
    ];

    public function execute(): void
    {
         $cookie = new ContainerFactoryHeaderCookie();
        $cookie->setName('cookiebanner');
        $cookie->setValue($this->data['value']);

        /** @var ContainerFactoryHeader $header */
        $this->header->setCookie($cookie);
    }

}
