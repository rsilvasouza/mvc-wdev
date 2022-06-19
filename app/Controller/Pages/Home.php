<?php

namespace App\Controller\Pages;
use App\Utils\View;
use App\Model\Entity\Organization;

class Home extends Page
{
    
    /**
     * Método responável por retornar o conteúdo (view) da home
     * @return string
     */
    public static function getHome()
    {

    //Organização
    $obOrganization = new Organization;

    //View da home
     $content = View::render('pages/home',[
            
            'name' => $obOrganization->name
        ]);

        //Retorna a View da Página
        return parent::getPage('Home > Ddev', $content);
    }
}
