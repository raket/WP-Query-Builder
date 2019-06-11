<?php
namespace WPQueryBuilder;

class JoinMultipleClause {

	const LEFT = 'LEFT';
	const RIGHT = 'RIGHT';
	const INNER = 'INNER';
	const FULL = 'FULL';
	const USING = 'USING';

	private $type = null;

	private $table = null;

	private $joins = null;

	public function __construct($type, $table){
		$this->assertValidJoinType($type);
		$this->type = $type;
		$this->table = $table;
	}

	public function on($joins) {
		$this->joins = $joins;
	}

    public function buildSql() {

        // TODO: Implement multiple joins
    }

	private function assertValidJoinType($type){
		$allowed = [
			JoinClause::LEFT, JoinClause::RIGHT, JoinClause::INNER, JoinClause::FULL
		];
		if(!in_array($type, $allowed, true)){
			throw new \InvalidArgumentException(sprintf(
				"Invalid JOIN type. Allowed values are: %s. You gave: '%s'",
				implode(', ', $allowed),
				$type
			));
		}
	}

	private function assertValidOperator($operator){
		$allowed = [
			WhereClause::EQUALS, WhereClause::NOTEQUALS, WhereClause::GREATER,
			WhereClause::LESS, WhereClause::GREATEREQUALS, WhereClause::LESSEQUALS,
			JoinClause::USING
		];
		if(!in_array($operator, $allowed, true)){
			throw new \InvalidArgumentException(sprintf(
				"Invalid operator for ON. Allowed values are: %s. You gave: '%s'",
				implode(', ', $allowed),
				$operator
			));
		}
	}
}
