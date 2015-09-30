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
class ProjectEntityTest extends \odTimeTrackerTest\AbstractModelTestCase
{
	/**
	 * @covers \odTimeTracker\Model\ProjectEntity::__construct
	 */
	public function testConstructWithNulls()
	{
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
	public function testConstructWithValues()
	{
		$projects = self::getDataProjects();

		foreach ($projects as $data) {
			$entity = new \odTimeTracker\Model\ProjectEntity($data);
			$this->assertEquals($data['ProjectId'], $entity->getId());
			$this->assertEquals($data['ProjectId'], $entity->getProjectId());
			$this->assertEquals($data['Name'], $entity->getName());
			$this->assertEquals($data['Description'], $entity->getDescription());
			$this->assertEquals($data['Created'], $entity->getCreated());
		}
	}

	/**
	 * @covers \odTimeTracker\Model\ProjectEntity::exchangeArray
	 */
	public function testExchangeArrayWithNulls()
	{
		$projects = self::getDataProjects();

		foreach ($projects as $data) {
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
	public function testExchangeArrayWithValues()
	{
		$projects = self::getDataProjects();

		foreach ($projects as $data) {
			$entity = new \odTimeTracker\Model\ProjectEntity();
			$entity->exchangeArray($data);
			$this->assertEquals($data['ProjectId'], $entity->getProjectId());
			$this->assertEquals($data['Name'], $entity->getName());
			$this->assertEquals($data['Description'], $entity->getDescription());
			$this->assertEquals($data['Created'], $entity->getCreated());
		}
	}

	/**
	 * @covers \odTimeTracker\Model\ProjectEntity::getArrayCopy
	 */
	public function testGetArrayCopyWithNulls()
	{
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
	public function testGetArrayCopyWithValues()
	{
		$projects = self::getDataProjects();

		foreach ($projects as $data) {
			$entity = new \odTimeTracker\Model\ProjectEntity($data);
			$copy = $entity->getArrayCopy();
			$this->assertEquals($data['ProjectId'], $copy['ProjectId']);
			$this->assertEquals($data['Name'], $copy['Name']);
			$this->assertEquals($data['Description'], $copy['Description']);
			$this->assertEquals($data['Created'], $copy['Created']);
		}
	}

	/**
	 * @covers \odTimeTracker\Model\ProjectEntity::getCreatedFormatted
	 */
	public function testGetCreatedFormatted()
	{
		$projects = self::getDataProjects();

		$project1 = new \odTimeTracker\Model\ProjectEntity($projects[2]);
		//$this->assertNull($project1->getCreatedFormatted());
		$this->assertEquals('5.10.2011 9:35', $project1->getCreatedFormatted());

		$project2 = new \odTimeTracker\Model\ProjectEntity($projects[3]);
		$this->assertEquals('5.10.2011 10:40', $project2->getCreatedFormatted());

		$project3 = new \odTimeTracker\Model\ProjectEntity($projects[4]);
		$this->assertEquals('10.10.2011 10:00', $project3->getCreatedFormatted());
	}

	/**
	 * @covers \odTimeTracker\Model\ProjectEntity::getCreatedRfc3339
	 */
	public function testGetCreatedRfc3339()
	{
		$projects = self::getDataProjects();

		$project1 = new \odTimeTracker\Model\ProjectEntity($projects[2]);
		//$this->assertNull($project1->getCreatedRfc3339());
		$this->assertEquals('2011-10-05T09:35:00+01:00', $project1->getCreatedRfc3339());

		$project2 = new \odTimeTracker\Model\ProjectEntity($projects[3]);
		$this->assertEquals('2011-10-05T10:40:00+01:00', $project2->getCreatedRfc3339());

		$project3 = new \odTimeTracker\Model\ProjectEntity($projects[4]);
		$this->assertEquals('2011-10-10T10:00:00+01:00', $project3->getCreatedRfc3339());
	}
}
