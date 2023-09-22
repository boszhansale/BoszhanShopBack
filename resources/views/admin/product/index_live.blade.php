<div>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-2">
                    <input wire:model="search" type="search" name="search" placeholder="поиск" class="form-control">
                </div>
                <div class="col-md-2">
                    <select wire:model="brand_id" class="form-control">
                        <option value="all">Все бренды</option>
                        @foreach($brands as $brand)
                            <option value="{{$brand->id}}">{{$brand->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select wire:model="category_id" class="form-control">
                        <option value="all">все категории</option>
                        @foreach($categories as $category)
                            <option value="{{$category->id}}">{{$category->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <table class="table">
                <thead>
                <tr>
                    <th></th>
                    <th>#</th>
                    <th>артикул</th>
                    <th>name</th>
                    @foreach(\App\Models\PriceType::where('id',3)->get() as $priceType)
                        <th>{{$priceType->name}}</th>
                    @endforeach
                    <th>шт/кг</th>
                    <th>остаток</th>
                </tr>
                </thead>
                <tbody>
                @foreach($products as $product)
                    <tr>
                        <td class="project-actions text-right">
                            <a class="btn btn-info btn-sm" href="{{route('admin.product.edit',$product->id)}}">
                                <i class="fas fa-pencil-alt">
                                </i>
                            </a>
                            <a class="btn btn-danger btn-sm"
                               href="{{route('admin.product.delete',$product->id)}}">
                                <i class="fas fa-trash">
                                </i>
                            </a>
                        </td>
                        <th>{{$product->id}}</th>
                        <th>{{$product->article}}</th>

                        <th>
                            <small>{{$product->category->name}}</small>
                            <br>
                            <a>{{$product->name}}</a>
                        </th>

                        @foreach(\App\Models\PriceType::where('id',3)->get() as $priceType)
                            <th>{{$product->prices()->where('price_type_id',$priceType->id)->first()?->price}}</th>
                        @endforeach
                        <th>
                            @if($product->measure == 1)
                                штука
                            @else
                                кг
                            @endif
                        </th>

                        <th>
                            {{$product->remainder}}
                        </th>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{$products->links()}}
        </div>
    </div>

</div>
