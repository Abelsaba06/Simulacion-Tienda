<?php
namespace App\Service;
use Symfony\Component\HttpFoundation\RequestStack;

class CartService
{
    private const KEY = '_cart';
    private $requestStack;
    public function __construct(RequestStack $requestStack){
        $this->requestStack = $requestStack;
    }
    public function getSession(){
        return $this->requestStack->getSession();
    }
    public function getCart(){
        return $this->getSession()->get(self::KEY, []);
    }
    public function add(int $id, int $quantity = 1){
        //https://symfony.com/doc/current/session.html
        $cart = $this->getCart();
        //Sólo añadimos si no lo está 
        if (!array_key_exists($id, $cart))
            $cart[$id] = $quantity;
        $this->getSession()->set(self::KEY, $cart);
    }
    public function update(int $id, int $quantity){
        $cart = $this->getCart();
        if (array_key_exists($id, $cart))
            $cart[$id] = $quantity;
        $this->getSession()->set(self::KEY, $cart);
    }
    public function remove(int $id){
        $cart = $this->getCart();
        if (array_key_exists($id, $cart))
            unset($cart[$id]);
        $this->getSession()->set(self::KEY, $cart);
    }
    public function removeAll(){
        $this->getSession()->remove(self::KEY);
    }
    public function totalItems(){
        $cart = $this->getCart();
        return array_sum($cart);
    }
}
