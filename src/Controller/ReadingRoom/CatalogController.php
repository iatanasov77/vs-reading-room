<?php namespace App\Controller\ReadingRoom;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Vankosoft\CatalogBundle\Controller\CatalogController as BaseCatalogController;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Doctrine\Persistence\ManagerRegistry;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\ArrayAdapter;
use Vankosoft\CatalogBundle\Model\Interfaces\ProductInterface;
use App\Component\ReadingRoom;

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
    
    /** @var ReadingRoom **/
    private $readingRoom;
    
    /** @var int **/
    private $itemsPerPage;
    
    public function __construct(
        RepositoryInterface $productCategoryRepository,
        RepositoryInterface $productRepository,
        int $latestProductsLimit,
        ManagerRegistry $doctrine,
        RepositoryInterface $genresRepository,
        ReadingRoom $readingRoom,
        int $itemsPerPage
    ) {
        parent::__construct( $productCategoryRepository, $productRepository, $latestProductsLimit );
        
        $this->doctrine	                = $doctrine;
        $this->genresRepository         = $genresRepository;
        $this->readingRoom              = $readingRoom;
        $this->itemsPerPage             = $itemsPerPage;
    }
    
    public function latestProductsAction( Request $request ): Response
    {
        $categories = $this->productCategoryRepository->findAll();
        $products   = $this->productRepository->findBy( [], ['updatedAt' => 'DESC'] );
        
        $resources  = new Pagerfanta( new ArrayAdapter( $products ) );
        $resources->setMaxPerPage( $this->itemsPerPage );
        
        $currentPage    = $request->query->get( 'page' );
        if ( $currentPage ) {
            $resources->setCurrentPage( $currentPage );
        }
        
        if($request->isXmlHttpRequest()) {
            return $this->render( '@VSCatalog/Pages/Catalog/partial/products-page.html.twig', [
                'readingRoomSettings'   => $this->readingRoom->settings(),
                'products'              => $resources,
                'translations'          => $this->getTranslations( $products ),
            ]);
        }
        
        return $this->render( '@VSCatalog/Pages/Catalog/latest_products.html.twig', [
            'readingRoomSettings'   => $this->readingRoom->settings(),
            'products'              => $resources,
            'translations'          => $this->getTranslations( $products ),
            'shoppingCart'          => $this->getShoppingCart( $request ),
            'categories'            => $categories,
            'genres'                => $this->genresRepository->findAll(),
        ]);
    }
    
    public function categoryProductsAction( $categorySlug, Request $request ): Response
    {
        $category   = $this->productCategoryRepository->findByTaxonCode( $categorySlug );
        $products   = $category->getProducts()->getValues();
        
        $resources  = new Pagerfanta( new ArrayAdapter( $products ) );
        $resources->setMaxPerPage( $this->itemsPerPage );
        
        $currentPage    = $request->query->get( 'page' );
        if ( $currentPage ) {
            $resources->setCurrentPage( $currentPage );
        }
        
        if($request->isXmlHttpRequest()) {
            return $this->render( '@VSCatalog/Pages/Catalog/partial/products-page.html.twig', [
                'readingRoomSettings'   => $this->readingRoom->settings(),
                'products'              => $resources,
                'translations'          => $this->getTranslations( $products ),
            ]);
        }
        
        return $this->render( '@VSCatalog/Pages/Catalog/category_products.html.twig', [
            'readingRoomSettings'   => $this->readingRoom->settings(),
            'products'              => $resources,
            'category'              => $category,
            'translations'          => $this->getTranslations( $products ),
            'shoppingCart'          => $this->getShoppingCart( $request ),
        ]);
    }
    
    public function showProductAction( $categorySlug, $productSlug, Request $request ): Response
    {
        $product   = $this->productRepository->findOneBy( ['slug' => $productSlug] );
        
        return $this->render( '@VSCatalog/Pages/Catalog/show_product.html.twig', [
            'product'           => $product,
            //'translations'      => $this->getTranslations( $product ),
            'bookTranslations'  => $this->geBookTranslations( $product ),
            'shoppingCart'      => $this->getShoppingCart( $request ),
        ]);
    }
    
    private function getTranslations( array $products ): array
    {
        $translations   = [];
        foreach ( $products as $product ) {
            $translations[$product->getId()] = $this->geBookTranslations( $product );
        }
        
        return $translations;
    }
    
    private function geBookTranslations( ProductInterface $product ): array
    {
        $translations   = [];
        foreach ( $product->getFiles() as $file ) {
            if ( ! \str_starts_with( $file->getCode(), 'product_content' ) ) {
                continue;
            }
            $translations[$file->getLocale()] = $file->getBookType();
        }
        
        return $translations;
    }
}