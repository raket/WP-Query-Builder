<?php
namespace WPQueryBuilder;

class CompositeWhereClause implements WhereClause {

	private $whereClauses = [];
	private $bindngs = [];

	public function __construct(){
		//$this->whereClauses = func_get_args();
	}

	public function isEmpty(){
		return count($this->whereClauses) === 0;
	}

	public function andWhere(WhereClause $where){
		$this->whereClauses[] = ['AND', $where, 'isRaw' => false];
	}

	public function orWhere(WhereClause $where){
		$this->whereClauses[] = ['OR', $where, 'isRaw' => false];
	}

    public function orWhereRaw($where){
        $this->whereClauses[] = ['OR', $where, 'isRaw' => true];
    }

    public function andWhereRaw($where){
        $this->whereClauses[] = ['AND', $where, 'isRaw' => true];
    }

	public function buildSql() {

		$this->bindings = [];

		if(count($this->whereClauses) === 0) {
			return '';
		}

		$sql = '';

		foreach($this->whereClauses as $i => $whereClause){

			if($i > 0) {
					$operator = $whereClause[0];
					$sql .= ' ' . $operator;
			}

			if ($whereClause['isRaw']) {
                $sql .= ' ' . $whereClause[1];
            } else {
                $whereClauseSql = $whereClause[1]->buildSql();

                if($whereClause[1] instanceof CompositeWhereClause){
                    $whereClauseSql = '(' . $whereClauseSql . ')';
                }

                $sql .= ' ' . $whereClauseSql;
                $this->bindings = array_merge($this->bindings, $whereClause[1]->getBindings());
            }
		}

		return trim($sql);
	}

	public function getBindings(){
		return $this->bindings;
	}

}
