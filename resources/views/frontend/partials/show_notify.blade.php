<div class="modal-body p-4 c-scrollbar-light">
    <div class="row">


        <div class="col-lg-12">
            <div class="row">
                <div class="col-lg-4">
                    <h2 class="mb-2 fs-20 fw-600 text-center">
                        {{ $product->getTranslation('name') }}
                    </h2>
                    <a href="{{ route('product', $product->slug) }}" class="d-block">
                        <img class="img-fit lazyload mx-auto hov-shadow-lg border-light rounded has-transition"
                            src="{{ static_asset('assets/img/placeholder.jpg') }}"
                            data-src="{{ uploaded_asset($product->thumbnail_img) }}" alt="{{ $product->getTranslation('name') }}"
                            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                    </a>
                </div>
                



                
                <div class="col-lg-8 mt-3">
                    <!--Request Stock Form-->

                    <div class="stock__data out-of-stock">
                        <form id="request-stock-form">
                            <div class="row">
                                <div class="form-group col-md-6 d-none">
                                    <label for="user_name">Your Name:</label>
                                    <input type="text" name="user_name" id="user_name" class="form-control">
                                </div>

                                <div class="form-group col-md-6 d-none">
                                    <label for="user_email">Your Email:</label>
                                    <input type="email" name="user_email" id="user_email" class="form-control">
                                </div>

                                <div class="form-group col-md-12">
                                    <label for="user_phone">Your Phone:</label>
                                    <input type="text" name="user_phone" id="user_phone" class="form-control"
                                        required>
                                </div>

                                <div class="form-group col-md-6 d-none">
                                    <label for="requested_quantity">Requested Quantity:</label>
                                    <input type="number" name="requested_quantity" id="requested_quantity"
                                        class="form-control">
                                </div>
                            </div>


                            <button type="submit" class="btn btn-primary">Notify Me</button>
                        </form>
                    </div>
                    <!--Request Stock Form-->


                </div>

            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    
    $(document).on('submit', '#request-stock-form', function(e) {
        e.preventDefault();
        var csrfToken = '{{ csrf_token() }}';        
        $.ajax({
            type: 'POST',
            url: '{{ route('product.request-stock', $product->id) }}',
            data: $(this).serialize(),
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            success: function(response) {
                
                AIZ.plugins.notify('success', "{{ translate('Your Request Submitted') }}");
            },
            error: function(error) {
                console.log(error.responseJSON.errors);
                // Optionally, display validation errors to the user
            }
        });
    });
</script>

<style>
    .stock__class {
        background-color: green;
        border-radius: 5px;
        margin-top: 10px;
        color: #ffffff;
        padding: 10px;
    }

    .stock__class_warning {
        background-color: #ff8400;
        border-radius: 5px;
        margin-top: 10px;
        color: #ffffff;
        padding: 10px;
    }

    .stock___data {
        border: 1px solid #ca4189;
        padding: 15px;
        border-radius: 10px;
    }

    .out-of-stock {
        margin-bottom: 27px;
    }
    }
</style>
