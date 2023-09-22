<div>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-2">
{{--                    <input wire:model="start_date"   type="date" class="form-control">--}}
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
                    <th>Товар</th>
                    <th>с склада</th>
                    <th>на склад</th>
                    <th>поступление</th>
                    <th>продажа</th>
                    <th>возврат</th>
                    <th>возврат поставщику</th>
                    <th>списание</th>
                    <th>остаток</th>
                </tr>
                </thead>
                <tbody>
                @foreach($reports as $report)
                    <tr>
                        <td>
                            <a href="{{route('admin.product.show',$report->product_id)}}">
                                <b>{{$report->name}}</b><br>
                                <small>{{$report->article}}</small>
                            </a>
                        </td>

                        <td>{{$report->moving_from}}</td>
                        <td>{{$report->moving_to}}</td>
                        <td>{{$report->receipt}}</td>
                        <td>{{$report->sale}}</td>
                        <td>{{$report->refund}}</td>
                        <td>{{$report->refund_producer}}</td>
                        <td>{{$report->reject}}</td>
                        <td>{{$report->remains}}</td>

                    </tr>
                @endforeach
                </tbody>
            </table>

        </div>
    </div>
</div>
