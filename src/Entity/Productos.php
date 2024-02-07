<?php

namespace App\Entity;

use App\Repository\ProductosRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductosRepository::class)]
class Productos
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $nombre = null;

    #[ORM\Column(length: 255)]
    private ?string $descripcion = null;

    #[ORM\Column]
    private ?float $peso = null;

    #[ORM\Column]
    private ?int $stock = null;

    #[ORM\Column]
    private ?int $precio = null;

    #[ORM\ManyToOne(inversedBy: 'productos')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Categorias $categoria = null;

    #[ORM\OneToMany(targetEntity: Pedidosproductos::class, mappedBy: 'producto', orphanRemoval: true)]
    private Collection $pedidosproductos;

    public function __construct()
    {
        $this->pedidosproductos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): static
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(string $descripcion): static
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function getPeso(): ?float
    {
        return $this->peso;
    }

    public function setPeso(float $peso): static
    {
        $this->peso = $peso;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): static
    {
        $this->stock = $stock;

        return $this;
    }

    public function getPrecio(): ?int
    {
        return $this->precio;
    }

    public function setPrecio(int $precio): static
    {
        $this->precio = $precio;

        return $this;
    }

    public function getCategoria(): ?categorias
    {
        return $this->categoria;
    }

    public function setCategoria(?categorias $categoria): static
    {
        $this->categoria = $categoria;

        return $this;
    }

    /**
     * @return Collection<int, Pedidosproductos>
     */
    public function getPedidosproductos(): Collection
    {
        return $this->pedidosproductos;
    }

    public function addPedidosproducto(Pedidosproductos $pedidosproducto): static
    {
        if (!$this->pedidosproductos->contains($pedidosproducto)) {
            $this->pedidosproductos->add($pedidosproducto);
            $pedidosproducto->setProducto($this);
        }

        return $this;
    }

    public function removePedidosproducto(Pedidosproductos $pedidosproducto): static
    {
        if ($this->pedidosproductos->removeElement($pedidosproducto)) {
            // set the owning side to null (unless already changed)
            if ($pedidosproducto->getProducto() === $this) {
                $pedidosproducto->setProducto(null);
            }
        }

        return $this;
    }
}
