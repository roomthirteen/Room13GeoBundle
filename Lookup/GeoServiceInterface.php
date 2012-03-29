<?php

namespace Room13\GeoBundle\Lookup;

interface GeoServiceInterface
{
    public function hierarchy($id);
    public function search($query, $maxRows=100, array $params=null);
    public function children($id);
    public function get($id);
}
