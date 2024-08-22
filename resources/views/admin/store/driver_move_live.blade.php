<div>
    <form action="{{route('admin.store.driver-moving')}}" method="post">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Выберите водителья "C"</div>
                    <div class="card-body">
                        <div class="form-group">
                            <select name="from_driver_id" wire:model="from_driver_id" class="form-control" required>
                                <option value="">Выберите</option>
                                @foreach($from_drivers as $driver)
                                    <option value="{{$driver->id}}">{{$driver->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            количество ТТ: <b>{{$from_driver_stores_count}}</b>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Выберите водителья "На"</div>
                    <div class="card-body">
                        <div class="form-group">
                            <select name="to_driver_id" required wire:model="to_driver_id" class="form-control">
                                <option value="">Выберите</option>

                                @foreach($to_drivers as $driver)
                                    <option value="{{$driver->id}}">{{$driver->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            количество ТТ: <b>{{$to_driver_stores_count}}</b>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <button type="submit" class="btn btn-primary">Переместит</button>
            </div>
        </div>
    </form>
</div>
