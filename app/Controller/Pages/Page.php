<?php

namespace App\Controller\Pages;
use App\Utils\View;

class Page
{

    /**
     * Metodo responsável por redenrizar o topo da página
     * @return string
     */
    private static function getHeader(){
        return View::render('pages/header');
    }

     /**
     * Metodo responsável por redenrizar o rodape da página
     * @return string
     */
    private static function getFooter(){
        return View::render('pages/footer');
    }

    /**
     * Método responável por retornar o conteúdo (view) da nossa página genérica
     * @return string
     */
    public static function getPage($title,$content)
    {
        return View::render('pages/page',[
            'title' => $title,
            'header' =>self::getHeader(),
            'content' => $content,
            'footer' =>self::getFooter()
        ]);
    }
}
