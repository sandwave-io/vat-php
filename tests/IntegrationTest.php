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
    public function testEuropeanVatRate(): void
    {
        $service = new Vat();
        Assert::assertSame(21.0, $service->europeanVatRate('NL'));
    }

    public function testNonEuropeanVatRate(): void
    {
        $service = new Vat();
        Assert::assertSame(0.0, $service->europeanVatRate('OM'));
    }
}
