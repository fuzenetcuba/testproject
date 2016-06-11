<?php

namespace BackendBundle\Entity;

use Doctrine\ORM\EntityManager;
use FOS\UserBundle\Model\UserManagerInterface;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\FOSUBUserProvider as BaseClass;
use Symfony\Component\Security\Core\User\UserInterface;

class OAuthUserProvider extends BaseClass
{
    /**
     * @var EntityManager
     */
    protected $entity_manager;

    /**
     * OAuthUserProvider constructor.
     * @param EntityManager $entity_manager
     */
    public function __construct(EntityManager $entity_manager, UserManagerInterface $userManager, array $properties)
    {
        parent::__construct($userManager, $properties);
        $this->entity_manager = $entity_manager;
    }


    /**
     * {@inheritdoc}
     */
     public function connect(UserInterface $user, UserResponseInterface $response)
    {
        //echo var_dump("HHHHHHHHHHHHHHHHHHHHHHHHHHHHHH"); die;
        $property = $this->getProperty($response);
        $username = $response->getUsername();
        //on connect - get the access token and the user ID
        $service = $response->getResourceOwner()->getName();
        $setter = 'set'.ucfirst($service);
        $setter_id = $setter.'Id';
        $setter_token = $setter.'AccessToken';
        //we "disconnect" previously connected users
        if (null !== $previousUser = $this->userManager->findUserBy(array($property => $username))) {
            $previousUser->$setter_id(null);
            $previousUser->$setter_token(null);
            $this->userManager->updateUser($previousUser);
        }
        //we connect current user
        $user->$setter_id($username);
        $user->$setter_token($response->getAccessToken());
        $this->userManager->updateUser($user);
    }
    /**
     * {@inheritdoc}
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        $username = $response->getUsername();
        $user = $this->userManager->findUserBy(array('username' => $username));
        //when the user is registrating

        //echo var_dump("HHHHHHHHHHHHHHHHHHHHHHHHHHHHHH"); die;
        if (null === $user) {
            $service = $response->getResourceOwner()->getName();
            $setter = 'set'.ucfirst($service);
            $setter_id = $setter.'Id';
            $setter_token = $setter.'AccessToken';
            // create new user here
            $user = $this->userManager->createUser();
            $user->$setter_id($username);
            $user->$setter_token($response->getAccessToken());
            //I have set all requested data with the user's username
            //modify here with relevant data
            $user->setUsername($response->getUsername());
            $user->setEmail($response->getEmail());
            $user->setPassword($response->getUsername());
            $user->setPlainPassword($response->getUsername());
            $user->setEnabled(true);
            $user->addRole("ROLE_CUSTOMER");

            //$this->entity_manager->persist($sysUser);
            $this->userManager->updateUser($user);
            return $user;
        }
        //if user exists - go with the HWIOAuth way
        $user = parent::loadUserByOAuthUserResponse($response);
        $serviceName = $response->getResourceOwner()->getName();
        $setter = 'set' . ucfirst($serviceName) . 'AccessToken';
        //update access token
        $user->$setter($response->getAccessToken());
        return $user;
    }
}
