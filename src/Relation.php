<?php

namespace Greabock\NodeBuilder;

use Greabock\NodeBuilder\Doctrine\Annotations\Node;
use Greabock\NodeBuilder\Support\Contracts\NodeRelationInterface;

class Relation extends Field implements NodeRelationInterface
{
    /**
     * @var int
     */
    protected $cast;

    /**
     * @var array
     */
    protected $type;

    public function __construct($value, Node $node, $type, $cast)
    {
        parent::__construct($value, $node);
        $this->type = $type;
        $this->cast = $cast;
    }

    public function getType():string
    {
        return $this->type;
    }

    public function castIs(int $cast):bool
    {
        return $this->cast == $cast;
    }
}