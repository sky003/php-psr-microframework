<?php
declare(strict_types = 1);

namespace App\Component\Console\Command\Helper;

use Lcobucci\JWT\Signer;
use Symfony\Component\Console\Helper\Helper;

/**
 * CLI Jwt Helper.
 *
 * @author Anton Pelykh <anton.pelykh.dev@gmail.com>
 */
class JwtHelper extends Helper
{
    /**
     * @var Signer
     */
    private $signer;
    /**
     * @var Signer\Key
     */
    private $key;

    public function __construct(Signer $signer, Signer\Key $key)
    {
        $this->signer = $signer;
        $this->key = $key;
    }

    /**
     * @return Signer
     */
    public function getSigner(): Signer
    {
        return $this->signer;
    }

    /**
     * @return Signer\Key
     */
    public function getKey(): Signer\Key
    {
        return $this->key;
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'jwt';
    }
}
