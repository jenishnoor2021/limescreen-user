<table class="table table-bordered">
    <thead>
        <tr>
            <th><strong>Branch</strong></th>
            <th><strong>User</strong></th>
            <th><strong>Kid Name</strong></th>
            <th><strong>Parent Name</strong></th>
            <th><strong>Email</strong></th>
            <th><strong>Contact</strong></th>
            <th><strong>WA No</strong></th>
            <th><strong>City</strong></th>
            <th><strong>Status</strong></th>
            <th><strong>Visited Date</strong></th>
            <th><strong>Remark</strong></th>
            <th><strong>Date</strong></th>
        </tr>
    </thead>
    <tbody>
        @forelse($data as $customer)
        <tr>
            <td>{{ $customer->branches->name ?? '' }}</td>
            <td>{{ $customer->users->name ?? '' }}</td>
            <td>{{ $customer->child_name }}</td>
            <td>{{ $customer->parent_name }}</td>
            <td>{{ $customer->email }}</td>
            <td>{{ $customer->mobile }}</td>
            <td>{{ $customer->whatsapp_number }}</td>
            <td>{{ $customer->city }}</td>
            <td>{{ $customer->status }}</td>
            <td>{{ !empty($customer->status_change_date) ? \Carbon\Carbon::parse($customer->status_change_date)->format('d-m-Y') : '-' }}</td>
            <td>{{ $customer->remark }}</td>
            <td>{{ $customer->created_at->format('d-m-Y') }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="11">
                <center>No data found.</center>
            </td>
        </tr>
        @endforelse
    </tbody>
</table>