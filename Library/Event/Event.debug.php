<?php

class Event_debug extends CoreDebug_abstract
{

    protected static array $countedCache = [];
    protected array        $tableTcs     = [];

    public function getData(): array
    {
        return $this->data;
    }

    public function getHtml(): string
    {
        $this->getEventAttach();

        /** @var ContainerExtensionTemplateLoad_cache_template $templateCache */
        $templateCache        = Container::get('ContainerExtensionTemplateLoad_cache_template',
                                               Core::getRootClass(__CLASS__),
                                               'debug');
        $templateCacheContent = $templateCache->getCacheContent();

        /** @var ContainerExtensionTemplate $template */
        $template = Container::get('ContainerExtensionTemplate');
        $template->set($templateCacheContent['debug']);
        $template->assign('EventDebugTable_EventDebugTable',
                          $this->tableTcs);

        $template->parseQuote();
        $template->parse();

        $template->parse();
        $template->catchDataClear();

//        eol();
        return $template->get();

    }

    protected function getEventAttach(): void
    {
        $indexTrigger = [];

        if (isset($this->data['trigger']) === true && is_array($this->data['trigger']) === true) {
            foreach ($this->data['trigger'] as $trigger) {
                if (!empty($indexTrigger[$trigger['path']])) {
                    $indexTrigger[$trigger['path']]++;
                }
                else {
                    $indexTrigger[$trigger['path']] = 1;
                }

            }

            foreach ($indexTrigger as $indexTriggerKey => $indexTriggerData) {
                $this->tableTcs[$indexTriggerKey] = [
                    'path'  => $indexTriggerKey,
                    'count' => $indexTriggerData,
                ];
            }
        }


    }

    public function getTitle(): string
    {
        $counted = $this->getCounter();

        /** @var ContainerExtensionTemplate $template */
        $template = Container::get('ContainerExtensionTemplate');

        $template->set('Event: {$count}');
        $template->assignArray([
                                   'count' => count($counted),
                               ]);
        $template->parse();
        return $template->get();

    }

    protected function getCounter(): array
    {

        if (count(self::$countedCache) === 0) {

            $triggerCount       = 0;
            $triggerAttach      = [];
            $triggerAttachAll   = [];
            $triggerAttachEmpty = [];

            $attachData = [];

            if (isset($this->data['attach']) && is_array($this->data['attach'])) {
                foreach ($this->data['attach'] as $attach) {
                    if (isset($attach['EventModul']) && $attach['EventModul'] !== '_') {
                        $triggerAttach[$attach['EventModul']][$attach['EventTrigger']] = 0;
                        $attachData[$attach['EventModul']][$attach['EventTrigger']][]  = $attach['Modul'] . '::' . $attach['EventCall'];
                    }
                    else {
                        $triggerAttachAll[($attach['EventTrigger'] ?? '')] = 0;
                    }
                }
            }

            if (isset($this->data['trigger']) === true && is_array($this->data['trigger']) === true) {

                foreach ($this->data['trigger'] as $triggerItem) {
                    if (isset($triggerItem['trigger'])) {
                        if (isset($triggerAttachAll[$triggerItem['trigger']])) {
                            ++$triggerAttachAll[$triggerItem['trigger']];
                            ++$triggerCount;
                        }
                        elseif (isset($triggerAttach[$triggerItem['class']][$triggerItem['trigger']])) {
                            ++$triggerAttach[$triggerItem['class']][$triggerItem['trigger']];
                            ++$triggerCount;
                        }
                        else {
                            if (isset($triggerAttachEmpty[$triggerItem['class']][$triggerItem['trigger']])) {
                                ++$triggerAttachEmpty[$triggerItem['class']][$triggerItem['trigger']];
                                ++$triggerCount;
                            }
                            else {
                                $triggerAttachEmpty[$triggerItem['class']][$triggerItem['trigger']] = 0;
                            }
                        }
                    }
                }
            }

            self::$countedCache = [
                'triggerCount'       => $triggerCount,
                'triggerAttachAll'   => $triggerAttachAll,
                'triggerAttach'      => $triggerAttach,
                'triggerAttachEmpty' => $triggerAttachEmpty,
                'triggerNotfound'    => ($this->data['triggerNotfound'] ?? 0),
            ];

        }

        return self::$countedCache;

    }

}

