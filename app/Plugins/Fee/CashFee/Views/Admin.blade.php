@extends($templatePathAdmin.'layout')

@section('main')
<div class="row">
  <div class="col-md-12">
     <div class="card">
          <div class="card-header with-border">
              <h2 class="card-title">{{ $title_description??'' }}</h2>

              <div class="card-tools">
                  <div class="btn-group pull-right" style="margin-right: 5px">
                      <a href="{{ sc_route_admin('admin_plugin',['code'=>'Shipping']) }}" class="btn  btn-flat btn-default" title="List"><i class="fa fa-list"></i><span class="hidden-xs"> {{sc_language_render('admin.back_list')}}</span></a>
                  </div>
              </div>
          </div>
            <!-- /.card-header -->
            <div class="card-body">
            <form id="form-add-item">
             <table class="table table-bordered table-hover">
                <thead>
                <tr>
                  <th width="40%">{{ sc_language_render($configKey.'.amount_total') }}</th>
                  <th width="40%">{{ sc_language_render($configKey.'.amount_fee') }}</th>
                  <th></th>
                </tr>
                </thead>
                <tbody>
                    @csrf
                    @if (count($valueConfig))
                        @foreach ($valueConfig as $range => $amount)
                            <tr>
                              <td>{{ number_format($range) }}</td>
                              <td>{{ number_format($amount) }}</td>
                              <td><span onClick="removeData($(this), '{{ $range }}');" title="{{ sc_language_render('action.remove') }}" class="btn btn-flat btn-danger btn-sm"><i class="fa fa-trash"></i></span></td>
                            </tr>
                        @endforeach
                    @endif
                    <tr id="add-item">
                      <td>
                        <button  type="button" class="btn btn-flat btn-success" id="add-item-button" title="{{sc_language_render('action.add') }}"><i class="fa fa-plus"></i> {{ sc_language_render('action.add') }}</button>
                        &nbsp;&nbsp;&nbsp;<button style="display: none; margin-right: 50px" type="button" class="btn btn-flat btn-warning" id="add-item-button-save"  title="Save"><i class="fa fa-save"></i> {{ sc_language_render('action.save') }}</button>
                      </td>
                    </tr>
                </tbody>
                <tfoot>
                </tfoot>
              </table>
            </form>
            </div>
            <!-- /.card-body -->
     </div>
  </div>
</div>
@endsection

@push('styles')
<!-- Ediable -->
<link rel="stylesheet" href="{{ sc_file('admin/plugin/bootstrap-editable.css')}}">
@endpush

@push('scripts')
<!-- Ediable -->
<script src="{{ sc_file('admin/plugin/bootstrap-editable.min.js')}}"></script>
<script type="text/javascript">
  $('#add-item-button').click(function() {
    var htmlRender = '{!! $htmlRender !!}';
    $('#add-item').before(htmlRender);
    $('#add-item-button-save').show();
    $('#add-item-button').hide();
  });

  $('#add-item-button-save').click(function(event) {
      $('#add-item-button').prop('disabled', true);
      $('#add-item-button-save').button('loading');
      $.ajax({
          url:'{{ route("admin_cod_fee.create") }}',
          type:'post',
          dataType:'json',
          data:$('form#form-add-item').serialize(),
          beforeSend: function(){
              $('#loading').show();
          },
          success: function(result){
            $('#loading').hide();
              if(parseInt(result.error) ==0){
                  location.reload();
              }else{
                alertJs('error', result.msg);
              }
          }
      });
  });

  function removeData(obj,key) {
    $.ajax({
            url:'{{ sc_route_admin('admin_cod_fee.remove') }}',
            type:'post',
            data: {
              key:key,
                _token: '{{ csrf_token() }}',
            },
            beforeSend: function(){
                $('#loading').show();
            },
            success: function(result){
              $('#loading').hide();
                if(parseInt(result.error) ==0){
                    location.reload();
                }else{
                  alertJs('error', result.msg);
                }
            }
        });
  }

</script>

@endpush
