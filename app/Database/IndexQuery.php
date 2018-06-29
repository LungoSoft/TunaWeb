<?php 

namespace Tuna\Database;

use Tuna\Database\Exceptions\NoIndexFoundException;
use Tuna\Database\Exceptions\IncorrectValueTypeException;
use Tuna\Database\Exceptions\IncorrectNumberOfArgumentsException;

class IndexQuery
{
    protected $indexes = [];
    protected $parameters = [
        'table' => '',
        'select' => '...',
        'insert' => ['first', 'second'],
        'set' => ['first', 'comparation', 'second'],
        'where' => ['first', 'comparation', 'second'],
        'orWhere' => ['first', 'comparation', 'second'],
        'join' => ['table', 'local_id', 'comparation', 'foreign_id'],
        'leftJoin' => ['table', 'local_id', 'comparation', 'foreign_id'],
        'rightJoin' => ['table', 'local_id', 'comparation', 'foreign_id'],
        'innerJoin' => ['table', 'local_id', 'comparation', 'foreign_id'],
    ];

    public function addArrgument($name, $value)
    {
        $this->exception($name);

        if( is_string($this->parameters[$name]) ) {

            if( $this->parameters[$name] == '...' ) {
                
                if( is_array($value) ) {
                    $this->indexes[] = [$name => $value];
                } else {
                    $this->indexes[] = [$name => [$value]];
                }

            } else {
                if( !is_string($value) ) {
                    throw new IncorrectValueTypeException("$vale is not a string, string required for index $name");
                }

                $this->indexes[] = [$name => $value];
            }

        }

        if( is_array($this->parameters[$name]) ) {
            if( !is_array($value) ) {
                throw new IncorrectValueTypeException("$vale is not an array, array required for index $name");
            }

            $params = $this->parameters[$name];
            $nArgNedded = count($params);
            $nArgPassed = count($value);

            if( $nArgNedded != $nArgPassed) {
                throw new IncorrectNumberOfArgumentsException("$name required $nArgNedded number of arguments, but $nArgPassed was recive");
            }

            foreach( $params as $key ) {
                if( !array_key_exists($key, $value) ) {
                    throw new NoIndexFoundException("$key was not founded in array and is required for index $name");
                }
            }

            $this->indexes[] = [$name => $value];
        }
    }

    public function exist($name)
    {
        return isset($this->parameters[$name]);
    }

    public function numberOfArguments($name)
    {
        $this->exception($name);
        
        if( is_string($this->parameters[$name]) )
            return 1;
        
        if( is_array($this->parameters[$name]) )
            return count($this->parameters[$name]);
    }

    public function getParameters($name)
    {
        $this->exception($name);

        return $this->parameters[$name];
    }

    public function get()
    {
        return $this->indexes;
    }

    private function exception($name)
    {
        if( !$this->exist($name) ) {
            throw new NoIndexFoundException("Index $name not found for create query");
        }
    }
}
