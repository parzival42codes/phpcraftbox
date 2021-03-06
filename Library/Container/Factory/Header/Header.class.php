<?php

class ContainerFactoryHeader
{

    protected array $header       = [];
    protected array $headerRemove = [];
    protected array $cookie       = [];

    public function set(string $key, string $content, bool $override = true): void
    {
        $this->header[$key][] = [
            'content'  => $content,
            'override' => $override
        ];
    }

    public function get(): array
    {
        return $this->header;
    }

    public function remove(string $remove): void
    {
        $this->headerRemove[] = $remove;
    }

    public function setCookie(ContainerFactoryHeaderCookie $cookie): void
    {
        $this->cookie[] = $cookie;
    }

    public function send(): void
    {
        foreach ($this->header as $key => $group) {
            foreach ($group as $value) {
                if ($key !== '#') {
                    header($key . ': ' . $value['content'],
                           $value['override']);
                }
                else {
                    header($value['content'],
                           $value['override']);
                }
            }
        }

        foreach ($this->headerRemove as $headerRemove) {
            header_remove($headerRemove);
        }

        foreach ($this->cookie as $cookie) {
            /** @var ContainerFactoryHeaderCookie $cookie */
            setcookie($cookie->getName(),
                      $cookie->getValue(),
                      [
                          'path'     => $cookie->getPath(),
                          'expires'  => time() + $cookie->getExpire(),
                          'domain'   => $cookie->getDomain(),
                          'secure'   => (bool)$cookie->getSecure(),
                          'httponly' => (bool)$cookie->getHttponly(),
                          'samesite' => $cookie->getSamesite(),
                      ]);
        }
    }

}
