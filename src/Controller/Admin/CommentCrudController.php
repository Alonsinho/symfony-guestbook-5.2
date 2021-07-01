<?php

namespace App\Controller\Admin;

use App\Entity\Comment;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;

class CommentCrudController extends AbstractCrudController
{
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setDateTimeFormat('dd-MM-Y HH-mm');
    }

    public static function getEntityFqcn(): string
    {
        return Comment::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            ImageField::new('photoFilename')
                ->setBasePath('uploads/photos')
                ->setLabel('Photo'),
            AssociationField::new('conference')
                ->setRequired(true)
                ->setHelp('Select a conference'),
            TextField::new('author'),
            TextareaField::new('text'),
            EmailField::new('email', 'Email Address'),
            DateTimeField::new('created_at')
                ->setRequired(true)
                ->renderAsChoice(),
        ];
    }
}
