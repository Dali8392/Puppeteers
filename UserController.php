<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserFormeType;
use DateTime;
use DateTimeZone;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class UserController extends AbstractController
{
    #[Route('/inscription', name: 'app_inscription')]
    public function addUser(Request $request,EntityManagerInterface $entityManager): Response
    {  $user= new User(); 
        
       $form = $this->createForm(UserFormeType::class,$user);
       $form->handleRequest($request);
       $id=$this->generateId();
       $user->setId($id);
       $user->setDateInscri(new DateTime('now', new DateTimeZone(date_default_timezone_get())));
      
       if ($form->isSubmitted() && $form->isValid()) { 
        $entityManager->persist($user);
            $entityManager->flush();

               //send mail welcome
               $templatePath = $this->getParameter('kernel.project_dir') . '/templates/emails/welcomeEmail.html.twig';
               $message = file_get_contents($templatePath);
               $message=str_replace("20User20",$user->getName(),$message);
               $message=str_replace("20id20",$user->getId(),$message);
               $this->sendMail($message,'Welcome to our website',$user);

                 return $this->redirectToRoute('app_inscription');

       
        }else{
        return $this->render('user/inscription.html.twig', [
            'form' => $form->createView(),
        ]);}
    }
    #[Route('/validate/user/form', name: 'validate_user_form')]
    public function validateUserForm(Request $request,EntityManagerInterface $entityManager)
    {  
        $maVariable = $request->request->get('ma_variable');
        $user= new User(); 
        $form = $this->createForm(UserFormeType::class,$user);
        if (isset($maVariable) && !empty($maVariable)) {
           
        $formView = $form->createView();
        $htmlForm = $this->renderView('user/inscription.html.twig', ['form' => $formView]);
        return new JsonResponse([ 'form' => $htmlForm]);    
        }
        
       
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
        $randomCode = $this->generateRandomCode();
        $templatePath = $this->getParameter('kernel.project_dir') . '/templates/emails/email.html.twig';
        $message = file_get_contents($templatePath);
        $message=str_replace("20name20",$user->getName(),$message);
        $message=str_replace("20lastName20",$user->getLastName(),$message);
        $message=str_replace("20email20",$user->getEmail(),$message);
        $message=str_replace("20code20",$randomCode,$message);
        $this->sendMail($message,'Mail confirmation',$user);

          return new JsonResponse(['success' => true,'code' => $randomCode]);

        }
        
        $formView = $form->createView();
        $htmlForm = $this->renderView('user/inscription.html.twig', ['form' => $formView]);
        return new JsonResponse(['success' => false, 'form' => $htmlForm]);
   
    }
    
    private function generateRandomCode()
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $code = '';
        $codeLength = 6; 

        $charactersLength = strlen($characters);
        for ($i = 0; $i < $codeLength; $i++) {
            $code .= $characters[rand(0, $charactersLength - 1)];
        }

        return $code;
    }
    private function sendMail($message,$subject,User $user)
    {
        require_once __DIR__ . '/../../public/mail.php';
        $mail->setFrom('mhama6970@gmail.com', 'HawesBiya@noReplay');
        $mail->addAddress($user->getEmail());
        $mail->Subject = $subject;
        $mail->Body    = $message;
        $mail->send();
    }
    private function generateId(): string
  {
   do {
    $randomLetter1 = chr(rand(65, 90));
    $randomLetter2 = chr(rand(65, 90));
    $randomLetter3 = chr(rand(65, 90));
    $randomNumber1 = rand(0, 9);
    $randomNumber2 = rand(0, 9);
    $randomNumber3 = rand(0, 9);
    $randomNumber4 = rand(0, 9);
    $randomNumber5 = rand(0, 9);
    $randomNumber6 = rand(0, 9);
    $randomNumber7 = rand(0, 9);
    $randomId = $randomNumber1 . $randomNumber2 . $randomNumber3 . $randomLetter1 . $randomLetter2 .
                $randomLetter3 . $randomNumber4 . $randomNumber5 . $randomNumber6 . $randomNumber7;
    $repository = $this->getDoctrine()->getRepository(User::class);
    $existingEntity = $repository->findOneBy(['id' => $randomId]);
   } while ($existingEntity);
    

    return $randomId;
}
  
    
}