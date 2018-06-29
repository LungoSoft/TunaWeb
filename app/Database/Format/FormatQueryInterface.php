<?php 

namespace Tuna\Database\Format;

interface FormatQueryInterface
{
    public function start();
    public function add($name, $value);
    public function end();
    public function generate();
}