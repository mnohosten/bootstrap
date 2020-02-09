<?php
declare(strict_types=1);

namespace App\UI\Http\Customer\Component;

use App\Component;
use App\Entity\Customer\CustomerRepository;
use App\Core\Template\Template;
use App\UI\Http\Common\Session\UserSession;

class ProfileForm implements Component
{
    /** @var Template */
    private Template $template;
    /** @var CustomerRepository */
    private CustomerRepository $customerRepository;
    /** @var UserSession */
    private UserSession $userSession;

    public function __construct(
        Template $template,
        CustomerRepository $customerRepository,
        UserSession $userSession
    )
    {
        $this->template = $template;
        $this->customerRepository = $customerRepository;
        $this->userSession = $userSession;
    }

    public function render(array $args = []): void
    {
        $customer = $this->customerRepository->findById(
            $this->userSession->getUser()->customer_id
        );
        $this->template->render(
            'customer/component/form.profile',
            [
                'customer' => $customer,
                'defaultAddress' => $this->customerRepository->profileAddressOf($customer),
            ]
        );
    }
}