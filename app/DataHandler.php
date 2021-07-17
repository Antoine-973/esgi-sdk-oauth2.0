<?php
namespace App;

class DataHandler {
    
    public function findData($criteria, $filename, $findAll = false)
    {
        $apps = read_file($filename);
        $results = array_values(
            array_filter(
                $apps,
                fn($app) => count(array_intersect_assoc($app, $criteria)) === count($criteria)
            )
        );

        if ($findAll) return $results;

        return count($results) === 1 ? $results[0] : null;
    }

    public function findApp($criteria)
    {
        return findData($criteria, './data/app.data');
    }

    public function findAllCode($criteria)
    {
        return findData($criteria, './data/code.data', true);
    }

    public function findCode($criteria)
    {
        return findData($criteria, './data/code.data');
    }

    public function findToken($criteria)
    {
        return findData($criteria, './data/token.data');
    }
}