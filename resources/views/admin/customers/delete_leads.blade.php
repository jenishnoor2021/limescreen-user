@extends('layouts.admin')
@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Delete Leads</h4>
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

                <form id="filterForm" action="{{ route('admin.delete-leads.show') }}" name="deleteLeadsShow" method="GET"
                    enctype="multipart/form-data">
                    @csrf

                    <div data-repeater-list="group-a">
                        <div data-repeater-item class="row">
                            @if(Session::get('user')['role'] == 'User')
                            <input type="hidden" name="users_id" value="{{Session::get('user')['id']}}">
                            <input type="hidden" name="branches_id" value="{{Session::get('user')['branches_id']}}">
                            @else
                            <div class="mb-3 col-lg-2">
                                <label for="branches_id">Branch</label>
                                <select name="branches_id" id="branches_id" class="form-select" required>
                                    <option value="">Select Branch</option>
                                    @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}" {{ request()->branches_id == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('branches_id'))
                                <div class="error text-danger">{{ $errors->first('branches_id') }}</div>
                                @endif
                            </div>

                            <div class="mb-3 col-lg-2">
                                <label for="users_id">Users</label>
                                <select name="users_id" id="users_id" class="form-select" required>
                                    <option value="">Select User</option>
                                </select>
                                @if ($errors->has('users_id'))
                                <div class="error text-danger">{{ $errors->first('users_id') }}</div>
                                @endif
                            </div>
                            @endif

                            <input type="hidden" id="selected_user_id" value="{{ request()->users_id }}">

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
                                            value="NotInterested"
                                            {{ in_array('NotInterested', (array) request()->status) ? 'checked' : '' }}>
                                        Not interested
                                    </label><br />
                                </div>
                                @if ($errors->has('status'))
                                <div class="error text-danger">{{ $errors->first('status') }}</div>
                                @endif
                            </div>

                            <div class="mb-3 col-lg-2">
                                <label for="date_by">Apply Date On</label>
                                <select name="date_by" id="date_by" class="form-select" required>
                                    <option value="created_at" {{ request()->date_by == 'created_at' ? 'selected' : '' }}>Import Date</option>
                                    <option value="status_change_date" {{ request()->date_by == 'status_change_date' ? 'selected' : '' }}>Status Date</option>
                                </select>
                                @if ($errors->has('date_by'))
                                <div class="error text-danger">{{ $errors->first('date_by') }}</div>
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
                                <div class="d-flex align-items-center gap-2">
                                    <input type="submit" class="btn btn-success mt-3 mt-lg-0" value="Show" />
                                    <a class="btn btn-light mt-3 mt-lg-0"
                                        href="{{ URL::to('/admin/delete-leads') }}">Clear</a>
                                    <div id="spinner" style="display: none;">
                                        <i class="fa fa-spinner fa-spin text-primary" style="font-size: 1.5rem;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>


        @if (count($data) > 0)
        <div class="card">
            <div class="card-body">
                @if(Session::get('user')['role'] == 'Admin')
                <button class="btn btn-danger waves-effect waves-light"
                    id="delete_selected_btn" style="font-size:15px;margin-bottom:10px;">Delete Selected</button>
                @endif
                <table id="datatable" class="table table-bordered dt-responsive nowrap w-100 mt-3">
                    <thead>
                        <tr>
                            <th></th>
                            <th><input type="checkbox" id="select_all"></th>
                            <th><strong>Branch</strong></th>
                            <th><strong>User</strong></th>
                            <th><strong>Status Date</strong></th>
                            <th><strong>Import Date</strong></th>
                            <th><strong>Kid Name</strong></th>
                            <th><strong>Parent Name</strong></th>
                            <th><strong>Email</strong></th>
                            <th><strong>Contact</strong></th>
                            <th><strong>WA No</strong></th>
                            <th><strong>City</strong></th>
                            <th><strong>Status</strong></th>
                            <th><strong>Remark</strong></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $customer)
                        <tr>
                            <td></td>
                            <td>
                                <input type="checkbox" class="row_checkbox" value="{{ $customer->id }}">
                            </td>
                            <td>{{ $customer->branches->name ?? '' }}</td>
                            <td>{{ $customer->users->name ?? '' }}</td>
                            <td>{{ !empty($customer->status_change_date) ? \Carbon\Carbon::parse($customer->status_change_date)->format('d-m-Y') : '-' }}</td>
                            <td>{{ $customer->created_at->format('d-m-Y') }}</td>
                            <td>{{ $customer->child_name }}</td>
                            <td>{{ $customer->parent_name }}</td>
                            <td>{{ $customer->email }}</td>
                            <td>{{ $customer->mobile }}</td>
                            <td>{{ $customer->whatsapp_number }}</td>
                            <td>{{ $customer->city }}</td>
                            <td>{{ $customer->status }}</td>
                            <td>{{ $customer->remark }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @elseif(request()->branches_id != '')
        <div class="card">
            <div class="card-body">
                <span class="text-danger">No Record founs</span>
            </div>
        </div>
        @endif

    </div>
</div>
<!-- end row -->

@endsection

@section('script')
<script>
    $(function() {
        $("form[name='deleteLeadsShow']").validate({
            rules: {
                branches_id: {
                    required: true,
                },
                users_id: {
                    required: true,
                },
            },
            submitHandler: function(form) {
                $('#spinner').show();
                form.submit();
            }
        });
    });
</script>
<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
<script>
    $(document).ready(function() {
        function loadUsers(branchId, selectedUserId = '') {
            if (branchId) {
                $.ajax({
                    url: '/get-users-by-branch/' + branchId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#users_id').empty();
                        $('#users_id').append('<option value="ALL">ALL</option>');

                        $.each(data, function(key, user) {
                            let selected = user.id == selectedUserId ? 'selected' : '';
                            $('#users_id').append('<option value="' + user.id + '" ' + selected + '>' + user.name + '</option>');
                        });

                        if (selectedUserId === 'ALL') {
                            $('#users_id').val('ALL');
                        }
                    }
                });
            } else {
                $('#users_id').empty().append('<option value="">Select User</option>');
            }
        }

        // On change
        $('#branches_id').change(function() {
            loadUsers($(this).val());
        });

        // On page load
        const initialBranchId = '{{ request()->branches_id }}';
        const initialUserId = '{{ request()->users_id }}';

        if (initialBranchId) {
            loadUsers(initialBranchId, initialUserId);
        }
    });

    // Select/Deselect All
    document.getElementById('select_all').addEventListener('change', function() {
        let checkboxes = document.querySelectorAll('.row_checkbox');
        checkboxes.forEach(cb => cb.checked = this.checked);
    });

    // Uncheck Select All if any individual checkbox is unchecked
    document.querySelectorAll('.row_checkbox').forEach(cb => {
        cb.addEventListener('change', function() {
            if (!this.checked) {
                document.getElementById('select_all').checked = false;
            } else {
                let allChecked = [...document.querySelectorAll('.row_checkbox')].every(cb => cb
                    .checked);
                document.getElementById('select_all').checked = allChecked;
            }
        });
    });

    // Delete selected
    document.getElementById('delete_selected_btn').addEventListener('click', function() {
        let selectedIds = Array.from(document.querySelectorAll('.row_checkbox:checked')).map(cb => cb.value);
        if (selectedIds.length === 0) {
            alert("Please select at least one Lead.");
            return;
        }

        if (!confirm("Are you sure you want to delete selected Leads?")) return;

        fetch("{{ route('admin.customers.bulkDelete') }}", {
                method: "POST",
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    ids: selectedIds
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert("Something went wrong.");
                }
            })
            .catch(err => console.error(err));
    });
</script>
@endsection