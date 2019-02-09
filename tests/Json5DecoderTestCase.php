<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Json;

use Railt\Json\Json5;

/**
 * Class Json5DecoderTestCase
 */
class Json5DecoderTestCase extends AbstractDecoderTestCase
{
    /**
     * @param string $value
     * @param int $options
     * @return array|mixed
     * @throws \Railt\Json\Exception\JsonException
     */
    protected function decode(string $value, int $options = 0)
    {
        return Json5::decoder()->setOptions($options)->decode($value);
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \Railt\Json\Exception\JsonException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testVerticalTabChar(): void
    {
        $this->assertSame("\u{000B}", $this->decode('"\v"'));
    }
}
