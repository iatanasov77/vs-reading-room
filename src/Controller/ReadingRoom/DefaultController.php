<?php namespace App\Controller\ReadingRoom;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Twig\Environment;
use Doctrine\Persistence\ManagerRegistry;
use Sylius\Component\Resource\Repository\RepositoryInterface;

use Vankosoft\ApplicationBundle\Component\Context\ApplicationContextInterface;

class DefaultController extends AbstractController
{
    use GlobalFormsTrait;

    /** @var ApplicationContextInterface */
    private $applicationContext;
    
    /** @var Environment */
    private $templatingEngine;

    /** @var ManagerRegistry **/
    private $doctrine;
    
    /** @var RepositoryInterface */
    private $sliderRepository;
    
    /** @var RepositoryInterface */
    private $categoryRepository;
    
    /** @var RepositoryInterface */
    private $productRepository;
    
    /** @var RepositoryInterface */
    private $genresRepository;
	
	/** @var int */
    private $latestProductsLimit;
    
    public function __construct(
        ApplicationContextInterface $applicationContext,
        Environment $templatingEngine,
        ManagerRegistry $doctrine,
        RepositoryInterface $sliderRepository,
        string $sliderPhotosDir,
        RepositoryInterface $categoryRepository,
        RepositoryInterface $productRepository,
        RepositoryInterface $genresRepository,
        int $latestProductsLimit = 6
    ) {
        $this->applicationContext   = $applicationContext;
        $this->templatingEngine     = $templatingEngine;

        $this->doctrine             = $doctrine;
        $this->sliderRepository     = $sliderRepository;
        $this->sliderPhotosDir      = $sliderPhotosDir;
        
        $this->categoryRepository   = $categoryRepository;
        $this->productRepository    = $productRepository;
        $this->genresRepository     = $genresRepository;
		
		$this->latestProductsLimit  = $latestProductsLimit;
    }
    
    public function index( Request $request ): Response
    {
		$featuredProducts   = $this->productRepository->getFeaturedProducts();
        $latestProducts     = $this->productRepository->findBy( [], ['updatedAt' => 'DESC'], $this->latestProductsLimit );
		
        $homePageSlider         = $this->sliderRepository->findBySlug( 'home-page-slider' );
        if ( ! $homePageSlider ) {
            throw new NotFoundHttpException( 'Home Page Slider Not Found !!!' );
        }
        
        return new Response( $this->templatingEngine->render( $this->getTemplate(), [
            'sliderItems'           => $homePageSlider->getPublicItems(),
            'sliderPhotosDir'       => $this->sliderPhotosDir,
            'shoppingCart'      => $this->getShoppingCart( $request ),
            'categories'        => $this->categoryRepository->findAll(),
            'featuredProducts'  => $featuredProducts,
            'latestProducts'    => $latestProducts,
            'genres'            => $this->genresRepository->findAll(),
        ] ) );
    }
    
    protected function getTemplate(): string
    {
        $template   = 'reading-room/Pages/Dashboard/index.html.twig';
        
        $appSettings    = $this->applicationContext->getApplication()->getSettings();
        if ( ! $appSettings->isEmpty() && $appSettings[0]->getTheme() ) {
            $template   = 'Pages/home.html.twig';
        }
        
        return $template;
    }
}
