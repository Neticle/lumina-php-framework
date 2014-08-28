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

use \system\core\exception\RuntimeException;
use \system\data\Model;
use \system\sql\Criteria;
use \system\sql\Reader;

/**
 * The Record combines the features provided by the Model and StatementFactory
 * classes in order to automate the input data validation and it's effects
 * on the database.
 *
 * @author Lumina Framework <lumina@incubator.neticle.com>
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
	 * Checks wether or not this record is yet to be saved.
	 *
	 * @return bool
	 *	Returns TRUE if the record has not been saved, FALSE otherwise.
	 */
	public function isNewRecord()
	{
		return $this->newRecord;
	}
	
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
		$instances = [];
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
	
	/**
	 * Creates a criteria instance based on the given attributes.
	 *
	 * @param array $attributes
	 *	The attributes to create the criteria from, indexed by name.
	 *
	 * @return Criteria
	 *	The criteria instance.
	 */
	private function createCriteriaFromAttributes(array $attributes)
	{
		$database = $this->getDatabase();
		$table = $this->getTableName();
	
		$criteria = new Criteria();
		
		foreach ($attributes as $name => $value)
		{
			$criteria->addComparison($database->quote($table . '.' . $name), $value);
		}
		
		return $criteria;
	}
	
	/**
	 * Finds and returns the first record matching the criteria.
	 *
	 * The returned record instance will be a clone from this model with its
	 * context set to 'update' and the new attributes.
	 *
	 * @param array $attributes
	 *	The attributes to create the criteria from, indexed by name.
	 *
	 * @return Record
	 *	The record instance, if any.
	 */
	public function findByAttributes(array $attributes)
	{
		return $this->find($this->createCriteriaFromAttributes($attributes));
	}
	
	/**
	 * Finds and returns all records matching the criteria.
	 *
	 * The returned record instance will be a clone from this model with its
	 * context set to 'update' and the new attributes.
	 *
	 * @param array $attributes
	 *	The attributes to create the criteria from, indexed by name.
	 *
	 * @return Record[]
	 *	The record instances.
	 */
	public function findAllByAttributes(array $attributes)
	{
		return $this->findAll($this->createCriteriaFromAttributes($attributes));
	}
	
	/**
	 * Counts and returns the number of records matching the given criteria.
	 *
	 * @throws PDOException
	 *	Thrown when the underlying statement fails to be prepared
	 *	or executed with the underlying database connection handle.
	 *
	 * @param Criteria|array $criteria
	 *	The criteria as an instance or an express configuration array.
	 *
	 * @return int
	 *	The number of records matching the criteria.
	 */
	public function count($criteria = null)
	{
		return $this->getDatabase()->count($this->getTableName(), $criteria);
	}
	
	/**
	 * Checks if there's any record matching the criteria.
	 *
	 * @throws PDOException
	 *	Thrown when the underlying statement fails to be prepared
	 *	or executed with the underlying database connection handle.
	 *
	 * @param Criteria|array $criteria
	 *	The criteria as an instance or an express configuration array.
	 *
	 * @return bool
	 *	Returns TRUE if there's at least one record matching the criteria,
	 *	FALSE otherwise.
	 */
	public function exists($criteria = null)
	{
		return $this->getDatabase()->exists($this->getTableName(), $criteria);
	}
	
	/**
	 * Saves the changes made to this record.
	 *
	 * @throws PDOException
	 *	Thrown when the underlying statement fails to be prepared
	 *	or executed with the underlying database connection handle.
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
		$attributes = $this->getAttributes();
		$autoIncrementable = false;
		$fields = [];
		
		// Go through all column schemas
		foreach ($columns as $name => $column)
		{
			if ($column->isAutoIncrementable())
			{
				$autoIncrementable = $name;
			}
			
			if (isset($attributes[$name]) || array_key_exists($name, $attributes))
			{
				$fields[$name] = $attributes[$name];
			}
		}
		
		
		if ($this->newRecord)
		{
			// It's a new record
			$db->insert($tableName, $fields);
			$this->newRecord = false;
			
			if ($autoIncrementable)
			{
				$id = $db->getLastInsertId();
				$this->setAttribute($autoIncrementable, $id);
				$this->primaryKey[$autoIncrementable] = $id;
			}
			else
			{
				// Reload the primary key field values
				$primaryKey = $table->getPrimaryKey();
		
				if (isset($primaryKey[0]))
				{
					foreach ($primaryKey as $field)
					{
						$this->primaryKey[$field] = $fields[$field];
					}
				}
			}
		}
		else
		{
			if (empty($this->primaryKey))
			{
				throw new RuntimeException('Can not update record without primary key.');
			}
			
			// Update based on the current primary key
			$criteria = $this->createCriteriaFromAttributes($this->primaryKey);			
			$db->update($tableName, $fields, $criteria);
			
			// Reload the current primary key
			$this->primaryKey = array_intersect_key($fields, $this->primaryKey);
		}
		
		return $this->onAfterSave();
	}
	
	/**
	 * Deletes this record from the database.
	 *
	 * @throws PDOException
	 *	Thrown when the underlying statement fails to be prepared
	 *	or executed with the underlying database connection handle.
	 *
	 * @return bool
	 *	Returns TRUE on success, FALSE otherwise.
	 */
	public function delete()
	{
		if ($this->newRecord || empty($this->primaryKey))
		{
			throw new RuntimeException('Can not delete record without primary key.');
		}
		
		if ($this->onDelete())
		{
			$db = $this->getDatabase();
			$criteria = $this->createCriteriaFromAttributes($this->primaryKey);
		
			$db->delete($this->getTableName(), $criteria);
			$this->primaryKey = null;
			$this->newRecord = true;
			return $this->onAfterDelete();
		}
		
		return false;
	}
	
	/**
	 * Deletes a set of records matching the criteria from the database.
	 *
	 * @throws PDOException
	 *	Thrown when the underlying statement fails to be prepared
	 *	or executed with the underlying database connection handle.
	 *
	 * @param Criteria|array $criteria
	 *	The criteria as an instance or an express configuration array.
	 */
	public function deleteAll($criteria = null)
	{
		if (isset($criteria) && is_array($criteria))
		{
			$criteria = new Criteria($criteria);
		}
		
		$this->getDatabase()->delete($this->getTableName(), $criteria);
	}
	
	/**
	 * This method encapsulates the 'delete' event.
	 *
	 * @return bool
	 *	Returns FALSE to cancel the event, TRUE otherwise.
	 */
	protected function onDelete()
	{
		return $this->raiseArray('delete');
	}
	
	/**
	 * This method encapsulates the 'afterDelete' event.
	 *
	 * Please note that even though the event may be canceled the record
	 * has already been deleted from the database and can not be brought back
	 * unless you are using transactions!
	 *
	 * @return bool
	 *	Returns FALSE to cancel the event, TRUE otherwise.
	 */
	protected function onAfterDelete()
	{
		return $this->raiseArray('afterDelete');
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
	 * Please note that even though the event may be canceled the record
	 * has already been saved to the database and can not be removed
	 * unless you are using transactions!
	 *
	 * @return bool
	 *	Returns FALSE to cancel the event, TRUE otherwise.
	 */
	protected function onAfterSave()
	{
		return $this->raiseArray('afterSave');
	}
	
}

