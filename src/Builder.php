<?php

namespace Greabock\NodeBuilder;

use Greabock\NodeBuilder\Support\Contracts\NodeFillerInterface;
use Greabock\NodeBuilder\Support\Contracts\NodeKnowledgeInterface;
use Greabock\NodeBuilder\Support\Contracts\NodeMapBuilderInterface;
use Greabock\NodeBuilder\Support\Contracts\NodeResolverInterface;

class Builder implements NodeMapBuilderInterface
{
    /**
     * @var NodeResolverInterface
     */
    protected $resolver;

    /**
     * @var NodeKnowledgeInterface
     */
    protected $knowledge;

    /**
     * @var NodeFillerInterface
     */
    protected $filler;

    public function __construct(NodeResolverInterface $resolver, NodeKnowledgeInterface $knowledge, NodeFillerInterface $filler)
    {
        $this->resolver = $resolver;
        $this->knowledge = $knowledge;
        $this->setFiller($filler);
    }

    /**
     * @param       $type
     * @param array $data
     *
     * @return mixed
     */
    public function buildTree($type, array $data)
    {
        $node = $this->resolver->resolve($type, $data);

        $fieldMap = $this->knowledge->compareFields($type, $data);


        $this->filler->fillNode($node, $fieldMap);

        return $node;
    }

    /**
     * @param NodeFillerInterface $filler
     */
    public function setFiller(NodeFillerInterface $filler)
    {
        $filler->setBuilder($this);
        $this->filler = $filler;
    }
}