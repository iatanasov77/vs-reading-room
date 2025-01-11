<?php namespace App\Controller\ReadingRoom;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Vankosoft\CatalogBundle\Controller\CatalogController as BaseCatalogController;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Doctrine\Persistence\ManagerRegistry;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\ArrayAdapter;
use Vankosoft\CatalogBundle\Model\Interfaces\ProductInterface;

class CatalogController extends BaseCatalogController
{
    use GlobalFormsTrait;
    
    /** @var ManagerRegistry **/
    private $doctrine;
    
    /** @var RepositoryInterface */
    private $genresRepository;
    
    /** @var RepositoryInterface */
    private $translationsRepository;
    
    /** @var RepositoryInterface */
    private $localesRepository;
    
    public function __construct(
        RepositoryInterface $productCategoryRepository,
        RepositoryInterface $productRepository,
        int $latestProductsLimit,
        ManagerRegistry $doctrine,
        RepositoryInterface $genresRepository,
        RepositoryInterface $translationsRepository,
        RepositoryInterface $localesRepository
    ) {
        parent::__construct( $productCategoryRepository, $productRepository, $latestProductsLimit );
        
        $this->doctrine	                = $doctrine;
        $this->genresRepository         = $genresRepository;
        $this->translationsRepository   = $translationsRepository;
        $this->localesRepository        = $localesRepository;
    }
    
    public function latestProductsAction( Request $request ): Response
    {
        $categories = $this->productCategoryRepository->findAll();
        $products   = $this->productRepository->findBy( [], ['updatedAt' => 'DESC'] );
        
        $resources  = new Pagerfanta( new ArrayAdapter( $products ) );
        //$resources->setMaxPerPage( 2 );
        
        $currentPage    = $request->query->get( 'page' );
        if ( $currentPage ) {
            $resources->setCurrentPage( $currentPage );
        }
        
        if($request->isXmlHttpRequest()) {
            return $this->render( '@VSCatalog/Pages/Catalog/partial/products-page.html.twig', [
                'products'  => $resources,
            ]);
        }
        
        return $this->render( '@VSCatalog/Pages/Catalog/latest_products.html.twig', [
            'products'      => $resources,
            'shoppingCart'  => $this->getShoppingCart( $request ),
            'categories'    => $categories,
            'genres'        => $this->genresRepository->findAll(),
        ]);
    }
    
    public function categoryProductsAction( $categorySlug, Request $request ): Response
    {
        $genre      = $this->genresRepository->findByTaxonCode( $categorySlug );
        $products   = $genre->getBooks()->getValues();
        
        $resources  = new Pagerfanta( new ArrayAdapter( $products ) );
        //$resources->setMaxPerPage( 2 );
        
        $currentPage    = $request->query->get( 'page' );
        if ( $currentPage ) {
            $resources->setCurrentPage( $currentPage );
        }
        
        if($request->isXmlHttpRequest()) {
            return $this->render( '@VSCatalog/Pages/Catalog/partial/products-page.html.twig', [
                'products'  => $resources,
            ]);
        }
        
        return $this->render( '@VSCatalog/Pages/Catalog/category_products.html.twig', [
            'category'      => $category,
            'shoppingCart'  => $this->getShoppingCart( $request ),
        ]);
    }
    
    public function showProductAction( $categorySlug, $productSlug, Request $request ): Response
    {
        $product   = $this->productRepository->findOneBy( ['slug' => $productSlug] );
        
        return $this->render( '@VSCatalog/Pages/Catalog/show_product.html.twig', [
            'product'       => $product,
            'translations'  => $this->getTranslations( $product ),
            'shoppingCart'  => $this->getShoppingCart( $request ),
        ]);
    }
    
    private function getTranslations( ProductInterface $product ): array
    {
        $translations   = [];
        foreach ( \array_keys( $this->translationsRepository->findTranslations( $product ) ) as $localeCode ) {
            $locale = $this->localesRepository->findOneBy( ['code' => $localeCode ] );
            $translations[$localeCode]    = $locale->getTitle();
        }
        
        return $translations;
    }
}