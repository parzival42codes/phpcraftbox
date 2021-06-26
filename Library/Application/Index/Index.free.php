<?php

class ApplicationIndex_free extends Base
{

    public function __construct()
    {
        /** @var ContainerFactoryRouter $router */
        $router = Container::getInstance('ContainerFactoryRouter');

        if ($router->getRoute() === 'favicon') {
            $this->favicon();
        }
    }

    protected function favicon(): void
    {
        header("Content-type: image/png");
        /** @var ContainerIndexPage_cache_favicon $favIconObj */
        $favIconObj = Container::get('ContainerIndexPage_cache_favicon');
        $favIcon    = $favIconObj->getCacheContent();

        $favIconDecode = base64_decode($favIcon);

        if (!empty($favIconDecode)) {
            $favIconImg = imagecreatefromstring($favIconDecode);

            if ($favIconImg !== false) {

                imagesavealpha($favIconImg,
                               true);
                $color = imagecolorallocatealpha($favIconImg,
                                                 0,
                                                 0,
                                                 0,
                                                 127);

//            $color = imagecolorallocate($favIconImg,
//                                        0,
//                                        0,
//                                        0);
                if ($color !== false) {

                    imagestring($favIconImg,
                                1,
                                1,
                                8,
                                '1234',
                                $color);

                    $imgPath = CMS_PATH_STORAGE_CACHE . '/favicon.png';

                    imagepng($favIconImg,
                             null,
                             9,
                             PNG_ALL_FILTERS);

                }
            }
        }


    }
}
