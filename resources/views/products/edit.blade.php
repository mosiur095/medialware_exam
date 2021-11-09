@extends('layouts.app')
@section('content')
<?php
// echo "<pre>";
// print_r($variants);
// json_encode($product_variant_prices);
?>
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Product</h1>
    </div>
    <div id="app">
        <edit-product :variants="{{ $variants }}" :products="{{ $products }}" :product_variants="{{json_encode($productvariants)}}" :product_variant_price="{{json_encode($product_variant_prices)}}">Loading</edit-product>
    </div>
@endsection
