<?php
namespace App;

class FileHandler{
    
    public function read_file($filename)
    {
        if (!file_exists($filename)) throw new RuntimeException("{$filename} not exists");

        $data = file($filename);
        return array_map(fn($item) => unserialize($item), $data);
    }

    public function write_file($data, $filename)
    {
        if (!file_exists($filename)) throw new RuntimeException("{$filename} not exists");

        $data = array_map(fn($item) => serialize($item), $data);
        return file_put_contents($filename, implode(PHP_EOL, $data));
    }

}