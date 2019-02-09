<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Json\Rfc7159;

use Railt\Json\Exception\JsonException;
use Railt\Json\JsonEncoder;

/**
 * Class NativeJsonEncoder
 */
class NativeJsonEncoder extends JsonEncoder
{
    use ErrorHandlerTrait;

    /**
     * NativeJsonEncoder constructor.
     */
    public function __construct()
    {
        \assert(\function_exists('\\json_encode'), 'PHP JSON extension required');
    }

    /**
     * Wrapper for JSON encoding logic with predefined options that
     * throws a Railt\Json\Exception\JsonException when an error occurs.
     *
     * @see http://www.php.net/manual/en/function.json-encode.php
     * @see http://php.net/manual/en/class.jsonexception.php
     * @param mixed $data
     * @return string
     * @throws JsonException
     */
    public function encode($data): string
    {
        return $this->wrap(function () use ($data) {
            return @\json_encode($data, $this->getOptions(), $this->getRecursionDepth());
        });
    }
}
