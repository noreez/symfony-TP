<?php


namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Http\Authentication\DefaultAuthenticationSuccessHandler;
use Symfony\Component\Security\Http\HttpUtils;

class AuthenticationSuccessHandler extends DefaultAuthenticationSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    /**
     * @var FlashBagInterface
     */
    private $flashBag;

    public function __construct(HttpUtils $httpUtils, array $options = array(), FlashBagInterface $flashBag)
    {
        parent::__construct($httpUtils, $options);

        $this->flashBag = $flashBag;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        $this->flashBag->add(
            'success',
            'Cogratulations, Connect sucess!'
        );

        return parent::onAuthenticationSuccess($request, $token);

    }
}
