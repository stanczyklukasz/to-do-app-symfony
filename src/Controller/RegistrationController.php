<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\User\RegisterUserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    #[Route('/register', name: 'register')]
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $form = $this->createFormBuilder()
            ->add('email', EmailType::class, [
                'required' => true,
                'attr' => ['class' => 'form-control']
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Hasła muszą być takie same',
                'options' => [
                    'attr' => ['class' => 'password-field']
                ],
                'required' => true,
                'first_options' => [
                    'label' => 'Hasło:',
                    'attr' => ['class' => 'form-control']
                ],
                'second_options' => [
                    'label' => 'Powtórz hasło:',
                    'attr' => ['class' => 'form-control']
                ]
            ])
            ->add('register', SubmitType::class, [
                'label' => 'Zarejestruj się',
                'attr' => [
                    'class' => 'btn btn-primary mt-3'
                ]
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $duplicate = $this->getDoctrine()->getRepository(User::class)->findOneBy(['email' => $data['email']]);


            if (is_object($duplicate)) {
                // dump($duplicate);
            } else {
                $user = new User();
                $user->setEmail($data['email']);
                $user->setPassword(
                    $passwordEncoder->encodePassword($user, $data['password'])
                );

                $em = $this->getDoctrine()->getManager();

                $em->persist($user);
                $em->flush();

                return $this->redirectToRoute('app_login');
            }
        }

        return $this->render('registration/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }














//TODO: PO CR
    public function __construct(
        UserRepository $userRepository
    )
    {
        $this->userRepository = $userRepository;
    }


    #[Route('/register_cr', name: 'register_cr', methods:["GET", "POST"])]
    public function registerPoCR(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $form = $this->createForm(RegisterUserType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $existedUser = $this->userRepository->findOneBy([
                'email' => $data['email']
            ]);

            if ($existedUser) {
                throw new \Exception('User with EMAIL already exist', 400);
            }

            //todo: do wyniesienia do osobnej warstwy, serwisów
            $user = new User();
            $user->setEmail($data['email']);
            $user->setPassword(
                $passwordEncoder->encodePassword($user, $data['password'])
            );

            $this->userRepository->save($user);

            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}

