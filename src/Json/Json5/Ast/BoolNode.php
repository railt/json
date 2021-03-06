<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Json\Json5\Ast;

use Phplrt\Ast\LeafInterface;

/**
 * @internal Internal class for json5 abstract syntax tree node representation
 */
class BoolNode implements NodeInterface
{
    /**
     * @var LeafInterface
     */
    private $value;

    /**
     * BoolNode constructor.
     *
     * @param array $children
     */
    public function __construct(array $children = [])
    {
        $this->value = \reset($children);
    }

    /**
     * @return bool
     */
    public function reduce(): bool
    {
        return $this->value->getName() === 'T_BOOL_TRUE';
    }
}
