<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use App\Repository\CategoriasRepository;

class CategoriaExtension extends AbstractExtension
{
    private $categoriaRepository;

    public function __construct(CategoriasRepository $categoriaRepository)
    {
        $this->categoriaRepository = $categoriaRepository;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('categorias', [$this, 'getCategorias']),
        ];
    }

    public function getCategorias()
    {
        return $this->categoriaRepository->findAll();
    }
}