@extends("admin.admin_app")

@section("content")
<div id="main">
    <div class="page-header">


        <h2>Order List</h2>

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

        <table id="order_data_table" class="table table-striped table-hover dt-responsive" cellspacing="0" width="100%">
            <thead>
            <tr>
                <th>Date</th>
                <th>User Name</th>
                <th>Mobile</th>
                <th>Email</th>
                <th>Address</th>
                <th>Total Price</th>
                <th>Assign </th>
                <th>Delivery Time </th>
                <th>Status </th>
                <th class="text-center width-100">Action</th>
            </tr>
            </thead>

            <tbody>
            @foreach($order_list as $i => $order)
            <tr>
                <td>{{ date('m-d-Y',$order->created_date)}}</td>
                <td>{{ \App\User::getUserFullname($order->user_id) }}</td>
                <td>{{ \App\User::getUserInfo($order->user_id)->mobile }}</td>
                <td>{{ \App\User::getUserInfo($order->user_id)->email }}</td>
                <td>{{ \App\User::getUserInfo($order->user_id)->address }}</td>
                <td>{{getcong('currency_symbol')}}{{ $order->quantity*$order->item_price }}</td>
                <td>
                    <div class="col-sm-9">
                        <select id="js-assignee" name="driver" class="selectpicker show-tick form-control" data-order_id="{{$order->id}}">
                            <option value="">Select Driver</option>

                            @foreach($drivers as $i => $driver)
                                @if(isset($order->assignee) && $order->assignee==$driver->id)
                                    <option value="{{$driver->id}}" selected >{{$driver->first_name}}</option>

                                @else
                                <option value="{{$driver->id}}">{{$driver->first_name}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </td>
                <td>
                    @if(isset($order->delivery_time))
                     {{$order->delivery_time}}
                    @else
                     <input id="js-delivery_time_{{$order->id}}" class="js-delivery_time" type="text" data-order_id="{{$order->id}}" value="{{ isset($order->delivery_time) ? $order->delivery_time : null }}" name="delivery_time" class="form-control" size="10" placeholder="Min(s)" />
                    @endif
                </td>
                <td>{{ $order->status }}</td>
                <td class="text-center">
                        <div class="btn-group">
                                <button type="button" class="btn btn-default-dark dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                    Actions <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right" role="menu">
                                    <li><a href="{{ url('admin/orderlist/'.$order->id.'/Pending') }}"><i class="md md-lock"></i> Pending</a></li>
                                    <li><a href="{{ url('admin/orderlist/'.$order->id.'/Processing') }}"><i class="md md-loop"></i> Processing</a></li>
                                    <li><a href="{{ url('admin/orderlist/'.$order->id.'/Completed') }}"><i class="md md-done"></i> Completed</a></li>
                                    <li><a href="{{ url('admin/orderlist/'.$order->id.'/Cancel') }}"><i class="md md-cancel"></i> Cancel</a></li>
                                    <li><a href="{{ url('admin/orderlist/'.$order->id) }}"><i class="md md-delete"></i> Delete</a></li>
                                </ul>
                            </div>

                </td>

            </tr>
           @endforeach

            </tbody>
        </table>

        <script type="text/javascript">
            $(document).ready(function() {

                $('#order_data_table').dataTable({
                    "order": [[ 0, "desc" ]],
                     "scrollX": true
                });

            } );
         </script>
    </div>
    <div class="clearfix"></div>
</div>

</div>
<script type="text/javascript">
 $(document).ready(function() {
   $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
   });

   $("select[name='driver']").change(function() {
    var order_id = $(this).data('order_id');
    var assignee = $(this).val();

     $.ajax({
            url: "order/"+order_id+"/assign/"+assignee,
                type: "post",
                datatype: 'JSON',
                success: function( response ) {
                    console.log(response);
                    location.reload(true);
                }
            });
   });

   $(".js-delivery_time").change(function() {
    var order_id = $(this).data('order_id');
    var delivery_time = $(this).val();


           $.ajax({
            url: "order/"+order_id+"/update",
                type: "post",
                data:{'delivery_time' : delivery_time},
                datatype: 'JSON',
                success: function( response ) {
                    console.log(response);
                    location.reload(true);
                }
            });
   });
});
</script>
@endsection
