<?php

namespace Room13\GeoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Room13\GeoBundle\Entity\Location
 *
 * @ORM\Table(name="room13_location")
 * @ORM\Entity(repositoryClass="Room13\GeoBundle\Entity\LocationRepository")
 * @Gedmo\Tree(type="nested")
 */
class Location implements \Gedmo\Translatable\Translatable
{

    const TYPE_UNDEFINED=0;
    const TYPE_COUNTRY=100;
    const TYPE_CITY=200;
    const TYPE_PLACE=300;

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=255)
     * @Gedmo\Translatable
     */
    private $name;

    /**
     * @var string $name
     *
     * @ORM\Column(name="shortcut", type="string", length=127, nullable=true)
     */
    private $shortcut;


    /**
     * @var integer $type
     *
     * @ORM\Column(name="type", type="integer")
     */
    private $type;

    /**
     * @var float $lat
     *
     * @ORM\Column(name="lat", type="float")
     */
    private $lat;

    /**
     * @var float $lng
     *
     * @ORM\Column(name="lng", type="float")
     */
    private $lng;

    /**
     * @Gedmo\Locale
     * Used locale to override Translation listener`s locale
     * this is not a mapped field of entity metadata, just a simple property
     */
    private $locale;




    /**
     * @var string $slug
     *
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(name="slug", type="string", length=255)
     */
    private $slug;


    /**
     * @Gedmo\TreeRoot
     * @ORM\Column(name="_root", type="integer", nullable=true)
     */
    private $root;

    /**
     * @Gedmo\TreeLeft
     * @ORM\Column(name="_left", type="integer", nullable=true)
     */
    private $left;

    /**
     * @Gedmo\TreeRight
     * @ORM\Column(name="_right", type="integer", nullable=true)
     */
    private $right;

    /**
     * @Gedmo\TreeLevel
     * @ORM\Column(name="_level", type="integer", nullable=true)
     */
    private $level;

    /**
     * @var Location
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity="Location", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="Location", mappedBy="parent")
     * @ORM\OrderBy({"left" = "ASC"})
     */
    private $children;


    function __construct()
    {

    }


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set type
     *
     * @param integer $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Get type
     *
     * @return integer 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set lat
     *
     * @param float $lat
     */
    public function setLat($lat)
    {
        $this->lat = $lat;
    }

    /**
     * Get lat
     *
     * @return float 
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * Set lng
     *
     * @param float $lng
     */
    public function setLng($lng)
    {
        $this->lng = $lng;
    }

    /**
     * Get lng
     *
     * @return float 
     */
    public function getLng()
    {
        return $this->lng;
    }

    public function setLocale($locale)
    {
        $this->locale = $locale;
    }

    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @param string $shortName
     */
    public function setShortName($shortName)
    {
        $this->shortName = $shortName;
    }

    /**
     * @return string
     */
    public function getShortName()
    {
        return $this->shortName;
    }

    /**
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param \Room13\GeoBundle\Entity\Location $parent
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
    }

    /**
     * @return \Room13\GeoBundle\Entity\Location
     */
    public function getParent()
    {
        return $this->parent;
    }

    public function getLevel()
    {
        return $this->level;
    }

    public function setChildren($children)
    {
        $this->children = $children;
    }

    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @return string
     */
    public function getShortcut()
    {
        return $this->shortcut;
    }


}