<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductVariantPrice;
use App\Models\Variant;
use Illuminate\Http\Request;
use DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index()
    {


        $product_list = array();
        $product = array(); 
        $total_product = DB::table('products')->select('id','title','description','created_at')->count();

        $product_data = DB::table('products')->select('id','title','description','created_at')->simplePaginate(2);

        foreach ($product_data as $key => $product_id) {
            $product_varient = DB::table('product_variants')
            ->select('variant')
            ->where('product_id', '=' ,$product_id->id)
            ->get();

            $product_varient_price = DB::table('product_variant_prices')
            ->select('price','stock')
            ->where('product_id', '=' ,$product_id->id)
            ->get();

            $product['id']                     = $product_id->id;
            $product['title']                  = $product_id->title;
            $product['description']            = $product_id->description;
            $product['created_at']             = $product_id->created_at;
            $product['variant']                = $product_varient;
            $product['product_variant_prices'] = $product_varient_price;

            array_push($product_list, $product);
            unset($product);
        }

        $varient = DB::table('product_variants')->distinct()->get(['variant']);


        return view('products.index',['product_list'=>$product_list,'total_product'=>$total_product,'varient'=>$varient]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function create()
    {
        $variants = Variant::all();
        return view('products.create', compact('variants'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $title = $request->title;
        $description = $request->description;
        $sku = $request->sku;
        $product_image = $request->product_image;

        $product_variant = $request->product_variant;
        $product_variant_prices = $request->product_variant_prices;        
        DB::table('products')->insert(['title'=>$title,'sku'=>$sku,'description'=>$description]);
        $product_id = DB::getPdo()->lastInsertId();
        
        foreach ($product_variant as $key => $variant) {
            $varient_id = $variant['option'];
            $varient_items = $variant['tags'];
            foreach ($varient_items as $key => $value) {
                DB::table('product_variants')->insert(['variant'=>$value,'variant_id'=>$varient_id,'product_id'=>$product_id]);
            } 
        }
        $product_variant_array = array();
        foreach ($product_variant_prices as $key => $variant_price) {
            $product_varience = explode("/", $variant_price['title']);
            foreach ($product_varience as $key => $varience) {
                $variantid = DB::table('product_variants')->select('id')->where([['variant','=',$varience],['product_id', '=' ,$product_id]])->get()->toArray();
                foreach ($variantid as $key => $ids) {
                    array_push($product_variant_array,$ids->id);
                }
            }
            $price = $variant_price['price'];
            $stock = $variant_price['stock'];

            if (array_key_exists(0, $product_variant_array)){
                $product_variant_one  = $product_variant_array[0];
            }
            else{
                $product_variant_one  =null;
            }
            if (array_key_exists(1, $product_variant_array)){
                $product_variant_two = $product_variant_array[1];
            }
            else{
                $product_variant_two  =null;
            }

            if (array_key_exists(2, $product_variant_array)){
                $product_variant_three = $product_variant_array[2];
            }
            else{
                $product_variant_three =null;
            }

            DB::table('product_variant_prices')->insert(
                [
                    'product_variant_one'=>$product_variant_one ,
                    'product_variant_two'=>$product_variant_two ,
                    'product_variant_three'=>$product_variant_three,
                    'price'=>$price,
                    'stock'=>$stock,
                    'product_id'=>$product_id
                ]
            );
        }
    }


    /**
     * Display the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function show($product)
    {
        dd('show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {

        $variants = Variant::all();
        $products = DB::table('products')
        ->select('*')
        ->where('id', '=' , request()->segment(2))
        ->get();
        $product_variant_prices = DB::table('product_variant_prices')
        ->select('*')
        ->where('product_id', '=' , request()->segment(2))
        ->get();

        $product_variants = DB::table('product_variants')
        ->select('id','variant')
        ->where('product_id','=', request()->segment(2))
        ->get();
        return view('products.edit', compact('variants','products','product_variant_prices','product_variants'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
       echo "update_is running";
   }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }
}
