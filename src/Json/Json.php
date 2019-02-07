<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Json;

use Railt\Json\Rfc7159\NativeJsonDecoder;
use Railt\Json\Rfc7159\NativeJsonEncoder;

/**
 * Class Json
 */
class Json extends Facade
{
    /**
     * @return JsonEncoderInterface
     */
    public static function encoder(): JsonEncoderInterface
    {
        return new NativeJsonEncoder();
    }

    /**
     * @return JsonDecoderInterface
     */
    public static function decoder(): JsonDecoderInterface
    {
        return new NativeJsonDecoder();
    }
}
