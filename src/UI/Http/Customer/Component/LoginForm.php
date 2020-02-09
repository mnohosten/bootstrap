<?php
declare(strict_types=1);

namespace App\UI\Http\Customer\Component;

use App\Component;
use App\Core\Template\Template;

class LoginForm implements Component
{
    /** @var Template */
    private $template;

    public function __construct(Template $template)
    {
        $this->template = $template;
    }

    public function render(array $args = []): void
    {
        $this->template->render(
            'customer/component/form.login.phtml'
        );
    }

}