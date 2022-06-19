<?php

namespace App\Controller\Pages;
use App\Utils\View;
use App\Model\Entity\Organization;

class About extends Page
{
    
    /**
     * Método responável por retornar o conteúdo (view) da página de sobre
     * @return string
     */
    public static function getAbout()
    {

    //Organização
    $obOrganization = new Organization;

    //View da home
     $content = View::render('pages/about',[
            
            'name' => $obOrganization->name,
            'description' => $obOrganization->descricao,
            'site' => $obOrganization->site
        ]);

        //Retorna a View da Página
        return parent::getPage('Sobre > Wdev', $content);
    }
}
