<?php

class CoreErrorhandler_debug extends CoreDebug_abstract
{

    public function getData(): array
    {
        return $this->data;
    }

    public function getHtml(): string
    {
        /** @var ContainerExtensionTemplateLoad_cache_template $templateCache */
        $templateCache        = Container::get('ContainerExtensionTemplateLoad_cache_template',
                                               Core::getRootClass(__CLASS__),
                                               'debug');
        $templateCacheContent = $templateCache->getCacheContent();

        $errorCounter = [];

        $tableTcs = [];
        foreach ($this->data as $elem) {

            $dialog         = uniqid();
            $templateDialog = Container::get('ContainerExtensionTemplateParseHelperDialog',
                                             $dialog);
            $templateDialog->setHeader('Backtrace');
            $templateDialog->setBody(ContainerHelperView::convertBacktraceView($elem['backtrace']));
            $templateDialog->setFooter();

            $tableTcsData = [];

            $tableTcsLevel = ($elem['level'] ?? '?');

            $tableTcsData['messageInfo'] = '';
            $tableTcsData['file']        = \ContainerFactoryFile::getReducedFilename($elem['file']);
            $tableTcsData['line']        = ($elem['line'] ?? '?');
            $tableTcsData['level']       = $tableTcsLevel;
            $tableTcsData['backtrace']   = $templateDialog->create('Backtrace');

            $tableTcsDataDetails = [];

            if (isset($elem['details']) === true && is_array($elem['details'])) {
                foreach ($elem['details'] as $elemKey => $elemDetails) {
                    $tableTcsDataDetails[] = $elemDetails;
                }
            }

            $tableTcsData['details'] = implode('<hr />',
                                               $tableTcsDataDetails);
            unset($tableTcsDataDetails);


            if ($elem['level'] === 1024) {

                $triggerData             = explode(' :: ',
                                                   $elem['message']);
                $triggerDataClassTrigger = explode('::',
                                                   $triggerData[0]);

                $need = 'Need: /trigger/' . $triggerData[1] . '/' . $triggerDataClassTrigger[1] . ' # ' . var_export($triggerData[2],
                                                                                                                     true);

                $tableTcsData['message'] = \ContainerFactoryLanguage::get('/' . $triggerDataClassTrigger[0] . '/',
                                                                          $need);
            }
            elseif ($elem['level'] === 8) {
                if (
                    strpos($elem['message'],
                           'Undefined variable') !== false
                ) {
                    $tableTcsData['message'] = $this->Level8UndefinedVariable($elem['message']);
                }
                else {
                    $tableTcsData['message'] = $elem['message'];
                }
            }
            else {

                if (isset($elem['message']['method'])) {
                    $tableTcsData['messageInfo'] = $elem['message']['method'] . ((isset($elem['message']['key']) ? ' / ' . $elem['message']['key'] : ''));
                }

                $tableTcsData['message'] = '<pre>' . htmlspecialchars(var_export($elem['message'],
                                                                                 true)) . '</pre>';
            }

            if ($tableTcsData['messageInfo'] !== '') {
                if (strlen($tableTcsData['message']) < 200) {
                    $tableTcsData['message'] = '<details><summary>' . $tableTcsData['message'] . '</summary>' . $tableTcsData['messageInfo'] . '</details>';
                }
                else {
                    $tableTcsData['message'] = '<details><summary> - Message too big - klick to view - </summary>' . $tableTcsData['message'] . '</details>';
                }
            }

            if (isset($elem['message']['data']) && is_array($elem['message']['data'])) {
                foreach ($elem['message']['data'] as $dataReplaceKey => $dataReplaceValue) {
                    $tableTcsData['message'] = str_replace('{$' . $dataReplaceKey . '}',
                                                           $dataReplaceValue,
                                                           $tableTcsData['message']);
                }
            }

            $tableTcs[] = $tableTcsData;
        }

        /** @var ContainerExtensionTemplate $template */
        $template = Container::get('ContainerExtensionTemplate');
        $template->set($templateCacheContent['debug']);
        $template->assign('DebugTableErrorhandler_DebugTableErrorhandler',
                          $tableTcs);

        $template->parseQuote();
        $template->parse();
        $template->catchDataClear();
        return $template->get();

    }

    protected function Level8UndefinedVariable(string $notice): string
    {
        $noticeData = explode(':',
                              $notice);

        $errorPath = '/trigger/level/8/UndefinedVariable';

        // simpleDebugDump('/CoreErrorhandler/' . $errorPath,
        //                  $notice);
        // eol();

        return \ContainerFactoryLanguage::get('/CoreErrorhandler' . $errorPath,
                                              $notice) . trim($noticeData[1]);
    }

    public function getTitle(): string
    {

        $errorCounter = [];
        foreach ($this->data as $elem) {
            if (isset($errorCounter[$elem['level']])) {
                $errorCounter[$elem['level']]++;
            }
            else {
                $errorCounter[$elem['level']] = 1;
            }
        }

        $errorCounterView = [];
        foreach ($errorCounter as $errorCounterKey => $errorCounterCount) {
            $errorCounterView[] = \ContainerFactoryLanguage::get('/CoreErrorhandler/template/errorLevel/' . $errorCounterKey) . ': ' . $errorCounterCount;
        }

        /** @var ContainerExtensionTemplate $template */
        $template = Container::get('ContainerExtensionTemplate');
        $template->set(\ContainerFactoryLanguage::get('/CoreErrorhandler/template/header'));
        $template->assign('errorCount',
                          implode(' ',
                                  $errorCounterView));
        $template->parse();
        return $template->get();
    }

}
