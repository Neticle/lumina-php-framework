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

namespace system\web\session;

use \system\web\session\Session;
use \system\web\session\ISessionSaveHandler;
use \system\core\exception\RuntimeException;
use \system\sql\Criteria;
use \system\sql\Expression;
use \system\sql\Reader;
use \system\sql\driver\Driver;

/**
 * The session component allows you to keep track of the user state
 * persistently across multiple requests.
 *
 * This implementation stores the session data in the database, through the
 * "connection" component.
 *
 * @author Lumina Framework <lumina@incubator.neticle.com>
 * @package system.web.session
 * @since 0.2.0
 */
class DatabaseSession extends Session implements ISessionSaveHandler
{
	/**
	 * The underlying database connection component.
	 *
	 * @type Connection
	 */
	private $connection;
	
	/**
	 * The name of the table holding the session data.
	 *
	 * @type string
	 */
	private $table = 'session';
	
	/**
	 * The name of the primary key column holding the session IDs.
	 *
	 * @type string
	 */
	private $keyColumn = 'id';
	
	/**
	 * The name of the column holding the session data.
	 *
	 * @type string
	 */
	private $dataColumn = 'data';
	
	/**
	 * The name of the column holding the session timestamp.
	 *
	 * @type string
	 */
	private $timestampColumn = 'timestamp';

	/**
	 * Creates or re-initializes the session store.
	 *
	 * @param string $savePath
	 *	The session save path.
	 *
	 * @param string $name
	 *	The session name.
	 *
	 * @return bool
	 *	Returns TRUE on success, FALSE otherwise.
	 */
	public function openSessionStore($savePath, $name)
	{
		$this->connection = $this->getComponent('database');
		$this->connection->setTableLocks($this->table, Driver::LOCK_WRITE);
		return true;
	}
	
	/**
	 * Closes the session store.
	 *
	 * @return bool
	 *	Returns TRUE on success, FALSE otherwise.
	 */
	public function closeSessionStore()
	{
		$this->connection->releaseAllLocks();
		return true;
	}
	
	/**
	 * Destroys data from the session store.
	 *
	 * @param string $id
	 *	The ID of the session to destroy.
	 *
	 * @return bool
	 *	Returns TRUE on success, FALSE otherwise.
	 */
	public function destroySessionData($id)
	{
		$criteria = new Criteria();
		$criteria->setAlias('session');
		$criteria->addComparison('session.id', $id);
		
		$this->connection->delete($this->table, $criteria);
		return true;
	}
	
	/**
	 * Purges any expired sessions data.
	 *
	 * @param int $expiry
	 *	The maximum age a session is allowed to be, in seconds.
	 *
	 * @return bool
	 *	Returns TRUE on success, FALSE otherwise.
	 */
	public function purgeSessionData($expiry)
	{
		$criteria = new Criteria();
		$criteria->setAlias('session');
		$criteria->addComparison('session.' . $this->keyColumn, $id);
		$criteria->addCondition('session.' . $this->timestampColumn . ' < (CURRENT_TIMESTAMP - :expiry)');
		$criteria->setParameter(':expiry', $expiry);
		
		$this->connection->delete($this->table, $criteria);
		return true;
	}
	
	/**
	 * Reads data from the session store.
	 *
	 * @param string $id
	 *	The ID of the session to read.
	 *
	 * @return string|bool
	 *	Returns the session contents on success, FALSE otherwise.
	 */
	public function readSessionData($id)
	{
		$criteria = new Criteria();
		$criteria->setAlias('session');
		$criteria->addComparison('session.id', $id);
		
		$row = $this->connection->select($this->table, $criteria)->fetch(Reader::FETCH_ASSOC, true);
		
		if ($row && isset($row[$this->dataColumn]))
		{
			return $row[$this->dataColumn];
		}
		
		return false;
	}
	
	/**
	 * Writes data to the session store.
	 *
	 * @param string $id
	 *	The ID of the session to read.
	 *
	 * @param string $data
	 *	The session contents, as a string.
	 *
	 * @return bool
	 *	Returns TRUE on success, FALSE otherwise.
	 */
	public function writeSessionData($id, $data)
	{
		$criteria = new Criteria();
		$criteria->setAlias('session');
		$criteria->addComparison('session.id', $id);
		
		$fields = array(
			'id' => $id,
			'data' => $data,
			'timestamp' => new Expression('CURRENT_TIMESTAMP')
		);
		
		if ($this->connection->exists($this->table, $criteria))
		{
			$this->connection->update($this->table, $fields, $criteria);
		}
		else
		{
			$this->connection->insert($this->table, $fields);
		}
		
		return true;
	}
}

