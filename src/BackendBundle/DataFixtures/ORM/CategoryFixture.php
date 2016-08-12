<?php

namespace BackendBundle\DataFixtures\ORM;

use BackendBundle\Entity\Business;
use BackendBundle\Entity\Category;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Loads sample data into the category table, for testing or bootstrapping
 * the application
 *
 * @package \BackendBundle\DataFixtures\ORM
 */
class CategoryFixture extends OrderedYamlFixture
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $categories = $this->loadData('categories');

        foreach ($categories['Category'] as $category) {
            $object = new Category();

            $object->setName($category['name']);
            $object->setDescription($category['description']);

            // subcategories
            /*
            foreach ($category['subcategories'] as $subcategory) {
                $subcategoryObject = new Category();

                $subcategoryObject->setName($subcategory['name']);
                $object->addChildren($subcategoryObject);

                $manager->persist($subcategoryObject);
            }
            */

            /** @var Business $business */
            $business = $this->getReference('business');
            $object->addBusiness($business);
            $business->addCategory($object);

            // add one category to the other business so we can test related businesses
            $business = $this->getReference('other-business');
            $object->addBusiness($business);
            $business->addCategory($object);

            $manager->persist($object);
        }

        $manager->flush();
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 2;
    }
}