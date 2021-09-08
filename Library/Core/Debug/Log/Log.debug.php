<?php

class CoreDebugLog_debug extends CoreDebug_abstract
{
    private int $countLog   = 0;
    private int $countNote  = 0;
    private int $countWarn  = 0;
    private int $countError = 0;

    protected function prepare(): void
    {
        foreach ($this->data as $elem) {
            if ($elem['type'] === CoreDebugLog::LOG_TYPE_LOG) {
                $this->countLog++;
            }
            elseif ($elem['type'] === CoreDebugLog::LOG_TYPE_NOTE) {
                $this->countNote++;
            }
            elseif ($elem['type'] === CoreDebugLog::LOG_TYPE_WARN) {
                $this->countWarn++;
            }
            elseif ($elem['type'] === CoreDebugLog::LOG_TYPE_ERROR) {
                $this->countError++;
            }
        }
    }

    public function getHtml(): string
    {
        $tableTcs = [];

        /** @var ContainerExtensionTemplateLoad_cache_template $templateCache */
        $templateCache = Container::get('ContainerExtensionTemplateLoad_cache_template',
                                        Core::getRootClass(__CLASS__),
                                        'debug');

        /** @var ContainerExtensionTemplate $template */
        $template = Container::get('ContainerExtensionTemplate');
        $template->set($templateCache->getCacheContent()['debug']);

        foreach ($this->data as $elem) {
            $tableTcs[] = [
                'icon'      => $elem['icon'],
                'type'      => $elem['type'],
                'ident'     => $elem['ident'],
                'text'      => $elem['text'],
                'microtime' => ContainerHelperCalculate::calculateMicroTimeDisplay($elem['microtime']),
                'file'      => ($elem['backtrace'][0]['file'] ?? '??'),
                'line'      => ($elem['backtrace'][0]['line'] ?? '??'),
            ];
        }

        $template->assign('TableData_TableData',
                          $tableTcs);

        $template->parseQuote();

        return $template->get();
    }

    public function getTitle(): string
    {
        /** @var ContainerExtensionTemplate $template */
        $template = Container::get('ContainerExtensionTemplate');
        $template->set(ContainerFactoryLanguage::get('/CoreDebugLog/template/header'));
        $template->assign('count',
            ($this->countLog + $this->countNote + $this->countWarn + $this->countError));
        $template->assign('countLog',
                          $this->countLog);
        $template->assign('countInfo',
                          $this->countNote);
        $template->assign('countWarn',
                          $this->countWarn);
        $template->assign('countError',
                          $this->countError);
        $template->parse();
        return $template->get();
    }

}
