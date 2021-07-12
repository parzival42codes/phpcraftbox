<?php

class ContainerIndexPageBox_install extends ContainerFactoryModulInstall_abstract
{

    public function install(): void
    {
        $this->importQueryDatabaseFromCrud('ContainerIndexPageBox_crud');

        $this->installFunction(function () {
            /** @var array $data */ /*$before*/

            /** @var ContainerIndexPageBox_crud $crud */
            $crud = Container::get('ContainerIndexPageBox_crud');
            $crud->setCrudClass(Core::getRootClass(__CLASS__));
            $crud->setCrudRow(2);
            $crud->setCrudFlex(1);
            $crud->setCrudPosition(1);
            $crud->setCrudDescription('Logo');
            $crud->setCrudContent('
<div class="card-container card-container--shadow">
<div class="card-container-content">Logo</div>
</div>
');
            $crud->setCrudAssignment('page');
            $crud->setCrudActive(true);

            $progressData['message'] = $crud->insert();

            /*$after*/
        });

        $this->installFunction(function () {
            /** @var array $data */ /*$before*/

            /** @var ContainerIndexPageBox_crud $crud */
            $crud = Container::get('ContainerIndexPageBox_crud');
            $crud->setCrudClass(Core::getRootClass(__CLASS__));
            $crud->setCrudRow(2);
            $crud->setCrudFlex(2);
            $crud->setCrudPosition(2);
            $crud->setCrudDescription('Foo Bar');
            $crud->setCrudContent('
<div class="card-container card-container--shadow">
<div class="card-container-content">Foo Bar</div>
</div>
');
            $crud->setCrudAssignment('page');
            $crud->setCrudActive(true);

            $progressData['message'] = $crud->insert();

            /*$after*/
        });

        $this->installFunction(function () {
            /** @var array $data */ /*$before*/

            /** @var ContainerIndexPageBox_crud $crud */
            $crud = Container::get('ContainerIndexPageBox_crud');
            $crud->setCrudClass(Core::getRootClass(__CLASS__));
            $crud->setCrudRow(2);
            $crud->setCrudFlex(1);
            $crud->setCrudPosition(3);
            $crud->setCrudDescription('User Links');
            $crud->setCrudContent('
<div class="card-container card-container--shadow">
<div class="card-container-content" style="text-align: center;">
{insert/widget class="ApplicationUser" widget="username"} {insert/widget class="ApplicationUser" widget="link"}
</div>
</div>
');
            $crud->setCrudAssignment('page');
            $crud->setCrudActive(true);

            $progressData['message'] = $crud->insert();

            /*$after*/
        });

        $this->installFunction(function () {
            /** @var array $data */ /*$before*/

            /** @var ContainerIndexPageBox_crud $crud */
            $crud = Container::get('ContainerIndexPageBox_crud');
            $crud->setCrudClass(Core::getRootClass(__CLASS__));
            $crud->setCrudRow(3);
            $crud->setCrudFlex(1);
            $crud->setCrudPosition(1);
            $crud->setCrudDescription('Menu');
            $crud->setCrudContent('
<div class="card-container card-container--shadow">
<div class="card-container-content">
<div id="Application_app_menu">
                                      {$applicationContentLeft}
                                      </div>
                                      </div></div>');
            $crud->setCrudAssignment('page');
            $crud->setCrudActive(true);

            $progressData['message'] = $crud->insert();

            /*$after*/
        });

        $this->installFunction(function () {
            /** @var array $data */ /*$before*/

            /** @var ContainerIndexPageBox_crud $crud */
            $crud = Container::get('ContainerIndexPageBox_crud');
            $crud->setCrudClass(Core::getRootClass(__CLASS__));
            $crud->setCrudRow(3);
            $crud->setCrudFlex(5);
            $crud->setCrudPosition(1);
            $crud->setCrudDescription('Main');
            $crud->setCrudContent('<div id="CMSMainContent">
                                      {$breadcrumb}
                                      {$notification}
                                      <main>
                                      {insert/positions position="/page/box/main/header"}
                                      {$applicationContent}
                                      {insert/positions position="/page/box/main/footer"}
                                      </main>
                                      </div>');
            $crud->setCrudAssignment('page');
            $crud->setCrudActive(true);

            $progressData['message'] = $crud->insert();

            /*$after*/
        });


        $this->installFunction(function () {
            /** @var array $data */ /*$before*/

            /** @var ContainerIndexPageBox_crud $crud */
            $crud = Container::get('ContainerIndexPageBox_crud');
            $crud->setCrudClass(Core::getRootClass(__CLASS__));
            $crud->setCrudRow(4);
            $crud->setCrudFlex(1);
            $crud->setCrudPosition(1);
            $crud->setCrudDescription('Main');
            $crud->setCrudContent('<div id="CMSFooter">
<div class="card-container card-container--shadow">
<div class="card-container-content" style="display: flex;">
<div style="flex: 1;">
{insert/positions position="/Content/Footer/Left"}
</div>
<div style="flex: 1;">
{insert/positions position="/Content/Footer/Middle"}
</div>
<div style="flex: 1;">
{insert/positions position="/Content/Footer/Right"}
</div>
</div>
</div>
</div>');
            $crud->setCrudAssignment('page');
            $crud->setCrudActive(true);

            $progressData['message'] = $crud->insert();

            /*$after*/
        });

    }

}
