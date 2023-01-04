<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Entity\User;
use App\Form\UserType;
use App\Entity\Comment;
use App\Form\CommentFormType;
use App\Repository\BlogRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class BlogController extends AbstractController
{
    #[Route('/accueil', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('blog/index.html.twig');
    }
    #[Route('/blog', name: 'app_blog')]
    public function blog(BlogRepository $blogrepo): Response
    {
        $blogs = $blogrepo->findAll();
        dump($blogs);
        return $this->render('blog/blog.html.twig', [
            'blogs' => $blogs
        ]);
    }
    #[Route('/detail/{id}', name: 'app_blog_detail')]
    // #[ParamConverter('blog', class: 'SensioBlogBundle:Blog')]
    public function detail($id, BlogRepository $blogrepo, Request $request): Response
    {
        $blog = $blogrepo->find($id);
        dump($blog);

        $comment = new Comment();
        $form = $this->createForm(CommentFormType::class, $comment);

        return $this->render('blog/detail.html.twig', [
            'blog' => $blog,
            'formComment' => $form->createView()
        ]);
    }
    #[Route('/addblog', name: 'app_add_blog')]
    public function addblog(Request $request, EntityManagerInterface $manager): Response
    {
        $blog = new Blog;
        $form = $this->createFormBuilder($blog)
            ->add('titre', TextType::class, [
                'attr' => [
                    'placeholder' => 'Le titre de l\'article'
                ]
            ])
            ->add('contenu', TextareaType::class, [
                'attr' => [
                    'placeholder' => 'Le contenu de l\'article'
                ]
            ])
            ->add('image', TextType::class, [
                'attr' => [
                    'placeholder' => 'L\url de l\'image'
                ]
            ])
            ->getForm();

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $blog->setDateCreation(new \DateTime());

            $manager->persist($blog);
            $manager->flush();

            return $this->redirectToRoute('app_blog');
        }

        return $this->render('blog/add_blog.html.twig', [
            'form' => $form->createView()
        ]);
    }
    #[Route('/connexion', name: 'app_connexion')]
    public function connexion(AuthenticationUtils $authuser): Response
    {
        $lastUser = $authuser->getLastUsername();
        dump($lastUser);

        return $this->render('security/connexion.html.twig', [
            'last_username' => $lastUser
        ]);
    }
    #[Route('/inscription', name: 'app_inscription')]
    public function inscription(Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $passwordhasher): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hashPass = $passwordhasher->hashPassword($user, $user->getPassword());
            $user->setPassword($hashPass);

            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('app_connexion');
        }

        return $this->render('security/inscription.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
