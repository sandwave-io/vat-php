<?php declare(strict_types = 1);

namespace SandwaveIo\Vat\Tests;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use SandwaveIo\Vat\Vat;

/**
 * This test actually talks to the remote API.
 * The remote API is aggressively rate-limited so the tests are run sequentially.
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
        sleep(2);
        Assert::assertSame(21.0, $this->service->europeanVatRate('NL'));
    }

    /** @depends testEuropeanVatRate */
    public function testNonEuropeanVatRate(): void
    {
        sleep(2);
        Assert::assertSame(0.0, $this->service->europeanVatRate('OM'));
    }

    /** @depends testNonEuropeanVatRate */
    public function testEuropeanVatNumber(): void
    {
        sleep(2);
        Assert::assertTrue($this->service->validateEuropeanVatNumber('NL861350480B01', 'NL'));
        Assert::assertFalse($this->service->validateEuropeanVatNumber('NL138250460B01', 'NL'));
    }

    /** @depends testEuropeanVatNumber */
    public function testNonEuropeanVatNumber(): void
    {
        sleep(2);
        Assert::assertFalse($this->service->validateEuropeanVatNumber('NL861350480B01', 'OM'));
    }
}
