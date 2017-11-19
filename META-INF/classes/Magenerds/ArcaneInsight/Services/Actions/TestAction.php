<?php

/**
 * \Magenerds\ArcaneInsight\Services\Actions\TestAction
 *
 * @copyright Copyright (c) 2017 TechDivision GmbH (http://www.techdivision.com)
 * @author    Bernhard Wick <b.wick@techdivision.com>
 * @link      https://github.com/wick-ed/arcane-insight
 */

namespace Magenerds\ArcaneInsight\Services\Actions;

use Magenerds\ArcaneInsight\Tests\TestInterface;
use Magenerds\ArcaneInsight\Entities\Test;

/**
 *
 * @copyright Copyright (c) 2017 TechDivision GmbH (http://www.techdivision.com)
 * @author    Bernhard Wick <b.wick@techdivision.com>
 * @link      https://github.com/wick-ed/arcane-insight
 *
 * @Stateless
 */
class TestAction extends AbstractRestAwareAction
{

    //<editor-fold desc="Class constants">
    /**
     * The entity type this bean is primarily targeting
     *
     * @var string TARGET_ENTITY
     */
    const TARGET_ENTITY = 'Magenerds\ArcaneInsight\Entities\Test';
    //</editor-fold>

    //<editor-fold desc="Public getters">
    /**
     * Returns the class name of the entity this bean primarily deals with
     *
     * @return string
     */
    public function getTargetEntity()
    {
        return static::TARGET_ENTITY;
    }
    //</editor-fold>

    /**
     * Loads all or only a certain user entity
     *
     * @param Test $test The test to create
     *
     * @return array|Test
     */
    public function create(Test $test)
    {
        return parent::create($test);
    }

    /**
     * Loads all or only a certain user entity
     *
     * @param string $key The key to find instance by
     *
     * @return array|Test
     */
    public function findByKey($key)
    {
        return $this->findBy(array('key' => $key));
    }

    /**
     *
     */
    public function syncKnownTests()
    {
        // get path to our tests
        $testReflection = new \ReflectionClass('Magenerds\ArcaneInsight\Tests\TestInterface');
        $testPath = dirname($testReflection->getFileName());

        // require all the files to make their names known to PHP
        foreach (array_diff(scandir($testPath), array('..', '.')) as $testFile) {
            $potentialFilePath = $testPath . DIRECTORY_SEPARATOR . $testFile;
            if (strpos($testFile, 'Magenerds_ArcaneInsight_Tests_') === 0) {
                require_once $potentialFilePath;
            }
        }

        // get all the test classes and create instances from them
        foreach (get_declared_classes() as $declaredClass) {
            if (strpos($declaredClass, 'Magenerds\ArcaneInsight\Tests\\') === 0 && strrpos($declaredClass, 'Interface') === false) {
                $testImplementation = new $declaredClass();
                if ($testImplementation instanceof TestInterface) {
                    $test = new Test();
                    $test->setKey($testImplementation->getKey());
                    $test->setImplementationClass($declaredClass);
                    $existingTest = $this->findByKey($test->getKey());
                    if (is_null($existingTest)) {
                        $this->create($test);
                    } else {
                        $test->setId($existingTest->getId());
                        $this->update(array($test));
                    }
                }
            }
        }
    }
}
