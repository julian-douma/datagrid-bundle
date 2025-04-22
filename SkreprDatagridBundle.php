<?php

namespace Skrepr\DatagridBundle;

use Skrepr\DatagridBundle\DependencyInjection\Compiler\Datagrid;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SkreprDatagridBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);
        $container->addCompilerPass(new Datagrid());
    }
}
