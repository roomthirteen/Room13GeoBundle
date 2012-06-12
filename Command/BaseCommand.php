<?php

namespace Room13\GeoBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

use Room13\GeoBundle\Entity\Country;
use Room13\GeoBundle\Entity\City;
use Room13\GeoBundle\Entity\Spot;


abstract class BaseCommand extends ContainerAwareCommand
{

    private $errorLog = array();

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    protected function getEntityManager()
    {
        return $this->getContainer()->get('doctrine.orm.entity_manager');
    }

    protected function persistAndFlush($entity)
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush($entity);
    }

    protected function geoInfo($query, $params = array())
    {
        $geoloc = $this->getContainer()->get('room13_geo.lookup')->search($query, 1, $params);

        return array_shift($geoloc);
    }

    protected function hasErrors()
    {
        return count($this->errorLog)>0;
    }

    protected function logError($data)
    {
        $this->errorLog[]=$data;
    }

    protected function printErrorReport(OutputInterface $output)
    {
        $output->writeln("");
        $output->writeln("Some errors occured during the import:");
        $output->writeln("");

        foreach($this->errorLog as $error)
        {
            $output->writeln(implode(",\t",$error));
        }

        $output->writeln("");
    }

    protected function printCsvErrorReport($fd)
    {
        foreach($this->errorLog as $error)
        {
            fputcsv($fd,$error);
        }
    }



}