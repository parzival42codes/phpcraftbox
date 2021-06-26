<?php

/**
 * Class ContainerFactoryCrypt
 *
 * @method string getEnCrypt() see _getEnCrypt
 * @method string getDeCrypt() see _getDeCrypt
 */

class ContainerFactoryCrypt extends Base
{
    protected string $cipher = '';
    protected string $text   = '';
    protected string $key    = '';

    /**
     * ContainerFactoryCrypt constructor.
     *
     * Read from environment config the Encryption Method
     *
     */
    public function __construct()
    {
        $this->cipher = (string)Config::get('/environment/secret/cipher');
    }

    /**
     * @param string $cipher set the Cipher Method
     *
     * @return void
     */
    public function setCipherMethod(string $cipher): void
    {
        $this->cipher = $cipher;
    }

    /**
     * Text to en/decrypt
     *
     * @param string $text Text to en/decrypt
     *
     * @return void
     */
    public function setText(string $text): void
    {
        $this->text = $text;
    }

    /**
     * Key to en/decrypt.
     *
     * @param string $key Key to en/decrypt
     *
     * @return void
     */
    public function setKey(string $key): void
    {
        $this->key = $key;
    }

    /**
     * Crypt a String
     *
     * @CMSprofilerSet          action encrypt
     * @CMSprofilerSetFromScope length
     * @CMSprofilerSetFromScope cipher
     * @CMSprofilerOption       isFunction true
     * @CMSprofilerOption       deph 7
     *
     * @return string The encoded String
     * @throws DetailedException Error when Crypt text is empty or the Crypt don't exists
     */
    public function _getEnCrypt(array &$scope): string
    {
        $scope['length'] = strlen($this->text);
        $scope['cipher'] = $this->cipher;

        if (
        in_array($this->cipher,
                 $this->getCipherMethods())
        ) {
            $ivlen = openssl_cipher_iv_length($this->cipher);

            if ($ivlen === false) {
                throw new DetailedException('openCypherError',
                                            0,
                                            null,
                                            [
                                                'debug' => [
                                                    $this->cipher
                                                ]
                                            ]);
            }

            $iv = openssl_random_pseudo_bytes($ivlen);

            $ciphertext = openssl_encrypt($this->text,
                                          $this->cipher,
                                          $this->key,
                                          $options = 0,
                                          $iv);

            if (empty($ciphertext)) {
                throw new DetailedException('cipherEncryptError',
                                            0,
                                            null,
                                            [
                                                'text'   => $this->text,
                                                'cipher' => $this->cipher,
                                            ]);

            }

            return base64_encode($ciphertext . '::' . $iv . '::' . $this->cipher);

        }
        else {
            throw new DetailedException('cipherNotFound',
                                        0,
                                        null,
                                        [
                                            'cipher' => $this->cipher,
                                        ]);
        }

    }

    /**
     * The exists Cypher Methods
     *
     * @return array
     */
    protected function getCipherMethods(): array
    {
        return openssl_get_cipher_methods();
    }

    /**
     * Decrypt a String
     *
     * @CMSprofilerSet          action decrypt
     * @CMSprofilerSetFromScope length
     * @CMSprofilerSetFromScope cipher
     * @CMSprofilerOption       isFunction true
     *
     * @param array $scope
     *
     * @return  Decrypted Text
     */
    public function _getDeCrypt(array &$scope, int $options = 0)
    {
        $cipherData = explode('::',
                              base64_decode($this->text));

        $scope['length'] = strlen($cipherData[0]);
        $scope['cipher'] = $cipherData[2];

        return openssl_decrypt($cipherData[0],
                               $cipherData[2],
                               $this->key,
                               $options,
                               $cipherData[1]);
    }

}
