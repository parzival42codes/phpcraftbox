<?php

define('CMS_CACHE_MAIN', 'modul');
define('CMS_LIBRARY_IDENT', 'modul');

require (dirname(__FILE__) . '/Core/config.inc.php');

 require (CMS_PATH_LIBRARY_CORE . DIRECTORY_SEPARATOR . 'Modul' . DIRECTORY_SEPARATOR. 'Work' . DIRECTORY_SEPARATOR. 'Work.class.php');

\CoreModulWork::doAction();