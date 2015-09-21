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
 * Abstract mapper.
 */
abstract class AbstractMapper implements MapperInterface
{
	/**
	 * @var \PDO $pdo
	 */
	protected $pdo;

	/**
	 * Constructor.
	 *
	 * @param \PDO $pdo
	 * @return void
	 */
	public function __construct(\PDO $pdo)
	{
		$this->pdo = $pdo;
	} // end __construct(\PDO $pdo)

	/**
	 * Create schema.
	 *
	 * @return boolean
	 */
	abstract function createSchema();

	/**
	 * Select all records.
	 *
	 * @param integer $limit (Optional.)
	 * @return array Returns array of {@see \odTimeTracker\Model\EntityInterface}.
	 */
	abstract function selectAll($limit = 0);

	/**
	 * Insert new record.
	 *
	 * @param EntityInterface $data
	 * @return mixed Returns `false` or {@see \odTimeTracker\Model\EntityInterface}.
	 */
	abstract function insert(EntityInterface $entity);

	/**
	 * Update record.
	 *
	 * @param EntityInterface $entity
	 * @return mixed Returns `false` or {@see \odTimeTracker\Model\EntityInterface}.
	 */
	abstract function update(EntityInterface $entity);

	/**
	 * Delete record.
	 *
	 * @param EntityInterface $entity
	 * @return boolean
	 */
	public function delete(EntityInterface $entity)
	{
		return $this->deleteById($entity->getId());	
	} // end delete(EntityInterface $entity)

	/**
	 * Delete record by its identifier.
	 *
	 * @param integer $id
	 * @return boolean
	 */
	public function deleteById($id)
	{
		$entityId = intval($entity);

		if ($entityId == 0) {
			return false;
		}

		$stmt = $this->db->getPdo()->prepare(<<<EOD
DELETE FROM `:table` WHERE `:pkcolname` = :entityId LIMIT 1;
EOD
		);
		$stmt->bindParam(':table', self::TABLE_NAME);
		$stmt->bindParam(':pkcolname', self::PK_COL_NAME);
		$stmt->bindParam(':entityId', $entityId, \PDO::PARAM_INT);

		return $stmt->execute();
	} // end deleteById($id)
} // End of AbstractMapper
