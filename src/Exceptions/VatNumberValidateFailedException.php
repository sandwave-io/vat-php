<?php declare(strict_types = 1);

namespace SandwaveIo\Vat\Exceptions;

use RuntimeException;
use Throwable;

final class VatNumberValidateFailedException extends RuntimeException
{
    /** @var array<string,array> */
    public array $payload;

    /**
     * VatNumberValidateFailedException constructor.
     *
     * @param string              $message
     * @param array<string,array> $payload
     * @param int                 $code
     * @param Throwable|null      $previous
     */
    public function __construct($message = '', array $payload = [], $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->payload = $payload;
    }
}
