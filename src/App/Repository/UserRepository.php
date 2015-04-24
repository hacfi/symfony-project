<?php

namespace App\Repository;

use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;

use App\Model\User;

class UserRepository extends EntityRepository implements UserProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function loadUserByUsername($username)
    {
        $q = $this
            ->createQueryBuilder('u')
            ->where('u.email = :username')
            ->setParameter('username', $username)
            ->getQuery()
        ;

        try {
            $user = $q->getSingleResult();
        } catch (NoResultException $e) {
            $ex = new UsernameNotFoundException(sprintf('There is no user with name "%s".', $username));
            $ex->setUsername($username);
            throw $ex;
        }

        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public function refreshUser(UserInterface $user)
    {
        $class = get_class($user);
        if (!$this->supportsClass($class)) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $class));
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    /**
     * {@inheritdoc}
     */
    public function supportsClass($class)
    {
        return $this->getEntityName() === $class || is_subclass_of($class, $this->getEntityName());
    }

    public function getAll($user = null)
    {
        $qb = $this
            ->createQueryBuilder('u')
            ->orderBy('u.id', 'ASC');

        if ($user instanceof User) {
            $qb
                ->where('u.id != :userId')
                ->setParameter('userId', $user->getId())
            ;
        }

        $q = $qb->getQuery();
        // $q->useResultCache(true, 60, 'users_get_all');

        $users = $q->getResult();

        return $users;
    }

}
