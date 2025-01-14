<?php namespace App\Controller\AdminPanel;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Form\FormTypeInterface;
use Doctrine\Persistence\ManagerRegistry;
use Sylius\Component\Resource\Repository\RepositoryInterface;

use Vankosoft\CatalogBundle\Controller\ProductExtController as BaseProductExtController;
use App\Form\BookForm;

class ProductExtController extends BaseProductExtController
{
    public function getForm( $itemId, $locale, Request $request ): Response
    {
        $em     = $this->doctrine->getManager();
        $item   = $this->productRepository->find( $itemId );
        
        if ( $locale != $request->getLocale() ) {
            $item->setTranslatableLocale( $locale );
            $em->refresh( $item );
        }
        
        $taxonomy   = $this->taxonomyRepository->findByCode(
            $this->getParameter( 'vs_catalog.product_category.taxonomy_code' )
        );
        
        $tagsContext    = $this->tagsWhitelistContextRepository->findByTaxonCode( 'catalog-products' );
        
        return $this->render( '@VSCatalog/Pages/Products/partial/product_form.html.twig', [
            'item'          => $item,
            'form'          => $this->createForm( BookForm::class, $item )->createView(),
            //'form'          => $this->productForm->createView(),
            'taxonomyId'    => $taxonomy->getId(),
            'productTags'   => [], // $tagsContext->getTagsArray(),
        ]);
    }
}