<div>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-2">
                    <input wire:model="start_date"  type="date" class="form-control">
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
                    <th>Время</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($reports as $report)
                    <tr>
                        <td>{{$report->id}}</td>
                        <td>{{$report->created_at->format('Y-m-d')}}</td>
                        <td>{{$report->created_at->format('H:i')}}</td>
                        <td  class="project-actions text-right">
                            <a class="btn btn-primary btn-sm" href="{{route('admin.store.z-report-show',$report->id)}}" target="_blank">
                                <i class="fas fa-folder">
                                </i>
                            </a>
                        </td>

                    </tr>
                @endforeach
                </tbody>
            </table>

        </div>
    </div>
    {{$reports->links()}}
</div>
