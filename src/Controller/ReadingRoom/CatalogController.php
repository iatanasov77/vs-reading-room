<?php namespace App\Controller\ReadingRoom;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Vankosoft\CatalogBundle\Controller\CatalogController as BaseCatalogController;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Doctrine\Persistence\ManagerRegistry;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\ArrayAdapter;

class CatalogController extends BaseCatalogController
{
    use GlobalFormsTrait;
    
    /** @var ManagerRegistry **/
    private $doctrine;
    
    public function __construct(
        RepositoryInterface $productCategoryRepository,
        RepositoryInterface $productRepository,
        int $latestProductsLimit,
        ManagerRegistry $doctrine
    ) {
        parent::__construct( $productCategoryRepository, $productRepository, $latestProductsLimit );
        
        $this->doctrine	= $doctrine;
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
        ]);
    }
    
    public function categoryProductsAction( $categorySlug, Request $request ): Response
    {
        $category   = $this->productCategoryRepository->findByTaxonCode( $categorySlug );
        $products   = $category->getProducts()->getValues();
        
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
            'shoppingCart'  => $this->getShoppingCart( $request ),
        ]);
    }
}