<?php

namespace App\Contracts;


interface ElasticPostSearch
{
    public function createQueryElastic(array $body);
}