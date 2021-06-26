<?php declare(strict_types=1);

class ContainerExtensionTemplateTagTable extends Base
{
    protected static array $container = [];

    public static function setFunction(ContainerExtensionTemplate $template): string
    {
        $template->setRegisteredFunctions('_table',
            function ($content, $htmlTags, $templateObject) {

                /** @var ContainerExtensionTemplate $template */
                $template = Container::get('ContainerExtensionTemplate');
                $template->set($content);
                $template->parseQuote();

                $contentJson = json_decode($template->get(),
                                           true);

                if ($contentJson === null) {

                    d($template->get());
                    eol();

                    throw new DetailedException('jsonError',
                                                0,
                                                null,
                                                [
                                                    'debug' => [
                                                        'error'    => json_last_error_msg(),
                                                        'template' => htmlentities($template->get()),
                                                    ]
                                                ]);
                }

                $assignObject  = $templateObject->getAssignObject();
                $iniDataConfig = ($contentJson['_config'] ?? []);
                unset($contentJson['_config']);

                /** @var ContainerExtensionTemplateTagTableTable $table */
                $table = Container::get('ContainerExtensionTemplateTagTableTable',
                                        $htmlTags,
                                        $assignObject,
                                        $iniDataConfig['table']);

                foreach ($contentJson as $contentJsonKey => $contentJsonItem) {
                    $table->setConfigHeader($contentJsonKey,
                        ($contentJsonItem ?? []));
                    $table->setConfigRow($contentJsonKey,
                        ($contentJsonItem ?? []));
                }

                if (!empty($iniDataConfig['uniqid'])) {
                    $table->setUniqid($iniDataConfig['uniqid']);
                }

                $table->setTableClass($iniDataConfig['cssClass'] ?? '');

                $table->setData($assignObject->get($iniDataConfig['table'] . '_' . $iniDataConfig['source']) ?? []);

                return $table->get();

            });

        return '';
    }

}

