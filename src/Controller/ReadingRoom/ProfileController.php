<?php namespace App\Controller\ReadingRoom;

use Vankosoft\UsersBundle\Controller\ProfileController as BaseProfileController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Persistence\ManagerRegistry;
use Sylius\Component\Resource\Factory\FactoryInterface;

use Vankosoft\CmsBundle\Component\Uploader\FileUploaderInterface;
use Vankosoft\UsersBundle\Security\UserManager;
use Vankosoft\AgentBundle\Component\VankosoftAgent;

use App\Form\ProfileFormType;

class ProfileController extends BaseProfileController
{
	public function __construct(
        ManagerRegistry $doctrine,
        string $usersClass,
        UserManager $userManager,
        FactoryInterface $avatarImageFactory,
	    FileUploaderInterface $imageUploader,
	    VankosoftAgent $vankosoftAgent
    ) {
        parent::__construct(
            $doctrine,
            $usersClass,
            $userManager,
            $avatarImageFactory,
            $imageUploader,
            $vankosoftAgent
        );
    }
    
    public function showAction( Request $request ): Response
    {
        if ( ! $this->getUser() ) {
            return $this->redirectToRoute( 'app_home' );
        }
        
        $profileEditForm    = $this->getProfileEditForm();
        $otherForms         = $this->getOtherForms();
		
        $params = [
            'profileEditForm'       => $profileEditForm->createView(),
            'changePasswordForm'    => $otherForms['changePasswordForm']->createView(),
        ];
        
        return $this->render( '@VSUsers/Profile/show.html.twig',
            array_merge( $params, $this->templateParams( $profileEditForm ) )
        );
    }
    
    public function handleProfileFormAction( Request $request ): Response
    {
        $form   = $this->getProfileEditForm();
        $form->handleRequest( $request );
        if ( ! $form->isSubmitted() ) {
            throw new \Exception( "Profile Form is Not Submited Properly !" );
        }
        
        $em             = $this->doctrine->getManager();
        $oUser          = $form->getData();
        
        if ( ! $oUser->getPreferedLocale() ) {
            $oUser->setPreferedLocale( $request->getLocale() );
        }
        
        $oUserInfo          = $oUser->getInfo();
        $profilePictureFile = $form->get( 'profilePicture' )->getData();
        if ( $profilePictureFile ) {
            $this->createAvatar( $oUserInfo, $profilePictureFile );
        }
        
        $oUserInfo->setTitle( $form->get( 'title' )->getData() );
        $oUserInfo->setFirstName( $form->get( 'firstName' )->getData() );
        $oUserInfo->setLastName( $form->get( 'lastName' )->getData() );
        $oUserInfo->setDesignation( $form->get( 'designation' )->getData() );
        
        $oUserInfo->setUser( $oUser );
        $em->persist( $oUserInfo );
        $em->persist( $oUser );
        $em->flush();
        
        return $this->redirectToRoute( 'vrr_profile_show' );
    }
    
    protected function getProfileEditForm()
    {
        $form       = $this->createForm( ProfileFormType::class, $this->getUser(), [
            'data'      => $this->getUser(),
            'action'    => $this->generateUrl( 'vs_users_profile_handle' ),
            'method'    => 'POST',
        ]);
        
        return $form;
    }
}