<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserFormeType;
use DateTime;
use DateTimeZone;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class UserController extends AbstractController
{
    ////////thez li inscription wa fi nafes wa9et ta3mel add fel base wa tab3eth mail welcom
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
    /////////////valide form wa ken valid tab3eth mail fih code validation
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
    ///////////t'hez lil back-offic user
    #[Route('/userAdmin', name: 'fetch_UserAdmin')]
    public function fetch(Request $request): Response
    {
        $result = $this->getDoctrine()->getRepository(User::class)->findAll();
    
        return $this->render('user/backoffice.html.twig', [
            'list' => $result,
        ]);
    }
    /////////tfase5 user
    #[Route('/user/delete/{id}', name: 'delet_user')]
      public function delete($id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        $entityManager->remove($user);
        $entityManager->flush();

        return new Response('User deleted successfully', Response::HTTP_OK);
    }
    ////////tbadel role li admine 
    #[Route('/user/addAdmin/{id}', name: 'add_admin')]
    public function addAdminAction($id): Response
    {
       
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        
        $user->setRole('admin');

        
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();

        return new Response('Admin role added successfully');
    }
     
    //////tbadel role li user
    #[Route('/user/deleteAdmin/{id}', name: 'delete_admin')]
    public function deleteAdminAction($id): Response
    {
       
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

       
        $user->setRole('user');

       
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();

        return new Response('Admin role deleted successfully');
    }
     ////////modif user 
    #[Route('/user/modif/{id}', name: 'modif_user')]
    public function modif($id , Request $request,ManagerRegistry $managerRegistry): Response
    {
        
        $user = $managerRegistry->getManager()->getRepository(User::class)->findOneBy(['id' => $id]);
        $form = $this->createForm(UserFormeType::class, $user);

       $form->handleRequest($request);
       
         if ($form->isSubmitted() && $form->isValid()) {
           $managerRegistry->getManager()->persist($user);
            $managerRegistry->getManager()->flush();
             return $this->redirectToRoute('fetch_UserAdmin');
        }
        return $this->render('user/editProfile.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }
     ////////reset password

 #[Route('/user/resetPassword', name: 'reset_password')]
    public function resetPassord(Request $request,ManagerRegistry $managerRegistry): JsonResponse
    {
        $id = $request->get('userId');
        $user = $managerRegistry->getManager()->getRepository(User::class)->findOneBy(['id' => $id]);
        $randomCode = $this->generateRandomCode();
        $templatePath = $this->getParameter('kernel.project_dir') . '/templates/emails/alertMail.html.twig';
        $message = file_get_contents($templatePath);
        $message=str_replace("20User20",$user->getName(),$message);
        $message=str_replace("20Code20",$randomCode,$message);

        $success=$this->sendMail($message,'Password Reset Alert',$user);
              return new JsonResponse(['success' => $success ,'code'=> $randomCode]);
       

        
    }





























    /////////////////////////////////////
    
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
    private function sendMail($message,$subject,User $user):bool
    {
        require_once __DIR__ . '/../../public/mail.php';
        $mail->setFrom('mhama6970@gmail.com', 'HawesBiya@noReplay');
        $mail->addAddress($user->getEmail());
        $mail->Subject = $subject;
        $mail->Body    = $message;
        
            return $mail->send();
       
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