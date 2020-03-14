@extends("admin.admin_app")

@section("content")
<div id="main">
    <div class="page-header">

        <div class="pull-right">
            <a href="{{URL::to('admin/stock/addstock')}}" class="btn btn-primary">Add <i class="fa fa-plus"></i></a>
        </div>
        <h2>Menu Stock</h2>

    </div>
    @if(Session::has('flash_message'))
                    <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
<span aria-hidden="true">&times;</span></button>
                        {{ Session::get('flash_message') }}
                    </div>
    @endif

<div class="panel panel-default panel-shadow">
    <div class="panel-body">

        <table id="data-table" class="table table-striped table-hover dt-responsive" cellspacing="0" width="100%">
            <thead>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Type</th>
                <th>Quantity</th>
                <th>Weight</th>
                <th class="text-center width-100">Action</th>
            </tr>
            </thead>

            <tbody>
            @foreach($stock_items as $i => $stock)
            <tr>
                <td>{{ $stock->menu_id }}</td>
                <td>{{ $stock->description }}</td>
                <td>{{ $stock->type }}</td>
                <td>{{ $stock->quantity }}</td>
                <td>{{ $stock->weight }}</td>
                <td class="text-center">
                <a href="{{ url('admin/stock/addstock/'.$stock->id) }}" class="btn btn-default btn-rounded"><i class="md md-edit"></i></a>
                <a href="{{ url('admin/stock/delete/'.$stock->id) }}" class="btn btn-default btn-rounded" onclick="return confirm('Are you sure? You will not be able to recover this.')"><i class="md md-delete"></i></a>
            </td>

            </tr>
           @endforeach

            </tbody>
        </table>

    </div>
    <div class="clearfix"></div>
</div>

</div>
@endsection
