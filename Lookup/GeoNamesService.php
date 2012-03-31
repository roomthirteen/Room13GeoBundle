<?php

namespace Room13\GeoBundle\Lookup;

class GeoNamesService implements GeoServiceInterface
{
    const URL_BASE = 'http://ws.geonames.org/';

    /**
     * retrieves data from ws.geonames.org and runs through it
     * creating GeoNames objects as needed and setting parents
     *
     * @param int $id
     *
     * @throws GeoNamesException
     * @access public
     * @return array
     */
    public function hierarchy($id)
    {
        if (!intval($id)) throw new GeoLokupException("Bad id supplied to GeoNamesService::hierarchy");
        $xml = simplexml_load_file(self::URL_BASE . 'hierarchy?geonameId=' . intval($id) . '&style=FULL', null, true);
        $results = array();
        foreach ($xml->children() as $child)
        {
            if ($child->getName() != 'geoname') continue;
            $geoname = new GeoObject($this,$child);
            if (isset($parent)) $geoname->setParent($parent);
            $parent = $geoname;
            $results[$geoname->geonameId] = $geoname;
        }
        return $results;
    }

    /**
     * retrieves data from ws.geonames.org and runs through it
     * creating GeoNames objects as needed and setting children
     *
     * @param int $id
     *
     * @throws GeoNamesException
     * @access public
     * @return array
     */
    public function children($id)
    {
        if (!intval($id)) throw new GeoLookupException("Bad id supplied to GeoNamesService::children");
        $xml = simplexml_load_file(self::URL_BASE . 'children?geonameId=' . intval($id) . '&style=FULL', null, true);
        $results = array();
        foreach ($xml->children() as $child)
        {
            if ($child->getName() != 'geoname') continue;
            $geoname = new GeoObject($this,$child);
            $results[$geoname->geonameId] = $geoname;
        }
        return $results;
    }

    /**
     * queries the geonames service using a search term
     * and returns all results in an array
     *
     * @param string $term
     * @param int $rows row count to return, defaults to 100
     *
     * @throws GeoNamesException
     * @access public
     * @return array
     */
    public function search($term, $rows=100, array $params=null)
    {


        if (!is_string($term) || strlen($term) == 0)
        {
            throw new GeoLookupException("Bad search term supplied to GeoNamesService::search");
        }

        $defaultParams = array(
            'q'         => $term,
            'maxRows'   => intval($rows),
            'style'     => 'FULL'
        );

        $params = array_merge($defaultParams,$params);

        $url = self::URL_BASE.'search?'.http_build_query($params);
        $xml = simplexml_load_file($url, null, true);
        $results = array();

        foreach ($xml->children() as $child)
        {
            if ($child->getName() != 'geoname') continue;
            $geoname = new GeoObject($this,$child);
            $results[$geoname->geonameId] = $geoname;
        }

        return $results;
    }

    /**
     * fetches all data for a single geonameId - but not it's hierarchy
     *
     * @param int $id
     *
     * @throws GeoNamesException
     * @access public
     * @return GeoNamesObject
     */
    public function get($id)
    {
        if (!intval($id)) throw new GeoLookupException("Bad id supplied to GeoNamesService::get");
        $xml = simplexml_load_file(self::URL_BASE . 'get?geonameId=' . intval($id) . '&style=FULL', null, true);
        $geoname = new GeoObject($this,$xml);

        return $geoname;
    }
}
