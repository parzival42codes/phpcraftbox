<?php

class ContainerFactoryHeaderCookie
{

    protected string $name     = '';
    protected string $value    = '';
    protected int    $expire   = 0;
    protected string $path     = '/';
    protected string $domain   = '';
    protected bool   $secure   = false;
    protected bool   $httponly = true;

    public function __construct()
    {

    }

    public function setName(string $value): void
    {
        $this->name = $value;
    }

    public function getName(): string
    {
        return $this->name;
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

}
