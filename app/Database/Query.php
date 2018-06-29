<?php

namespace Tuna\Database;

use Tuna\Database\Exceptions\IncorrectNumberOfArgumentsException;
use Tuna\Database\Format\MySqlFormat;
use Tuna\Database\Format\FormatQueryInterface;

class Query
{
    /**
     * check $indexes for know content
     */
    protected $attributes;
    protected $format;

    public function __construct(IndexQuery $attributes, FormatQueryInterface $format, string $name)
    {
        $this->attributes = $attributes;
        $this->format = $format;
        $this->attributes->addArrgument('table', $name);
    }

    public function __call($name, $arguments)
    {
        if( $name == 'table' ) {
            throw new \Exception("You cant use table property again");
        }

        if( count($arguments) == 1) {
            $this->attributes->addArrgument($name, $arguments[0]);
        } else {
            $params = $this->attributes->getParameters($name);
            $nArgs = count($params);

            if( $nArgs != count($arguments) ) {
                throw new IncorrectNumberOfArgumentsException("Invalid number of arguments for $name function, must be $nArgs");
            }

            $result = array_combine($params, $arguments);

            $this->attributes->addArrgument($name, $result);
        }

        return $this;
    }
    
    public function __toString()
    {
        $query = $this->attributes->get();
        $this->format->start();

        foreach( $query as $value) {

            if( is_array($value) ) {
                current($value);
                $key = key($value);
                $this->format->add($key, $value[$key]);
            } else { 
                $this->format->add($key, $value);
            }

        }

        $this->format->end();

        return $this->format->generate();
    }

    public function get()
    {
        $str = $this->__toString();
        return Connect::instance()->execute($str);
    }

    public static function table($name)
    {
        $attributes = new IndexQuery();
        $format = new MySqlFormat();
        $qry = new Query($attributes, $format, $name);
        return $qry;
    }
}
