<?php

namespace BackendBundle\Entity;

use FOS\UserBundle\Model\UserManagerInterface;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\FOSUBUserProvider as BaseClass;
use FrontendBundle\Event\Event;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Security\Core\User\UserInterface;

class OAuthUserProvider extends BaseClass
{
    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    private $eventDispatcher;
    private $vichMappings;

    /**
     * Constructor.
     *
     * @param UserManagerInterface                                        $userManager FOSUB user provider.
     * @param array                                                       $properties  Property mapping.
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $eventDispatcher
     */
    public function __construct(UserManagerInterface $userManager, array $properties,
                                EventDispatcherInterface $eventDispatcher, $vichMappings)
    {
        parent::__construct($userManager, $properties);
        $this->eventDispatcher = $eventDispatcher;
        $this->vichMappings = $vichMappings;
    }

    /**
     * {@inheritdoc}
     */
    public function connect(UserInterface $user, UserResponseInterface $response)
    {
        $username = $response->getUsername();

        //on connect - get the access token and the user ID
        $service      = $response->getResourceOwner()->getName();
        $setter       = 'set' . ucfirst($service);
        $setter_id    = $setter . 'Id';
        $setter_token = $setter . 'AccessToken';

        $property = $service . 'Id';

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

        $service = $response->getResourceOwner()->getName();
        $property = $service . 'Id';

        // asking if the user is registered as facebook user
        $user = $this->userManager->findUserBy(array($property => $username));

        //when the user is registrating
        if (null === $user) {

            $setter       = 'set' . ucfirst($service);
            $setter_id    = $setter . 'Id';
            $setter_token = $setter . 'AccessToken';

            // asking if the user is registered as system user
            $user = $this->userManager->findUserBy(array('email' => $response->getEmail()));

            if (null !== $user) {

                // update system user with facebook data
                $user->$setter_id($username);
                $user->$setter_token($response->getAccessToken());

            } else {

                // create new user here
                $user = $this->userManager->createUser();
                $user->$setter_id($username);
                $user->$setter_token($response->getAccessToken());

                //I have set all requested data with the user's username
                //modify here with relevant data
                $emailPart = explode('@', $response->getEmail());
                $user->setUsername($emailPart[0]);
                $user->setEmail($response->getEmail());
                $user->setPassword($username);
                $user->setPlainPassword($username);
                $user->setEnabled(true);
                $user->addRole("ROLE_CUSTOMER");
            }

            // adding social data
            $fullName = explode(' ', $response->getNickname(), 2);
            $user->setFirstName($fullName[0]);
            if(count($fullName) > 1){
                $user->setLastName($fullName[1]);
            }


            if ($response->getProfilePicture() !== null) {
                //echo var_dump($socialData);
                //die;
                $newImageName = $username . ".jpg";
                $imagePath = $this->vichMappings['user_image']['upload_destination'];
                $newImagePath = $imagePath . "/" . $newImageName;

                // copy the remote image to the server images directory
                copy($response->getProfilePicture(), $newImagePath);

                $user->setImage($newImageName);
            }

            $this->userManager->updateUser($user);

            // this is a new user, let's launch the corresponding event
            /*$this->eventDispatcher->dispatch(RewardEvents::REWARD_ON_REGISTER,
                new Event($user, RewardEvents::REWARD_ON_REGISTER))
            ;*/

            return $user;
        }
        //if user exists - go with the HWIOAuth way
        $user        = parent::loadUserByOAuthUserResponse($response);
        $serviceName = $response->getResourceOwner()->getName();
        $setter      = 'set' . ucfirst($serviceName) . 'AccessToken';
        //update access token
        $user->$setter($response->getAccessToken());

        return $user;
    }
}
