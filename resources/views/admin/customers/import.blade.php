@extends('layouts.admin')
@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Customer Import</h4>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="mdi mdi-check-all me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
                @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="mdi mdi-check-all me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                <form action="{{ route('admin.import.store') }}" name="customerImport" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div data-repeater-list="group-a">
                        <div data-repeater-item class="row">
                            @if(Session::get('user')['role'] == 'User')
                            <input type="hidden" name="users_id" value="{{Session::get('user')['id']}}">
                            <input type="hidden" name="branches_id" value="{{Session::get('user')['branches_id']}}">
                            @else
                            <div class="mb-3 col-lg-3">
                                <label for="branches_id">Branch</label>
                                <select name="branches_id" id="branches_id" class="form-select" required>
                                    <option value="">Select Branch</option>
                                    @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('branches_id'))
                                <div class="error text-danger">{{ $errors->first('branches_id') }}</div>
                                @endif
                            </div>
                            <div class="mb-3 col-lg-3">
                                <label for="users_id">Users</label>
                                <select name="users_id" id="users_id" class="form-select" required>
                                    <option value="">Select User</option>
                                </select>
                                @if ($errors->has('users_id'))
                                <div class="error text-danger">{{ $errors->first('users_id') }}</div>
                                @endif
                            </div>
                            @endif
                            <div class="mb-3 col-lg-3">
                                <label for="file">File</label>
                                <input type="file" name="file" class="form-control" id="file" accept=".xls,.xlsx">
                                @if ($errors->has('file'))
                                <div class="error text-danger">{{ $errors->first('file') }}</div>
                                @endif
                            </div>

                            <div class="col-lg-1 align-self-center">
                                <div class="d-flex gap-2">
                                    <input type="submit" class="btn btn-success mt-3 mt-lg-0" value="Submit" />
                                    <a class="btn btn-light mt-3 mt-lg-0" href="{{ URL::to('/admin/customers') }}">Back</a>
                                </div>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- end row -->
@endsection

@section('script')
<script>
    $(function() {
        $("form[name='customerImport']").validate({
            rules: {
                file: {
                    required: true,
                },
            },
            submitHandler: function(form) {
                form.submit();
            }
        });
    });
</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#branches_id').change(function() {
            var branchId = $(this).val();
            if (branchId) {
                $.ajax({
                    url: '/get-users-by-branch/' + branchId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#users_id').empty();
                        $('#users_id').append('<option value="">Select User</option>');
                        $.each(data, function(key, user) {
                            $('#users_id').append('<option value="' + user.id + '">' + user.name + '</option>');
                        });
                    }
                });
            } else {
                $('#users_id').empty().append('<option value="">Select User</option>');
            }
        });
    });
</script>
@endsection