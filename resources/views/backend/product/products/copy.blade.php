@extends('backend.layouts.app')
@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
   <div class="row align-items-center">
      <div class="col-auto">
         <h1 class="h3">{{translate('Copy Product')}}</h1>
      </div>
      @if($type != 'Seller')
      <div class="col text-right">
         <a href="{{ route('products.create') }}" class="btn btn-circle btn-info">
         <span>{{translate('Add New Product')}}</span>
         </a>
      </div>
      @endif
   </div>
</div>
<br>
<div class="card">
   <form class="" id="sort_products" action="/admin/products/copy-save" method="post">
       @csrf
      <div class="card-header row gutters-5">
         <div class="col">
            <h5 class="mb-md-0 h6">{{ translate('Copy Product') }}</h5>
         </div>
         <div class="col-md-4">
            <label>From Seller</label>
            <select class="form-control form-control-sm aiz-selectpicker mb-2 mb-md-0" data-live-search="true" id="user_id" name="from_id"  required>
               <option value="">--Select--</option>
               @foreach (App\Models\User::where('user_type', '=', 'admin')->orWhere('user_type', '=', 'seller')->get() as $key => $seller)
               <option value="{{ $seller->id }}">{{ $seller->name }}</option>
               @endforeach
            </select>
         </div>
         <div class="col-md-4">
            <label>To Seller</label>
            <select class="form-control form-control-sm aiz-selectpicker mb-2 mb-md-0" data-live-search="true" id="user_id" name="to_id"  required>
               <option value="">--Select--</option>
               @foreach (App\Models\User::where('user_type', '=', 'admin')->orWhere('user_type', '=', 'seller')->get() as $key => $seller)
               <option value="{{ $seller->id }}">{{ $seller->name }}</option>
               @endforeach
            </select>
         </div>
         <div class="col-md-2">
            <div class="form-group mb-0">
               <button class="btn btn-info" type="submit" style="margin-top:25px">Copy Now</button>
            </div>
         </div>
      </div>
   </form>
</div>
@endsection
@section('modal')
@include('modals.delete_modal')
@endsection
@section('script')
<script type="text/javascript">
   $(document).on("change", ".check-all", function() {
       if(this.checked) {
           // Iterate each checkbox
           $('.check-one:checkbox').each(function() {
               this.checked = true;                        
           });
       } else {
           $('.check-one:checkbox').each(function() {
               this.checked = false;                       
           });
       }
     
   });
   
   $(document).ready(function(){
       //$('#container').removeClass('mainnav-lg').addClass('mainnav-sm');
   });
   
   function update_todays_deal(el){
       if(el.checked){
           var status = 1;
       }
       else{
           var status = 0;
       }
       $.post('{{ route('products.todays_deal') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
           if(data == 1){
               AIZ.plugins.notify('success', '{{ translate('Todays Deal updated successfully') }}');
           }
           else{
               AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
           }
       });
   }
   
   function update_published(el){
       if(el.checked){
           var status = 1;
       }
       else{
           var status = 0;
       }
       $.post('{{ route('products.published') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
           if(data == 1){
               AIZ.plugins.notify('success', '{{ translate('Published products updated successfully') }}');
           }
           else{
               AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
           }
       });
   }
   
   function update_approved(el){
       if(el.checked){
           var approved = 1;
       }
       else{
           var approved = 0;
       }
       $.post('{{ route('products.approved') }}', {
           _token      :   '{{ csrf_token() }}', 
           id          :   el.value, 
           approved    :   approved
       }, function(data){
           if(data == 1){
               AIZ.plugins.notify('success', '{{ translate('Product approval update successfully') }}');
           }
           else{
               AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
           }
       });
   }
   
   function update_featured(el){
       if(el.checked){
           var status = 1;
       }
       else{
           var status = 0;
       }
       $.post('{{ route('products.featured') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
           if(data == 1){
               AIZ.plugins.notify('success', '{{ translate('Featured products updated successfully') }}');
           }
           else{
               AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
           }
       });
   }
   
   function sort_products(el){
       $('#sort_products').submit();
   }
   
   function bulk_delete() {
       var data = new FormData($('#sort_products')[0]);
       $.ajax({
           headers: {
               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
           },
           url: "{{route('bulk-product-delete')}}",
           type: 'POST',
           data: data,
           cache: false,
           contentType: false,
           processData: false,
           success: function (response) {
               if(response == 1) {
                   location.reload();
               }
           }
       });
   }
   
</script>
@endsection