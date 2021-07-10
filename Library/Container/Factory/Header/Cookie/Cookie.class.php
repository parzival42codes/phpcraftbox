<?php

class ContainerFactoryHeaderCookie
{

    private string $name     = '';
    private string $value    = '';
    private int    $expire   = 0;
    private string $path     = '/';
    private string $domain   = '';
    private string $samesite = 'Lax';
    private bool   $secure   = false;
    private bool   $httponly = true;

    public function __construct()
    {
        $this->expire = time() + Config::get('/environment/cookie/expire');
    }

    public function setName(string $value): void
    {
        $this->name = $value;
    }

    public function getName(): string
    {
        return Config::get('/environment/cookie/name') . $this->name;
    }

    public function setValue(string $value): void
    {
        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function setExpire(int $value): void
    {
        $this->expire = time() + $value;
    }

    public function getExpire(): int
    {
        return $this->expire;
    }

    public function setPath(string $value): void
    {
        $this->path = $value;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function setDomain(string $value): void
    {
        $this->domain = $value;
    }

    public function getDomain(): string
    {
        return $this->domain;
    }

    public function setSecure(bool $value): void
    {
        $this->secure = $value;
    }

    public function getSecure(): bool
    {
        return $this->secure;
    }

    public function setHttponly(bool $value): void
    {
        $this->httponly = $value;
    }

    public function getHttponly(): bool
    {
        return $this->httponly;
    }

    /**
     * @return string
     */
    public function getSamesite(): string
    {
        return $this->samesite;
    }

    /**
     * @param string $samesite
     */
    public function setSamesite(string $samesite): void
    {
        $this->samesite = $samesite;
    }

}
