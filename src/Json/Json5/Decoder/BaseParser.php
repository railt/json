<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Json\Json5\Decoder;

use Railt\Lexer\Driver\NativeRegex;
use Railt\Lexer\LexerInterface;
use Railt\Parser\Parser;
use Railt\Parser\Rule\Alternation;
use Railt\Parser\Rule\Concatenation;
use Railt\Parser\Rule\Repetition;
use Railt\Parser\Rule\Terminal;

/**
 * --- DO NOT EDIT THIS FILE ---
 *
 * Class BaseParser has been auto-generated.
 * Generated at: 15-02-2019 22:12:21
 *
 * --- DO NOT EDIT THIS FILE ---
 */
class BaseParser extends Parser
{
    public const T_COMMENT = 'T_COMMENT';
    public const T_DOC_COMMENT = 'T_DOC_COMMENT';
    public const T_BRACKET_OPEN = 'T_BRACKET_OPEN';
    public const T_BRACKET_CLOSE = 'T_BRACKET_CLOSE';
    public const T_BRACE_OPEN = 'T_BRACE_OPEN';
    public const T_BRACE_CLOSE = 'T_BRACE_CLOSE';
    public const T_COLON = 'T_COLON';
    public const T_COMMA = 'T_COMMA';
    public const T_PLUS = 'T_PLUS';
    public const T_MINUS = 'T_MINUS';
    public const T_BOOL_TRUE = 'T_BOOL_TRUE';
    public const T_BOOL_FALSE = 'T_BOOL_FALSE';
    public const T_NULL = 'T_NULL';
    public const T_INF = 'T_INF';
    public const T_NAN = 'T_NAN';
    public const T_HEX_NUMBER = 'T_HEX_NUMBER';
    public const T_FLOAT_LD_NUMBER = 'T_FLOAT_LD_NUMBER';
    public const T_FLOAT_TG_NUMBER = 'T_FLOAT_TG_NUMBER';
    public const T_INT_NUMBER = 'T_INT_NUMBER';
    public const T_EXPONENT_PART = 'T_EXPONENT_PART';
    public const T_IDENTIFIER = 'T_IDENTIFIER';
    public const T_DOUBLE_QUOTED_STRING = 'T_DOUBLE_QUOTED_STRING';
    public const T_SINGLE_QUOTED_STRING = 'T_SINGLE_QUOTED_STRING';
    public const T_HORIZONTAL_TAB = 'T_HORIZONTAL_TAB';
    public const T_LINE_FEED = 'T_LINE_FEED';
    public const T_VERTICAL_TAB = 'T_VERTICAL_TAB';
    public const T_FORM_FEED = 'T_FORM_FEED';
    public const T_CARRIAGE_RETURN = 'T_CARRIAGE_RETURN';
    public const T_WHITESPACE = 'T_WHITESPACE';
    public const T_NON_BREAKING_SPACE = 'T_NON_BREAKING_SPACE';
    public const T_LINE_SEPARATOR = 'T_LINE_SEPARATOR';
    public const T_PARAGRAPH_SEPARATOR = 'T_PARAGRAPH_SEPARATOR';
    public const T_UTF32BE_BOM = 'T_UTF32BE_BOM';
    public const T_UTF32LE_BOM = 'T_UTF32LE_BOM';
    public const T_UTF16BE_BOM = 'T_UTF16BE_BOM';
    public const T_UTF16LE_BOM = 'T_UTF16LE_BOM';
    public const T_UTF8_BOM = 'T_UTF8_BOM';
    public const T_UTF7_BOM = 'T_UTF7_BOM';

    /**
     * Lexical tokens list.
     *
     * @var string[]
     */
    protected const LEXER_TOKENS = [
        self::T_COMMENT              => '//[^\\n]*\\n',
        self::T_DOC_COMMENT          => '/\\*.*?\\*/',
        self::T_BRACKET_OPEN         => '\\[',
        self::T_BRACKET_CLOSE        => '\\]',
        self::T_BRACE_OPEN           => '{',
        self::T_BRACE_CLOSE          => '}',
        self::T_COLON                => ':',
        self::T_COMMA                => ',',
        self::T_PLUS                 => '\\+',
        self::T_MINUS                => '\\-',
        self::T_BOOL_TRUE            => 'true\\b',
        self::T_BOOL_FALSE           => 'false\\b',
        self::T_NULL                 => 'null\\b',
        self::T_INF                  => 'Infinity\\b',
        self::T_NAN                  => 'NaN\\b',
        self::T_HEX_NUMBER           => '0x([0-9a-fA-F]+)',
        self::T_FLOAT_LD_NUMBER      => '[0-9]*\\.[0-9]+',
        self::T_FLOAT_TG_NUMBER      => '[0-9]+\\.[0-9]*',
        self::T_INT_NUMBER           => '[0-9]+',
        self::T_EXPONENT_PART        => '[eE]((?:\\-|\\+)?[0-9]+)',
        self::T_IDENTIFIER           => '[\\$_A-Za-z][\\$_0-9A-Za-z]*',
        self::T_DOUBLE_QUOTED_STRING => '"([^"\\\\]*(\\\\.[^"\\\\]*)*)"',
        self::T_SINGLE_QUOTED_STRING => '\'([^\'\\\\]*(\\\\.[^\'\\\\]*)*)\'',
        self::T_HORIZONTAL_TAB       => '\\x09',
        self::T_LINE_FEED            => '\\x0A',
        self::T_VERTICAL_TAB         => '\\x0B',
        self::T_FORM_FEED            => '\\x0C',
        self::T_CARRIAGE_RETURN      => '\\x0D',
        self::T_WHITESPACE           => '\\x20',
        self::T_NON_BREAKING_SPACE   => '\\xA0',
        self::T_LINE_SEPARATOR       => '\\x2028',
        self::T_PARAGRAPH_SEPARATOR  => '\\x2029',
        self::T_UTF32BE_BOM          => '^\\x00\\x00\\xFE\\xFF',
        self::T_UTF32LE_BOM          => '^\\xFE\\xFF\\x00\\x00',
        self::T_UTF16BE_BOM          => '^\\xFE\\xFF',
        self::T_UTF16LE_BOM          => '^\\xFF\\xFE',
        self::T_UTF8_BOM             => '^\\xEF\\xBB\\xBF',
        self::T_UTF7_BOM             => '^\\x2B\\x2F\\x76\\x38\\x2B\\x2F\\x76\\x39\\x2B\\x2F\\x76\\x2B\\x2B\\x2F\\x76\\x2F',
    ];

    /**
     * List of skipped tokens.
     *
     * @var string[]
     */
    protected const LEXER_SKIPPED_TOKENS = [
        'T_COMMENT',
        'T_DOC_COMMENT',
        'T_HORIZONTAL_TAB',
        'T_LINE_FEED',
        'T_VERTICAL_TAB',
        'T_FORM_FEED',
        'T_CARRIAGE_RETURN',
        'T_WHITESPACE',
        'T_NON_BREAKING_SPACE',
        'T_LINE_SEPARATOR',
        'T_PARAGRAPH_SEPARATOR',
        'T_UTF32BE_BOM',
        'T_UTF32LE_BOM',
        'T_UTF16BE_BOM',
        'T_UTF16LE_BOM',
        'T_UTF8_BOM',
        'T_UTF7_BOM',
    ];

    /**
     * Parser root rule name.
     *
     * @var string
     */
    protected const PARSER_ROOT_RULE = 'Json';

    /**
     * BaseParser constructor.
     */
    public function __construct()
    {
        parent::__construct($this->createLexer(), $this->createGrammarRules(), static::PARSER_ROOT_RULE);
    }

    /**
     * @return LexerInterface
     */
    protected function createLexer(): LexerInterface
    {
        return new NativeRegex(static::LEXER_TOKENS, static::LEXER_SKIPPED_TOKENS);
    }

    /**
     * @return array|\Railt\Parser\Rule\Rule[]
     */
    protected function createGrammarRules(): array
    {
        return [
            new Concatenation(0, ['Value'], null),
            (new Concatenation('Json', [0], 'Json'))->setDefaultId('Json'),
            new Concatenation(2, ['Int'], null),
            new Alternation('Value', ['Object', 'Array', 'String', 'Boolean', 'Null', 'Inf', 'NaN', 'Hex', 'Float', 2], null),
            new Terminal(4, 'T_BRACE_OPEN', false),
            new Repetition(5, 0, 1, 'ObjectBody', null),
            new Terminal(6, 'T_COMMA', false),
            new Repetition(7, 0, 1, 6, null),
            new Terminal(8, 'T_BRACE_CLOSE', false),
            (new Concatenation('Object', [4, 5, 7, 8], 'Object'))->setDefaultId('Object'),
            new Terminal(10, 'T_COMMA', false),
            new Concatenation(11, ['ObjectPair', 10], null),
            new Repetition(12, 0, -1, 11, null),
            new Concatenation(13, ['ObjectPair'], null),
            new Concatenation('ObjectBody', [12, 13], null),
            new Terminal(15, 'T_COLON', false),
            new Concatenation(16, ['ObjectValue'], null),
            (new Concatenation('ObjectPair', ['ObjectKey', 15, 16], 'ObjectPair'))->setDefaultId('ObjectPair'),
            new Concatenation(18, ['Identifier'], null),
            new Alternation('ObjectKey', ['String', 18], null),
            new Concatenation('ObjectValue', ['Value'], null),
            new Terminal(21, 'T_BRACKET_OPEN', false),
            new Repetition(22, 0, 1, 'ArrayBody', null),
            new Terminal(23, 'T_COMMA', false),
            new Repetition(24, 0, 1, 23, null),
            new Terminal(25, 'T_BRACKET_CLOSE', false),
            (new Concatenation('Array', [21, 22, 24, 25], 'Array'))->setDefaultId('Array'),
            new Terminal(27, 'T_COMMA', false),
            new Concatenation(28, ['Value', 27], null),
            new Repetition(29, 0, -1, 28, null),
            new Concatenation(30, ['Value'], null),
            new Concatenation('ArrayBody', [29, 30], null),
            new Terminal(32, 'T_DOUBLE_QUOTED_STRING', true),
            new Concatenation(33, [32], 'String'),
            new Terminal(34, 'T_SINGLE_QUOTED_STRING', true),
            new Concatenation(35, [34], 'String'),
            (new Alternation('String', [33, 35], null))->setDefaultId('String'),
            new Terminal(37, 'T_BOOL_TRUE', true),
            new Concatenation(38, [37], 'Boolean'),
            new Terminal(39, 'T_BOOL_FALSE', true),
            new Concatenation(40, [39], 'Boolean'),
            (new Alternation('Boolean', [38, 40], null))->setDefaultId('Boolean'),
            new Terminal(42, 'T_NULL', false),
            (new Concatenation('Null', [42], 'Null'))->setDefaultId('Null'),
            new Terminal(44, 'T_IDENTIFIER', true),
            (new Concatenation('Identifier', [44], 'Identifier'))->setDefaultId('Identifier'),
            new Terminal(46, 'T_PLUS', true),
            new Concatenation(47, [46], 'Sign'),
            new Terminal(48, 'T_MINUS', true),
            new Concatenation(49, [48], 'Sign'),
            (new Alternation('Sign', [47, 49], null))->setDefaultId('Sign'),
            new Terminal(51, 'T_EXPONENT_PART', true),
            (new Concatenation('ExponentPart', [51], 'ExponentPart'))->setDefaultId('ExponentPart'),
            new Repetition(53, 0, 1, 'Sign', null),
            new Terminal(54, 'T_INF', true),
            (new Concatenation('Inf', [53, 54], 'Inf'))->setDefaultId('Inf'),
            new Repetition(56, 0, 1, 'Sign', null),
            new Terminal(57, 'T_NAN', true),
            (new Concatenation('NaN', [56, 57], 'NaN'))->setDefaultId('NaN'),
            new Repetition(59, 0, 1, 'Sign', null),
            new Terminal(60, 'T_FLOAT_LD_NUMBER', true),
            new Concatenation(61, [60], 'Float'),
            new Terminal(62, 'T_FLOAT_TG_NUMBER', true),
            new Concatenation(63, [62], 'Float'),
            new Alternation(64, [61, 63], null),
            new Repetition(65, 0, 1, 'ExponentPart', null),
            (new Concatenation('Float', [59, 64, 65], null))->setDefaultId('Float'),
            new Repetition(67, 0, 1, 'Sign', null),
            new Terminal(68, 'T_INT_NUMBER', true),
            new Repetition(69, 0, 1, 'ExponentPart', null),
            (new Concatenation('Int', [67, 68, 69], 'Int'))->setDefaultId('Int'),
            new Repetition(71, 0, 1, 'Sign', null),
            new Terminal(72, 'T_HEX_NUMBER', true),
            (new Concatenation('Hex', [71, 72], 'Hex'))->setDefaultId('Hex'),
        ];
    }
}
