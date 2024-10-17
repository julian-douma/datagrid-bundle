<?php
namespace Skrepr\DatagridBundle\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * Class Datagrid
 * @package Skrepr\Datagrid\Annotation
 * @Annotation
 */
class DoctrineSource extends Annotation
{
    public $entityClass = '';
}
