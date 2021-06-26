<?php declare(strict_types=1);

class ApplicationUser_widget_username extends ContainerExtensionTemplateParseInsertWidget_abstract
{
    public function get(): string
    {
        /** @var ContainerFactoryUser $user */
        $user = Container::getInstance('ContainerFactoryUser');
        return $user->getUserName();
    }

}
