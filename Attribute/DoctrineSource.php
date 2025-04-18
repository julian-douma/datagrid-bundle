<?php
namespace Skrepr\DatagridBundle\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class DoctrineSource
{
    /**
     * @param class-string $entityClass
     */
    public function __construct(public string $entityClass){}
}
