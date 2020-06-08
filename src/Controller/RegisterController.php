<?php


namespace App\Controller;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterController extends AbstractController
{
    /**
     * @Route("/api/register", name="api_register", methods={"POST"})
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param Request $request
     * @return JsonResponse
     */
    public function register(UserPasswordEncoderInterface $passwordEncoder, Request $request)
    {


        $data = json_decode($request->getContent(), true);
        $entityManager = $this->getDoctrine()->getManager();
        $user = new User();
        $email                  = $data['email'];
        $password               = $data['password'];
        $name                   = $data['name'];
        $errors = [];


        if(strlen($password) < 6)
        {
            $errors[] = "Password should be at least 6 characters.";
        }

        if(!$errors)
        {
            $encodedPassword = $passwordEncoder->encodePassword($user, $password);
            $user->setEmail($email);
            $user->setPassword($encodedPassword);
            $user->setName($name);
            try
            {
                $entityManager->persist($user);
                $entityManager->flush();

                return $this->json([
                    'user' => $user
                ]);
            }
            catch(UniqueConstraintViolationException $e)
            {
                $errors[] = "The email provided already has an account!";
            }
            catch(\Exception $e)
            {
                $errors[] = "Unable to save new user at this time.";
            }
        }
        return $this->json([
            'errors' => $errors
        ], 400);
    }





}


