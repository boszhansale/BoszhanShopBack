<div>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-2">
                    <input wire:model="search" type="search" name="search" placeholder="поиск" class="form-control">
                </div>


                <div class="col-md-2">
                    <select wire:model="counteragentId" class="form-control">
                        <option value="">все контрагенты</option>
                        @foreach($counteragents as $counteragent)
                            <option value="{{$counteragent->id}}">{{$counteragent->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <input wire:model="start_date" type="date" class="form-control">
                    <input wire:model="end_date" type="date" class="form-control">
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            Количество торговых точек: <b>{{$store_count}}</b>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Название</th>
                    <th>Контрагент</th>
                    <th>Телефон</th>
                    <th>БИН</th>
                    <th>скидка %</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($stores as $store)
                    <tr class="{{$store->removed_at != null ?'bg-red':''}}">
                        <th>{{$store->id}}</th>
                        <th>

                            <a href="{{route('admin.store.show',$store->id)}}">
                                <b>{{$store->name}}</b><br>
                                <small>{{$store->address}}</small>
                            </a>
                        </th>
                        <td>
                            @if($store->counteragent)
                                {{$store->counteragent->name}}

                            @endif
                        </td>
                        <td>
                            {{$store->phone}}
                        </td>


                        <td>
                            {{$store->bin}}
                        </td>


                        <td>
                            {{$store->discount}}
                        </td>



                        <td class="project-actions text-right">
                            <a class="btn btn-primary btn-sm" href="{{route('admin.store.show',$store->id)}}">
                                <i class="fas fa-folder">
                                </i>
                            </a>
                            <a class="btn btn-info btn-sm" href="{{route('admin.store.edit',$store->id)}}">
                                <i class="fas fa-pencil-alt">
                                </i>
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

        </div>
    </div>
    {{$stores->links()}}
</div>
