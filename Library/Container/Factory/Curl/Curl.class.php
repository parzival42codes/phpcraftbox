<?php

class ContainerFactoryCurl extends Base
{

    protected        $curlConnection;
    protected        $curlStatus;
    protected string $curlError = '';
    protected string $curlUrl   = '';
    protected int    $curlPort  = 80;
    protected string $curlFile  = '';
    protected        $returnData;

    public function __construct()
    {
        $this->curlConnection = curl_init();
        curl_setopt($this->curlConnection,
                    CURLOPT_RETURNTRANSFER,
                    true);
        curl_setopt($this->curlConnection,
                    CURLOPT_SSL_VERIFYPEER,
                    false);
        curl_setopt($this->curlConnection,
                    CURLOPT_SSL_VERIFYHOST,
                    false);
        curl_setopt($this->curlConnection,
                    CURLOPT_CONNECTTIMEOUT,
                    320);

        $useragent = 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/533.2 (KHTML, like Gecko) Chrome/5.0.342.3 Safari/533.2';
        curl_setopt($this->curlConnection,
                    CURLOPT_FOLLOWLOCATION,
                    true);
        curl_setopt($this->curlConnection,
                    CURLOPT_USERAGENT,
                    $useragent);
    }

    public function connect(): void
    {
        $fp = fopen($this->curlFile,
                    'w+');

        if ($fp === false) {
            throw new DetailedException('fileCouldNotBeOpen',
                                        0,
                                        null,
                                        [
                                            'debug' => [
                                                $this->curlFile
                                            ]
                                        ]);
        }

        curl_setopt($this->curlConnection,
                    CURLOPT_FILE,
                    $fp);
        $this->returnData = curl_exec($this->curlConnection);
        fclose($fp);

        $this->curlStatus = curl_getinfo($this->curlConnection);
        $this->curlError  = curl_error($this->curlConnection);
        // -------------------------------------------------------------------------------------------------
    }

    public function close(): void
    {
        curl_close($this->curlConnection);
    }

    public function setUrl(string $url): void
    {
        $this->curlUrl = $url;
        curl_setopt($this->curlConnection,
                    CURLOPT_URL,
                    $url);
        if (
            strpos(strtolower($this->curlUrl),
                   'https://') !== false
        ) {
            curl_setopt($this->curlConnection,
                        CURLOPT_PORT,
                        443);
            $this->curlPort = 443;
        }
        else {
            curl_setopt($this->curlConnection,
                        CURLOPT_PORT,
                        80);
            $this->curlPort = 80;
        }
    }

    public function getUrl(): string
    {
        return $this->curlUrl;
    }

    public function setFile(string $filePath): void
    {
        // -------------------------------------------------------------------------------------------------
        curl_setopt($this->curlConnection,
                    CURLOPT_RETURNTRANSFER,
                    false);
        $this->curlFile = $filePath;
        // -------------------------------------------------------------------------------------------------

    }

    public function getFile(): string
    {
        // -------------------------------------------------------------------------------------------------
        return $this->curlFile;
        // -------------------------------------------------------------------------------------------------
    }

    public function setReturn(): void
    {
        curl_setopt($this->curlConnection,
                    CURLOPT_RETURNTRANSFER,
                    true);
    }

    public function setOpt(int $key, string $value): void
    {
        curl_setopt($this->curlConnection,
                    $key,
                    $value);
    }

    public function getReturnData(): string
    {
        return $this->returnData;
    }

    public function getStatus(): string
    {
        return $this->curlStatus;
    }

    public function getError(): string
    {
        // -------------------------------------------------------------------------------------------------
        return $this->curlError;
        // -------------------------------------------------------------------------------------------------
    }

    public function getPort(): int
    {
        // -------------------------------------------------------------------------------------------------
        return $this->curlPort;
        // -------------------------------------------------------------------------------------------------
    }

}
