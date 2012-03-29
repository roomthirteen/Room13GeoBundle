<?php

namespace Room13\GeoBundle\Lookup;

class GeoObject
{
    private $_parent;
    private $_children;
    private $_provider;


    public $geonameId;
    public $name;
    public $lat;
    public $lng;
    public $countryCode;
    public $countryName;
    public $fcl;
    public $fcode;
    public $fclName;
    public $fcodeName;
    public $population;
    public $adminCode1;
    public $adminName1;
    public $adminCode2;
    public $adminName2;
    public $timezone;
    public $alternate_names;

    /**
     * pass in a geonameId to construct the hierarchy of data
     *
     * @param SimpleXMLElement $fragment
     *
     * @throws GeoLoolupException
     * @access public
     * @return void
     */
    public function __construct(GeoServiceInterface $provider, \SimpleXMLElement $fragment)
    {
        $this->provider = $provider;

        $this->geonameId = intval($fragment->geonameId);
        $this->name = (string)$fragment->name;
        $this->lat = (float)$fragment->lat;
        $this->lng = (float)$fragment->lng;
        $this->countryCode = (string)$fragment->countryCode;
        $this->countryName = (string)$fragment->countryName;
        $this->fcl = (string)$fragment->fcl;
        $this->fcode = (string)$fragment->fcode;
        $this->fclName = (string)$fragment->fclName;
        $this->fcodeName = (string)$fragment->fcodeName;
        $this->population = intval($fragment->population);
        $this->adminCode1 = (string)$fragment->AdminCode1;
        $this->adminName1 = (string)$fragment->AdminName1;
        $this->adminCode2 = (string)$fragment->AdminCode2;
        $this->adminName2 = (string)$fragment->AdminName2;
        $timezone = $fragment->timezone;
        try
        {
            $timezone_atts = $timezone->attributes();
            $this->timezone = array(
                'name' => (string) $timezone,
                'dstOffset' => (float) $timezone_atts->dstOffset,
                'gmtOffset' => (float) $timezone_atts->gmtOffset,
            );
        }
        catch (\Exception $e)
        {
            $this->timezone = array(
                'name' => '',
                'dstOffset' => 0,
                'gmtOffset' => 0,
            );
        }
        $this->alternate_names = array();
        try
        {
            foreach ($fragment->alternateName as $altname)
            {
                $atts = $altname->attributes();
                $this->alternate_names[(string) $atts->lang] = (string) $altname;
            }
        }
        catch (Exception $e)
        {
        }
    }

    /**
     * sets a parent GeoObject object
     *
     * @param GeoObject $parent
     *
     * @access public
     * @return $this
     */
    public function setParent(GeoObject $parent)
    {
        $this->_parent = $parent;
        return $this;
    }

    /**
     * fetches a parent GeoObject object
     *
     * @access public
     * @return GeoObject|false
     */
    public function getParent()
    {
        if (!isset($this->_parent))
        {
            if ($this->name == 'Earth' && $this->lat == 0 && $this->lng == 0) return false;
            $parent = false;
            $hierarchy = $this->hierarchy($this->geonameId);
            foreach (array_reverse($hierarchy) as $obj)
            {
                if ($obj->geonameId == $this->geonameId)
                {
                    $parent = $obj->getParent();
                    break;
                }
            }
            $this->_parent = $parent;
        }
        return $this->_parent;
    }

    /**
     * sets children of the GeoObject
     *
     * @param array $children
     *
     * @access public
     * @return $this
     */
    public function setChildren(array $children)
    {
        foreach ($children as $child)
        {
            if (!($child instanceof GeoObject)) throw new GeoLookupException("Objects of wrong type supplied to GeoObject::setChildren");
            $child->setParent($this);
        }
        $this->_children = $children;
        return $this;
    }

    /**
     * fetches children of the GeoObject
     *
     * @access public
     * @return array
     */
    public function getChildren()
    {
        if (!isset($this->_children))
        {
            $this->setChildren($this->children($this->geonameId));
        }
        return $this->_children;
    }
}
