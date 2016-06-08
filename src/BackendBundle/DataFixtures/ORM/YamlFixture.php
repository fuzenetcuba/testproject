<?php

namespace BackendBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Symfony\Component\Yaml\Yaml;

/**
 * Class YamlFixture
 *
 * @package \BackendBundle\DataFixtures\ORM
 */
abstract class YamlFixture extends AbstractFixture
{
    protected function loadData($file)
    {
        $path = realpath(dirname(__FILE__) . '/../fixtures/');

        return Yaml::parse(file_get_contents($path . '/' . $file . '.yml'));
    }

}