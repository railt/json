<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\Json\Json5\Decoder\Ast;

/**
 * @internal Internal class for json5 abstract syntax tree node representation
 */
class FloatNode extends NumberNode
{
    /**
     * @return float
     */
    public function reduce(): float
    {
        return $this->isPositive() ? $this->formatValue() : -$this->formatValue();
    }

    /**
     * @return float
     */
    private function formatValue(): float
    {
        [$exp, $value] = [$this->getExponent(), $this->getValue()];

        return $exp === 0 ? (float)$value : (float)($value . 'e' . $exp);
    }
}
