<?php

namespace App\Http\Controllers;

use Validator;
use App\Models\Branch;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Imports\CustomerImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CustomersExport;
use Illuminate\Support\Carbon;

class AdminCustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filter = $request->input('date_filter', 'today');

        $query = Customer::query();

        if (Auth::user()->role === 'Admin') {
            // No restriction
        } elseif (Auth::user()->role === 'BreanchHead') {
            $loginBranchesId = Auth::user()->branches_id;
            $userIds = User::where('branches_id', $loginBranchesId)->pluck('id');
            $query->where('branches_id', $loginBranchesId)->whereIn('users_id', $userIds);
        } else {
            $query->where('users_id', Auth::user()->id);
        }

        // Date filtering
        switch ($filter) {
            case 'yesterday':
                $query->whereDate('created_at', Carbon::yesterday());
                break;

            case 'week':
                $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                break;

            case 'last_week':
                $query->whereBetween('created_at', [
                    Carbon::now()->subWeek()->startOfWeek(),
                    Carbon::now()->subWeek()->endOfWeek()
                ]);
                break;

            case 'month':
                $query->whereMonth('created_at', Carbon::now()->month)
                    ->whereYear('created_at', Carbon::now()->year);
                break;

            case 'last_month':
                $query->whereMonth('created_at', Carbon::now()->subMonth()->month)
                    ->whereYear('created_at', Carbon::now()->subMonth()->year);
                break;

            case 'custom':
                $start = $request->input('start_date');
                $end = $request->input('end_date');
                if ($start && $end) {
                    $query->whereBetween('created_at', [Carbon::parse($start)->startOfDay(), Carbon::parse($end)->endOfDay()]);
                }
                break;

            case 'all':
                // No date filter applied
                break;

            case 'today':
            default:
                $query->whereDate('created_at', Carbon::today());
                break;
        }

        // 🔍 Search logic
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('child_name', 'LIKE', "%$search%")
                    ->orWhere('parent_name', 'LIKE', "%$search%")
                    ->orWhere('mobile', 'LIKE', "%$search%")
                    ->orWhere('whatsapp_number', 'LIKE', "%$search%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Final data fetch
        $customers = $query->orderBy('id', 'DESC')->paginate(500)->appends($request->all());

        return view('admin.customers.index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $branches = Branch::orderBy('id', 'DESC')->get();
        $users = User::orderBy('id', 'DESC')->get();
        return view('admin.customers.create', compact('branches', 'users'));
    }

    public function getUsersByBranch($branch_id)
    {
        $loginRole = Session::get('user')->role;
        if ($loginRole == 'Admin') {
            $users = User::where('branches_id', $branch_id)->get();
        } else {
            $users = User::where('branches_id', $branch_id)->where('role', '!=', 'BreanchHead')->get();
        }
        return response()->json($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'child_name' => ['required'],
            'parent_name' => ['required'],
            'email' => ['required'],
            'mobile' => ['required'],
            'whatsapp_number' => ['required'],
            'users_id' => ['required'],
            'branches_id' => ['required'],
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        }

        Customer::create($request->all());

        return redirect('admin/customers')->with('success', "Add Record Successfully");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $customer = Customer::findOrFail($id);
        $branches = Branch::orderBy('id', 'DESC')->get();
        $users = User::orderBy('id', 'DESC')->get();
        return view('admin.customers.edit', compact('customer', 'branches', 'users'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $input = $request->all();

        $validator = Validator::make($request->all(), [
            'child_name' => ['required'],
            'parent_name' => ['required'],
            'email' => ['required'],
            'mobile' => ['required'],
            'whatsapp_number' => ['required'],
            'users_id' => ['required'],
            'branches_id' => ['required'],
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        }

        $customer = Customer::findOrFail($id);
        $customer->update($input);

        return redirect('admin/customers')->with('success', "Update Record Successfully");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();

        return Redirect::back()->with('success', "Delete Record Successfully");
    }

    public function import()
    {
        $loginRole = Session::get('user')->role;
        $loginBrancgId = Session::get('user')->branches_id;
        if ($loginRole != 'Admin') {
            $branches = Branch::where('id', $loginBrancgId)->get();
        } else {
            $branches = Branch::orderBy('id', 'DESC')->get();
        }
        $users = User::orderBy('id', 'DESC')->get();
        return view('admin.customers.import', compact('branches', 'users'));
    }

    public function importStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => ['required', 'file'],
            'users_id'     => ['required'],
            'branches_id'  => ['required'],
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        }
        try {
            Excel::import(
                new CustomerImport($request->users_id, $request->branches_id),
                $request->file('file')
            );
            return redirect()->back()->with('success', 'Customers imported successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error importing file: ' . $e->getMessage());
        }
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:customers,id',
            'status' => 'required|string',
            'status_change_date' => 'nullable|date'
        ]);

        $customer = Customer::find($request->id);
        $customer->status = $request->status;

        if ($request->status === 'Visited' && $request->status_change_date) {
            $customer->status_change_date = $request->status_change_date;
        } else {
            $customer->status_change_date = today();
        }

        $customer->save();

        return response()->json(['message' => 'Status updated successfully']);
    }

    public function report()
    {
        $loginBranchesId = Session::get('user')->branches_id;
        $loginRole = Session::get('user')->role;
        if ($loginRole == 'Admin') {
            $branches = Branch::orderBy('id', 'DESC')->get();
        } else {
            $branches = Branch::where('id', $loginBranchesId)->get();
        }
        $data = [];
        return view('admin.customers.report', compact('branches', 'data'));
    }

    public function exportShow(Request $request)
    {
        $loginBranchesId = Session::get('user')->branches_id;
        $loginRole = Session::get('user')->role;
        if ($loginRole == 'Admin') {
            $branches = Branch::orderBy('id', 'DESC')->get();
        } else {
            $branches = Branch::where('id', $loginBranchesId)->get();
        }
        $data = Customer::query()
            ->when(
                $request->filled('branches_id') && $request->branches_id !== 'ALL',
                fn($q) =>
                $q->where('branches_id', $request->branches_id)
            )
            ->when(
                $request->filled('users_id') && $request->users_id !== 'ALL',
                fn($q) =>
                $q->where('users_id', $request->users_id)
            )
            ->when(
                $request->filled('status') && is_array($request->status) && !in_array('ALL', $request->status) && count($request->status),
                fn($q) =>
                $q->whereIn('status', $request->status)
            )
            ->when(
                $request->start_date,
                fn($q, $start) =>
                $q->whereDate($request->date_by ?? 'created_at', '>=', $start)
            )
            ->when(
                $request->end_date,
                fn($q, $end) =>
                $q->whereDate($request->date_by ?? 'created_at', '<=', $end)
            )
            ->get();

        if ($request->has('download')) {
            $fileName = time() . '_customers.xlsx';  // Change file extension to .xlsx
            return Excel::download(new CustomersExport($data), $fileName, \Maatwebsite\Excel\Excel::XLSX);
        }

        // $view = view('partials.customer_report_modal', compact('data'))->render();
        // return response()->json([
        //     'html' => $view,
        //     'hasData' => $data->isNotEmpty(),
        // ]);
        return view('admin.customers.report', compact('branches', 'data'));
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids');

        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No Lead IDs provided.']);
        }

        Customer::whereIn('id', $ids)->delete();

        return response()->json(['success' => true, 'message' => 'Selected Leads deleted successfully.']);
    }

    public function deleteLeads()
    {
        $branches = Branch::orderBy('id', 'DESC')->get();
        $data = [];

        return view('admin.customers.delete_leads', compact('branches', 'data'));
    }

    public function deleteLeadsShow(Request $request)
    {
        $loginBranchesId = Session::get('user')->branches_id;
        $loginRole = Session::get('user')->role;
        if ($loginRole == 'Admin') {
            $branches = Branch::orderBy('id', 'DESC')->get();
        } else {
            $branches = Branch::where('id', $loginBranchesId)->get();
        }
        $data = Customer::query()
            ->when(
                $request->filled('branches_id') && $request->branches_id !== 'ALL',
                fn($q) =>
                $q->where('branches_id', $request->branches_id)
            )
            ->when(
                $request->filled('users_id') && $request->users_id !== 'ALL',
                fn($q) =>
                $q->where('users_id', $request->users_id)
            )
            ->when(
                $request->filled('status') && is_array($request->status) && !in_array('ALL', $request->status) && count($request->status),
                fn($q) =>
                $q->whereIn('status', $request->status)
            )
            ->when(
                $request->start_date,
                fn($q, $start) =>
                $q->whereDate($request->date_by ?? 'created_at', '>=', $start)
            )
            ->when(
                $request->end_date,
                fn($q, $end) =>
                $q->whereDate($request->date_by ?? 'created_at', '<=', $end)
            )
            ->get();

        if ($request->has('download')) {
            $fileName = time() . '_customers.xlsx';  // Change file extension to .xlsx
            return Excel::download(new CustomersExport($data), $fileName, \Maatwebsite\Excel\Excel::XLSX);
        }

        return view('admin.customers.delete_leads', compact('branches', 'data'));
    }
}
