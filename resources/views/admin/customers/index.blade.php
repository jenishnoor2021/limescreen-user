@extends('layouts.admin')
@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Leads List</h4>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                @if (session()->has('message'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="mdi mdi-check-all me-2"></i>
                    {{ session()->get('message') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                <div id="right">
                    <div id="menu" class="mb-3">
                        <span id="menu-navi"
                            class="d-sm-flex flex-wrap text-center text-sm-start justify-content-sm-between">
                            <div class="d-flex align-items-center gap-2 flex-wrap mb-3">
                                @if (Session::get('user')['role'] == 'Admin')
                                <a class="btn btn-info waves-effect waves-light"
                                    href="{{ route('admin.customers.create') }}"><i class="fa fa-plus editable"
                                        style="font-size:15px;">&nbsp;ADD</i></a>
                                @endif
                                <a class="btn btn-success waves-effect waves-light" href="{{ route('admin.import') }}"
                                    style="font-size:15px;">Import</a>

                                @if(Session::get('user')['role'] == 'Admin')
                                <button class="btn btn-danger waves-effect waves-light"
                                    id="delete_selected_btn" style="font-size:15px;">Delete Selected</button>
                                @endif

                                <form method="GET" action="{{ route('admin.customers.index') }}"
                                    class="d-flex align-items-center gap-2 flex-wrap mb-0">
                                    <select name="date_filter" class="form-select w-auto"
                                        onchange="toggleCustomRange(this.value)">
                                        <option value="today"
                                            {{ request()->date_filter == 'today' ? 'selected' : '' }}>Today</option>
                                        <option value="yesterday"
                                            {{ request()->date_filter == 'yesterday' ? 'selected' : '' }}>Yesterday
                                        </option>
                                        <option value="week" {{ request()->date_filter == 'week' ? 'selected' : '' }}>
                                            This Week</option>
                                        <option value="last_week" {{ request()->date_filter == 'last_week' ? 'selected' : '' }}>Last Week</option>
                                        <option value="month"
                                            {{ request()->date_filter == 'month' ? 'selected' : '' }}>This Month
                                        </option>
                                        <option value="last_month" {{ request()->date_filter == 'last_month' ? 'selected' : '' }}>Last Month</option>
                                        <option value="custom"
                                            {{ request()->date_filter == 'custom' ? 'selected' : '' }}>Custom Range
                                        </option>
                                        <option value="all" {{ request()->date_filter == 'all' ? 'selected' : '' }}>All</option>
                                    </select>

                                    <input type="date" name="start_date" class="form-control form-control-sm w-auto"
                                        value="{{ request('start_date') }}" id="start_date"
                                        style="display: {{ request('date_filter') == 'custom' ? 'block' : 'none' }};">

                                    <input type="date" name="end_date" class="form-control form-control-sm w-auto"
                                        value="{{ request('end_date') }}" id="end_date"
                                        style="display: {{ request('date_filter') == 'custom' ? 'block' : 'none' }};">

                                    <button type="submit"
                                        class="btn btn-primary waves-effect waves-light">Filter</button>
                                </form>

                            </div>
                        </span>

                    </div>
                </div>

                <table id="datatable" class="table table-bordered dt-responsive nowrap w-100 mt-3">
                    <thead>
                        <tr>
                            <th>Action</th>
                            @if(Session::get('user')['role'] == 'Admin')
                            <th><input type="checkbox" id="select_all"></th>
                            @endif
                            <th>Branch</th>
                            <th>User</th>
                            <th>Child Name</th>
                            <th>Parent Name</th>
                            <th>Email</th>
                            <th>Mobile</th>
                            <th>Whatsapp No</th>
                            <th>Status</th>
                            <th>Remark</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($customers as $customer)
                        <tr>
                            <td>
                                <a href="{{ route('admin.customers.edit', $customer->id) }}"
                                    class="btn btn-outline-primary waves-effect waves-light"><i
                                        class="fa fa-edit"></i></a>
                                <a href="{{ route('admin.customers.destroy', $customer->id) }}"
                                    onclick="return confirm('Sure ! You want to delete ?');"
                                    class="btn btn-outline-danger waves-effect waves-light"><i
                                        class="fa fa-trash"></i></a>
                            </td>
                            @if(Session::get('user')['role'] == 'Admin')
                            <td><input type="checkbox" class="row_checkbox" value="{{ $customer->id }}"></td>
                            @endif
                            <td>{{ $customer->branches->name }}</td>
                            <td>{{ $customer->users->name }}</td>
                            <td>{{ $customer->child_name }}</td>
                            <td>{{ $customer->parent_name }}</td>
                            <td>{{ $customer->email }}</td>
                            <td>{{ $customer->mobile }}</td>
                            <td>{{ $customer->whatsapp_number }}</td>
                            <td><select class="form-select form-select-sm status-dropdown"
                                    data-id="{{ $customer->id }}">
                                    <option value="NewLead" {{ $customer->status == 'NewLead' ? 'selected' : '' }}>
                                        New Lead</option>
                                    <option value="Visited" {{ $customer->status == 'Visited' ? 'selected' : '' }}>
                                        Visited</option>
                                    <option value="PhotoReceived"
                                        {{ $customer->status == 'PhotoReceived' ? 'selected' : '' }}>Photo Received
                                    </option>
                                    <option value="Interested"
                                        {{ $customer->status == 'Interested' ? 'selected' : '' }}>Interested
                                    </option>
                                    <option value="NotInterested"
                                        {{ $customer->status == 'NotInterested' ? 'selected' : '' }}>Not interested
                                    </option>
                                </select>
                            </td>
                            <td>
                                <p class="add-read-more show-less-content">{{ $customer->remark }}</p>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->
@endsection

@section('script')
<script>
    $(document).ready(function() {
        $('.status-dropdown').change(function() {
            var status = $(this).val();
            var customerId = $(this).data('id');

            $.ajax({
                url: '/customers/update-status', // Your route here
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    id: customerId,
                    status: status
                },
                success: function(response) {
                    alert(response.message || 'Status updated successfully');
                },
                error: function(xhr) {
                    alert('Failed to update status');
                }
            });
        });
    });
</script>
<script>
    function toggleCustomRange(value) {
        const show = value === 'custom';
        document.getElementById('start_date').style.display = show ? 'block' : 'none';
        document.getElementById('end_date').style.display = show ? 'block' : 'none';
    }
</script>
<script>
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