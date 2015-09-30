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
 * Simple interface for our model entities.
 */
interface EntityInterface
{
	/**
	 * Set entity with given data.
	 *
	 * @param array $data
	 * @return void
	 */
	public function exchangeArray(array $data);

	/**
	 * Retrieve entity as array.
	 *
	 * @return array
	 */
	public function getArrayCopy();

	/**
	 * Retrieve numeric identifier of the entity (usually primary key).
	 *
	 * @return integer|null
	 */
	public function getId();
}
