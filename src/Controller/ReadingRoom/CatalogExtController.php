<?php namespace App\Controller\ReadingRoom;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CatalogExtController extends AbstractController
{
    /** @var RepositoryInterface */
    private $categoryRepository;
    
    /** @var RepositoryInterface */
    private $productRepository;
    
    public function __construct(
        RepositoryInterface $categoryRepository,
        RepositoryInterface $productRepository
    ) {
        $this->categoryRepository   = $categoryRepository;
        $this->productRepository    = $productRepository;
    }
    
    public function getProducts( $categoryId, $page, Request $request ): Response
    {
        $category   = $this->productCategoryRepository->findByTaxonCode( $categorySlug );
        
        return $this->render( 'Pages/catalog.html.twig' );
        return $this->render( '@VSCatalog/Pages/Catalog/category_products.html.twig', [
            'category'      => $category,
            'shoppingCart'  => $this->getShoppingCart( $request ),
        ]);
    }
}