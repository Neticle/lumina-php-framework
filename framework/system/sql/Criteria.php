<?php

// =============================================================================
//
// Copyright 2013 Neticle
// http://lumina.neticle.com
//
// This file is part of "Lumina/PHP Framework", hereafter referred to as 
// "Lumina".
//
// Lumina is free software: you can redistribute it and/or modify it under the 
// terms of the GNU General Public License as published by the Free Software 
// Foundation, either version 3 of the License, or (at your option) any later
// version.
//
// Lumina is distributed in the hope that it will be useful, but WITHOUT ANY
// WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
// A PARTICULAR PURPOSE. See theGNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License along with
// "Lumina". If not, see <http://www.gnu.org/licenses/>.
//
// =============================================================================

namespace system\sql;

use \system\core\Express;

/**
 * Criteria.
 *
 * @author Lumina Framework <lumina@incubator.neticle.com>
 * @package system.sql
 * @since 0.2.0
 */
class Criteria extends Express
{
	/**
	 * The fields to select.
	 *
	 * @type string
	 */
	private $select = '*';
	
	/**
	 * A flag indicating wether or not a distinct selectis applied.
	 *
	 * @type bool
	 */
	private $isDistinct = false;
	
	/**
	 * The alias to be applied to the main table.
	 *
	 * @type string
	 */
	private $alias;
	
	/**
	 * The foreign table JOIN to be applied.
	 *
	 * @type string
	 */
	private $join;
	
	/**
	 * The condition to be applied.
	 *
	 * @type string
	 */
	private $condition;
	
	/**
	 * The result grouping rule.
	 *
	 * @type string
	 */
	private $group;
	
	/**
	 * The result sorting rule.
	 *
	 * @type string
	 */
	private $sort;
	
	/**
	 * The number of records to skip.
	 *
	 * @type int
	 */
	private $offset;
	
	/**
	 * The maximum number of records to return.
	 *
	 * @type int
	 */
	private $limit;
	
	/**
	 * Constructor.
	 *
	 * @param array $configuration
	 *	The express configuration array.
	 */
	public function __construct(array $configuration = null)
	{
		parent::__construct($configuration);
	}
	
	/**
	 * Defines the fields to be selected.
	 *
	 * The fields specified should be quoted when required in order to prevent
	 * collision with reserved SQL keywords.
	 *
	 * @param string $select
	 *	The fields to be selected, as a CSV string.
	 */
	public function setSelect($select)
	{
		$this->select = $select;
	}
	
	/**
	 * Adds a new field to be selected.
	 *
	 * @param string $field
	 *	The field to be selected, properly quoted when required.
	 */
	public function addSelectField($field)
	{
		$this->select = empty($this->select) ?
			$field : ($this->select . ', ' . $field);
	}
	
	/**
	 * Returns the fields to be selected, as a string.
	 *
	 * @return string
	 *	The fields to be selected.
	 */
	public function getSelect()
	{
		return $this->select;
	}
	
	/**
	 * Returns the fields to be selected, as an array of strings.
	 *
	 * @return string[]
	 *	The fields to be selected.
	 */
	public function getSelectAsArray()
	{
		return preg_split('/(\s*\,\s*)/', $this->select, -1, PREG_SPLIT_NO_EMPTY);
	}
	
	/**
	 * Defines wether or not a distinct select should be performed.
	 *
	 * @param bool $distinct
	 *	When set to TRUE a distinct select will be performed.
	 */
	public function setDistinct($distinct)
	{
		$this->isDistinct = $distinct;
	}
	
	/**
	 * Checks wether or not a distinct select should be performed.
	 *
	 * @return bool
	 *	Returns TRUE for a distinct select, FALSE otherwise.
	 */
	public function isDistinct()
	{
		return $this->isDistinct;
	}
	
	/**
	 * Defines the alias to be applied to the main table.
	 *
	 * @param string $alias
	 *	The alias to be applied to the main table.
	 */
	public function setAlias($alias)
	{
		$this->alias = $alias;
	}
	
	/**
	 * Returns the alias to be applied to the main table.
	 *
	 * @return string
	 *	The alias to be applied to the main table.
	 */
	public function getAlias()
	{
		return $this->alias;
	}
	
	/**
	 * Defines the condition to be applied to this criteria.
	 *
	 * Fields matching commonly reserved keywords should be quoted before
	 * usage. You are encouraged to prefix all columns with the table name
	 * or alias.
	 *
	 * @param string $condition
	 *	The condition to be applied, which may include named parameter markers
	 *	that can later be bound with "setParameter" or "setParameters".
	 */
	public function setCondition($condition)
	{
		$this->condition = $condition;
	}
	
	/**
	 * Defines an additional condition to be applied to this criteria.
	 *
	 * Fields matching commonly reserved keywords should be quoted before
	 * usage. You are encouraged to prefix all columns with the table name
	 * or alias.
	 *
	 * @param string $condition
	 *	The condition to be applied, which may include named parameter markers
	 *	that can later be bound with "setParameter" or "setParameters".
	 *
	 * @param string $glue
	 *	The operator to be applied between the new condition and the ones
	 *	previously defined. Valid values are: "AND" and "OR".
	 */
	public function addCondition($condition, $glue = 'AND')
	{
		$this->condition = empty($this->condition) ?
			$condition : ($this->condition . ' ' . $glue . ' ' . $condition);
	}
	
	/**
	 * Returns the condition to be applied to this criteria.
	 *
	 * @param string $condition
	 *	The condition to be applied.
	 */
	public function getCondition()
	{
		return $this->condition;
	}
	
	/**
	 * Defines a new foreign table join to be applied to this criteria.
	 *
	 * Fields matching commonly reserved keywords should be quoted before
	 * usage. You are encouraged to prefix all columns with the table name
	 * or alias.
	 *
	 * @param string $join
	 *	The foreign table join to be applied.
	 */
	public function setJoin($join)
	{
		$this->join = $join;
	}
	
	/**
	 * Defines an additional foreign table join to be applied to this criteria.
	 *
	 * Fields matching commonly reserved keywords should be quoted before
	 * usage. You are encouraged to prefix all columns with the table name
	 * or alias.
	 *
	 * @param string $join
	 *	The foreign table join to be applied.
	 */
	public function addJoin($join)
	{
		$this->join = empty($this->join) ?
			$join : ($this->join . ' ' . $join);
	}
	
	/**
	 * Returns the foreign table join to be applied to this criteria.
	 *
	 * @param string $condition
	 *	The foreign table join to be applied.
	 */
	public function getJoin()
	{
		return $this->join;
	}
	
	/**
	 * Defines the result grouping rules.
	 *
	 * @param string $group
	 *	The result grouping rules.
	 */
	public function setGroup($group)
	{
		$this->group = $group;
	}
	
	/**
	 * Returns the result grouping rules.
	 *
	 * @return string
	 *	The result grouping rules.
	 */
	public function getGroup()
	{
		return $this->group;
	}
	
	/**
	 * Defines the sorting rules to be applied.
	 *
	 * @param string $sort
	 *	The sorting rules to be applied.
	 */
	public function setSort($sort)
	{
		$this->sort = $sort;
	}
	
	/**
	 * Defines an additional sorting rules to be applied.
	 *
	 * @param string $sort
	 *	The sorting rules to be applied.
	 */
	public function addSort($sort)
	{
		$this->sort = empty($this->sort) ?
			$sort : ($this->sort . ', ' . $sort);
	}
	
	/**
	 * Returns the sorting rules to be applied.
	 *
	 * @return string
	 *	The sorting rules to be applied.
	 */
	public function getSort()
	{
		return $this->sort;
	}
	
	/**
	 * Defines the number of results to skip.
	 *
	 * @param int $offset
	 *	The number of results to skip.
	 */
	public function setOffset($offset)
	{
		$this->offset = $offset;
	}
	
	/**
	 * Returns the number of results to skip.
	 *
	 * @return int
	 *	The number of results to skip.
	 */
	public function getOffset()
	{
		return $this->offset;
	}
	
	/**
	 * Defines the number of records to select.
	 *
	 * @param int $limit
	 *	The number of records to select.
	 */
	public function setLimit($limit)
	{
		$this->limit = $limit;
	}
	
	/**
	 * Returns the number of records to select.
	 *
	 * @return int
	 *	The number of records to select.
	 */
	public function getLimit()
	{
		return $this->limit;
	}
	
	/**
	 * Returns a representation of this instance as an associative array.
	 *
	 * This method is intended to be used mainly by the statement factory
	 * instances, due to the fact that array access is significantly faster,
	 * thus resulting in a small performance boost.
	 *
	 * @return array
	 *	The criteria as an associative array.
	 */
	public function toArray()
	{
		return array(
			'select' => $this->select,
			'distinct' => $this->isDistinct,
			'alias' => $this->alias,
			'join' => $this->join,
			'condition' => $this->condition,
			'group' => $this->group,
			'sort' => $this->sort,
			'offset' => $this->offset,
			'limit' => $this->limit
		);
	}	
}

