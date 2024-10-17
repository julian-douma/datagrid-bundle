<?php
namespace Skrepr\DatagridBundle\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * Class Datagrid
 * @package Skrepr\DatagridBundle\Annotation
 * @Annotation
 */
class Datagrid extends Annotation
{
    public $dependencies = [];
}
