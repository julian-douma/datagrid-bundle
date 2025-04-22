<?php
namespace Skrepr\DatagridBundle\Service;

use ReflectionClass;
use Skrepr\DatagridBundle\Attribute\Datagrid as DatagridAttribute;
use Skrepr\DatagridBundle\Attribute\DoctrineSource as DoctrineSourceAttribute;
use Skrepr\Datagrid\Datasource\DoctrineSource;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Annotations\AnnotationReader;

class Datagrid
{
    /**
     * @var array
     */
    private $serviceIds;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var string
     */
    private $viewPath;

    public function __construct($serviceIds, ContainerInterface $container, $viewPath, EntityManager $entityManager)
    {
        $this->serviceIds = $serviceIds;
        $this->container = $container;
        $this->viewPath = $viewPath;
        $this->entityManager = $entityManager;
    }

    /**
     * @param $datagridClass
     * @return \Skrepr\Datagrid\Datagrid
     * @throws \Exception
     */
    public function create($datagridClass)
    {
        $reflection = new ReflectionClass($datagridClass);

        $attributes = $reflection->getAttributes();
        $deps = [];
        $setDatasource = null;

        foreach ($attributes as $attribute) {
            $instance = $attribute->newInstance();

            if ($instance instanceof DatagridAttribute) {
                foreach ($instance->dependencies as $dep) {
                    if (str_starts_with($dep, '@')) {
                        $deps[] = $this->container->get(ltrim($dep, '@'));
                    } elseif (str_starts_with($dep, '%')) {
                        $deps[] = $this->container->getParameter(trim($dep, '%'));
                    } else {
                        $deps[] = $this->container->get($dep);
                    }
                }
            }

            if ($instance instanceof DoctrineSourceAttribute && $instance->entityClass) {
                $repo = $this->entityManager->getRepository($instance->entityClass);
                $setDatasource = new DoctrineSource($repo);
            }
        }

        if ($setDatasource !== null) {
            $deps[] = $setDatasource;
        }

        $datagrid = $reflection->newInstanceArgs($deps);

        $datagrid->setViewPath($this->viewPath);

        if ($setDatasource !== null) {
            $datagrid->setDatasource($setDatasource);
        }

        return $datagrid;
    }
}
