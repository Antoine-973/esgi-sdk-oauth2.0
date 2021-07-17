<?php
namespace App;

class ProviderCollection{

    private $credentials = [];

    public function __construct(array $providersCredentials)
    {
        $this->credentials = $providersCredentials;

    }

    public function getAllProviders()
    {
        $instances = [];

        foreach($this->credentials as $name => $params){

            if($class = $this->getProviderClass($name)){
                $values = [];
                foreach($params as $key => $value){
                        $values[] = $value;
                }

                $instance = new $class(...$values);
                $instances[$name] = $instance;
            }
        }

        return $instances;
    }

    public function getProvider(string $providerName)
    {
        if($class = $this->getProviderClass($providerName)){
            $params = $this->credentials[$providerName] ?? null;
            $values = array_values($params) ?? null;

            return ($values) ? new $class(...$values) : null;
        }

    }

    protected function getProviderClass($providerName)
    {
        $file = __DIR__ . DIRECTORY_SEPARATOR . ucfirst($providerName) . 'Provider.php';
        $class = 'App\\' . ucfirst($providerName) . 'Provider';
        if(file_exists($file) && class_exists($class)){
            return $class;
        }

        return false;
    }
}
