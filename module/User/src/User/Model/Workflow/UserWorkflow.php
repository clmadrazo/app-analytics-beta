<?php
namespace User\Model\Workflow;

use User\Entity\User;
use Doctrine\ORM\EntityNotFoundException;
use Zend\ServiceManager\FactoryInterface,
    Zend\ServiceManager\ServiceLocatorInterface;

class UserWorkflow implements FactoryInterface
{
    /**
     *
     * @var ServiceLocatorInterface 
     */
    protected $_serviceLocator;
    
    /**
     * 
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function __construct(ServiceLocatorInterface $serviceLocator)
    {
        $this->createService($serviceLocator);
    }
    
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $this->_serviceLocator = $serviceLocator;        
    }
    
    /**
     * Add|Update a User entity with the given $data attributes
     * @param array $data User attributes
     * @param integer|null $userId User ID. If an ID value is given, an UPDATE will be performed.
     *                     Otherwise a new entity will be created.
     *                     Defaults to null.
     * 
     * @return \User\Entity\User
     * @throws EntityNotFoundException If the given $userId was not found
     */
    public function persistUser(array $data, $userId = null)
    {
        /* @var $entityManager \Doctrine\ORM\EntityManager */
        $entityManager = $this->_serviceLocator->get('Doctrine\ORM\EntityManager');

        $user = is_null($userId)
            ? new User()
            : $this->getUserById($userId);

        if (!is_null($user)) {

            foreach ($data as $attribute => $value) {
                $method = 'set' . ucwords($attribute);
                if (method_exists($user, $method)) {
                    $user->{$method}($value);
                }
            }

            $entityManager->persist($user);
            $entityManager->flush();

            return $user;

        } else {
            throw new EntityNotFoundException();
        }
    }
    
    /**
     * Returns a User by the given $userId
     * @param integer $userId The User ID
     * 
     * @return User|null The User instance or NULL if the entity can not be found.
     */
    public function getUserById($userId)
    {
        /* @var $entityManager \Doctrine\ORM\EntityManager */
        $entityManager = $this->_serviceLocator->get('Doctrine\ORM\EntityManager');
        return $entityManager->find('\User\Entity\User', (int) $userId);
    }
}
