<?php namespace App\Controller\ReadingRoom;

use Vankosoft\UsersBundle\Controller\ProfileController as BaseProfileController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Persistence\ManagerRegistry;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Vankosoft\CmsBundle\Component\Uploader\FileUploaderInterface;
use Vankosoft\UsersBundle\Security\UserManager;

use Vankosoft\AgentBundle\Component\VankosoftAgent;

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
}