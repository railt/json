<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Json;

use Railt\Json\Exception\JsonException;
use Railt\Json\Exception\Message;

/**
 * Class JsonRuntime
 */
abstract class JsonRuntime implements JsonRuntimeInterface
{
    /**
     * User specified recursion depth.
     *
     * @var int
     */
    protected $depth = self::DEFAULT_RECURSION_DEPTH;

    /**
     * @return int
     */
    public function getRecursionDepth(): int
    {
        return $this->depth;
    }

    /**
     * @param int $depth
     * @return JsonRuntimeInterface|$this
     */
    public function withRecursionDepth(int $depth): JsonRuntimeInterface
    {
        \assert($depth > 0, 'Depth value should be greater than 0');

        $this->depth = $depth;

        return $this;
    }

    /**
     * @param \Closure $expression
     * @return mixed
     * @throws JsonException
     */
    protected function wrap(\Closure $expression)
    {
        try {
            $result = $expression();
        } catch (\JsonException $e) {
            //
            // Since PHP >= 7.3 parsing json containing errors can throws
            // an exception. It is necessary to handle these cases.
            //
            throw $this->throwFromJsonException($e);
        } catch (\Throwable $e) {
            //
            // Other times we may get other (includes generally) errors.
            //
            throw $this->throwFromInternal($e);
        }

        // If PHP is lower or equal to version 7.2, then we must
        // handle the error in the old good way.
        if (($errorCode = \json_last_error()) !== \JSON_ERROR_NONE) {
            throw $this->throwFromJsonErrorCode($errorCode);
        }

        return $result;
    }

    /**
     * @param \JsonException $original
     * @return JsonException
     */
    private function throwFromJsonException(\JsonException $original): JsonException
    {
        $exception = JsonException::getExceptionByCode($original->getCode());

        return new $exception(Message::getByException($original), $original->getCode(), $original);
    }

    /**
     * @param \Throwable $e
     * @return JsonException
     */
    private function throwFromInternal(\Throwable $e): JsonException
    {
        $exception = JsonException::getExceptionByCode($e->getCode());

        if (\get_class($e) === 'Exception' && \strpos($e->getMessage(), 'Failed calling ') === 0) {
            $e = $e->getPrevious() ?: $e;
        }

        throw new $exception($e->getMessage(), $e->getCode(), $e);
    }

    /**
     * @param int $code
     * @return JsonException
     */
    private function throwFromJsonErrorCode(int $code): JsonException
    {
        $exception = JsonException::getExceptionByCode($code);

        return new $exception(Message::getByCode($code), $code);
    }
}