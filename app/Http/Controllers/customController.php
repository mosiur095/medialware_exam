<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class customController extends Controller
{

	public function search_product(Request $request){

		$title     = $request->input('title');
		$variant   = $request->input('variant');
		$price_from= $request->input('price_from');
		$price_to  = $request->input('price_to');
		$date      = $request->input('date');


		// $product_data = DB::table('products')
		// ->join('product_variants', 'products.id' ,'=','product_variants.product_id')
		// ->join('product_variant_prices', 'products.id','=','product_variant_prices.product_id')
		// ->select('title', 'description', 'products.created_at','variant','price')
		// ->WHERE(
		// 	[
		// 		['title','LIKE',"'%".$title."%'"],
		// 		['variant','LIKE',"'%".$variant."%'"],
		// 		['price','>=',$price_from],
		// 		['price','<=',$price_to],
		// 		['products.created_at','LIKE',"'%".$date."%'"]
		// 	]
		// )
		// ->get();

		// dd($product_data);
		$varient = DB::table('product_variants')->distinct()->get(['variant']);


		$sql = "SELECT `products`.`id`,`title`, `description`, `products`.`created_at`,`variant`,`price`,`stock` FROM `products` INNER JOIN product_variants ON `products`.`id` = `product_variants`.`product_id` INNER JOIN `product_variant_prices` ON `products`.`id` = `product_variant_prices`.`product_id` WHERE `products`.`title` LIKE '%".$title."%' AND `products`.`created_at` LIKE '%".$date."%' AND `product_variants`.`variant`='".$variant."' AND `product_variant_prices`.`price` >= '".$price_from."' AND `product_variant_prices`.`price` <= '".$price_to."' ORDER BY  `title`";
		$product_lists = DB::select($sql);
		return view('products.searched_product',['product_list'=>$product_lists,'varient'=>$varient]);
	}

}
