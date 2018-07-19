<?php

namespace Chaplean\Bundle\GitlabBundle\Security;

use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * Class GitlabTokenUserProvider.
 *
 * @package   Chaplean\Bundle\ApiBundle\Security
 * @author    Matthias - Chaplean <matthias@chaplean.coop>
 * @copyright 2014 - 2016 Chaplean (https://www.chaplean.coop)
 * @since     1.3.0
 *
 * See http://symfony.com/doc/current/security/api_key_authentication.html
 */
class GitlabTokenUserProvider implements UserProviderInterface
{
    /**
     * @var string
     */
    protected $token;

    /**
     * GitlabTokenUserProvider constructor.
     *
     * @param string $token
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * @param $token
     *
     * @return GitlabTokenUser|null
     */
    public function getUserForToken($token)
    {
        if ($token === $this->token) {
            return $this->loadUserByUsername('');
        }

        return null;
    }

    /**
     * @param string $username
     *
     * @return GitlabTokenUser|null
     */
    public function loadUserByUsername($username)
    {
        return new GitlabTokenUser();
    }

    /**
     * @param UserInterface $user
     *
     * @return void
     */
    public function refreshUser(UserInterface $user)
    {
        throw new UnsupportedUserException();
    }

    /**
     * @param string $class
     *
     * @return boolean
     */
    public function supportsClass($class)
    {
        return GitlabTokenUser::class === $class;
    }
}
