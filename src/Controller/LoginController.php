<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\Type\LoginType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private AuthenticationUtils $authenticationUtils,
        private Security $security,
    ) {
    }

    #[Route('/login', name: 'app_login', methods: ['GET', 'POST'])]
    public function login(
        Request $request,
    ): Response {
        $error = $this->authenticationUtils->getLastAuthenticationError();

        if ($request->isMethod('POST')) {
            $email = $request->request->get('username');
            $password = $request->request->get('password');

            $user = $this->entityManager
                ->getRepository(User::class)
                ->findOneBy([
                    'email' => $email,
                    'password' => $password,
                ])
            ;

            if (!$user instanceof User) {
                throw new AuthenticationException('Invalid credentials.');
            }

            return  $this->security->login($user);
        }

        $form = $this->createForm(LoginType::class);

        $lastUsername = $this->authenticationUtils->getLastUsername();

        return $this->render('login/index.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/logout', name: 'app_logout', methods: ['GET'])]
    public function logout(): void
    {
        $this->security->logout();
    }
}
