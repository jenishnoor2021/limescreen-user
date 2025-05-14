@extends('layouts.admin')
@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Customer Report</h4>
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

                    <form id="filterForm" action="{{ route('admin.export.show') }}" name="exportShow" method="GET"
                        enctype="multipart/form-data">
                        @csrf
                        <div data-repeater-list="group-a">
                            <div data-repeater-item class="row">
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

                                <div class="mb-3 col-lg-2">
                                    <label for="start_date">Status</label>
                                    <div>
                                        <label class="form-check-label mb-2">
                                            <input type="checkbox" class="form-check-input" name="status[]" value="NewLead"
                                                {{ in_array('NewLead', (array) request()->status) ? 'checked' : '' }}>
                                            New Lead
                                        </label><br />
                                        <label class="form-check-label mb-2">
                                            <input type="checkbox" name="status[]" class="form-check-input" value="Visited"
                                                {{ in_array('Visited', (array) request()->status) ? 'checked' : '' }}>
                                            Visited
                                        </label><br />
                                        <label class="form-check-label mb-2">
                                            <input type="checkbox" name="status[]" class="form-check-input"
                                                value="PhotoReceived"
                                                {{ in_array('PhotoReceived', (array) request()->status) ? 'checked' : '' }}>
                                            Photo Received
                                        </label><br />
                                        <label class="form-check-label mb-2">
                                            <input type="checkbox" name="status[]" class="form-check-input"
                                                value="Interested"
                                                {{ in_array('Interested', (array) request()->status) ? 'checked' : '' }}>
                                            Interested
                                        </label><br />
                                        <label class="form-check-label mb-2">
                                            <input type="checkbox" name="status[]" class="form-check-input"
                                                value="Not_Interested"
                                                {{ in_array('Not_Interested', (array) request()->status) ? 'checked' : '' }}>
                                            Not interested
                                        </label><br />
                                    </div>
                                    @if ($errors->has('status'))
                                        <div class="error text-danger">{{ $errors->first('status') }}</div>
                                    @endif
                                </div>

                                <div class="mb-3 col-lg-2">
                                    <label for="start_date">Start Date:</label>
                                    <input type="date" name="start_date" class="form-control" id="start_date"
                                        value="{{ request()->start_date }}">
                                    @if ($errors->has('start_date'))
                                        <div class="error text-danger">{{ $errors->first('start_date') }}</div>
                                    @endif
                                </div>

                                <div class="mb-3 col-lg-2">
                                    <label for="end_date">End Date:</label>
                                    <input type="date" name="end_date" class="form-control" id="end_date"
                                        value="{{ request()->end_date }}">
                                    @if ($errors->has('end_date'))
                                        <div class="error text-danger">{{ $errors->first('end_date') }}</div>
                                    @endif
                                </div>

                                <div class="col-lg-1 align-self-center">
                                    <div class="d-flex gap-2">
                                        <input type="submit" class="btn btn-success mt-3 mt-lg-0" value="Show" />
                                        <a class="btn btn-light mt-3 mt-lg-0"
                                            href="{{ URL::to('/admin/report') }}">Clear</a>
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

    <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="processModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Customer Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="filterModalBody" style="overflow-x:scroll;overflow-y:scroll;height:60vh">
                    <!-- AJAX content loads here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                    <!-- Export button -->
                    <button type="button" class="btn btn-success" id="exportBtn">Export Excel</button>

                    <!-- Clear button -->
                    <a href="/admin/report" class="btn btn-light">Clear</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(function() {
            $("form[name='exportShow']").validate({
                rules: {
                    branches_id: {
                        required: true,
                    },
                    users_id: {
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
                            $('#users_id').append('<option value="ALL">ALL</option>');
                            $.each(data, function(key, user) {
                                $('#users_id').append('<option value="' + user.id +
                                    '">' + user.name + '</option>');
                            });
                        }
                    });
                } else {
                    $('#users_id').empty().append('<option value="">Select User</option>');
                }
            });
        });

        $(document).ready(function() {
            $('#filterForm').on('submit', function(e) {
                e.preventDefault();

                const query = $(this).serialize();

                $.ajax({
                    url: $(this).attr('action') + '?' + query,
                    method: 'GET',
                    success: function(response) {
                        $('#filterModalBody').html(response.html);
                        $('#filterModal').modal('show');

                        if (response.hasData) {
                            $('#exportBtn').prop('disabled', false);
                        } else {
                            $('#exportBtn').prop('disabled', true);
                        }
                    },
                    error: function() {
                        alert('Failed to load report data.');
                    }
                });
            });
        });

        $('#exportBtn').on('click', function() {
            // $('#filterForm').data('exporting', true).trigger('submit');
            const form = $('#filterForm');
            const action = form.attr('action');
            const formData = form.serialize(); // convert form data to query string

            // Create a hidden iframe or redirect
            const url = `${action}?${formData}&download=1`;
            window.location.href = url;
        });
    </script>
@endsection
