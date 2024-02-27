<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Visitor;
use App\Form\UserFormeType;
use DateInterval;
use DatePeriod;
use DateTime;
use DateTimeZone;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use GeoIp2\Database\Reader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Process\Process;

class UserController extends AbstractController
{  
    private $session;
    

public function __construct(SessionInterface $session,)
{
    $this->session = $session;
   
}

    #[Route('/', name: 'app_home')]
    public function home(Request $request,EntityManagerInterface $entityManager):Response {
        
         
         $vistor= new Visitor();
         $vistor->setIpAddress($request->getClientIp());
         $vistor->setDateInscri(new DateTime('now', new DateTimeZone(date_default_timezone_get())));
         $entityManager->persist($vistor);
         $entityManager->flush();


       
         return $this->render('base.html.twig');
       
    }
    #[Route('/dashboard', name: 'app_homeAdmine')]
    public function homeAdmine():Response {
            return $this->render('baseAdmin.html.twig');
    }

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
        $user->setPassword($this->crypterMotDePasse($user->getPassword()));
        $entityManager->persist($user);
            $entityManager->flush();

               //send mail welcome
               $templatePath = $this->getParameter('kernel.project_dir') . '/templates/emails/welcomeEmail.html.twig';
               $message = file_get_contents($templatePath);
               $message=str_replace("20User20",$user->getName(),$message);
               $message=str_replace("20id20",$user->getId(),$message);
               $this->sendMail($message,'Welcome to our website',$user);

               ///////
               $this->session->set('id', $user->getId());
               $this->session->set('name', $user->getName());
               $this->session->set('lastName', $user->getLastName());
               $this->session->set('email', $user->getEmail());
              
               return $this->redirectToRoute('recognition_app', ['id' => $user->getId()]);


       
        }else{
        return $this->render('user/inscription.html.twig', [
            'form' => $form->createView(),
        ]);}
    }
    /////////////valide form wa ken valid tab3eth mail fih code validation
    #[Route('/validate/user/form', name: 'validate_user_form')]
    public function validateUserForm(Request $request,ManagerRegistry $managerRegistry)
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
            $userEx = $managerRegistry->getManager()->getRepository(User::class)->findOneBy(['email' => $user->getEmail()]);
              if($userEx){
                $form->get('email')->addError(new FormError('this mail is already exists try again with other mail'));
              }else{
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
        $user->setPassword($this->decrypterMotDePasse($user->getPassword()));
        $form = $this->createForm(UserFormeType::class, $user);

       $form->handleRequest($request);
       
         if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($this->crypterMotDePasse($user->getPassword()));
           $managerRegistry->getManager()->persist($user);
            $managerRegistry->getManager()->flush();
            return $this->redirectToRoute('profile_user', ['id' => $user->getId()]);
             
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

//////////Login user
#[Route('/login', name: 'login_user')]
    public function loginUser(Request $request,ManagerRegistry $managerRegistry): Response
    {
        
        
       
        $form = $this->createFormBuilder()
        ->add('id', TextType::class, [
            'constraints' => [
                new NotBlank(['message' => 'ID is required.']),
                new Regex([
                    'pattern' => '/^\d{3}[A-Z]{3}\d{4}$/',
                    'message' => 'ID must match  pattern.'
                ])
            ]
        ])
        ->add('password', PasswordType::class ,[
            'constraints' => [
                new NotBlank(['message' => 'Password is required.']),
                new Length(['min' => 8, 'minMessage' => 'Password must be at least {{ limit }} characters long.']),
                new Regex([
                    'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',
                    'message' => 'Password must contain at least one lowercase letter, one uppercase letter, one number, and one special character.'
                ]),
            ],
        ])
        ->add('sign_up', SubmitType::class, [
            'label' => 'Sign in'
        ])
        ->getForm();
        $form->handleRequest($request);
       
         if ($form->isSubmitted() && $form->isValid()) {

            $formData = $form->getData();
            $id = $formData['id'];
           $user = $managerRegistry->getManager()->getRepository(User::class)->findOneBy(['id' => $id]);
           if(!$user){
            $form->get('id')->addError(new FormError('ID does not exist. Try again.'));
            }else{
                if($this->decrypterMotDePasse($user->getPassword())==$formData['password']){
                    $this->session->set('id', $user->getId());
                    $this->session->set('name', $user->getName());
                    $this->session->set('lastName', $user->getLastName());
                    $this->session->set('email', $user->getEmail());
                    $this->session->set('role', $user->getRole());
                    if($user->getRole() == "user"){return $this->redirectToRoute('app_home');}
                    else if ($user->getRole() == "admin"){return $this->redirectToRoute('app_homeAdmine');}
                 
            }else{
                $form->get('id')->addError(new FormError('Something is wrong!! Incorrect ID or password'));
  
            }
            }

         }

         return $this->render('user/login.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /////////////////profile 
    #[Route('/user/profile/{id}', name: 'profile_user')]
    public function profileUser($id,Request $request,ManagerRegistry $managerRegistry): Response
            {
             
                return $this->render('user/profile.html.twig', [
                'user' => $managerRegistry->getManager()->getRepository(User::class)->findOneBy(['id' => $id]),
            ]);
            }

             /////////////////logout
       #[Route('/logout', name: 'logout_user')]
        public function logoutUser(): Response
            {
                $this->session->clear(); 
             
                return $this->redirectToRoute('app_home');
           
            }
////////////////////////statis

#[Route('/statis', name: 'statis_user')]
public function statis(Request $request, ManagerRegistry $managerRegistry): JsonResponse
{
    $period = $request->get('period');
  
    $data1 = $this->getDataStatistics(User::class,  $period,$managerRegistry);
    $data2= $this->getDataStatistics(Visitor::class,  $period,$managerRegistry);
    $data3= $this->getCountrieStatic($managerRegistry);
    

    
  
   return new JsonResponse(['nameDate'=>array_keys($data1) ,'inscri' => array_values($data1),'visitor' => array_values($data2),"CountrieName" => array_keys($data3),'CountrieOcc' => array_values($data3)]);
}
///////////////////////////////get info et trie 
private function getDataStatistics(string $className, string $period,ManagerRegistry $managerRegistry): array 
{
    
 $infos = $managerRegistry->getManager()->getRepository($className)->findByRegistrationDate($period);

    $data = [];
    foreach ($infos as $user) {
        $date = $user->getDateInscri()->format('Y-m-d'); // Format date as YYYY-MM-DD
        $data[$date] = isset($data[$date]) ? $data[$date] + 1 : 1; // Count registrations for each date
    }

    // Fill in missing dates with 0 registrations
    switch ($period) {
        case 'last_7_days':
            $startDate = new \DateTime('-7 days');
            break;
        case 'last_30_days':
            $startDate = new \DateTime('-30 days');
            break;
        default:
        $startDate = new \DateTime('2024-01-01'); 
    } 
    $endDate = new \DateTime(); 
    $interval = DateInterval::createFromDateString('1 day');
    $periodDates = new DatePeriod($startDate, $interval, $endDate);

   
    foreach ($periodDates as $date) {
        $dateString = $date->format('Y-m-d');
        if (!isset($data[$dateString])) {
            $data[$dateString] = 0;
        }
    }
    
    
    $today = new \DateTime();
    $todayString = $today->format('Y-m-d');
    if (!isset($data[$todayString])) {
        $data[$todayString] = 0;
    }

    
    ksort($data);

    return $data;
}

///////////////////////////////////////
private function getCountrieStatic(ManagerRegistry $managerRegistry): array
{

    $vistors= $managerRegistry->getManager()->getRepository(Visitor::class)->findAll();
    $Countries=[];
    
    foreach ($vistors as $vistor) {
         $Countrie =$this->getCountrie( $vistor->getIpAddress());
        $Countries[$Countrie]=isset($Countries[$Countrie]) ? $Countries[$Countrie] + 1 : 1;;
    }
    return  $Countries;
}
///////////////////////////save photo
#[Route('/take-photo', name: 'take_photo')]
public function takePhoto(Request $request): Response
{
    $photoData = json_decode($request->getContent(), true);
    $photoBase64 = $photoData['photo'];

    
    $photoBinary = base64_decode(str_replace('data:image/jpeg;base64,', '', $photoBase64));

    
    $nomFichier = $photoData['id'] . '.jpg';

    
    $dossierDestination = $this->getParameter('dossier_photos');
    $cheminPhoto = $dossierDestination . '/' . $nomFichier;

    
    file_put_contents($cheminPhoto, $photoBinary);

    
    return new JsonResponse(['chemin_photo' => $cheminPhoto]);

}
#[Route('/recognition/{id}', name: 'recognition_app')]
public function dd($id): Response{
    return $this->render('user/recognition.html.twig',[
        'id' => $id,
    ]);
    
}

#[Route('/loginFace', name: 'app_loginFace')]
    public function loginFace(ManagerRegistry $managerRegistry): JsonResponse
    {
       
       $scriptPath = $this->getParameter('kernel.project_dir') . '/public/facial_recognition.py';

      
       $process = new Process(['python', $scriptPath]);
       $process->setTimeout(null);
       $process->run();

    

      
       $output = $process->getOutput();
       $output = trim($output);
        if($output){
         $user=$managerRegistry->getManager()->getRepository(User::class)->findOneBy(['id' => $output]);
         return new JsonResponse(['success' => true ,'id' => $user->getId() , 'password' => $this->decrypterMotDePasse($user->getPassword())]);
      }
   
       return new JsonResponse(['success' => false]);
       
    }



























////////////////////////////////
private function getCountrie($ipAddress){
       //  // Construire le chemin vers la base de données GeoIP2 (dans le dossier public)
          $databasePath = $this->getParameter('kernel.project_dir') . '/public/GeoLite2-City_20240220/GeoLite2-City.mmdb';
 
         // Créer une instance du lecteur GeoIP2
          $reader = new Reader($databasePath);
         try {
            // Utiliser le lecteur GeoIP2 pour obtenir des informations sur l'emplacement basées sur l'adresse IP
            $record = $reader->city($ipAddress);

           

            return $record->country->name;
           
        } catch (\Exception $e) {
       
          return "private address";
         }

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
    //////////////////////send mail
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
////////////////////////crypt password base64
private function crypterMotDePasse($motDePasse)
{
    $motDePasseCrypte = base64_encode($motDePasse);
    return $motDePasseCrypte;
}


private function decrypterMotDePasse($motDePasseCrypte)
{
    $motDePasse = base64_decode($motDePasseCrypte);
    return $motDePasse;
}
  
    
}