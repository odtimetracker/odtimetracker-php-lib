<?php
/**
 * odtimetracker-php-cgi
 *
 * @license Mozilla Public License 2.0 https://www.mozilla.org/MPL/2.0/
 * @author Ondřej Doněk, <ondrejd@gmail.com>
 */

namespace odTimeTrackerTest\Model;

/**
 * @covers \odTimeTracker\Model\ProjectEntity
 */
class ProjectEntityTest extends \PHPUnit_Framework_TestCase {
	/**
	 * @var array $testProjects
	 */
	protected $testProjects;

	protected function setUp() {
		$this->testProjects = \TestData::getProjects();
	}

	protected function tearDown() {
		parent::tearDown();
	}

	/**
	 * @internal
	 * @param mixed $datetime
	 * @return mixed
	 */
	private function getDateTime($datetime) {
		if (($datetime instanceof \DateTime)) {
			return $datetime;
		}

		if (empty($datetime)) {
			return null;
		}

		return new \DateTime($datetime);
	}

	/**
	 * @covers \odTimeTracker\Model\ProjectEntity::__construct
	 */
	public function testConstructWithNulls() {
		$entity = new \odTimeTracker\Model\ProjectEntity();
		$this->assertNull($entity->getId());
		$this->assertNull($entity->getProjectId());
		$this->assertNull($entity->getName());
		$this->assertNull($entity->getDescription());
		$this->assertNull($entity->getCreated());
	}

	/**
	 * @covers \odTimeTracker\Model\ProjectEntity::__construct
	 */
	public function testConstructWithValues() {
		foreach ($this->testProjects as $data) {
			$entity = new \odTimeTracker\Model\ProjectEntity($data);
			$this->assertEquals($data['ProjectId'], $entity->getId());
			$this->assertEquals($data['ProjectId'], $entity->getProjectId());
			$this->assertEquals($data['Name'], $entity->getName());
			$this->assertEquals($data['Description'], $entity->getDescription());

			$this->assertEquals($this->getDateTime($data['Created']), $this->getDateTime($entity->getCreated()));
		}
	}

	/**
	 * @covers \odTimeTracker\Model\ProjectEntity::exchangeArray
	 */
	public function testExchangeArrayWithNulls() {
		foreach ($this->testProjects as $data) {
			$entity = new \odTimeTracker\Model\ProjectEntity();
			$entity->exchangeArray($data);
			$entity->exchangeArray(array());
			$this->assertNull($entity->getId());
			$this->assertNull($entity->getProjectId());
			$this->assertNull($entity->getName());
			$this->assertNull($entity->getDescription());
			$this->assertNull($entity->getCreated());
		}
	}

	/**
	 * @covers \odTimeTracker\Model\ProjectEntity::exchangeArray
	 */
	public function testExchangeArrayWithValues() {
		foreach ($this->testProjects as $data) {
			$entity = new \odTimeTracker\Model\ProjectEntity();
			$entity->exchangeArray($data);
			$this->assertEquals($data['ProjectId'], $entity->getProjectId());
			$this->assertEquals($data['Name'], $entity->getName());
			$this->assertEquals($data['Description'], $entity->getDescription());
			$this->assertEquals($this->getDateTime($data['Created']), $this->getDateTime($entity->getCreated()));
		}
	}

	/**
	 * @covers \odTimeTracker\Model\ProjectEntity::getArrayCopy
	 */
	public function testGetArrayCopyWithNulls() {
		$entity = new \odTimeTracker\Model\ProjectEntity();
		$copy = $entity->getArrayCopy();
		$this->assertNull($copy['ProjectId']);
		$this->assertNull($copy['Name']);
		$this->assertNull($copy['Description']);
		$this->assertNull($copy['Created']);
	}

	/**
	 * @covers \odTimeTracker\Model\ProjectEntity::getArrayCopy
	 */
	public function testGetArrayCopyWithValues() {
		foreach ($this->testProjects as $data) {
			$entity = new \odTimeTracker\Model\ProjectEntity($data);
			$copy = $entity->getArrayCopy();
			$this->assertEquals($data['ProjectId'], $copy['ProjectId']);
			$this->assertEquals($data['Name'], $copy['Name']);
			$this->assertEquals($data['Description'], $copy['Description']);
			$this->assertEquals($this->getDateTime($data['Created']), $this->getDateTime($copy['Created']));
		}
	}

	/**
	 * @covers \odTimeTracker\Model\ProjectEntity::getCreatedFormatted
	 */
	public function testGetCreatedFormatted() {
		$project1 = new \odTimeTracker\Model\ProjectEntity($this->testProjects[2]);
		$this->assertNull($project1->getCreatedFormatted());

		$project2 = new \odTimeTracker\Model\ProjectEntity($this->testProjects[3]);
		$this->assertEquals('5.10.2011 10:00', $project2->getCreatedFormatted());

		$project3 = new \odTimeTracker\Model\ProjectEntity($this->testProjects[4]);
		$this->assertEquals('10.10.2011 10:00', $project3->getCreatedFormatted());
	}

	/**
	 * @covers \odTimeTracker\Model\ProjectEntity::getCreatedRfc3339
	 */
	public function testGetCreatedRfc3339() {
		$project1 = new \odTimeTracker\Model\ProjectEntity($this->testProjects[2]);
		$this->assertNull($project1->getCreatedRfc3339());

		$project2 = new \odTimeTracker\Model\ProjectEntity($this->testProjects[3]);
		$this->assertEquals('2011-10-05T10:00:00+01:00', $project2->getCreatedRfc3339());

		$project3 = new \odTimeTracker\Model\ProjectEntity($this->testProjects[4]);
		$this->assertEquals('2011-10-10T10:00:00+01:00', $project3->getCreatedRfc3339());
	}
}
