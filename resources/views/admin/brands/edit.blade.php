@extends('admin.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">					
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Brand</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('brands.index') }}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <form action="{{ route('brands.update', $brand->id) }}" id="editBrandForm" method="post">
                @csrf
                @method('PUT')

                <div class="card">
                    <div class="card-body">								
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name">Name</label>
                                    <input type="text" name="name" id="name" class="form-control" placeholder="Name" value="{{ $brand->name }}">
                                    <p></p>	
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="slug">Slug</label>
                                    <input type="text" readonly name="slug" id="slug" class="form-control" placeholder="Slug" value="{{ $brand->slug }}">	
                                    <p></p>
                                </div>
                            </div>		
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status">Status</label>
                                    <select name="status" id="status" class="form-control">
                                        <option {{ ($brand->status == 1) ? 'selected' : ''}} value="1">Active</option>
                                        <option {{ ($brand->status == 0) ? 'selected' : ''}} value="0">Block</option>
                                    </select>                                           
                                    <p></p>
                                </div>
                            </div>				
                        </div>
                    </div>							
                </div>
                <div class="pb-5 pt-3">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('brands.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
                </div>
            </form>
        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->
@endsection

@section('customJs')
<script>
    $("#editBrandForm").submit(function(event) {
        event.preventDefault();
        $("button[type=submit]").prop('disabled', true);

        $.ajax({
            url: $(this).attr('action'),
            type: 'post',
            data: $(this).serialize(), 
            dataType: 'json',
            success: function(response) {
                $("button[type=submit]").prop('disabled', false);
                if (response.status) {
                    // window.location.href = "{{ route('brands.index') }}";
                } else {
                    var errors = response.errors;
                    if (errors.name) {
                        $("#name").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors.name);
                    } else {
                        $("#name").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
                    }

                    if (errors.slug) {
                        $("#slug").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors.slug);
                    } else {
                        $("#slug").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
                    }
                }
            }, 
            error: function() {
                console.log("Something went wrong");
                $("button[type=submit]").prop('disabled', false);
            }
        });
    });

    $("#name").on('input', function() {
        var element = $(this);
        if (element.val().trim() === '') {
            $("#slug").val('');
            return;
        }
        $("button[type=submit]").prop('disabled', true);
        $.ajax({
            url: '{{ route("getSlug") }}',
            type: 'get',
            data: { title: element.val() },
            dataType: 'json',
            success: function(response) {
                $("button[type=submit]").prop('disabled', false);
                if (response.status) {
                    $("#slug").val(response.slug);
                }
            }
        });
    });
</script>
@endsection
