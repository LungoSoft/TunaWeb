<?php 

namespace Tuna\Database\Format;

use Tuna\Database\Exceptions\InvalidQueryException;

class MySqlFormat implements FormatQueryInterface
{
    protected $table;
    protected $delete;
    protected $select;
    protected $set;
    protected $where;
    protected $joins;
    protected $insert;

    public function start()
    {
        $this->table = "";
        $this->delete = "";
        $this->select = "";
        $this->where = "";
        $this->joins = "";
        $this->set = "";
        $this->insert = [];
    }

    public function add($name, $value)
    {
        switch( $name ) {
            case 'table': $this->table = trim($value); break;
            case 'delete': $this->delete = 'd'; break;
            case 'insert': 
                if( !$this->insert ) {
                    $this->insert[0] = $value['first'];
                    $this->insert[1] = $value['second'];
                } else {
                    $this->insert[0] .= ', '.$value['first'];
                    $this->insert[1] .= ', '.$value['second'];
                }
                break;
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
        if( $this->set && ($this->select || $this->joins) ) {
            throw new InvalidQueryException("You can't use select or joins with set statement at the same time");
        }
        if( $this->insert && ($this->joins || $this->select || $this->where || $this->set) ) {
            throw new InvalidQueryException("You can't use joins, select, where or set with insert statement at the same time");
        }
        if( $this->delete && ($this->joins || $this->select || $this->set) ) {
            throw new InvalidQueryException("You can't use joins, select or set with delete statement at the same time");
        }

        //create a insert statement if exists
        if( $this->insert ) {
            $this->query = 'insert into '.$this->table.'('.$this->insert[0].') values('.$this->insert[1].')';
            return;
        }

        //create a delete statement if exists
        if( $this->delete ) {
            $this->query = 'delete from '.$this->table;
            if( $this->where ) {
                $this->query .= ' '.trim($this->where);
            }
            return;
        }

        //create a update statement if exists
        if ($this->set ) {
            $this->query = 'update '.$this->table.' '.trim($this->set);
            if( $this->where ) {
                $this->query .= ' '.trim($this->where);
            }
            return;
        }

        //create a select statement
        if( !$this->select ) {
            $this->select = "*";
        }
        $this->query = 'select '.trim($this->select).' from '.$this->table;
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