@extends('backend/layouts/default')

@section('title0')
			
    @if (Input::get('status'))
        @if (Input::get('status')=='Pending')
            @lang('general.pending')
        @elseif (Input::get('status')=='RTD')
            @lang('general.ready_to_deploy')
        @elseif (Input::get('status')=='Undeployable')
            @lang('general.undeployable')
        @elseif (Input::get('status')=='Deployable')
            @lang('general.deployed')
         @elseif (Input::get('status')=='Requestable')
            @lang('admin/hardware/general.requestable')
        @elseif (Input::get('status')=='Archived')
            @lang('general.archived')
         @elseif (Input::get('status')=='Deleted')
            @lang('general.deleted')
        @endif
    @else
            @lang('general.all')
    @endif

    @lang('general.assets')
@stop

{{-- Page title --}}
@section('title')
    @yield('title0') :: @parent
@stop

{{-- Page content --}}
@section('content')

<style>
.dataTables_filter {padding-right: 20px;}

</style>

<div class="row header">
    <div class="col-md-12">
        <a href="{{ route('create/hardware') }}" class="btn btn-success pull-right"><i class="fa fa-plus icon-white"></i> @lang('general.create')</a>
        <h3>@yield('title0')</h3>
    </div>
</div>

<div class="row">



 {{ Form::open([
      'method' => 'POST',
      'route' => ['hardware/bulkedit'],
	  'class' => 'form-horizontal' ]) }}



{{ Datatable::table()
    ->addColumn('<i class="fa fa-check"></i>',Lang::get('admin/hardware/form.name'), 
    	Lang::get('admin/hardware/table.asset_tag'), 
    	Lang::get('admin/hardware/table.serial'),
		Lang::get('admin/hardware/form.model'),
    	Lang::get('admin/hardware/table.status'),
		Lang::get('admin/hardware/table.location'),
    	Lang::get('general.category'),
    	Lang::get('admin/hardware/table.eol'),
    	Lang::get('admin/hardware/table.checkout_date'), 
    	Lang::get('admin/hardware/table.change'), 
    	Lang::get('table.actions'))
    ->setUrl(route('api.hardware', Input::get('status')))   // this is the route where data will be retrieved
    ->setOptions(
            array(
                'deferRender'=> true,
                'stateSave'=> true,
                'stateDuration'=> -1,
                'dom' =>'CT<"clear">lfrtip',
                'tableTools' => array(
                    'sSwfPath'=> Config::get('app.url').'/assets/swf/copy_csv_xls_pdf.swf',
                    'aButtons'=>array(
                        'copy',
                        'print',
                        array(
                            'sExtends'=>'collection',
                            'sButtonText'=>'Export',
                            'aButtons'=>array(
                                'csv',
                                'xls',
                                'pdf'
                                )
                            )
                        ) 
                    ),
                'colVis'=> array('showAll'=>'Show All','restore'=>'Restore','exclude'=>array(0,9,10),'activate'=>'mouseover'),
                'columnDefs'=> array(array('visible'=>false,'targets'=>array(7,8,9)),array('bSortable'=>false,'targets'=>array(0,10,11))),
				'order'=>array(array(1,'asc')),
            )
        )
    ->render() }}
    <br><button class="btn btn-default" id="bulkEdit" disabled>Bulk Edit</button>

 {{ Form::close() }}

</div>


<script>

	$(function() {

	    $('body').on('change','input.one_required',function() {

	        var check_checked = $('input.one_required:checked').length;
	        //console.log(check_checked);
	        if (check_checked > 0) {
	            $('#bulkEdit').removeAttr('disabled');
	        }
	        else {
	            $('#bulkEdit').attr('disabled', 'disabled');
	        }
	    });
	});
</script>



@stop
