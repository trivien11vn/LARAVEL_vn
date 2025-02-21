<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Services\CartService;

class CartController extends Controller
{
    protected $cart;
    public function __construct(CartService $cart){
        $this->cart = $cart;
    }

    public function index(){
        return view('admin.cart.customer', [
            'title' => 'Danh sách đơn đặt hàng',
            'customers' => $this->cart->getCustomer()
        ]);
    }

    public function show(Customer $customer){
        return view('admin.cart.detail',[
            'title' => 'Chi tiết đơn hàng: '. $customer->name,
            'customer' => $customer,
            'carts' => $customer -> carts() -> with('product') -> get()
        ]);
    }
}
