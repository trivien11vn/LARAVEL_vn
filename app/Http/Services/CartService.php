<?php

namespace App\Http\Services;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Session;

class CartService
{
    public function create ($request){
        $quantity = (int)$request->input('num_product');
        $product_id = (int)$request->input('product_id');

        if($quantity <= 0 || $product_id <= 0){
            Session::flash('error', 'Số lượng hoặc sản phẩm không chính xác !');
            return  false;
        }

        $carts = Session::get('carts');
        if (is_null($carts)) {
            Session::put('carts', [
                $product_id => $quantity
            ]);
            return true;
        }

        $exists = Arr::exists($carts, $product_id);
        if ($exists) {
            $carts[$product_id] += $quantity;
            Session::put('carts', $carts);
            return true;
        }

        $carts[$product_id] = $quantity;
        Session::put('carts', $carts);

        return true;
    }

    public function getProduct(){
        $carts = Session::get('carts');
        if(is_null($carts)) {
            return [];
        }
        else{
            $productId = array_keys($carts);
            return Product::select('id', 'name', 'price', 'price_sale', 'thumb')
                ->where('active', 1)
                ->whereIn('id', $productId)
                ->get();
        }
    }

    public function update($request){
        Session::put('carts', $request->input('num_product'));

        return true;
    }
}