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
            <table class="table table-hover text-nowrap">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Дата</th>
                    <th>Сумма</th>
                    <th>Сумма скидки</th>
                </tr>
                </thead>
                <tbody>
                @foreach($reports as $report)
                    <tr>
                        <td>
                            <a href="{{route('admin.order.show',$report->id)}}">
                                {{ $report->id}}
                            </a>
                        </td>
                        <td>{{$report->created_at->format('d.m.Y')}}</td>
                        <td>{{$report->total_price}}</td>
                        <td>{{$report->total_discount_price}}</td>


                    </tr>
                @endforeach
                </tbody>
            </table>

        </div>
    </div>
</div>
