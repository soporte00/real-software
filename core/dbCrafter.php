<?php namespace core;

use Closure;
use core\dbManager;
class dbCrafter extends dbManager{

    private $table,
            $haveid=false,
            $haveprimary=false,
            $columns=[];

    
    public function __construct(string $table)
    {
        parent::__construct();

        $this->table = $table;
    }
    
    public function addColumn($column){
       array_push($this->columns, $column);
       return $this;
    }

    public function addParameter($parameter){
        $column = array_pop($this->columns);
        $column = $column.' '.$parameter;
        $this->addColumn($column);
        return $this;
    }
    
    public function nullable(bool $status = true, string $data=''){
        if(!$status){
            return $this->addParameter("NOT NULL default '{$data}'");
        }else{
            return $this->addParameter("NULL");
        }
    }

    public function unsigned(){
        return $this->addParameter("unsigned");
    }

    public function unique($column){
        return parent::run("CREATE UNIQUE INDEX `unique_{$column}` ON {$this->config_prefix}{$this->table}({$column})");
    }
    
    public function primary(string $column){
        
        if($this->haveid || $this->haveprimary) return false;
        
        $this->haveprimary = true;
        return $this->addColumn("PRIMARY KEY (`{$column}`)");
    }
           
    public function foreign(string $column, string $ref,string $ondelete='CASCADE',string $onupdate='CASCADE'){
        $ref = explode('.',$ref);

        return parent::run("ALTER TABLE `{$this->config_prefix}{$this->table}` ADD CONSTRAINT `foreign_{$this->config_prefix}{$this->table}_{$column}` FOREIGN KEY (`{$column}`) REFERENCES `{$this->config_prefix}{$ref[0]}`(`{$ref[1]}`) ON DELETE {$ondelete} ON UPDATE {$onupdate}");
    }

    public function put(array $value,$trace=false){

        if(parent::insert($this->table,$value,$trace)){
            echo "insert finished\n";
        }else{
            echo "insert failed\n";
        }
    }


    public function craft(Closure $callback = null){

        if (! is_null($callback)) {
            $callback($this);
        }

        $columns = implode(',',$this->columns); 
        if(parent::run("CREATE TABLE IF NOT EXISTS `{$this->config_prefix}{$this->table}` (".$columns.")")){
            echo "{$this->config_prefix}{$this->table} table was created\n";
        }else{
            echo "crafting failed\n";
        }
    }

    public function uncraft(){
        if(parent::run("DROP TABLE `{$this->config_prefix}{$this->table}`")){
            echo "{$this->config_prefix}{$this->table} table was removed\n";
        }else{
            echo "uncrafting failed\n";
        }

    }
    
    public function dropIndex($index){
        if( parent::run("ALTER TABLE `{$this->config_prefix}{$this->table}` DROP INDEX `{$index}`")){
            echo "{$index} index from {$this->config_prefix}{$this->table} table was removed\n";
        }else{
            echo "removing index failed\n";
        }
    }

    public function dropPrimary(){
        $this->dropIndex('PRIMARY');
    }

    public function dropUnique($column){
        $this->dropIndex("unique_{$column}");
    }

    public function dropForeign($column){
        if(parent::run("ALTER TABLE {$this->config_prefix}{$this->table} DROP FOREIGN KEY `foreign_{$this->config_prefix}{$this->table}_{$column}`")){
            echo "foreign_{$this->config_prefix}{$this->table}_{$column} key from {$this->config_prefix}{$this->table} table was removed\n";
        }else{
            echo "removing foreign key failed\n";
        }
    }




    public function id(){
      $this->haveid = true;
      $this->addColumn('`id` bigint(15) unsigned AUTO_INCREMENT NOT NULL, PRIMARY KEY (`id`)');     
    }

    public function string(string $column,int $n = 255){
        return $this->addColumn("`{$column}` varchar({$n})");
    }

    public function text(string $column){
        return $this->addColumn("`{$column}` text");
    }

    public function tiny(string $column){
        return $this->addColumn("`{$column}`tinyint(1)");
    }

    public function idnumber(string $column){
        return $this->addColumn("`{$column}` bigint(15) unsigned");
    }

    public function bignumber(string $column){
        return $this->addColumn("`{$column}` bigint(15)");
    }

    public function number(string $column){
        return $this->addColumn("`{$column}` int(10)");
    }

    public function float(string $column,$range='10,3'){
        return $this->addColumn("`{$column}` float({$range})");
    }

    public function double(string $column,$range='10,3'){
        return $this->addColumn("`{$column}` double({$range})");
    }

    public function json(string $column){
        return $this->addColumn("`{$column}`JSON ");
    }

    public function time($column){
        return $this->addColumn("`{$column}` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP");
    }

    public function trace(){
        return $this->addColumn("`created_at` DATETIME(4) NOT NULL DEFAULT CURRENT_TIMESTAMP(4),`created_by` bigint(15) unsigned NOT NULL");
    }

    public function custom($column,$def){
        return $this->addColumn("`{$column}` {$def}");
    }
}