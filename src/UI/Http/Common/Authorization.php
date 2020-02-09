<?php
declare(strict_types=1);

namespace App\UI\Http\Common;

use App\Entity\User\Credentials;
use App\Entity\User\User;
use App\Entity\User\UserRepository;
use App\UI\Http\Common\Session\UserSession;

class Authorization
{
    /** @var UserSession */
    private UserSession $userSession;
    /** @var UserRepository */
    private UserRepository $userRepository;
    /** @var JWT */
    private JWT $jwt;

    public function __construct(
        UserRepository $userRepository,
        UserSession $userSession,
        JWT $jwt
    )
    {
        $this->userSession = $userSession;
        $this->userRepository = $userRepository;
        $this->jwt = $jwt;
    }

    public function login(Credentials $credentials): void
    {
        $this->authorize(
            $this->userRepository->findByCredentials($credentials)
        );
    }

    public function authorize(User $user): void
    {
        $this->userSession->store($user, true);
    }

    public function revokeAuthorization()
    {
        $this->userSession->logout();
    }

    public function getUser(): User
    {
        return $this->userSession->getUser();
    }

    public function getUserSession(): UserSession
    {
        return $this->userSession;
    }

    public function getJwt(): JWT
    {
        return $this->jwt;
    }

}