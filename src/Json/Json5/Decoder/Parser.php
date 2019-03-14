<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Json\Json5\Decoder;

use Railt\Parser\Ast\Rule;

/**
 * Class Parser
 */
class Parser extends BaseParser
{
    /**
     * List of rule delegates.
     *
     * @var string[]
     */
    protected const PARSER_DELEGATES = [
        'Json'       => \Railt\Json\Json5\Decoder\Ast\Json5Node::class,
        'Object'     => \Railt\Json\Json5\Decoder\Ast\ObjectNode::class,
        'Array'      => \Railt\Json\Json5\Decoder\Ast\ArrayNode::class,
        'String'     => \Railt\Json\Json5\Decoder\Ast\StringNode::class,
        'Boolean'    => \Railt\Json\Json5\Decoder\Ast\BoolNode::class,
        'Null'       => \Railt\Json\Json5\Decoder\Ast\NullNode::class,
        'Identifier' => \Railt\Json\Json5\Decoder\Ast\IdentifierNode::class,
        'Inf'        => \Railt\Json\Json5\Decoder\Ast\InfNode::class,
        'NaN'        => \Railt\Json\Json5\Decoder\Ast\NaNNode::class,
        'Float'      => \Railt\Json\Json5\Decoder\Ast\FloatNode::class,
        'Int'        => \Railt\Json\Json5\Decoder\Ast\IntNode::class,
        'Hex'        => \Railt\Json\Json5\Decoder\Ast\HexNode::class,
    ];

    /**
     * @var int
     */
    private $options;

    /**
     * Parser constructor.
     *
     * @param int $options
     */
    public function __construct(int $options)
    {
        $this->options = $options;

        parent::__construct();
    }

    /**
     * @param string $rule
     * @param array $children
     * @param int $offset
     * @return mixed|Rule|\Railt\Parser\Ast\RuleInterface
     */
    protected function create(string $rule, array $children, int $offset)
    {
        if (isset(self::PARSER_DELEGATES[$rule])) {
            $delegate = self::PARSER_DELEGATES[$rule];

            return new $delegate($children, $this->options);
        }

        return new Rule($rule, $children, $offset);
    }
}
