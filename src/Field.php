<?php

namespace Greabock\NodeBuilder;

use Greabock\NodeBuilder\Doctrine\Annotations\Node;
use Greabock\NodeBuilder\Support\Contracts\NodeFieldInterface;

class Field implements NodeFieldInterface
{

    /**
     * @var
     */
    protected $value;

    /**
     * @var Node
     */
    protected $node;

    /**
     * Field constructor.
     *
     * @param      $value
     * @param Node $node
     */
    public function __construct($value, Node $node)
    {
        $this->value = $value;
        $this->node = $node;
    }

    /**
     * @return callable|null
     */
    public function getMethod()
    {
        return $this->node->setter;
    }

    /**
     * @return array|string[]
     */
    public function getMiddleware():array
    {
        return $this->node->middleware;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getFieldName()
    {
        return $this->node->fieldName;
    }
}