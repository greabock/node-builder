<?php

namespace Greabock\NodeBuilder;

use Greabock\NodeBuilder\Support\Contracts\NodeFieldInterface;
use Greabock\NodeBuilder\Support\Contracts\NodeFillerInterface;
use Greabock\NodeBuilder\Support\Contracts\NodeMapBuilderInterface;
use Greabock\NodeBuilder\Support\Contracts\NodeRelationInterface;

class Filler implements NodeFillerInterface
{
    /**
     * @var NodeMapBuilderInterface
     */
    protected $builder;

    /**
     * @var int
     */
    protected $onMissingMethod;


    /**
     * @param                                                     $node
     * @param array|NodeFillerInterface[]|NodeRelationInterface[] $fieldMap
     */
    public function fillNode($node, $fieldMap)
    {
        foreach ($fieldMap as $nodeField) {
            $this->setField($node, $nodeField);
        }
    }

    public function setBuilder(NodeMapBuilderInterface $builder)
    {
        $this->builder = $builder;
    }

    /**
     * @param                                          $node
     * @param NodeFieldInterface|NodeRelationInterface $nodeField
     */
    private function setField($node, NodeFieldInterface $nodeField)
    {

        $value = $this->resolveValue($nodeField);

        $middlewares = $nodeField->getMiddleware();

        $result = array_reduce($middlewares, function ($value, $middleware) {
            return $middleware->handle($value);
        }, $value);

        $setter = $nodeField->getMethod();

        $setter($node, $result);
    }

    private function resolveValue(NodeFieldInterface $nodeField)
    {

        if ($nodeField instanceof NodeRelationInterface) {
            $type = $nodeField->getType();

            if ($nodeField->castIs(NodeRelationInterface::CAST_TO_MANY)) {

                $value = [];

                $maps = $nodeField->getValue();

                foreach ($maps as $map) {
                    $value[] = $this->builder->buildTree($type, $map);
                }

                return $value;
            }

            return $this->builder->buildTree($type, $nodeField->getValue());
        }

        return $nodeField->getValue();
    }
}