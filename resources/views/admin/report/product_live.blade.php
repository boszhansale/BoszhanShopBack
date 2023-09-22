<div>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-2">
                    <input wire:model="start_date"   type="date" class="form-control">
                    <input wire:model="end_date"  type="date" class="form-control">
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <table class="table table-hover text-nowrap table-responsive">
                <thead>
                <tr>
                    <th>Название</th>
                    <th>Остат нач</th>
                    <th>Поступило</th>
                    <th>Возв от покуп</th>
                    <th>Излишки</th>
                    <th>Пеоем со скл</th>
                    <th>Списано общее</th>
                    <th>Списание</th>
                    <th>Возв постав</th>
                    <th>Продажи</th>
                    <th>Перем со скл</th>
                    <th>Остат конец</th>
                </tr>
                </thead>
                <tbody>
                @foreach($reports as $report)
                    <tr>
                        <td>
                            <a href="{{route('admin.product.show',$report['id'])}}">
                                {{$report['product_name']}}
                            </a>
                        </td>

                        <td>{{$report['remains_start']}}</td>
                        <td>{{$report['receipt_all']}}</td>
                        <td>{{$report['refund']}}</td>
                        <td>{{$report['overage']}}</td>

                        <td>{{$report['moving_from']}}</td>

                        <td>{{$report['reject']}}</td>
                        <td>{{$report['reject_all']}}</td>
                        <td>{{$report['refund_producer']}}</td>

                        <td>{{$report['order']}}</td>
                        <td>{{$report['moving_to']}}</td>
                        <td>{{$report['remains_end']}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>

        </div>
    </div>
</div>
