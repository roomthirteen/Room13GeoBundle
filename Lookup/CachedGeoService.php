<?php

namespace Room13\GeoBundle\Lookup;

use winzou\CacheBundle\Cache\CacheInterface;

class CachedGeoService implements GeoServiceInterface
{
    /**
     * @var GeoServiceInterface
     */
    private $target;

    /**
     * @var CacheInterface
     */
    private $cache;

    function __construct(GeoServiceInterface $target,CacheInterface $cache)
    {
        $this->target = $target;
        $this->cache = $cache;
    }


    public function hierarchy($id)
    {
        // TODO: Implement hierarchy() method.
        throw new \Exception("IMPLEMENT ME");
    }

    public function search($query, $maxRows = 100, array $params = null)
    {

        $id = 'geonames.search.'.md5($query.$maxRows.join('.',$params));

        if($this->cache->contains($id))
        {
            echo "cached";
            return $this->cache->fetch($id);
        }
        else
        {
            $result = $this->target->search($query,$maxRows,$params);
            $this->cache->save($id,$result);
            return $result;
        }
    }

    public function children($id)
    {
        // TODO: Implement children() method.
        throw new \Exception("IMPLEMENT ME");
    }

    public function get($id)
    {
        // TODO: Implement get() method.
        throw new \Exception("IMPLEMENT ME");
    }


}
