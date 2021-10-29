<?php declare(strict_types=1);

class ContainerExtensionTemplateParseInsertLanguage extends ContainerExtensionTemplateParseInsert_abstract
{

    function parse(): string
    {
        $parameter = $this->getParameter();
        $path      = '/' . $parameter['class'] . $parameter['path'];

        $content = ContainerFactoryLanguage::get($path);

  //        d('/' . $parameter['class'] . $parameter['path']);

        if ($content !== null) {
            return $content;
        }
        else {
            return $this->scanLanguage();
        }

    }

    protected function scanLanguage(): string
    {
        $contentParse = str_replace('insert/language',
                                    '',
                                    $this->getParseString());

        $parameter = $this->getParameter();

        preg_match_all('@(.*?)="(.*?)"@si',
                       $contentParse,
                       $matchLanguage,
                       PREG_SET_ORDER);
        $languageCollect      = [];
        $languageCollectValue = [];
        foreach ($matchLanguage as $item) {
            $key   = trim($item[1]);
            $value = trim($item[2]);

            if (
                strpos($key,
                       'language') === false
            ) {
                $languageCollect[$key] = $value;
            }
            else {
                $keyLanguage                           = explode('-',
                                                                 $key,
                                                                 2);
                $languageCollectValue[$keyLanguage[1]] = $value;
            }

        }

        $content = '';

        if (isset($languageCollect['class']) && isset($languageCollect['path']) && isset($languageCollect['class'])) {
            foreach ($languageCollectValue as $languageCollectKey => $languageCollectValueItem) {

//                d(ContainerFactoryLanguage::getAll());

                if ($languageCollectKey === Config::get('/environment/language')) {
                    $content = $languageCollectValueItem;
                    ContainerFactoryLanguage::set('/' . $parameter['class'] . $parameter['path'],
                                                  $content);
                }

                /** @var ContainerFactoryLanguage_crud $crud */
                $crud = Container::get("ContainerFactoryLanguage_crud");
                $crud->setCrudClass($parameter['class']);
                $crud->setCrudLanguageLanguage($languageCollectKey);
                $crud->setCrudLanguageKey($languageCollect['path']);
                $crud->setCrudLanguageValue($languageCollectValueItem);
                $crud->setCrudLanguageValueDefault($languageCollectValueItem);
                $crud->insertUpdate();

//                d($crud->getCrudId());
//                d($languageCollectValueItem);
//                d(ContainerFactoryLanguage::getAll());
//
//                eol();

            }
        }

        return $content;
    }

}
