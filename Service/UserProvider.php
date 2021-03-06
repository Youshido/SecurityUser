<?php
/**
 * Date: 10.11.15
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace Youshido\SecurityUserBundle\Service;


use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Youshido\SecurityUserBundle\Entity\SecuredUser;

class UserProvider
{

    use ContainerAwareTrait;

    public function getUserClass()
    {
        return $this->container->getParameter('youshido_security_user.model');
    }

    /**
     * @param $id
     * @return SecuredUser
     */
    public function findUserById($id)
    {
        return $this->container->get('doctrine')
            ->getRepository($this->getUserClass())
            ->find($id);
    }

    /**
     * @param $activationCode
     * @return SecuredUser
     */
    public function findUserByActivationCode($activationCode)
    {
        return $this->container->get('doctrine')
            ->getRepository($this->getUserClass())
            ->findOneBy(['activationCode' => $activationCode]);
    }

    /**
     * @param $email
     * @return SecuredUser
     */
    public function findUserByEmail($email)
    {
        return $this->container->get('doctrine')
            ->getRepository($this->getUserClass())
            ->findOneBy(['email' => $email]);
    }

    /**
     * @return SecuredUser
     */
    public function createNewUserInstance()
    {
        $userClass = $this->getUserClass();

        return new $userClass;
    }

    public function activateUser(SecuredUser $user, $withFlush = true, $clearActivationCode = true)
    {
        $user->setActive(true);
        $user->setActivatedAt(new \DateTime());

        if ($clearActivationCode) {
            $this->clearActivationCode($user, false);
        }

        $this->flushUser($user, $withFlush);
    }

    private function flushUser(SecuredUser $user, $confirm = true)
    {
        if ($confirm) {
            $this->container->get('doctrine')->getManager()->persist($user);
            $this->container->get('doctrine')->getManager()->flush();
        }
    }

    public function clearActivationCode(SecuredUser $user, $withFlush = true)
    {
        $user->setActivationCode(null);

        $this->flushUser($user, $withFlush);
    }

    public function generateUserPassword(SecuredUser &$user, $password = '')
    {
        $encoder = $this->container->get('security.password_encoder');

        if (!$password) {
            $password = md5(uniqid() . time());
        }


        $user->setPassword($encoder->encodePassword($user, $password));
    }

    public function generateUserActivationCode(SecuredUser &$user, $withFlush = true)
    {
        $user->setActivationCode(md5(time() . uniqid()));

        $this->flushUser($user, $withFlush);
    }
}