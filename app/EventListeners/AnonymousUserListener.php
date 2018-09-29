<?php

namespace StudentList\EventListeners;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\KernelEvents;
use StudentList\AuthManager;

class AnonymousUserListener implements EventSubscriberInterface
{
    /**
     * @var AuthManager
     */
    private $authManager;

    /**
     * Create a new AnonymousUserListener instance
     *
     * @param AuthManager $authManager
     */
    public function __construct(AuthManager $authManager)
    {
        $this->authManager = $authManager;
    }

    /**
     * Redirect to the register if user is anonymous and path is only for authorized users
     *
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        $path = $request->getPathInfo();

        if (!$this->authManager->checkIfAuthorized() && $this->isAuthorizedUserPath($path)) {
            $response = new RedirectResponse("/register");
            $event->setResponse($response);
        }
    }

    /**
     * Check if request path is only for authorized users
     *
     * @param string $path
     * @return bool
     */
    private function isAuthorizedUserPath(string $path): bool
    {
        return preg_match("/\/profile/", $path) ? true : false;
    }

    public static function getSubscribedEvents()
    {
        return array(KernelEvents::REQUEST => array("onKernelRequest"));
    }
}