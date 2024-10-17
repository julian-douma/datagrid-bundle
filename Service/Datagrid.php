<?php
namespace Skrepr\DatagridBundle\Service;

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
        // annotation reader
        $reflection = new \ReflectionClass($datagridClass);
        $reader = new AnnotationReader();

        $annotationDatagrid = $reader->getClassAnnotation($reflection, 'Skrepr\\DatagridBundle\\Annotation\\Datagrid');

        // datasource
        $annotation = $reader->getClassAnnotation($reflection, 'Skrepr\\DatagridBundle\\Annotation\\DoctrineSource');

        $setDatasource = null;
        if ($annotation !== null && $annotation->entityClass != '') {
            $repo = $this->entityManager->getRepository($annotation->entityClass);
            $setDatasource = new DoctrineSource($repo);
        }

        if (isset($this->serviceIds[$datagridClass])) {
            // service
            $service = $this->container->get($this->serviceIds[$datagridClass]);

            if ($service instanceof \Skrepr\Datagrid\Datagrid) {
                $datagrid = clone $service;
            } else {
                $datagrid = new \Skrepr\Datagrid\Datagrid();
                $service->create($datagrid);
            }
        } else {
            // normale class, hier worden dependencies ondersteund
            $deps = [];
            if ($annotationDatagrid !== null && count($annotationDatagrid->dependencies)) {
                foreach ($annotationDatagrid->dependencies as $dep) {
                    if (strstr($dep, '@')) {
                        $deps[] = $this->container->get(str_replace('@', '', $dep));
                    } else if(strstr($dep, '%')) {
                        $deps[] = $this->container->getParameter(str_replace('%', '', $dep));
                    }
                }
            }

            if ($setDatasource !== null) {
                $deps[] = $setDatasource;
            }

            $datagrid = call_user_func_array(
                array($reflection, 'newInstance'),
                $deps
            );
        }

        // configuratie
        $datagrid->setViewPath($this->viewPath);

        if ($setDatasource !== null) {
            $datagrid->setDatasource($setDatasource);
        }

        return $datagrid;
    }
}
