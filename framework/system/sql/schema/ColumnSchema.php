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

namespace system\sql\schema;

use \system\sql\schema\Schema;

/**
 * The column schema object.
 *
 * @author Lumina Framework <lumina@incubator.neticle.com>
 * @since 0.2.0
 */
class ColumnSchema extends Schema
{
	/**
	 * Defines the type for a string data column.
	 *
	 * @type string
	 */
	const TYPE_STRING = 'string';
	
	/**
	 * Defines the type for an integer data column.
	 *
	 * @type string
	 */
	const TYPE_INT = 'int';
	
	/**
	 * Defines the type for a floating point number data column.
	 *
	 * @type string
	 */
	const TYPE_FLOAT = 'float';
	
	/**
	 * Defines the type for a character data column.
	 *
	 * @type string
	 */
	const TYPE_CHAR = 'char';
	
	/**
	 * Defines the type for a boolean data column.
	 *
	 * @type string
	 */
	const TYPE_BOOL = 'bool';
	
	/**
	 * Defines the type for a binary data column.
	 *
	 * @type string
	 */
	const TYPE_BINARY = 'binary';
	
	/**
	 * Defines the type for a enumeration data column.
	 *
	 * @type string
	 */
	const TYPE_ENUM = 'enum';
	
	/**
	 * Defines the type for a timestamp data column.
	 *
	 * @type string
	 */
	const TYPE_TIMESTAMP = 'timestamp';

	/**
	 * The column data type, as defined by the ColumnSchema::TYPE_* constants.
	 *
	 * @type string
	 */
	private $type;
	
	/**
	 * The column size.
	 *
	 * @type int
	 */
	private $size;
	
	/**
	 * The column options, only applicable to "enum".
	 *
	 * @type array
	 */
	private $options;
	
	/**
	 * A flag indicating when the column is required (NOT NULL).
	 *
	 * @type bool
	 */
	private $required;
	
	/**
	 * A flag indicating wether or not this column is auto incrementable.
	 *
	 * @type bool
	 */
	private $autoIncrementable;
	
	/**
	 * A flag indicating wether or not this column's data type is unsigned.
	 *
	 * @type bool
	 */
	private $unsigned;
	
	/**
	 * The column's default value.
	 *
	 * @type string
	 */
	private $default;
	
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
	 * Defines the column data type, as defined by 
	 * the ColumnSchema::TYPE_* constants.
	 *
	 * @param string $type
	 *	The column data type.
	 */
	public function setType($type)
	{
		$this->type = $type;
	}
	
	/**
	 * Returns the column data type, as defined by 
	 * the ColumnSchema::TYPE_* constants.
	 *
	 * @return string
	 *	The column data type.
	 */
	public function getType()
	{
		return $this->type;
	}
	
	/**
	 * Defines the column size.
	 *
	 * The given value may have different meaning depending on the
	 * column type. For instance: when the column is of type "binary", the
	 * value refers to the maximum amount of bytes the column can hold; when
	 * the column is of type "string", the value refers to the maximum amount
	 * of characters a column can hold -- and some characters can have several
	 * bytes depending on the character set.
	 *
	 * @param int $size
	 *	The column size.
	 */
	public function setSize($size)
	{
		$this->size = $size;
	}
	
	/**
	 * Returns the column size.
	 *
	 * The given value may have different meaning depending on the
	 * column type. For instance: when the column is of type "binary", the
	 * value refers to the maximum amount of bytes the column can hold; when
	 * the column is of type "string", the value refers to the maximum amount
	 * of characters a column can hold -- and some characters can have several
	 * bytes depending on the character set.
	 *
	 * @param int $size
	 *	The column size.
	 */
	public function getSize()
	{
		return $this->size;
	}
	
	/**
	 * Defines a flag indicating when the column is required (NOT NULL).
	 *
	 * @param bool $required
	 *	A flag indicating when the column is required (NOT NULL).
	 */
	public function setRequired($required)
	{
		$this->required = $required;
	}
	
	/**
	 * Returns a flag indicating when the column is required (NOT NULL).
	 *
	 * @return bool
	 *	A flag indicating when the column is required (NOT NULL).
	 */
	public function isRequired()
	{
		return $this->required;
	}
	
	/**
	 * Defines the column options, only applicable to "enum".
	 *
	 * @param array $options
	 *	The column options, only applicable to "enum".
	 */
	public function setOptions(array $options)
	{
		$this->options = $options;
	}
	
	/**
	 * Returns the column options, only applicable to "enum".
	 *
	 * @return array
	 *	The column options, only applicable to "enum".
	 */
	public function getOptions()
	{
		return $this->options;
	}
	
	/**
	 * Defines wether or not this column is auto incrementable.
	 *
	 * @param bool $autoIncrementable
	 *	Set to TRUE if the column is auto incrementable, FALSE otherwise.
	 */
	public function setAutoIncrementable($autoIncrementable)
	{
		$this->autoIncrementable = $autoIncrementable;
	}
	
	/**
	 * Checks wether or not this column is auto incrementable.
	 *
	 * @return bool
	 *	Returns TRUE if the column is auto incrementable, FALSE otherwise.
	 */
	public function isAutoIncrementable()
	{
		return $this->autoIncrementable;
	}
	
	/**
	 * Defines wether or not this column's data type is unsigned.
	 *
	 * @param bool $unsigned
	 *	Set to TRUE if the column data type is unsigned, FALSE otherwise.
	 */
	public function setUnsigned($unsigned)
	{
		$this->unsigned = $unsigned;
	}
	
	/**
	 * Checks wether or not this column's data type is unsigned.
	 *
	 * @returns bool
	 *	Returns TRUE if the data type is unsigned, FALSE otherwise.
	 */
	public function isUnsigned()
	{
		return $this->unsigned;
	}
	
	/**
	 * Defines the column's default value.
	 *
	 * @param string $default
	 *	The default value assumed by the column.
	 */
	public function setDefault($default)
	{
		$this->default = $default;
	}
	
	/**
	 * Returns the column's default value.
	 *
	 * @returns string
	 *	Returns the column's default value, if any.
	 */
	public function getDefault()
	{
		return $this->default;
	}
	
}

