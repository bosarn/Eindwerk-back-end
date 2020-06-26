<?php


namespace App\Controller;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

class RegisterController extends AbstractController
{
    /**
     * @Route("/api/register", name="api_register", methods={"POST"})
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param Request $request
     * @param MailerInterface $Cumfuckerkakbitch
     * @return JsonResponse
     * @throws TransportExceptionInterface
     */
    public function register(UserPasswordEncoderInterface $passwordEncoder, Request $request, MailerInterface $Cumfuckerkakbitch)
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
            //give register key
            $user->setIsDeleted(true);
            $registerkey = '1234123412341234';
            $user->setRegister($registerkey);

            try
            {
                $mailservice = (new TemplatedEmail())
                    ->from('bosmansarnoo@gmail.com')
                    // ->to('$email')
                    ->to('arnobosmans1993@gmail.com')
                    ->subject('Confirm your mail for 3D-printDomain!')
                    ->htmlTemplate('emails/signup.html.twig')
                    ->context([
                'register' => $registerkey
            ]);
                $Cumfuckerkakbitch->send($mailservice);

                $entityManager->persist($user);
                $entityManager->flush();

                return $this->json([

                    'user' => $user
                ], 200);
            }
            catch(UniqueConstraintViolationException $e)
            {
                $errors[] = "The email provided already has an account!";
            }
            catch(\Exception $e)
            {
                $errors[] = 'unable to update username at the time';
            } catch (TransportExceptionInterface $e) {
            }
        }
        return $this->json([
            'errors' => $errors
        ], 400);
    }


    /**
     * @Route("/api/registerconfirm", name="api_confirm")
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param Request $request
     * @param MailerInterface $Cumfuckerkakbitch
     * @return \Symfony\Component\HttpFoundation\Response
 */
    public function confirmRegister ( Request $request)
    {

        $key = $request->get('key');
        $userRepo = $this->getDoctrine()->getRepository(User::class);
        $em = $this->getDoctrine()->getManager();
        $user = $userRepo->findOneBy(['register' => $key]);
        if (!$user) return $this->render('emails/return.html.twig', [
            'title' => 'Something went wrong',
            'content' => 'This mail address may already be in use!',

        ]);

        $user->setisDeleted(false);
        $user->setRegister('OK');
        $em->persist($user);
        $em->flush();

        return $this->render('emails/return.html.twig', [
            'title' => 'Email confirmed',
            'content' => 'Sign in',

        ]);
    }




}


