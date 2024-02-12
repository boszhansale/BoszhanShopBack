<div>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-2">
                    <small>поиск</small>
                    <input wire:model="search" type="search" name="search" placeholder="поиск" class="form-control">
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <table class="table table-hover text-nowrap">
                <thead>
                <tr>
                    <th>имя</th>
                    <th>номер</th>
                    <th>скидка</th>
                    <th>начало</th>
                    <th>конец</th>
                    <th>кол. заказов</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($promoCodes as $promoCode)
                    <tr>
                        <td>{{$promoCode->name}}</td>
                        <td>{{$promoCode->phone}}</td>
                        <td>{{$promoCode->discount}}%</td>
                        <td>{{$promoCode->start}}</td>
                        <td>{{$promoCode->end}}</td>
                        @if($promoCode->orders()->whereBetween('created_at',[$promoCode->start,$promoCode->end])->count() > 0)
                            <td><a href="{{route('admin.order.index',['discount_phone'=> $promoCode->phone])}}">{{$promoCode->orders()->whereBetween('created_at',[$promoCode->start,$promoCode->end])->count()}}</a></td>
                        @else
                            <td>{{$promoCode->orders()->whereBetween('created_at',[$promoCode->start,$promoCode->end])->count()}}</td>
                        @endif

                        <td class="project-actions text-left">
                            @if(Auth::id() == 1)
                                <a class="btn btn-info btn-sm" href="{{route('admin.promo-code.edit',$promoCode->id)}}">
                                    <i class="fas fa-pencil-alt">
                                    </i>
                                </a>
                                <a class="btn btn-danger btn-sm"
                                   href="{{route('admin.promo-code.delete',$promoCode->id)}}">
                                    <i class="fas fa-trash">
                                    </i>
                                </a>
                            @endif
                        </td>
                        </tr>
                @endforeach
                </tbody>
            </table>

        </div>

    </div>
    {{$promoCodes->links()}}
</div>
