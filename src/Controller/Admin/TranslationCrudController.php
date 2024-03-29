<?php

namespace App\Controller\Admin;

use App\Entity\Translation;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class TranslationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Translation::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
