<?php

namespace BackendBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

/**
 * Ordered fixture class, loads the data from yaml files and
 * and hability to specify the desired orders
 *
 * @package \BackendBundle\DataFixtures\ORM
 */
abstract class OrderedYamlFixture extends YamlFixture implements OrderedFixtureInterface
{
}