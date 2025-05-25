@extends('backend.layouts.app')
@section('content')
<div class="card">
   <div class="card-header">
      <h5 class="mb-0 h6">{{translate('Product Bulk Export')}}</h5>
   </div>
  
   
   <!--Date Wise-->
   <div class="card-body">
      <br>
      <form method="get" action="/product-bulk-download-date-wise">
      <div class="row">
         <div class="col-md-4">
            <label>Start Date *</label>
           <input name="start_date" id="start_date" class="form-control" type="date" required>
         </div>
         <div class="col-md-4">
            <label>End Date *</label>
            <input name="end_date" id="end_date" class="form-control" type="date" required>
         </div>
         <div class="col-md-4" style="margin-top:20px">
             <a href="{{ static_asset('download/product_bulk_demo.xlsx') }}" download><button class="btn btn-info">{{ translate('Download CSV')}}</button></a>
         </div>
      </div>
      </form>
   </div>
   <!--Date Wise-->
</div>
@endsection