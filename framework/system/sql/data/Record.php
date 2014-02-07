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

namespace system\sql\data;

use \system\data\Model;
use \system\sql\Criteria;
use \system\sql\Reader;

/**
 * The Record combines the features provided by the Model and StatementFactory
 * classes in order to automate the input data validation and it's effects
 * on the database.
 *
 * @author Lumina Framework <lumina@incubator.neticle.com>
 * @package system.sql.data
 * @since 0.2.0
 */
abstract class Record extends Model
{
	/**
	 * The schema of the table this record is linked to.
	 *
	 * @type TableSchema
	 */
	private $schema;
	
	/**
	 * A flag indicating wether or not this is a new record.
	 *
	 * @type bool
	 */
	private $newRecord;
	
	/**
	 * The primary key values identifying this record in the database.
	 *
	 * @type array
	 */
	private $primaryKey;

	/**
	 * Constructor.
	 *
	 * @param string $context
	 *	The initial model context.
	 *
	 * @param array $attributes
	 *	An associative array containing the initial attribute values.
	 */
	public function __construct($context = 'insert', array $attributes = null)
	{
		parent::__construct($context, $attributes);
		$this->newRecord = true;
	}
	
	/**
	 * Returns the name of the table this record is to be linked with.
	 *
	 * @return string
	 *	The record table name.
	 */
	protected abstract function getTableName();
	
	/**
	 * Returns the database component to be used by the record.
	 *
	 * The default implementation returns the application "database"
	 * component.
	 *
	 * @return Connection
	 *	The database connection component.
	 */
	protected function getDatabase()
	{
		return $this->getComponent('database');
	}
	
	/**
	 * Returns the schema of the table this record is linked to.
	 *
	 * @param bool $refresh
	 *	When set to TRUE any previously cached schema information will be
	 *	invalidated and re-fetched.
	 *
	 * @return TableSchema
	 *	The schema of the table this record is linked to.
	 */
	protected final function getTableSchema($refresh = false)
	{
		if (!isset($this->schema))
		{
			$this->schema = $this->getDatabase()->getDriver()->
				getSchema()->getTableSchema($this->getTableName(), $refresh);
		}
		
		return $this->schema;
	}
	
	/**
	 * Returns the primary key values of this instance.
	 *
	 * If the record is still to be saved, or if a primary key is not defined
	 * for the table it is linked to, NULL will be returned.
	 *
	 * @return array
	 *	The primary key values, indexed by field, or NULL.
	 */
	public final function getPrimaryKey()
	{
		return $this->primaryKey;
	}
	
	/**
	 * Finds and returns the first record matching the criteria.
	 *
	 * The returned record instance will be a clone from this model with its
	 * context set to 'update' and the new attributes.
	 *
	 * @param Criteria|array $criteria
	 *	An instance of Criteria or an associative array defining it's
	 *	express configuration.
	 *
	 * @return Record
	 *	The record instance, if any.
	 */
	public function find($criteria = null)
	{
		$db = $this->getDatabase();
		$reader = $db->select($this->getTableName(), $criteria);
		$record = $reader->fetch(Reader::FETCH_ASSOC, true);
		
		if ($record)
		{
			$primaryKey = $this->getTableSchema()->getPrimaryKey();
		
			$instance = clone $this;
			$instance->setContext('update');
			$instance->setAttributes($record);
			$instance->newRecord = false;
			
			// Define the current primary key values to enable 'update'
			if (isset($primaryKey[0]))
			{
				$instance->primaryKey = array_intersect_key(
					$record, array_flip($primaryKey)
				);
			}
			
			return $instance;
		}
	}
	
	/**
	 * Finds and returns all records matching the criteria.
	 *
	 * The returned record instance will be a clone from this model with its
	 * context set to 'update' and the new attributes.
	 *
	 * @param Criteria|array $criteria
	 *	An instance of Criteria or an associative array defining it's
	 *	express configuration.
	 *
	 * @return Record[]
	 *	The record instances.
	 */
	public function findAll($criteria = null)
	{
		$db = $this->getDatabase();
		$instances = array();
		$primaryKey = $this->getTableSchema()->getPrimaryKey();
		$reader = $db->select($this->getTableName(), $criteria);
		
		if (isset($primaryKey[0]))
		{
			$primaryKey = array_flip($primaryKey);
		}
		else
		{
			$primaryKey = null;
		}
		
		while ($record = $reader->fetch(Reader::FETCH_ASSOC, false))
		{
			$instance = clone $this;
			$instance->setContext('update');
			$instance->setAttributes($record);
			
			$instance->newRecord = false;
			
			// Define the current primary key values to enable 'update'
			if (isset($primaryKey))
			{
				$instance->primaryKey = array_intersect_key($record, $primaryKey);
			}
			
			$instances[] = $instance;
		}
		
		return $instances;
	}
	
	private function createCriteriaFromAttributes(array $attributes)
	{
		$database = $this->getDatabase();
	
		$criteria = new Criteria();
		$criteria->setAlias('t');
		
		foreach ($attributes as $name => $value)
		{
			$criteria->addComparison($database->quote('t.' . $name), $value);
		}
		
		return $criteria;
	}
	
	public function findByAttributes(array $attributes)
	{
		return $this->find($this->createCriteriaFromAttributes($attributes));
	}
	
	public function findAllByAttributes(array $attributes)
	{
		return $this->findAll($this->createCriteriaFromAttributes($attributes));
	}
	
	/**
	 * Saves the changes made to this record.
	 *
	 * @param bool $validate
	 *	When set to TRUE the record will be validated before the save
	 *	procedure actually starts.
	 *
	 *	You are strongly encouraged to validate it before saving any
	 *	value to the database.
	 *
	 * @return bool
	 *	Returns TRUE on success, FALSE otherwise.
	 */
	public function save($validate = true)
	{	
		if (!$this->onBeforeSave())
		{
			return false;
		}
	
		if ($validate && !$this->validate())
		{
			return false;
		}
		
		if (!$this->onSave())
		{
			return false;
		}
		
		// Build the fields array
		$db = $this->getDatabase();
		$table = $this->getTableSchema();
		$tableName = $table->getName();
		$columns = $table->getColumns();
		$primaryKey = $table->getPrimaryKey();
		$autoIncrementable = false;
		$fields = array();
		
		// Go through all column schemas
		foreach ($columns as $name => $column)
		{
			if ($column->isAutoIncrementable())
			{
				$autoIncrementable = $name;
			}
			
			$fields[$name] = $this->getAttribute($name);
		}
		
		
		if ($this->newRecord)
		{
			// It's a new record
			$db->insert($tableName, $fields);
			$this->newRecord = false;
			
			if ($autoIncrementable)
			{
				$this->setAttribute($autoIncrementable, $db->getLastInsertId());
			}
		}
		else
		{
			// Update records matching this 
			$criteria = new Criteria();
			$criteria->setAlias('t');
			
			if (!isset($primaryKey[0]))
			{
				throw new RuntimeException('Can not update record without primary key.');
			}
			
			foreach ($primaryKey as $index => $key)
			{
				$parameter = ':srsp_' . $index;
				
				$criteria->addCondition('t.' . $db->quote($key) . '=' . $parameter);
				$criteria->setParameter($parameter, $this->primaryKey[$key]);
			}
			
			$db->update($tableName, $fields, $criteria);
		}
		
		// Reload the primary key field values
		if (isset($primaryKey[0]))
		{
			foreach ($primaryKey as $field)
			{
				$this->primaryKey[$field] = $this->getAttribute($field);
			}
		}
		
		if ($this->onAfterSave())
		{
			return true;
		}
		
		return false;
	}
	
	/**
	 * This method encapsulates the 'save' event.
	 *
	 * @return bool
	 *	Returns FALSE to cancel the event, TRUE otherwise.
	 */
	protected function onSave()
	{
		return $this->raiseArray('save');
	}
	
	/**
	 * This method encapsulates the 'beforeSave' event.
	 *
	 * @return bool
	 *	Returns FALSE to cancel the event, TRUE otherwise.
	 */
	protected function onBeforeSave()
	{
		return $this->raiseArray('beforeSave');
	}
	
	/**
	 * This method encapsulates the 'afterSave' event.
	 *
	 * @return bool
	 *	Returns FALSE to cancel the event, TRUE otherwise.
	 */
	protected function onAfterSave()
	{
		return $this->raiseArray('afterSave');
	}
	
}

