@extends('layouts.app')

@section('content')

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Products</h1>
    </div>


    <div class="card">
        <form action="{{url('/search_product')}}" method="POST" class="card-header">
            @csrf
            <div class="form-row justify-content-between">
                <div class="col-md-2">
                    <input type="text" name="title" placeholder="Product Title" class="form-control">
                </div>
                <div class="col-md-2">
                    <select name="variant" id="" class="form-control" style="width:167px;">
                        @foreach($varient as $varient_name)
                        <option>{{$varient_name->variant}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Price Range</span>
                        </div>
                        <input type="text" name="price_from" aria-label="First name" placeholder="From" class="form-control">
                        <input type="text" name="price_to" aria-label="Last name" placeholder="To" class="form-control">
                    </div>
                </div>
                <div class="col-md-2">
                    <input type="date" name="date" placeholder="Date" class="form-control">
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary float-right"><i class="fa fa-search"></i></button>
                </div>
            </div>
        </form>

        <div class="card-body">
            <div class="table-response">
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Variant</th>
                        <th width="150px">Action</th>
                    </tr>
                    </thead>

                    <tbody>
                        <?php
                        $paginate = 0;
                        ?>
                        @foreach($product_list as $value)
                            <tr>
                                <td>{{$value['id']}}</td>
                                <td>{{$value['title']}}<br> Created at : {{$value['created_at']}}</td>
                                <td>{{$value['description']}}t</td>
                                <td>
                                    <dl class="row mb-0" style="height: 80px; overflow: hidden" id="variant">

                                        <dt class="col-sm-3 pb-0">
                                           @foreach($value['variant'] as $variant)
                                                {{$variant->variant}}/
                                           @endforeach
                                        </dt>
                                        <dd class="col-sm-9">
                                            <dl class="row mb-0">
                                                @foreach($value['product_variant_prices'] as $product_variant_prices)
                                                    <dt class="col-sm-6 pb-0" style="font-size: 10px;">Price : {{ $product_variant_prices -> price}}</dt>
                                                    <dd class="col-sm-6 pb-0" style="font-size: 10px;">InStock : {{ $product_variant_prices -> stock }}</dd>
                                                @endforeach
                                            </dl>
                                        </dd>
                                    </dl>
                                    <button onclick="$('#variant').toggleClass('h-auto')" class="btn btn-sm btn-link">Show more</button>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('product.edit', $value['id']) }}" class="btn btn-success">Edit</a>
                                    </div>
                                </td>
                            </tr>
                            <?php
                            $paginate += 1;
                            ?>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>

        <div class="card-footer">
            <div class="row justify-content-between">
                <div class="col-md-6">
                    <p>Showing 1 to {{$paginate}} out of {{$total_product}}</p>
                </div>
                <div class="col-md-2">

                </div>
            </div>
        </div>
    </div>

@endsection
