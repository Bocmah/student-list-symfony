<?php

namespace StudentList\EventListeners;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\KernelEvents;
use StudentList\AuthManager;

class LoggedInUserListener implements EventSubscriberInterface
{
    /**
     * @var AuthManager
     */
    private $authManager;

    /**
     * Create a new LoggedInUserListener
     *
     * @param AuthManager $authManager
     */
    public function __construct(AuthManager $authManager)
    {
        $this->authManager = $authManager;
    }

    /**
     * Redirect to the profile page if user is authorized and path is only for anonymous users
     *
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        $path = $request->getPathInfo();

        if ($this->authManager->checkIfAuthorized() && $this->isAnonymousPath($path)) {
            $response = new RedirectResponse("/profile");
            $event->setResponse($response);
        }
    }

    /**
     * Check if request path is only for anonymous users
     *
     * @param string $path
     * @return bool
     */
    private function isAnonymousPath(string $path): bool
    {
        return preg_match("/\/register/", $path) ? true : false;
    }

    public static function getSubscribedEvents()
    {
        return array(KernelEvents::REQUEST => array("onKernelRequest"));
    }
}