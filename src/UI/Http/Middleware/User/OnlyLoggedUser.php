<?php
declare(strict_types=1);

namespace App\UI\Http\Middleware\User;

use App\Core\Bus\InputBus;
use App\Core\Router\LinkGenerator;
use App\Query\User\ById;
use App\UI\Http\Common\Authorization;
use App\UI\Http\Common\Session\UserSession;
use App\UI\Http\Middleware\Middleware;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class OnlyLoggedUser implements Middleware
{

    /** @var LinkGenerator */
    private $linkGenerator;
    /** @var UserSession */
    private UserSession $userSession;
    /** @var Request */
    private Request $request;
    /** @var Authorization */
    private Authorization $authorization;
    /** @var InputBus */
    private InputBus $bus;

    public function __construct(
        InputBus $bus,
        Authorization $authorization,
        LinkGenerator $linkGenerator,
        Request $request
    )
    {
        $this->bus = $bus;
        $this->authorization = $authorization;
        $this->linkGenerator = $linkGenerator;
        $this->request = $request;
    }

    public function execute(): ?Response
    {
        try {
            $this->authorizeFromJwt();
        } catch (Throwable $exception) {
            return $this->getUnauthorizedJsonResponse($exception->getMessage());
        }
        return $this->getResponse();
    }

    /**
     * @return bool
     */
    private function isJson(): bool
    {
        return $this->request->getContentType() == 'json';
    }

    /**
     * @return Response|null
     */
    private function getResponse()
    {
        $response = null;
        if (!$this->authorization->getUserSession()->isLoggedIn()) {
            $response = $this->getRedirectResponse();
        }
        return $response;
    }

    private function getRedirectResponse(): Response
    {
        if ($this->isJson()) {
            return $this->getUnauthorizedJsonResponse();
        }
        return new RedirectResponse($this->linkGenerator->generate('customer:login'));
    }

    private function authorizeFromJwt(): void
    {
        if ($this->isJson()) {
            $jwt = str_replace('Bearer ', '', $this->request->headers->get('Authorization'));
            $token = $this->authorization->getJwt()->parseToken($jwt);
            $this->authorization->authorize(
                $this->bus->handle(new ById($token->getClaim('uid')))
            );
        }
    }

    private function getUnauthorizedJsonResponse(string $msg = null): JsonResponse
    {
        return new JsonResponse(
            [
                'acknowledged' => false,
                'reason' => $msg ?:'401 Unauthorized',
            ],
            401
        );
    }

}