<?php

namespace App\Http\Services\Menu;

use App\Models\Menu;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class MenuService{

    public function getParent(){
       return Menu::where('parent_id', 0)->get();
    }

    public function getAll()
    {
        return Menu::orderbyDesc('id')->where('parent_id',0)->paginate(20);
    }

    public function show(){
        return Menu::select('name', 'id')->orderbyDesc('id')->get();
    }
    
    public function create ($request){
        try {
            Menu::create([
                'name' => (string) $request->input('name'),
                'parent_id' => (int) $request->input('parent_id'),
                'description' => (string) $request->input('description'),
                'content' => (string) $request->input('content'),
                'active' => (string) $request->input('active'),
            ]);
            Session::flash('success', 'Tạo Danh Mục Thành Công');

        } catch (\Exception $err) {
            Session::flash('error', $err->getMessage());
            return false;
        }

        return true;
    }

    public function destroy($request)
    {
        $id = (int)$request->input('id');
        $menu = Menu::where('id', $id)->first();
        if ($menu) {
            return Menu::where('id', $id)->orWhere('parent_id', $id)->delete();
        }

        return false;
    }

    public function update($request, $menu){
        if($request->input('parent_id') !== $menu-> id){
            $menu->parent_id = (int) $request->input('parent_id');
        }

        $menu->name = (string) $request->input('name');
        $menu->description = (string) $request->input('description');
        $menu->content = (string) $request->input('content');
        $menu->active = (int) $request->input('active');
        $menu->save();

        Session::flash('success','Cập nhật thành công danh mục');
        return true;
    }

    public function getId($id){
        return Menu::where('id', $id)->where('active', 1)->firstOrFail();
    }

    public function getProduct($menu, $request){
        $query =  $menu->products()
            ->select('id','name','price','price_sale', 'thumb')
            -> where('active',1);
            
        if ($request->input('price')) {
            $query->orderBy('price', $request->input('price'));
        }
        else{
            $query->orderbyDesc('id');
        }

        return $query
            ->paginate(12)
            ->withQueryString();
    }
}