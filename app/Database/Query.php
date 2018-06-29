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

        if( count($arguments) == 0) {
            $this->attributes->addArrgument($name);
            return $this;
        }

        if( count($arguments) == 1) {
            $this->attributes->addArrgument($name, $arguments[0]);
        } else {
            $params = $this->attributes->getParameters($name);
            $nArgs = count($params);

            if( $params != '...' && $nArgs != count($arguments) ) {
                throw new IncorrectNumberOfArgumentsException("Invalid number of arguments for $name function, must be $nArgs");
            }

            if( $params == '...' ) {
                $result = $arguments;
            } else {
                $result = array_combine($params, $arguments);
            }

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

    public function get($array = [])
    {
        $str = $this->__toString();

        $stmt = Connect::instance()->prepare($str);
        if( count($array) ) {
            $stmt->execute($array);
        } else {
            $stmt->execute();
        }

        if( substr($str, 0, 6) == 'insert' ) { 
            return Connect::instance()->lastInsertId();
        }
        
        if( substr($str, 0, 6) == 'select' ) {
            $res = $stmt->fetchAll(\PDO::FETCH_OBJ);
            if( count($res) == 1 ) {
                return $res[0];
            } else {
                return $res;
            }
        }
    }

    public static function table($name)
    {
        $attributes = new IndexQuery();
        $format = new MySqlFormat();
        $qry = new Query($attributes, $format, $name);
        return $qry;
    }

    public static function transaction($callback) {
        Connect::instance()->beginTransaction();
        $res = $callback();

        if( $res ) {
            Connect::instance()->commit();
        } else {
            Connect::instance()->rollBack();
        }
    }
}
