<?php
namespace Skrepr\DatagridBundle\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Datagrid
{
    public function __construct(public array $dependencies = []){}
}
