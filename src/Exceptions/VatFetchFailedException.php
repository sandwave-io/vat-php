<?php declare(strict_types = 1);

namespace SandwaveIo\Vat\Exceptions;

use RuntimeException;
use Throwable;

final class VatFetchFailedException extends RuntimeException
{
    /** @var array<string,array<mixed>> */
    public array $payload;

    /**
     * VatFetchFailedException constructor.
     *
     * @param array<string,array<mixed>> $payload
     */
    public function __construct(string $message = '', array $payload = [], int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->payload = $payload;
    }
}
