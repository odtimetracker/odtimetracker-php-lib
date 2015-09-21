<?php
/**
 * odtimetracker-php-lib
 *
 * @license Mozilla Public License 2.0 https://www.mozilla.org/MPL/2.0/
 * @author Ondřej Doněk, <ondrejd@gmail.com>
 * @link https://github.com/odTimeTracker/odtimetracker-php-lib
 */

namespace odTimeTracker\Model;

/**
 * Interface for mappers.
 */
interface MapperInterface
{
	/**
	 * Create schema.
	 *
	 * @return boolean
	 */
	public function createSchema();

	/**
	 * Select all records.
	 *
	 * @param integer $limit (Optional.)
	 * @return array Returns array of {@see \odTimeTracker\Model\EntityInterface}.
	 */
	public function selectAll($limit = 0);

	/**
	 * Insert new record.
	 *
	 * @param EntityInterface $data
	 * @return mixed Returns `false` or {@see \odTimeTracker\Model\EntityInterface}.
	 */
	public function insert(EntityInterface $entity);

	/**
	 * Update record.
	 *
	 * @param EntityInterface $entity
	 * @return boolean
	 */
	public function update(EntityInterface $entity);

	/**
	 * Delete record.
	 *
	 * @param EntityInterface $entity
	 * @return boolean
	 */
	public function delete(EntityInterface $entity);
} // End of MapperInterface
