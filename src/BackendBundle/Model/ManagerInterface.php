<?php

namespace BackendBundle\Model;

/**
 * Interface to be implemented by the entity managers. This adds an additional level
 * of abstraction between your application, and the actual repository.
 *
 * This interface provides the very basic operations (CRUD) so each implementation
 * should add their own operations depending on their domain.
 *
 * @author Jorge Luis Betancourt <betancourt.jorge@gmail.com>
 */
interface ManagerInterface
{
    /**
     * Return the DQL to fetch all model objects
     *
     * @return mixed
     */
    public function findAllQuery();

    /**
     * Return all model objects
     *
     * @return mixed
     */
    public function findAll();

    /**
     * Return a new empty model object
     *
     * @return mixed
     */
    public function create();

    /**
     * Return the object model associated with {id}
     *
     * @param $id
     *
     * @return mixed
     */
    public function find($id);

    /**
     * Deletes a model object referenced by {id}
     *
     * @param $id
     *
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function deleteById($id);

    /**
     * Deletes an instance of a model object
     *
     * @param $instance
     *
     * @return mixed
     */
    public function delete($instance);
}
