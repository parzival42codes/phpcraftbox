<?php

class ContainerExternResourcesCss_cache_css extends ContainerExtensionCache_abstract
{
    protected bool $gzip = false;
    protected string $styleSelected='';

       public function prepare(): void
    {
        if ($this->parameter[0] === '_gzip') {
                $this->gzip     = true;
        } else {
                $this->gzip     = false;
        }

        $this->styleSelected = ($this->parameter[1] ?? 'default');

        $this->ident = __CLASS__ . '/' . $this->gzip . '/' . $this->styleSelected;
    }

    public function create(): void
    {
      $resourcesCSS = [
            1 => '',
            2 => '',
            3 => '',
            4 => '',
            5 => '',
        ];

        $resourcesCSS[1] .= '#Style {@CMS-PATH-HTTP: "' . \Config::get('/server/http/base/url') . '";}';
        $resourcesCSS[1] .= '#Style {@CMS-GZIP: "' . (($this->gzip === true) ? 'gzip_' : '') . '";}';

$resourcesCSSObj = Container::get('ContainerExternResourcesCss_cache_init',  $this->styleSelected);
          $resourcesCSS[1] .= $resourcesCSSObj->get();

          /** @var ContainerFactoryDatabaseQuery $query */
        $query = Container::get('ContainerFactoryDatabaseQuery',
                                 __METHOD__ . '#select',
                                 true,
                                 ContainerFactoryDatabaseQuery::MODE_SELECT);
        $query->setTable('index_module');
        $query->select('crudModul');
        $query->select('crudHasCssFiles');
        $query->setParameterWhere('crudHasCss',
                                  '1');

        $query->construct();
        $smtp = $query->execute();
        /** @var ePDOStatement $smtp */

            $cssLoadList = '';

        while ($smtpData = $smtp->fetch()) {

            $cssFiles = [
                ''
            ];

            if(!empty($smtpData['crudHasCssFiles'])) {
                $cssFiles = explode('|', $smtpData['crudHasCssFiles']);
            }

            foreach($cssFiles as $cssFile) {

            $cssFileData = explode(';',$cssFile);

            $cssFileName = (empty($cssFileData[0]) ? 'style': 'style_'.$cssFileData[0]).'_css';

                $fileLoadName = $smtpData['crudModul'].'_'.$cssFileName;
                $fileLoadNamePath = Core::getClassFileName($fileLoadName);

                    /** @var ContainerFactoryFile $file */
                        $file = Container::get('ContainerFactoryFile',$fileLoadName);

                        if ($file->exists() === true) {

                            $cssLoadList .='/*' .$fileLoadName.' ('.$fileLoadNamePath. ') */' . PHP_EOL;

                            $file->load();
                            $fileLoad = $file->get();

                            $fileLoad =str_replace('thisClass',$smtpData['crudModul'], $fileLoad);

                           $fileLoad =  preg_replace_callback("@url\((.*?)\)@si",['SELF','doURLReplace'],$fileLoad);

                            $resourcesCSS[($cssFileData[1] ?? 3)] .= $fileLoad;
                        } else {
                                $cssLoadList .='/* NOT FOUND: '. $fileLoadName.' ('.$fileLoadNamePath. ') */' . PHP_EOL;
                        }
                }
        }

        $styleNameSelected = ucfirst(\Config::get('/Style/selected'));
        $styleName = 'Style'.$styleNameSelected.'_style_css';

        /** @var ContainerFactoryFile $file */
        $file = Container::get('ContainerFactoryFile', $styleName);
        $file->load();

//        d($file->exists());
//        d($file->get());
//        eol();

        $resourcesCSS[5] .= $file->get();

        /** @var ContainerFactoryExtensionCss $css */
        $css = Container::get('ContainerFactoryExtensionCss');

        $css->setCSS(implode('',$resourcesCSS));
        $css->parse();

        $this->setCacheContent([
            'content'    =>
                $cssLoadList
                . $css->getCss(),
                'contentAboveTheFold' => $css->getCssAboveTheFold(),
        ]);

    }

    protected function doURLReplace(array $a) :string{
        $extSearch = explode('_',$a[1]);

        /** @var ContainerFactoryRouter $router */
        $router = Container::getInstance('ContainerFactoryRouter');
        $router->setRoute('other');
        $router->setApplication(\Core::getParentClass(__CLASS__));
        $router->setParameter('hash', md5($a[1]));
        $router->setParameter('ext', end($extSearch));

        return 'url("'.$router->getUrlReadable().'")';

    }
}

