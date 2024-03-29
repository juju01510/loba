<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use App\Entity\User;
use App\Form\TranslationEditType;
use App\Form\TranslationPostNewType;
use App\Form\TranslationSectionNewType;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PostCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Post::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle(Crud::PAGE_INDEX, 'Posts')
            ->setPageTitle(Crud::PAGE_DETAIL, 'Post')
            ->setDefaultSort(['dateCreated' => 'DESC']);
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('title');
        yield TextEditorField::new('content');
        yield CollectionField::new('translations')
            ->setEntryType(TranslationPostNewType::class)
            ->setFormTypeOptions([
                'by_reference' => false,
                'delete_empty' => true,
            ])
            ->onlyWhenCreating()
            ->setLabel('Translation')
            ->setRequired(true)
            ->setHelp('WARNING! Add ONE translation field for the title and ONE for the content!!!');

        yield CollectionField::new('translations')
            ->allowAdd(false)
            ->allowDelete(false)
            ->setEntryType(TranslationEditType::class)
            ->setFormTypeOptions([
                'by_reference' => false,
                'delete_empty' => true,
            ])
            ->onlyWhenUpdating()
            ->setLabel('Translation')
            ->setRequired(true);
        yield ImageField::new('media')
            ->setRequired(false)
            ->setUploadDir('public/uploads/images')
            ->setBasePath('uploads/images')
            ->setUploadedFileNamePattern('[slug]-[timestamp].[extension]');
        yield AssociationField::new('user')
            ->setLabel('Created by')
            ->hideOnForm();
        yield DateField::new('dateCreated')
            ->setLabel('Created on')
            ->hideOnForm();
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            throw new \LogicException('Currently logged in user is not an instance of User?!');
        }
        $entityInstance->setUser($user);

        parent::updateEntity($entityManager, $entityInstance);
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $user = $this->getUser();
        if (!$entityInstance instanceof Post) {
            return;
        }
        $entityInstance->setUser($user);

        parent::persistEntity($entityManager, $entityInstance);
    }
}
