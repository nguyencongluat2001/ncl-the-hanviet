<?php

namespace Modules\Client\Page\Home\Controllers;

use App\Http\Controllers\Controller;
use Modules\Base\Library;
use Illuminate\Http\Request;
use Modules\Client\Page\Home\Services\HomeService;
use Modules\System\Dashboard\Blog\Services\BlogService;
use Modules\System\Dashboard\Product\Services\ProductService;
use Modules\System\Dashboard\Category\Services\CategoryService;
use Modules\System\Dashboard\Category\Services\CateService;
use Illuminate\Support\Facades\Http;
use DB;
/**
 * Phân quyền người dùng 
 *
 * @author Luatnc
 */
class HomeController extends Controller
{

    public function __construct(
        CateService $cateService,
        CategoryService $categoryService,
        HomeService $homeService,
        BlogService $blogService,
        ProductService $ProductService
    ){
        $this->cateService = $cateService;
        $this->categoryService = $categoryService;
        $this->blogService = $blogService;
        $this->homeService = $homeService;
        $this->ProductService = $ProductService;
    }

    /**
     * Trang chủ: mỗi đầu mục (cổng cưới, thiệp cưới, rạp cưới...) là một carousel
     *
     * @return view
     */
    public function index(Request $request)
    {
        $sections = config('landing.sections');
        foreach ($sections as $key => $section) {
            $sections[$key]['products'] = $this->ProductService->where('code_category', $section['code_category'])
                ->where('status', 1)->orderByDesc('created_at')->take(20)->get();
        }
        $data['sections'] = $sections;
        // Ảnh cho mockup điện thoại và nền mờ ở hero
        $data['showcase'] = collect($sections)->pluck('products')->flatten()
            ->filter(fn ($product) => !empty($product->image))
            ->map(fn ($product) => url('/file-image-client/product/' . rawurlencode($product->image)))
            ->values();
        return view('client.landing.home', $data);
    }

    /**
     * Xem tất cả sản phẩm của một đầu mục
     *
     * @return view
     */
    public function category(Request $request, $code)
    {
        $section = collect(config('landing.sections'))->firstWhere('code_category', $code);
        abort_if(empty($section), 404);
        $data['section'] = $section;
        $data['sections'] = config('landing.sections');
        $data['products'] = $this->ProductService->where('code_category', $code)
            ->where('status', 1)->orderByDesc('created_at')->get();
        return view('client.landing.category', $data);
    }

    /**
     * khởi tạo dữ liệu, Load các file js, css của đối tượng
     *
     * @return view
     */
    public function nhatrai(Request $request)
    {
        $dataSearch = '';
        $data['status'] = 1;
        // $objResult = $this->ProductService->where('status',1)->get()->take(8);
        // $datas['datas']= $objResult;
        return view('client.home.home',$data);
    }
    /**
     * khởi tạo dữ liệu, Load các file js, css của đối tượng
     *
     * @return view
     */
    public function nhagai(Request $request)
    {
        $dataSearch = '';
        $data['status'] = 2;
        // $objResult = $this->ProductService->where('status',1)->get()->take(8);
        // $datas['datas']= $objResult;
        return view('client.home.home',$data);
    }
    
    
     /**
     * load màn hình danh sách lấy chỉ số thị trường
     *
     * @param Request $request
     *
     * @return json $return
     */
    public function loadList(Request $request)
    { 
        $arrInput = $request->input();
        $data = $this->homeService->loadList($arrInput);
        return view("client.home.loadlist", $data);
    }
     
    /**
     * load màn hình danh sách
     *
     * @param Request $request
     *
     * @return json $return
     */
    public function loadListBlog(Request $request)
    { 
        $arrInput = $request->input();
        $data = array();
        $param = $arrInput;
        // if($param['category'] == '' || $param['category'] == null){
        //     unset($param['category']);
        // }
        $objResult = $this->blogService->where('status',1)->get()->take(4);
        foreach($objResult as $key => $value){
            $category = $this->categoryService->where('code_category', $value->code_category)->first();
            if(!empty($category)){
                $objResult[$key]->cate_name = $category->name_category;
            }
        }
        $data['datas']= $objResult;
        return view("client.home.loadlist-blog", $data);
    }
}
