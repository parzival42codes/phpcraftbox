<?php

class ContainerIndexTab_event extends Event_abstract
{
    public static function insertJs(string $class, string $method, object $object): void
    {
        /** @var ContainerIndexPage $page */
        $page = Container::getInstance('ContainerIndexPage');
        $page->addPageJavascript(ContainerIndexTab::getCollect());
    }
}
