<?php 

namespace Tuna\Database\Format;

use Tuna\Database\Exceptions\InvalidQueryException;

class MySqlFormat implements FormatQueryInterface
{
    protected $table;
    protected $select;
    protected $set;
    protected $where;
    protected $joins;

    public function start()
    {
        $this->table = "";
        $this->select = "";
        $this->where = "";
        $this->joins = "";
        $this->set = "";
    }

    public function add($name, $value)
    {
        switch( $name ) {
            case 'table': $this->table = $value; break;
            case 'select': $this->select = implode(", ", $value); break;
            case 'set': 
                $comparation = "{$value['first']} {$value['comparation']} {$value['second']}";
                if( !$this->set ) {
                    $this->set .= "set $comparation";
                } else {
                    $this->set .= ", $comparation";
                }
                break;
            case 'where': 
            case 'orWhere': 
                $comparation = "{$value['first']} {$value['comparation']} {$value['second']}";
                if( !$this->where ) {
                    $this->where .= "where $comparation";
                } else {
                    $type = $name == 'where' ? 'and' : 'or';
                    $this->where .= " $type $comparation";
                }
                break;
            case 'join': 
            case 'leftJoin': 
            case 'rightJoin': 
            case 'innerJoin': 
                $type = str_replace("_", " ", strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $name)));//"leftJoin" to "left join"
                $comparation = "{$value['table']} on {$value['local_id']} {$value['comparation']} {$value['foreign_id']}";
                $this->joins .= "$type $comparation ";
                break;
        }
    }

    public function end()
    {
        if( $this->set && $this->select ) {
            throw new InvalidQueryException("You can't use select and set at the same time");
        }
        if( $this->set && $this->joins ) {
            throw new InvalidQueryException("You can't use joins and set at the same time");
        }

        if( !$this->select ) {
            $this->select = "*";
        }

        if ($this->set ) {
            $this->query = 'update '.trim($this->table).' '.trim($this->set);
        } else {
            $this->query = 'select '.trim($this->select).' from '.trim($this->table);
        }

        if( $this->joins ) {
            $this->query .= ' '.trim($this->joins);
        }
        if( $this->where ) {
            $this->query .= ' '.trim($this->where);
        }
    }

    public function generate()
    {
        return trim($this->query);
    }
}