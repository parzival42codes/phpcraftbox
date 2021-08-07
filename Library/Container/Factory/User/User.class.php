<?php

class ContainerFactoryUser extends Base
{

    protected int    $userId        = 0;
    protected string $userName      = '';
    protected        $userGroupId   = 0;
    protected        $userGroupName = '';
    protected array  $userAccess    = [];

    public function __construct(int $userId = 0)
    {
        $this->userId = $userId;

        $this->userName    = ContainerFactoryLanguage::get('/ContainerFactoryUser/guest/username');
        $this->userGroupId = Config::get('/ContainerFactoryUser/guest/group');

        if ($this->userId > 0) {
            /** @var ContainerFactoryUser_crud $user */
            $user = Container::get('ContainerFactoryUser_crud');
            $user->setCrudId($userId);
            $user->findById(true);
            $this->userName    = $user->getCrudUsername();
            $this->userGroupId = $user->getCrudUserGroupId();

            if (empty($this->userName)) {
                $this->userName = ContainerFactoryLanguage::get('/ContainerFactoryUser/session/username');
            }

        }

        /** @var ContainerFactoryUserGroup_crud $ContainerFactoryUserGroupCrud */
        $ContainerFactoryUserGroupCrud = Container::get('ContainerFactoryUserGroup_crud');
        $ContainerFactoryUserGroupCrud->setCrudId($this->userGroupId);
        $ContainerFactoryUserGroupCrud->findById(true);

        /** @var ContainerFactoryLanguageParseIni $iniStringParse */
        $iniStringParse = Container::get('ContainerFactoryLanguageParseIni',
                                         $ContainerFactoryUserGroupCrud->getCrudData());

        $iniStringParseData  = $iniStringParse->get();
        $this->userGroupName = ($iniStringParseData['name'] ?? '?');

        /** @var ContainerFactoryUserGroup_crud_groupaccess $crudAccess */
        $crudAccess     = Container::get('ContainerFactoryUserGroup_crud_groupaccess');
        $crudAccessRead = $crudAccess->find([
                                                'crudUserGroupId' => $this->userGroupId
                                            ]);

        /** @var ContainerFactoryUserGroup_crud_groupaccess $crudAccessReadItem */
        foreach ($crudAccessRead as $counter => $crudAccessReadItem) {
            $this->userAccess[$crudAccessReadItem->getCrudAccess()] = $counter;
        }

    }

    public function getUserAccess(): array
    {
        return $this->userAccess;
    }

    public function checkUserAccess(string $value, bool $exception = false): bool
    {
        if (isset($this->userAccess[$value]) === true) {
            return true;
        }
        else {
            if ($exception === false) {
                return false;
            }
            else {
                throw new DetailedException('accessDenied',
                                            0,
                                            null,
                                            [
                                                'debug' => [
                                                    $value
                                                ]
                                            ]);
            }
        }

    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * @return string
     */
    public function getUserName(): string
    {
        return $this->userName;
    }

    /**
     * @param string $userName
     */
    public function setUserName(string $userName): void
    {
        $this->userName = $userName;
    }

    /**
     * @return int
     */
    public function getUserGroupId(): int
    {
        return $this->userGroupId;
    }

    /**
     * @param int $userGroupId
     */
    public function setUserGroupId(int $userGroupId): void
    {
        $this->userGroupId = $userGroupId;
    }

}
