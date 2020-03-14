@extends("admin.admin_app")

@section("content")

<div id="main">
    <div class="page-header">
        <h2> {{ isset($stock->stock_id) ? 'Edit: '. $stock->stock_id : 'Add stock' }}</h2>

        <a href="{{ URL::to('admin/stock') }}" class="btn btn-default-light btn-xs"><i class="md md-backspace"></i> Back</a>

    </div>
    @if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
     @if(Session::has('flash_message'))
                    <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
<span aria-hidden="true">&times;</span></button>
                        {{ Session::get('flash_message') }}
                    </div>
    @endif

    <div class="panel panel-default">
            <div class="panel-body">
                {!! Form::open(array('url' => array('admin/stock/addstock'),'class'=>'form-horizontal padding-15','name'=>'stock_form','id'=>'stock_form','role'=>'form','enctype' => 'multipart/form-data')) !!}

                <input type="hidden" name="restaurant_id" value="{{$restaurant_id}}">
                <input type="hidden" name="id" value="{{ isset($stock->id) ? $stock->id : null }}">

                <div class="form-group">
                    <label for="" class="col-sm-3 control-label">Menu</label>
                    <div class="col-sm-4">
                        <select id="basic" name="menu_id" class="selectpicker show-tick form-control">
                            <option value="">Select Menu</option>

                            @foreach($menu_items as $i => $menu)
                                @if(isset($stock->menu_id) && $stock->menu_id==$menu->id)
                                    <option value="{{$menu->id}}" selected >{{$menu->menu_name}}</option>

                                @else
                                <option value="{{$menu->id}}">{{$menu->menu_name}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="col-sm-3 control-label">Sort Description</label>
                      <div class="col-sm-9">
                        <input type="text" name="description" value="{{ isset($stock->description) ? $stock->description : null }}" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="col-sm-3 control-label">Type</label>
                    <div class="col-sm-4">
                        <select id="type" name="type" class="selectpicker show-tick form-control js-stock_type">
                            <option value="">Select Type</option>

                            @foreach($stock_types as $key => $value)
                                @if(isset($stock->type) && $stock->type==$key)
                                    <option value="{{$key}}" selected >{{$value}}</option>

                                @else
                                <option value="{{$value}}">{{$value}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                @php
                    $qtyShow='hide';
                    $weightShow='hide';

                @endphp
                @if(isset($stock->type))
                 @if($stock->type == 'Quantity')
                    @php $qtyShow='show';@endphp
                 @elseif($stock->type == 'Weight')
                    @php $weightShow='show';@endphp
                 @endif
                @endif
                <div class="form-group js-quantity {{ $qtyShow }}">
                    <label for="" class="col-sm-3 control-label">Quantity</label>
                      <div class="col-sm-9">
                        <input type="text" value="{{ isset($stock->quantity) ? $stock->quantity : null }}" name="quantity" class="form-control"/>
                    </div>
                </div>
                <div class="form-group js-weight {{ $weightShow }}">
                    <label for="" class="col-sm-3 control-label">Weight</label>
                      <div class="col-sm-9">
                        <input type="text" value="{{ isset($stock->weight) ? $stock->weight : null }}" name="weight" class="form-control"/>
                    </div>
                </div>
                <hr>
                <div class="form-group">
                    <div class="col-md-offset-3 col-sm-9 ">
                        <button type="submit" class="btn btn-primary">{{ isset($stock->id) ? 'Edit stock ' : 'Add stock' }}</button>

                    </div>
                </div>

                {!! Form::close() !!}
            </div>
        </div>


</div>
<script type="text/javascript">
$(document).ready(function() {
   $(".js-stock_type").change(function() {
        var selected = $(this).val();
        if(selected == 'Quantity'){
            $('.js-quantity').removeClass('hide');
            $('.js-weight').removeClass('show');
            $('.js-weight').addClass('hide');
        }

        if(selected == 'Weight') {
           $('.js-weight').removeClass('hide');
           $('.js-quantity').removeClass('show');
           $('.js-quantity').addClass('hide');
        }
    });
});
</script>
@endsection
