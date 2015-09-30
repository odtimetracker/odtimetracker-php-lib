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
	 * @var string $tableName
	 */
	protected $tableName;

	/**
	 * @var string $pkColName
	 */
	protected $pkColName;

	/**
	 * Constructor.
	 *
	 * @param \PDO $pdo
	 * @return void
	 */
	public function __construct(\PDO $pdo, $tableName, $pkColName)
	{
		$this->pdo = $pdo;
		$this->tableName = $tableName;
		$this->pkColName = $pkColName;
	} // end __construct(\PDO $pdo)

	/**
	 * Select all records.
	 *
	 * @param integer $limit (Optional.)
	 * @return array Returns array of {@see \odTimeTracker\Model\EntityInterface}.
	 */
	abstract function selectAll($limit = 0);

	/**
	 * Select entity by its ID.
	 *
	 * @param integer $id
	 * @return EntityInterface|null
	 */
	public function selectById($id)
	{
		$sql = "SELECT * FROM `$this->tableName` WHERE `$this->pkColName` = %d ";
		$stmt = $this->pdo->prepare(sprintf($sql, $id));
		$res = $stmt->execute();

		if ($res === false) {
			return null;
		}

		$rows = $stmt->fetchAll();

		if (count($rows) !== 1) {
			return null;
		}

		return $this->prepareEntity($rows[0]);
	} // end selectById($id)

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
		$sql = "DELETE FROM `$this->tableName` WHERE `$this->pkColName` = :entityId ";

		$stmt = $this->pdo->prepare($sql);
		$stmt->bindParam(':entityId', intval($id), \PDO::PARAM_INT);
		$res = $stmt->execute();

		return ($res !== false && $stmt->rowCount() === 1);
	} // end deleteById($id)

	/**
	 * @internal
	 * @param array $data
	 * @return EntityInterface
	 */
	abstract protected function prepareEntity($data);
} // End of AbstractMapper
