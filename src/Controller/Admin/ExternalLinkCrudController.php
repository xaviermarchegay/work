<?php

namespace App\Controller\Admin;

use App\Entity\ExternalLink;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;

class ExternalLinkCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ExternalLink::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('External Link')
            ->setEntityLabelInPlural('External Links')
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('title');
        yield UrlField::new('link');
    }
}
