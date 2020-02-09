<?php
declare(strict_types=1);

namespace App\UI\Http\Common\Session;

use App\Core\DataStructure\ArrayIterator;
use App\Core\DataStructure\NullAccess;
use App\Entity\User\User;
use Symfony\Component\HttpFoundation\Session\Session as SymfonySession;

class UserSession
{
    /** @var SymfonySession */
    private SymfonySession $session;

    private const NS = '__app_user';

    public function __construct(SymfonySession $session)
    {
        $this->session = $session;
    }

    public function store(User $user, bool $isLoggedIn = false): void
    {
        $this->session->set(
            self::NS,
            [
                'isLoggedIn' => $isLoggedIn,
                'user' => $user->jsonSerialize(),
            ]
        );
    }

    public function getUser(): User
    {
        $userData = new NullAccess();
        if ($this->session->has(self::NS)) {
            $sessionData = $this->session->get(self::NS);
            $userData = new ArrayIterator($sessionData['user']);
        }
        return new User($userData);
    }

    public function isLoggedIn(): bool
    {
        if ($this->session->has(self::NS)) {
            $data = $this->session->get(self::NS);
            return $data['isLoggedIn'] ?? false;
        }
        return false;
    }

    public function logout(): void
    {
        $this->session->clear();
    }

    public function addFlash(string $message, string $type = 'info'): void
    {
        $flashBag = $this->session->getFlashBag();
        $flashBag->add($type, $message);
    }

    public function getFlashes(): array
    {
        static $flashes = null;
        if(!isset($flashes)) {
            $flashes = $this->session->getFlashBag()->all();
        }
        return $flashes;
    }

}