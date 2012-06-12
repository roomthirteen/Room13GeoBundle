<?php

namespace Room13\GeoBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

use Room13\GeoBundle\Entity\Country;
use Room13\GeoBundle\Entity\City;
use Room13\GeoBundle\Entity\Spot;


class InitLocationsCommand extends BaseCommand
{

    public function configure()
    {

        $this
            ->setName('room13:geo:init')
            ->setDescription('Initialized the geo database based on some text files containing language and city names')
            ->addArgument('path',InputArgument::REQUIRED,'Path to geo text files')
            ->addOption('clear','c',InputOption::VALUE_NONE,'If to clear all existing locations before import')
            ->addOption('csv-errors','csv',InputOption::VALUE_NONE,'If set the error log will be outputted as csv')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $dataDirectory = $input->getArgument('path');
        $countries = $this->getImportDataset($dataDirectory.'/countries.txt');

        if($input->getOption('clear'))
        {
            $output->writeln('Clearing existing locations');
            $this->getEntityManager()->getConnection()->exec('TRUNCATE room13_geo_location');
        }


        $output->writeln('Importing '.count($countries).' countries...');

        foreach($countries as $countryName)
        {
            $output->writeln("  => $countryName");
            $country = $this->importCountry($countryName);

            if(!is_object($country))
            {
                continue;
            }

            $cityNames = $this->getImportDataset($dataDirectory.'/cities_'.$countryName.'.txt');

            foreach($cityNames as $cityName)
            {
                $output->writeln('      => '.$cityName);
                $this->importCity($cityName,$country);
            }

        }

        $output->writeln('');
        if($this->hasErrors())
        {
            if($input->getOption('csv-errors'))
            {
                $this->printCsvErrorReport(STDERR);
            }
            else
            {
                $this->printErrorReport($output);
            }
        }
    }

    private function importCity($name,Country $country)
    {
        $cityInfo = $this->geoInfo($name, array(
            'featureClass' => 'P',
            'country' => $country->getCountryCode(),
        ));

        if(!is_object($cityInfo))
        {
            $this->logError(array(
               'geocode','city',$name,$country->getName()
            ));

            return;
        }

        $city = new City();
        $city->setCountry($country);
        $city->setName($cityInfo->name);
        $city->setLat($cityInfo->lat);
        $city->setLng($cityInfo->lng);

        $this->getEntityManager()->persist($city);
        $this->getEntityManager()->flush($city);

        return $city;
    }

    private function importCountry($name)
    {
        // geocode country
        $countryInfo = $this->geoInfo($name, array(
            'featureClass' => 'A',
        ));

        if(!is_object($countryInfo))
        {
            $this->logError(array(
                'geocode','country',$name
            ));

            return;
        }

        $country = new Country();
        $country->setName($countryInfo->name);
        $country->setCountryCode(strtolower($countryInfo->countryCode));
        $country->setLat($countryInfo->lat);
        $country->setLng($countryInfo->lng);

        $this->getEntityManager()->persist($country);
        $this->getEntityManager()->flush($country);

        return $country;
    }

    private function getImportDataset($file)
    {
        $plainContent = file_get_contents($file);

        // get and trim lines
        $entries = array_map('trim',explode("\n",$plainContent));

        // filter out empty lines
        $entries = array_filter($entries,function($line){
            return strlen($line)>0 && substr($line,0,1)!=='#';
        });

        return $entries;

    }
}