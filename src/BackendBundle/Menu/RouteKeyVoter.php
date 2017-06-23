<?php

namespace BackendBundle\Menu;

use Knp\Menu\ItemInterface;
use Knp\Menu\Matcher\Voter\VoterInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class RouteKeyVoter
 *
 * @package \BackendBundle\Menu
 */
class RouteKeyVoter implements VoterInterface
{
    private $request;

    public function __construct(Request $request = null)
    {
        $this->request = $request;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function setRequest($request)
    {
        $this->request = $request;
    }


    /**
     * Checks whether an item is current.
     *
     * If the voter is not able to determine a result,
     * it should return null to let other voters do the job.
     *
     * @param ItemInterface $item
     *
     * @return boolean|null
     */
    public function matchItem(ItemInterface $item)
    {
        if (null === $this->request) {
            return null;
        }

        $routeKey = explode('_', $this->request->get('_route'))[0];
        $itemKey = explode('_', $item->getExtra('routes')[0]['route'])[0];

//        var_dump($routeKey);
//        var_dump($itemKey);

        if ($routeKey === $itemKey) {
            return true;
        }
    }
}