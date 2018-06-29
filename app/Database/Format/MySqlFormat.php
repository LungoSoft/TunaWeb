<?php 

namespace Tuna\Database\Format;

class MySqlFormat implements FormatQueryInterface
{
    protected $query;
    protected $where;
    protected $joins;

    public function start()
    {
        $this->query = "select 1 from 2";
        $this->where = "";
        $this->joins = "";
    }

    public function add($name, $value)
    {
        switch( $name ) {
            case 'table': $this->query = str_replace("2", $value, $this->query); break;
            case 'select': 
                $selects = implode(", ", $value);
                $this->query = str_replace("1", $selects, $this->query); break;
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
        $this->query .= ' '.trim($this->joins).' '.trim($this->where);
    }

    public function generate()
    {
        return trim($this->query);
    }
}