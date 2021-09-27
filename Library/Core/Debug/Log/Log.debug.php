<?php

class CoreDebugLog_debug extends CoreDebug_abstract
{
    private int $countLog        = 0;
    private int $countNote       = 0;
    private int $countWarn       = 0;
    private int $countError      = 0;
    private int $countDeprecated = 0;

    protected function prepare(): void
    {
        foreach ($this->data as $elem) {
            if ($elem['type'] === CoreDebugLog::LOG_TYPE_LOG) {
                $this->countLog++;
            }
            elseif ($elem['type'] === CoreDebugLog::LOG_TYPE_NOTE) {
                $this->countNote++;
            }
            elseif ($elem['type'] === CoreDebugLog::LOG_TYPE_WARNING) {
                $this->countWarn++;
            }
            elseif ($elem['type'] === CoreDebugLog::LOG_TYPE_ERROR) {
                $this->countError++;
            }
            elseif ($elem['type'] === CoreDebugLog::LOG_TYPE_DEPRECATED) {
                $this->countDeprecated++;
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

            if (!$elem['icon']) {
                switch ($elem['type']) {
                    case CoreDebugLog::LOG_TYPE_LOG:
                        $elem['icon'] = '{insert/function function="googlematerialicons" icon="note" class="icon-big"}';
                        break;
                    case CoreDebugLog::LOG_TYPE_NOTE:
                        $elem['icon'] = '{insert/function function="googlematerialicons" icon="speaker_notes" class="icon-big"}';
                        break;
                    case CoreDebugLog::LOG_TYPE_WARNING:
                        $elem['icon'] = '<span class="colorWarning">{insert/function function="googlematerialicons" icon="warning_amber" class="icon-big"}</span>';
                        break;
                    case CoreDebugLog::LOG_TYPE_ERROR:
                        $elem['icon']
                            = '<span class="colorError">{insert/function function="googlematerialicons" icon="error" class="icon-big"}</span>';
                        break;
                    case CoreDebugLog::LOG_TYPE_EXCEPTION:
                        $elem['icon'] = '<span class="colorException">{insert/function function="googlematerialicons" icon="priority_high" class="icon-big"}</span>';
                        break;
                    case CoreDebugLog::LOG_TYPE_DEPRECATED:
                        $elem['icon'] = '<span class="colorInfo">{insert/function function="googlematerialicons" icon="elderly" class="icon-big"}</span>';
                        break;
                }
            }

            $tableTcs[] = [
                'icon'      => $elem['icon'],
                'type'      => ContainerFactoryLanguage::get('/CoreDebugLog/type/' . $elem['type'],
                                                             ''),
                'ident'     => str_replace(' ',
                                           '&nbsp;',
                                           $elem['ident']),
                'text'      => $elem['text'],
                'microtime' => ContainerHelperCalculate::calculateMicroTimeDisplay($elem['microtime']),
                'file'      => (ContainerFactoryFile::getReducedFilename($elem['backtrace'][2]['file'] ?? '??')),
                'line'      => ($elem['backtrace'][2]['line'] ?? '??'),
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
            ($this->countLog + $this->countNote + $this->countDeprecated + $this->countWarn + $this->countError));
        $template->assign('countLog',
                          $this->countLog);
        $template->assign('countInfo',
                          $this->countNote);
        $template->assign('countDeprecated',
                          $this->countDeprecated);
        $template->assign('countWarn',
                          $this->countWarn);
        $template->assign('countError',
                          $this->countError);
        $template->parse();
        return $template->get();
    }

}
