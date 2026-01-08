<?php namespace App\Entity\UserManagement;

use Doctrine\ORM\Mapping as ORM;
use Vankosoft\UsersBundle\Model\User as BaseUser;

use Vankosoft\UsersSubscriptionsBundle\Model\Interfaces\SubscribedUserInterface;
use Vankosoft\UsersSubscriptionsBundle\Model\Traits\SubscribedUserEntity;
use Vankosoft\PaymentBundle\Model\Interfaces\UserPaymentAwareInterface;
use Vankosoft\PaymentBundle\Model\Traits\UserPaymentAwareEntity;
use Vankosoft\PaymentBundle\Model\Interfaces\CustomerInterface;
use Vankosoft\PaymentBundle\Model\Traits\CustomerEntity;
use Vankosoft\CatalogBundle\Model\Interfaces\UserSubscriptionAwareInterface;
use Vankosoft\CatalogBundle\Model\Traits\UserSubscriptionAwareEntity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Entity\Catalog\Product;

/**
 * @Doctrine\Common\Annotations\Annotation\IgnoreAnnotation( "ORM\MappedSuperclass" )
 * @Doctrine\Common\Annotations\Annotation\IgnoreAnnotation("ORM\Column")
 */
#[ORM\Entity]
#[ORM\Table(name: "VSUM_Users")]
class User extends BaseUser implements
    SubscribedUserInterface,
    UserPaymentAwareInterface,
    CustomerInterface,
    UserSubscriptionAwareInterface
{
    use SubscribedUserEntity;
    use UserPaymentAwareEntity;
    use CustomerEntity;
    use UserSubscriptionAwareEntity;
    
    /** @var Collection */
    #[ORM\OneToMany(targetEntity: Product::class, mappedBy: "user", indexBy: "id")]
    private $submittedBooks;
    
    /** @var bool */
    #[ORM\Column(name: "auto_bookmark", type: "boolean", options: ["default" => 0, ])]
    private $autoBookmark;
    
    public function __construct()
    {
        $this->newsletterSubscriptions  = new ArrayCollection();
        $this->orders                   = new ArrayCollection();
        $this->pricingPlanSubscriptions = new ArrayCollection();
        
        $this->submittedBooks           = new ArrayCollection();
        
        parent::__construct();
    }
    
    /**
     * {@inheritDoc}
     */
    public function getRoles(): array
    {
        /* Use RolesCollection */
        return $this->getRolesFromCollection();
    }
    
    public function getSubmittedBooks(): Collection
    {
        return $this->submittedBooks;
    }
    
    public function getAutoBookmark()
    {
        return $this->autoBookmark;
    }
    
    public function setAutoBookmark( $autoBookmark )
    {
        $this->autoBookmark = $autoBookmark;
        
        return $this;
    }
}
