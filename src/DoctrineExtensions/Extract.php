<?php
namespace App\DoctrineExtensions;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;

class Extract extends FunctionNode
{
    public $unit = null;
    public $timestamp = null;

    public function parse(Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);

        $parser->match(Lexer::T_IDENTIFIER);
        $lexer = $parser->getLexer();
        $this->unit = $lexer->token['value'];

        $parser->match(Lexer::T_IDENTIFIER);
        $this->timestamp = $parser->ArithmeticPrimary();
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

    public function getSql(SqlWalker $sqlWalker)
    {
        $unit = strtoupper($this->unit);
        // return 'Extract('.
        //     $this->field->dispatch($sqlWalker)  . " FROM " .
        //     $this->type->dispatch($sqlWalker)   . " "      .
        //     $this->source->dispatch($sqlWalker) . ')';

        return 'EXTRACT(' . $unit . ' FROM ' . $this->timestamp->dispatch($sqlWalker) . ' AT TIME ZONE \'UTC\') * 1000';
    }
}
