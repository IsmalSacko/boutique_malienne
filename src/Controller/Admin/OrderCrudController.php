<?php

namespace App\Controller\Admin;

use App\Classe\Mailjet;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator;

class OrderCrudController extends AbstractCrudController
{
    private $mapper;
    private $crudUlGenerator;

    /**
     * OrderCrudController constructor.
     * @param $manager
     * @param $crudUlGenerator
     */
    public function __construct(EntityManagerInterface $manager,CrudUrlGenerator $crudUlGenerator)
    {
        $this->manager = $manager;
        $this->crudUlGenerator = $crudUlGenerator;
    }

    public static function getEntityFqcn(): string

    {
        return Order::class;
    }
    public function configureActions(Actions $actions): Actions
    {
        $updatePreparation = Action::new('updatePreparation','Préparation en cours', 'fas fa-box-open ml-3')
            ->linkToCrudAction
        ('updatePreparation');
        $updateDelivery = Action::new('updateDelivery','Livraison en cours','fas fa-truck')->linkToCrudAction('updateDelivery');

        return $actions
            ->add('detail',$updatePreparation)
            ->add('detail',$updateDelivery)
            ->add('index', 'detail');
    }
    public function updatePreparation(AdminContext $context){
           $order = $context->getEntity()->getInstance() ;
           $order->setState(2);
           $this->manager->flush();
           $this->addFlash('notice',"<span style='color: green'><strong>La commande "
               .$order->getReference()." est bien <u>en cours de préparation<u/>.</strong></span>");
           $url = $this->crudUlGenerator->build()
           ->setController(OrderCrudController::class)
           ->setAction('index')
           ->generateUrl();
           $email = new Mailjet();
           $email->send($order->getUser()->getEmail(),
               $order->getUser()->getFirstname(),
               'Commande en cours de préparation',
               'Bonjour '.$order->getUser()->getFirstname()
               .', nous vous informons que votre commande '
               .$order->getReference()
               .' est en cours de préparation .<br> A très bientôt !');
           return $this->redirect($url);
    }
    public function updateDelivery(AdminContext $context){
           $order = $context->getEntity()->getInstance() ;
           $order->setState(3);
           $this->manager->flush();
           $this->addFlash('notice',"<span style='color: orange'><strong>La commande "
               .$order->getReference()." est bien <u>en cours de livraison<u/>.</strong></span>");
           $url = $this->crudUlGenerator->build()
           ->setController(OrderCrudController::class)
           ->setAction('index')
           ->generateUrl();
        $email = new Mailjet();
        $email->send($order->getUser()->getEmail(),
            $order->getUser()->getFirstname(),
            'Commande en cours de livraison',
            'Bonjour '.$order->getUser()->getFirstname()
            .', nous vous informons que votre commande '
            .$order->getReference()
            .' est en cours de livraison .<br> A très bientôt !');
           return $this->redirect($url);
    }
    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setDefaultSort(['id'=>'DESC']);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            DateTimeField::new('created_at', 'Passée le :'),
            TextField::new('user.getFullName','Utilisateur :'),
            TextEditorField::new('delivery', 'Adresse de livraison :')->onlyOnDetail(),
            MoneyField::new('total', 'Total :')->setCurrency('EUR'),
            TextField::new('carierName','Transporteur :'),
            MoneyField::new('carierPrice','Frais de prot :')->setCurrency('EUR'),
            ChoiceField::new('state' ,'Status de la commande :')->setChoices([
              'Non-payée' => 0,
              'Payée' => 1,
              'Préparation en cours' => 2,
              'Livraison en cours' => 3,

            ]),
            ArrayField::New('orderDetails', 'Produits achetés')->hideOnIndex()
        ];
    }

}
