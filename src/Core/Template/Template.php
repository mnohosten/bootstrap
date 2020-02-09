<?php /** @noinspection PhpIncludeInspection */
declare(strict_types=1);

namespace App\Core\Template;

use App\UI\Http\Common\Session\UserSession;
use Twig\Environment;

class Template
{

    /** @var UserSession */
    private $userSession;
    /** @var Environment */
    private Environment $twig;
    private $data = [];
    /** @var string */
    private string $suffix;

    public function __construct(Environment $twig, UserSession $userSession, string $suffix = '.twig')
    {
        $this->twig = $twig;
        $this->userSession = $userSession;
        $this->suffix = $suffix;
    }

    public function render(string $path, array $data = []): void
    {
        $this->data += $data;
        echo $this->twig->render(
            $path . $this->suffix,
            $this->data
        );
    }

    public function toString(string $path, array $data = []): string
    {
        ob_start();
        $this->render($path, $data);
        return (string)ob_get_clean();
    }
}