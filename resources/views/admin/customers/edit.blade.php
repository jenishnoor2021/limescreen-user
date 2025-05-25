@extends('layouts.admin')
@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Edit Lead</h4>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Edit</h4>

                {!! Form::model($customer, [
                'method' => 'PATCH',
                'action' => ['AdminCustomerController@update', $customer->id],
                'files' => true,
                'class' => 'form-horizontal',
                'name' => 'customerEditForm',
                ]) !!}
                @csrf

                @if (Session::get('user')['role'] != 'Admin')
                <input type="hidden" name="users_id" value="{{ Session::get('user')['id'] }}">
                <input type="hidden" name="branches_id" value="{{ Session::get('user')['branches_id'] }}">
                @else
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="branches_id">Branch<span class="text-danger">*</span></label>
                            <select name="branches_id" id="branches_id" class="form-select" required>
                                <option value="">Select Branch</option>
                                @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}"
                                    {{ (string) $branch->id === (string) $customer->branches_id ? 'selected' : '' }}>
                                    {{ $branch->name }}
                                </option>
                                @endforeach
                            </select>
                            @if ($errors->has('branches_id'))
                            <div class="error text-danger">{{ $errors->first('branches_id') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="users_id">Users<span class="text-danger">*</span></label>
                            <select name="users_id" id="users_id" class="form-select" required>
                                @foreach ($users as $user)
                                <option value="{{ $user->id }}"
                                    {{ $user->id == $customer->users_id ? 'selected' : '' }}>{{ $user->name }}
                                </option>
                                @endforeach
                            </select>
                            @if ($errors->has('users_id'))
                            <div class="error text-danger">{{ $errors->first('users_id') }}</div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="child_name" class="form-label">Child Name<span
                                    class="text-danger">*</span></label>
                            <input type="text" name="child_name" class="form-control" id="child_name"
                                placeholder="Enter your child name" value="{{ $customer->child_name }}" required>
                            @if ($errors->has('child_name'))
                            <div class="error text-danger">{{ $errors->first('child_name') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="parent_name" class="form-label">Parents name<span
                                    class="text-danger">*</span></label>
                            <input type="text" name="parent_name" class="form-control" id="parent_name"
                                placeholder="Enter parent name" value="{{ $customer->parent_name }}" required>
                            @if ($errors->has('parent_name'))
                            <div class="error text-danger">{{ $errors->first('parent_name') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email<span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" id="email"
                                placeholder="Enter email" value="{{ $customer->email }}" required>
                            @if ($errors->has('email'))
                            <div class="error text-danger">{{ $errors->first('email') }}</div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="mobile" class="form-label">Mobile no<span class="text-danger">*</span></label>
                            <input type="number" name="mobile" class="form-control" id="mobile"
                                placeholder="Enter number"
                                value="{{ $customer->mobile }}" required>
                            @if ($errors->has('mobile'))
                            <div class="error text-danger">{{ $errors->first('mobile') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="whatsapp_number" class="form-label">Whatsapp number<span
                                    class="text-danger">*</span></label>
                            <input type="number" name="whatsapp_number" class="form-control" id="whatsapp_number"
                                placeholder="Enter whatsapp number" value="{{ $customer->whatsapp_number }}" required>
                            @if ($errors->has('whatsapp_number'))
                            <div class="error text-danger">{{ $errors->first('whatsapp_number') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="status" class="form-label">Status<span class="text-danger">*</span></label>
                            <select name="status" id="status1" class="form-select" required>
                                <option value="NewLead" {{ $customer->status == 'NewLead' ? 'selected' : '' }}>New
                                    Lead</option>
                                <option value="Visited" {{ $customer->status == 'Visited' ? 'selected' : '' }}>Visited
                                </option>
                                <option value="PhotoReceived"
                                    {{ $customer->status == 'PhotoReceived' ? 'selected' : '' }}>Photo Received
                                </option>
                                <option value="Interested" {{ $customer->status == 'Interested' ? 'selected' : '' }}>
                                    Interested</option>
                                <option value="NotInterested"
                                    {{ $customer->status == 'NotInterested' ? 'selected' : '' }}>Not interested
                                </option>
                            </select>
                            @if ($errors->has('status'))
                            <div class="error text-danger">{{ $errors->first('status') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-3" id="ifVisitedDiv" style="display:none">
                        <div class="mb-3">
                            <label for="status_change_date" class="form-label">Visited Date<span
                                    class="text-danger">*</span></label>
                            <input type="date" name="status_change_date" class="form-control" id="status_change_date" placeholder="Enter visited date" value="{{$customer->status_change_date}}" required>
                            @if ($errors->has('status_change_date'))
                            <div class="error text-danger">{{ $errors->first('status_change_date') }}</div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="remark" class="form-label">Remark</label>
                    <textarea type="text" name="remark" class="form-control" id="remark" placeholder="Enter remark">{{ $customer->remark }}</textarea>
                    @if ($errors->has('remark'))
                    <div class="error text-danger">{{ $errors->first('remark') }}</div>
                    @endif
                </div>

                <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <textarea type="text" name="address" class="form-control" id="address" placeholder="Enter Address">{{ $customer->address }}</textarea>
                    @if ($errors->has('address'))
                    <div class="error text-danger">{{ $errors->first('address') }}</div>
                    @endif
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-md">Update</button>
                    <a class="btn btn-light w-md" href="{{ URL::to('/admin/customers') }}">Back</a>
                </div>
                </form>
            </div>
            <!-- end card body -->
        </div>
        <!-- end card -->
    </div>
    <!-- end col -->
</div>
<!-- end row -->
@endsection

@section('script')
<script>
    $(function() {

        $("form[name='customerEditForm']").validate({
            rules: {
                child_name: {
                    required: true,
                },
                parent_name: {
                    required: true,
                },
                email: {
                    required: true,
                },
                mobile: {
                    required: true,
                },
                whatsapp_number: {
                    required: true,
                },
                // address: {
                //     required: true,
                // }
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
</script>
<script>
    // document.getElementById('mobile').addEventListener('input', function() {
    //     this.value = this.value.replace(/\D/g, '').slice(0, 12);
    // });
    // document.getElementById('whatsapp_number').addEventListener('input', function() {
    //     this.value = this.value.replace(/\D/g, '').slice(0, 15);
    // });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const statusDropdown = document.getElementById("status1");
        const visitedDiv = document.getElementById("ifVisitedDiv");

        function toggleVisitedDiv() {
            if (statusDropdown.value === "Visited") {
                visitedDiv.style.display = "block";
            } else {
                visitedDiv.style.display = "none";
            }
        }

        // Run once on page load in case "Visited" is already selected
        toggleVisitedDiv();

        // Add event listener for change
        statusDropdown.addEventListener("change", toggleVisitedDiv);
    });
</script>

@endsection