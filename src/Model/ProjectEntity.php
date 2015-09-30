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
 * Project entity.
 */
class ProjectEntity implements \odTimeTracker\Model\EntityInterface
{
	/**
	 * Project's identifier.
	 *
	 * @var integer $projectId
	 */
	protected $projectId;

	/**
	 * Project's name.
	 *
	 * @var string $name
	 */
	protected $name;

	/**
	 * Project's description.
	 *
	 * @var string $description
	 */
	protected $description;

	/**
	 * Date time of project's creation (RFC3339).
	 *
	 * @var \DateTime $created
	 */
	protected $created;

	/**
	 * Constructor.
	 *
	 * @param array $data (Optional). Data to initialize entity with.
	 * @return void
	 */
	public function __construct($data = array())
	{
		$this->exchangeArray($data);
	} // end __construct($data = array())

	/**
	 * Set entity with given data.
	 *
	 * @param array $data
	 * @return void
	 */
	public function exchangeArray(array $data)
	{
		$this->setProjectId(isset($data['ProjectId']) ? $data['ProjectId'] : null);
		$this->setName(isset($data['Name']) ? $data['Name'] : null);
		$this->setDescription(isset($data['Description']) ? $data['Description'] : null);
		$this->setCreated(isset($data['Created']) ? $data['Created'] : null);
	} // end exchangeArray(array $data)

	/**
	 * Retrieve entity as array.
	 *
	 * @return array
	 */
	public function getArrayCopy()
	{
		return array(
			'ProjectId' => $this->projectId,
			'Name' => $this->name,
			'Description' => $this->description,
			'Created' => $this->created,
			// *Extra* properties
			'CreatedFormatted' => $this->getCreatedFormatted()
		);
	} // end getArrayCopy()

	/**
	 * Retrieve numeric identifier of the entity (usually primary key).
	 *
	 * @return integer|null
	 */
	public function getId()
	{
		return $this->getProjectId();
	} // end getId()

	/**
	 * Retrieve project's identifier.
	 *
	 * @return integer|null
	 */
	public function getProjectId()
	{
		return $this->projectId;
	} // end getProjectId()

	/**
	 * Set project's identifier.
	 *
	 * @param integer $val
	 * @return \odTimeTracker\Model\ProjectEntity
	 */
	public function setProjectId($val)
	{
		$this->projectId = $val ? (int) $val : null;
	} // end setProjectId($val)

	/**
	 * Retrieve project's name.
	 *
	 * @return string|null
	 */
	public function getName()
	{
		return $this->name;
	} // end getName()

	/**
	 * Set project's name.
	 *
	 * @param string $val
	 * @return \odTimeTracker\Model\ProjectEntity
	 */
	public function setName($val)
	{
		$this->name = $val ? (string) $val : null;
	} // end setName($val)

	/**
	 * Retrieve project's description.
	 *
	 * @return string|null
	 */
	public function getDescription()
	{
		return $this->description;
	} // end getDescription()

	/**
	 * Set project's description.
	 *
	 * @param string $val
	 * @return \odTimeTracker\Model\ProjectEntity
	 */
	public function setDescription($val)
	{
		$this->description = $val ? (string) $val : null;
	} // end setDescription($val)

	/**
	 * Retrieve date time when was the project created.
	 *
	 * @return \DateTime|null
	 */
	public function getCreated()
	{
		return $this->created;
	} // end getCreated()

	/**
	 * Retrieve formatted date time when was project created.
	 *
	 * @return string|null
	 */
	public function getCreatedFormatted()
	{
		return (is_null($this->created)) ? null : $this->created->format('j.n.Y G:i');
	} // end getCreatedFormatted()

	/**
	 * @return string|null
	 */
	public function getCreatedRfc3339()
	{
		$created = $this->getCreated();
		return (is_null($created)) ? null : $created->format(\DateTime::RFC3339);
	} // end getCreatedRfc3339()

	/**
	 * Set date time when was the project created.
	 *
	 * @param DateTime|string $val Date time of creation.
	 * @return \odTimeTracker\Model\ProjectEntity
	 */
	public function setCreated($val)
	{
		if (($val instanceof \DateTime)) {
			$this->created = $val;
		}
		else if (is_string($val) && !empty($val)) {
			$this->created = new \DateTime($val);
		}
		else {
			$this->created = null;
		}

		return $this;
	} // end setCreated($val)
} // End of ProjectEntity
