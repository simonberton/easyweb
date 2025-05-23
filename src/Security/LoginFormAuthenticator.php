<?php

namespace App\Security;

use App\Entity\User;
use App\Repository\UserInterface;
use App\Repository\UserRepository;
use App\Security\Exception\UserNotActiveException;
use App\Service\UserService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\CustomCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\SecurityRequestAttributes;


class LoginFormAuthenticator extends AbstractAuthenticator
{
    private Request $request;

    public function __construct(
        protected UrlGeneratorInterface $urlGenerator,
        protected UserRepository $userRepository,
        protected UserPasswordHasherInterface $passwordEncoder)
    { }

    public function supports(Request $request): ?bool
    {
        return ($request->getPathInfo() === '/login' && $request->isMethod('POST'));
    }

    public function authenticate(Request $request): Passport
    {
        $username = $request->request->get('email');
        $password = $request->request->get('password');

        $this->request = $request;
        return new Passport(
            new UserBadge($username, function($userIdentifier) {
                /** @var User $user */
                $user = $this->userRepository->findOneBy(['email' => $userIdentifier]);
                if (!$user) {
                    throw new UserNotFoundException();
                }

                return $user;
            }),
            new CustomCredentials(function($credentials, User $user) {
                return $this->passwordEncoder->isPasswordValid($user, $credentials);
            }, $password)
        );
    }

    public function onAuthenticationSuccess(
        Request $request,
        TokenInterface $token,
        string $firewallName): ?Response
    {
        $user = $token->getUser();
        //$this->userService->saveLoggedDate($user, new \DateTimeImmutable());

        return new RedirectResponse($this->urlGenerator->generate('admin_index'));
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $request->getSession()->set(SecurityRequestAttributes::AUTHENTICATION_ERROR, $exception);
        return new RedirectResponse(
            $this->urlGenerator->generate('app_login')
        );
    }
}
