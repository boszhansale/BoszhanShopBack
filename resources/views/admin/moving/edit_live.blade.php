<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#productsCreate">
                    добавить позицию
                </button>

                <div wire:ignore.self class="modal fade" id="productsCreate" tabindex="-1" role="dialog" aria-labelledby="productsCreateLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="product_id">Продукт</label>
                                    <select wire:model="createProductId"  class="form-control" id="product_id" >
                                        <option value="">выберите</option>
                                        @foreach($products as $product)
                                            <option value="{{$product->id}}">{{$product->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="price">Факт. кол.</label>
                                    <input wire:model="createCount" type="number" class="form-control" id="count"  value="0">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
                                <button type="button" wire:click="createProduct" data-dismiss="modal" class="btn btn-primary">Создать</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-responsive table-striped">
                    <thead>
                    <tr>
                        <th></th>
                        <th>Продукт</th>
                        <th>артикул</th>
                        <th>Цена</th>
                        <th>количество</th>

                    </tr>
                    </thead>
                    <tbody>
                    @foreach($moving->products()->get() as $basket)
                        <tr>
                            <td>
                                <a class="btn btn-danger btn-sm" href="#" onclick="return confirm('Удалить?')" wire:click="delete({{$basket->id}})">
                                    <i class="fas fa-trash">
                                    </i>
                                </a>
                            </td>
                            <td>{{$basket->product->name}}</td>
                            <td>{{$basket->product->article}}</td>
                            <td>{{$basket->price}}</td>
                            <td>
                                <input type="text" value="{{$basket->count}}" name="products[{{$basket->id}}][count]">
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
