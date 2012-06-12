<?php

namespace Room13\GeoBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

use Room13\GeoBundle\Entity\Country;
use Room13\GeoBundle\Entity\City;
use Room13\GeoBundle\Entity\Spot;


class TranslateLocationsCommand extends BaseCommand
{

    public function configure()
    {

        $this
            ->setName('room13:geo:translate')
            ->setDescription('Translates the geodatabase into a given language')
            ->addArgument('language',InputArgument::REQUIRED,'Language code to translate to')
            ->addOption('csv-errors','csv',InputOption::VALUE_NONE,'If set the error log will be outputted as csv')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {

        $destinationLanguage = $input->getArgument('language');
        $countryRepo = $this->getEntityManager()->getRepository('Room13GeoBundle:Country');
        $cityRepo = $this->getEntityManager()->getRepository('Room13GeoBundle:City');
        $transRepo = $this->getEntityManager()->getRepository('Gedmo\\Translatable\\Entity\\Translation');

        $output->writeln("Translating geodatabase to language $destinationLanguage");

        foreach($countryRepo->findAll() as $country)
        {

            $countryInfo = $this->geoInfo($country->getName(), array(
                'featureClass' => 'A',
            ));


            if(!isset($countryInfo->alternate_names[$destinationLanguage]))
            {
                $this->logError(array(
                    'translate','country',$country->getName()
                ));

                continue;
            }

            $translatedCountryName = $countryInfo->alternate_names[$destinationLanguage];

            if($translatedCountryName!==$country->getName())
            {
               // translate only if different to default locale
                $output->writeln('  => '.$country->getName()." - ".$translatedCountryName);


                $country->setName($translatedCountryName);
                $country->setTranslatableLocale($destinationLanguage);
                $this->persistAndFlush($country);

            }




            foreach($cityRepo->findByCountry($country) as $city)
            {

                $cityInfo = $this->geoInfo($city->getName(), array(
                    'featureClass' => 'P',
                    'country' => $country->getCountryCode(),
                ));


                if(!isset($cityInfo->alternate_names[$destinationLanguage]))
                {
                    $this->logError(array(
                        'translate','city',$city->getName(),$country->getName()
                    ));

                    continue;
                }

                $translatedCityName = $cityInfo->alternate_names[$destinationLanguage];

                if($translatedCityName!==$city->getName())
                {
                    $output->writeln('      => '.$city->getName()." - ".$translatedCityName);


                    $city->setName($translatedCityName);
                    $city->setTranslatableLocale($destinationLanguage);
                    $this->persistAndFlush($city);

                    //$transRepo->translate($city, 'name', $destinationLanguage, $translatedCityName);
                }
            }

            $this->getEntityManager()->flush();


        }

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



}