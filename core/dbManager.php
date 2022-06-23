<?php namespace core;

use \PDO;
use \PDOException;
use config\dbConfig;
use core\logmaker;

class dbManager extends dbConfig
{
	private $conn,$join,$order,$limit,$page;
	
	
	public function __construct()
	{
	
		try {
			//host - db - user - pass
			$this->conn = new PDO('mysql:host='.$this->config_host.';dbname='.$this->config_db, $this->config_user, $this->config_password,[
				PDO::ATTR_PERSISTENT => True,
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
			]);
			$this->conn->exec('SET NAMES UTF8');
			self::set();
		} catch (PDOException $e) {
			logmaker::create($e->getMessage());
		}
	}

	public function set(){
		$this->join='';
		$this->order='';
		$this->limit=$this->config_pagination;
		$this->page=1;
	}

	public function join(string $join){
		$this->join = $join;
	}
	
	public function order(string $order){
		$this->order='ORDER BY '. $order;
	}
	
	public function limit(int $limit){
		$this->limit = $limit;
	}
	
	public function page(int $page=1){
		$this->page = $page;
	}
	
	public function run(string $query,array $bind=null,string $fetch=null,bool $reset=false){
		try{
			
			$q = $this->conn->prepare($query);
			$q->execute($bind);
			


			if($fetch==null){
				return $q;
			}elseif($fetch=='fetch'){
				$response = $q->fetch(PDO::FETCH_ASSOC);
			}elseif($fetch == 'fetchAll'){
				$response = $q->fetchAll(PDO::FETCH_ASSOC);
			}elseif($fetch == 'count'){
				$response = $q->rowCount();
			}else{
				$response = false;
			}
			
			
			// reset join,order,limit,page
			if($reset)self::set();
			
			return $response;
		}catch(PDOException $e){
			logmaker::create($e->getMessage()."\n->".$query);
		}		
	}








	
	public function count(string $select,string $table, string $where,array $bind = null){
		return $this->run(
			"SELECT {$select} FROM {$this->config_prefix}{$table} {$this->join} WHERE {$where} {$this->order} LIMIT {$this->limit}",
			$bind,
			'count',
			true
		);
	}










	public function select(string $select,string $table, string $where,array $bind){
		return $this->run(
			"SELECT {$select} FROM {$this->config_prefix}{$table} {$this->join} WHERE {$where} {$this->order}",
			$bind,
			'fetch',
			true
		);		
	}











	/**
	 * ->Listed All rows from a table limited by this->limit
	 * ->the response was established with the current page number and the total number of them
	 */
	public function listed(string $select,string $table, string $where,array $bind = null){
		
		if(isset($_GET['page']) && is_numeric($_GET['page'])) $this->page = (int)$_GET['page'];

		$offset = $this->page > 1 ? ($this->limit * $this->page) - $this->limit : 0 ;

		/**
		 * Number of pages
		 */
		$pages = $this->run(
			"SELECT id FROM {$this->config_prefix}{$table} {$this->join} WHERE {$where}",
			$bind,
			'count',
			false
		);

		$current = $this->page;
		$pages = ceil($pages / $this->limit);
		
		/**
		 * Query
		 */
		$query = $this->run(
			"SELECT {$select} FROM {$this->config_prefix}{$table} {$this->join} WHERE {$where} {$this->order} LIMIT {$this->limit} OFFSET {$offset}",
			$bind,
			'fetchAll',
			true
		);

		return ["current"=>$current,"pages"=>$pages,"list"=>$query];
	}












	public function update(string $table,string $set, string $where,array $bind = null){
		return $this->run("UPDATE {$this->config_prefix}{$table} SET {$set} WHERE $where",$bind);
	}












	
	/**
	 * insert(string tablename,array ['columnName'=>'columnValues'], bool trace_stamp)
	 */
	public function insert(string $table,array $bind,bool $trace=false){

		$timeCrafter=date('Y/m/d H:i:s'.substr(microtime(), 1, 5));
		
		$userCrafter=(isset(session::Read('USER')['id']))?session::Read('USER')['id']:0;

		if($trace){
			$bind['created_at']=$timeCrafter;
			$bind['created_by']=$userCrafter;
		}

		$fields=array_keys($bind);
		$preparedFields=[];
	

		foreach($fields as $key){
			array_push($preparedFields,':'.$key);
		}


		
		if(
			$this->run(
				"INSERT INTO {$this->config_prefix}{$table} (". implode(',',$fields) .") VALUES (". implode(',',$preparedFields).")",
				$bind
			)
		)
		{
			return [$timeCrafter,$userCrafter];
		}


		return false;
	}










	public function delete(string $table,string $where,array $bind){
		return $this->run("DELETE FROM {$this->config_prefix}{$table} WHERE $where",$bind);
	}











	public static function searchFilter($search = null)
	{
		if ($search == null || trim($search) == '') return '%%';
		return '%' . str_replace(' ', '%', $search) . '%';
	}








	public function __destruct(){$this->conn = null;}
}