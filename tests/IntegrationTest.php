<?php declare(strict_types = 1);

namespace SandwaveIo\Vat\Tests;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use SandwaveIo\Vat\Vat;

/**
 * This test actually talks to the remote API.
 *
 * @coversNothing
 * @large
 * @runInSeparateProcess
 */
final class IntegrationTest extends TestCase
{
    protected Vat $service;

    protected function setUp(): void
    {
        $this->service = new Vat();
    }

    public function testEuropeanVatRate(): void
    {
        Assert::assertSame(21.0, $this->service->europeanVatRate('NL'));
    }

    public function testNonEuropeanVatRate(): void
    {
        Assert::assertSame(0.0, $this->service->europeanVatRate('OM'));
    }

    public function testEuropeanVatNumber(): void
    {
        Assert::assertTrue($this->service->validateEuropeanVatNumber('NL861350480B01', 'NL'));
        Assert::assertFalse($this->service->validateEuropeanVatNumber('NL138250460B01', 'NL'));
    }

    public function testNonEuropeanVatNumber(): void
    {
        Assert::assertFalse($this->service->validateEuropeanVatNumber('NL861350480B01', 'OM'));
    }
}
