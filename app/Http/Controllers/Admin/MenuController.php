<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Menu\CreateFormRequest;
use App\Models\Menu;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Services\Menu\MenuService;

class MenuController extends Controller
{
    protected $menuService;

    public function __construct(MenuService $menuService){
        $this->menuService = $menuService;
    }

    public function create(){
       return view('admin.menu.add',[
        'title' => 'Thêm Danh Mục Mới',
        'menus' => $this->menuService->getParent(),
       ]);
    }

    public function update(Menu $menu, CreateFormRequest $request){
        $this->menuService->update($request, $menu);
        return redirect('/admin/menus/list');
    }

    public function store(CreateFormRequest $request){

        $this->menuService->create($request);
        return redirect()->back();
    }

    public function index(){
        return view('admin.menu.list',[
            'title' => 'Danh Sach Danh Muc Moi',
            'menus' => $this->menuService->getAll(),
        ]);
    }

    public function show(Menu $menu){
        return view('admin.menu.edit',[
            'title' => 'Chinh Sua Danh Muc ' . $menu->name,
            'menu' => $menu,
            'menus' => $this->menuService->getParent(),
        ]);
    }   
    
     public function destroy(Request $request): JsonResponse
    {
        $result = $this->menuService->destroy($request);
        if ($result) {
            return response()->json([
                'error' => false,
                'message' => 'Xóa thành công danh mục'
            ]);
        }

        return response()->json([
            'error' => true
        ]);
    }
}
