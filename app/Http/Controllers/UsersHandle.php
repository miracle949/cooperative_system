<?php

namespace App\Http\Controllers;

use App\Models\educational_tbl;
use App\Models\Membergovern_ids_tbl;
use App\Models\Membervehi_tbl;
use App\Models\Otherinfo_tbl;
use App\Models\Spouse_tbl;
use App\Models\Users_tbl;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;


class UsersHandle extends Controller
{
    public function applicationFormButton(Request $request, $id)
    {
        // dd($request->all());
        try {
            $request->validate([
                // users_tbls
                'fullname' => 'nullable|string|max:255',
                'username' => 'nullable|string|max:255',
                'email' => 'nullable|email|max:255',

                // otherinfo_tbls
                'date_of_birth' => 'nullable|string|max:255',
                'place_of_birth' => 'nullable|string|max:255',
                'contact_no' => 'nullable|string|max:255',
                'present_address' => 'nullable|string|max:255',
                'permanent_address' => 'nullable|string|max:255',
                'sex' => 'nullable|string|max:255',
                'civil_status' => 'nullable|string|max:255',
                'citizenship' => 'nullable|string|max:255',
                'height' => 'nullable|string|max:255',
                'weight' => 'nullable|string|max:255',
                'blood_type' => 'nullable|string|max:255',
                'skills' => 'nullable|string|max:255',

                // spouse_tbls
                'spouse_name' => 'nullable|string|max:255',
                'spouse_date_birth' => 'nullable|date',
                'spouse_place_birth' => 'nullable|string|max:255',
                'number_son' => 'nullable|string|max:255',
                'number_daughter' => 'nullable|string|max:255',
                'other_spec' => 'nullable|string|max:255',

                // membergovern_ids_tbls
                'sss_id' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
                'philhealth_id' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
                'pagibig_id' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
                'tin_id' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',

                // membervehi_tbls
                'uv_plate_no' => 'nullable|array',
                'uv_plate_no.*' => 'nullable|string',
                'taxi_plate_no' => 'nullable|array',
                'taxi_plate_no.*' => 'nullable|string',
                'bus_plate_no' => 'nullable|array',
                'bus_plate_no.*' => 'nullable|string',
                'mini_bus_plate_no' => 'nullable|array',
                'mini_bus_plate_no.*' => 'nullable|string',
                'jeep_plate_no' => 'nullable|array',
                'jeep_plate_no.*' => 'nullable|string',
                'multi_cab_plate_no' => 'nullable|array',
                'multi_cab_plate_no.*' => 'nullable|string',
                'tricycle_plate_no' => 'nullable|array',
                'tricycle_plate_no.*' => 'nullable|string',
                'total_uv' => 'nullable|integer|min:0',
                'total_taxi' => 'nullable|integer|min:0',
                'total_bus' => 'nullable|integer|min:0',
                'total_mini_bus' => 'nullable|integer|min:0',
                'total_jeep' => 'nullable|integer|min:0',
                'total_multi_cab' => 'nullable|integer|min:0',
                'total_tricycle' => 'nullable|integer|min:0',

                // educational_tbls
                'educational_level' => 'nullable|array',
                'educational_level.*' => 'nullable|string|max:255',
                'edu_status' => 'nullable|array',
                'edu_status.*' => 'nullable|string|max:255',
                'edu_specify' => 'nullable|array',
                'edu_specify.*' => 'nullable|string|max:255',
            ]);

            Users_tbl::where('id', $id)->update($request->only([
                'fullname',
                'username',
                'email',
            ]));

            Spouse_tbl::updateOrCreate(
                ['member_id' => $id],
                [
                    'spouse_name' => $request->spouse_name,
                    'spouse_date_birth' => $request->spouse_date_birth,
                    'spouse_place_birth' => $request->spouse_place_birth,
                    'number_son' => $request->number_son,
                    'number_daughter' => $request->number_daughter,
                    'other_spec' => $request->other_spec,
                ]
            );

            Otherinfo_tbl::updateOrCreate(
                ['member_id' => $id],
                [
                    'date_of_birth' => $request->date_of_birth,
                    'place_of_birth' => $request->place_of_birth,
                    'contact_no' => $request->contact_no,
                    'present_address' => $request->present_address,
                    'permanent_address' => $request->permanent_address,
                    'sex' => $request->sex,
                    'civil_status' => $request->civil_status,
                    'citizenship' => $request->citizenship,
                    'height' => $request->height,
                    'weight' => $request->weight,
                    'blood_type' => $request->blood_type,
                    'skills' => $request->skills,
                ]
            );

            $governmentData = [];
            $fileFields = ['sss_id', 'philhealth_id', 'pagibig_id', 'tin_id'];

            foreach ($fileFields as $field) {
                if ($request->hasFile($field)) {
                    $path = $request->file($field)->store('government_ids', 'public');
                    $governmentData[$field] = $path;
                }
            }

            Membergovern_ids_tbl::updateOrCreate(
                ['member_id' => $id],
                $governmentData
            );

            Membervehi_tbl::where('member_id', $id)->delete();

            $vehicleTypes = [
                'UV' => ['plate_name' => 'uv_plate_no', 'qty_name' => 'total_uv'],
                'TAXI' => ['plate_name' => 'taxi_plate_no', 'qty_name' => 'total_taxi'],
                'BUS' => ['plate_name' => 'bus_plate_no', 'qty_name' => 'total_bus'],
                'MINI BUS' => ['plate_name' => 'mini_bus_plate_no', 'qty_name' => 'total_mini_bus'],
                'JEEP' => ['plate_name' => 'jeep_plate_no', 'qty_name' => 'total_jeep'],
                'MULTI-CAB' => ['plate_name' => 'multi_cab_plate_no', 'qty_name' => 'total_multi_cab'],
                'TRICYCLE' => ['plate_name' => 'tricycle_plate_no', 'qty_name' => 'total_tricycle'],
            ];

            foreach ($vehicleTypes as $type => $fields) {
                $quantity = (int) $request->input($fields['qty_name'], 0);
                $plates = $request->input($fields['plate_name']);

                if ($quantity <= 0 || empty($plates) || !is_array($plates)) {
                    continue;
                }

                foreach ($plates as $plate_no) {
                    $plate_no = trim((string) ($plate_no ?? ''));
                    if ($plate_no === '')
                        continue;

                    Membervehi_tbl::create([
                        'member_id' => $id,
                        'plate_no' => $plate_no,
                        'vehicle_type' => $type,
                        'quantity' => 1,
                    ]);
                }
            }

            $levels = ['Elementary', 'Secondary', 'Vocational/Trade Course', 'College'];

            foreach ($levels as $index => $level) {
                educational_tbl::updateOrCreate(
                    ['member_id' => $id, 'educational_level' => $level],
                    [
                        'status' => $request->edu_status[$index] ?? null,
                        'specify' => $request->edu_specify[$index] ?? null,
                    ]
                );
            }

            $user = Users_tbl::findOrFail($id);
            $vehicles = Membervehi_tbl::where('member_id', $id)->get()->groupBy('vehicle_type');
            $spouse = Spouse_tbl::where('member_id', $id)->first();
            $other = Otherinfo_tbl::where('member_id', $id)->first();
            $education = educational_tbl::where('member_id', $id)->get();
            $governmentIds = Membergovern_ids_tbl::where('member_id', $id)->first();

            // return view('members_components.application_form', compact(
            //     'user',
            //     'vehicles',
            //     'spouse',
            //     'other',
            //     'education',
            //     'governmentIds'
            // ));
            return redirect()->route('applicationForm', $id)
                ->with('success', 'Application form submitted successfully!');

        } catch (\Exception $e) {
            dd($e->getMessage(), $e->getLine(), $e->getFile());
        }
    }

    public function MemberPortal()
    {
        $username = Auth::check() ? Auth::user()->username : null;
        $email = Auth::check() ? Auth::user()->email : null;


        return view(
            "members_components.member_portal",
            [
                "username" => $username,
                "email" => $email
            ]
        );
    }

    public function LoanApplication()
    {

        $username = Auth::check() ? Auth::user()->username : null;
        $email = Auth::check() ? Auth::user()->email : null;

        return view(
            "members_components.loan_application",
            [
                "username" => $username,
                "email" => $email
            ]
        );
    }

    public function ShareCapitalMember()
    {

        $username = Auth::check() ? Auth::user()->username : null;
        $email = Auth::check() ? Auth::user()->email : null;

        $memberId = Auth::id();

        $account = DB::table('share_capital_account_tbls')
            ->where('member_id', $memberId)
            ->first();

        $currentBalance = $account->total_amount ?? 0;
        $currentShares = $account->total_shares ?? 0;

        // Fetch real contribution history
        $contributions = $account
            ? DB::table('share_capital_transaction_tbls')
                ->where('share_capital_account_id', $account->id)
                ->orderBy('created_at', 'desc')
                ->get()
            : collect();

        return view(
            'members_components.share_capital',
            [
                "username" => $username,
                "email" => $email
            ],
            compact('currentBalance', 'currentShares', 'contributions')
        );
    }

    public function ProfileMember()
    {
        $username = Auth::check() ? Auth::user()->username : null;
        $email = Auth::check() ? Auth::user()->email : null;

        return view(
            "members_components.profile",
            [
                "username" => $username,
                "email" => $email
            ]
        );
    }

    public function Navbar2()
    {
        $username = Auth::check() ? Auth::user()->username : null;
        $email = Auth::check() ? Auth::user()->email : null;


        return view(
            "components.navbar2",
            [
                "username" => $username,
                "email" => $email
            ]
        );
    }

    public function logout()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route("login");
    }

    public function UserHandle()
    {
        $user = Auth::user();

        if ($user->role === "Member") {
            return redirect()->route("MemberPortal")->with("message", "Login successfully!");
        } else {
            return redirect()->route("dashboard")->with("message", "Login successfully!");
        }
    }

    public function login(Request $request)
    {
        $incomingFields = $request->validate([
            "login" => "required",
            "password" => "required"
        ]);

        $loginInput = $incomingFields['login'];
        $isEmail = filter_var($loginInput, FILTER_VALIDATE_EMAIL);

        // Check if the user exists first
        $user = $isEmail
            ? DB::table('users_tbls')->where('email', $loginInput)->first()
            : DB::table('users_tbls')->where('username', $loginInput)->first();

        if (!$user) {
            $field = $isEmail ? 'email' : 'username';
            $message = $isEmail
                ? 'That email isn’t registered yet.'
                : 'That username isn’t registered yet.';

            return redirect()->back()
                ->withErrors(['login' => $message])
                ->withInput($request->only('login'));
        }

        // if (!$user) {
        //     $field = $isEmail ? 'email' : 'username';
        //     $message = $isEmail
        //         ? 'No account found with that email address.'
        //         : 'No account found with that username.';

        //     return redirect()->back()
        //         ->withErrors(['login' => $message])
        //         ->withInput($request->only('login'));
        // }

        // Attempt authentication
        $credentials = [
            'email' => $user->email,
            'password' => $incomingFields['password']
        ];

        if (auth()->attempt($credentials)) {
            $otherInfo = DB::table('otherinfo_tbls')
                ->where('member_id', auth()->id())
                ->first();

            if (!$otherInfo || $otherInfo->status !== 'Approved') {
                auth()->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->back()
                    ->withErrors(['login' => 'Your account is still pending approval.'])
                    ->withInput($request->only('login'));
            }

            $request->session()->regenerate();
            return redirect()->route('UserHandle');

        } else {
            return redirect()->back()
                ->withErrors(['login' => 'Incorrect password. Please try again.'])
                ->withInput($request->only('login'));
        }
    }

    public function registration(Request $request)
    {
        try {
            $request->validate([
                "first_name" => "required",
                "middle_name" => "required",
                "last_name" => "required",
                "profile_picture" => "nullable|image|max:2048",
                "date_of_birth" => "required|date",
                "place_of_birth" => "required",
                "email" => ["required", "email", Rule::unique("users_tbls", "email")],
                "password" => "required|confirmed|min:8",
                "membership_category" => "required",
                "civil_status" => "required",
                "number_son" => "nullable|integer",
                "number_daughter" => "nullable|integer",
                "other_spec" => "nullable",

                "sss_id" => "nullable|file|mimes:jpg,jpeg,png,pdf|max:2048",
                "philhealth_id" => "nullable|file|mimes:jpg,jpeg,png,pdf|max:2048",
                "pagibig_id" => "nullable|file|mimes:jpg,jpeg,png,pdf|max:2048",
                "tin_id" => "nullable|file|mimes:jpg,jpeg,png,pdf|max:2048",

                "uv_plate_no" => "nullable|array",
                "uv_plate_no.*" => "nullable|string",
                "taxi_plate_no" => "nullable|array",
                "taxi_plate_no.*" => "nullable|string",
                "bus_plate_no" => "nullable|array",
                "bus_plate_no.*" => "nullable|string",
                "mini_bus_plate_no" => "nullable|array",
                "mini_bus_plate_no.*" => "nullable|string",
                "jeep_plate_no" => "nullable|array",
                "jeep_plate_no.*" => "nullable|string",
                "multi_cab_plate_no" => "nullable|array",
                "multi_cab_plate_no.*" => "nullable|string",
                "tricycle_plate_no" => "nullable|array",
                "tricycle_plate_no.*" => "nullable|string",

                "total_uv" => "nullable|integer|min:0",
                "total_taxi" => "nullable|integer|min:0",
                "total_bus" => "nullable|integer|min:0",
                "total_mini_bus" => "nullable|integer|min:0",
                "total_jeep" => "nullable|integer|min:0",
                "total_multi_cab" => "nullable|integer|min:0",
                "total_tricycle" => "nullable|integer|min:0",

                "signature" => "required",
            ]);

            // Profile picture
            $profilePicturePath = null;
            if ($request->hasFile('profile_picture')) {
                $profilePicturePath = $request->file('profile_picture')->store('profile_pictures', 'public');
            }

            // Create user
            $users = Users_tbl::create([
                "fullname" => $request->first_name . ' ' . $request->middle_name . '. ' . $request->last_name,
                "username" => $request->username,
                "email" => $request->email,
                "password" => bcrypt($request->password),
                "role" => "Member",
            ]);

            // Spouse
            Spouse_tbl::create([
                "member_id" => $users->id,
                "spouse_name" => $request->spouse_name,
                "spouse_date_birth" => $request->spouse_date_birth ?: null,
                "spouse_place_birth" => $request->spouse_place_birth,
                "number_son" => $request->number_son,
                "number_daughter" => $request->number_daughter,
                "other_spec" => $request->other_spec,
            ]);


            $governmentIds = ['member_id' => $users->id];
            $fileFields = ['sss_id', 'philhealth_id', 'pagibig_id', 'tin_id'];

            foreach ($fileFields as $field) {
                if ($request->hasFile($field)) {
                    $governmentIds[$field] = $request->file($field)->store('government_ids', 'public');
                } else {
                    $governmentIds[$field] = null;
                }
            }

            Membergovern_ids_tbl::create($governmentIds);

            // Other info
            Otherinfo_tbl::create([
                "member_id" => $users->id,
                "membership_category" => $request->membership_category,
                "date_of_birth" => $request->date_of_birth,
                "place_of_birth" => $request->place_of_birth,
                "sex" => $request->sex,
                "civil_status" => $request->civil_status,
                "skills" => $request->skills,
                "signature" => $request->signature,
                "profile_picture" => $profilePicturePath,
                "status" => "Pending",
            ]);

            // Vehicles
            $vehicleTypes = [
                'UV' => ['plate_name' => 'uv_plate_no', 'qty_name' => 'total_uv'],
                'TAXI' => ['plate_name' => 'taxi_plate_no', 'qty_name' => 'total_taxi'],
                'BUS' => ['plate_name' => 'bus_plate_no', 'qty_name' => 'total_bus'],
                'MINI BUS' => ['plate_name' => 'mini_bus_plate_no', 'qty_name' => 'total_mini_bus'],
                'JEEP' => ['plate_name' => 'jeep_plate_no', 'qty_name' => 'total_jeep'],
                'MULTI-CAB' => ['plate_name' => 'multi_cab_plate_no', 'qty_name' => 'total_multi_cab'],
                'TRICYCLE' => ['plate_name' => 'tricycle_plate_no', 'qty_name' => 'total_tricycle'],
            ];

            foreach ($vehicleTypes as $type => $fields) {
                $quantity = (int) $request->input($fields['qty_name'], 0);
                $plates = $request->input($fields['plate_name']);

                if ($quantity <= 0 || empty($plates) || !is_array($plates)) {
                    continue;
                }

                foreach ($plates as $plate_no) {
                    $plate_no = trim((string) ($plate_no ?? ''));
                    if ($plate_no === '')
                        continue;

                    Membervehi_tbl::create([
                        'member_id' => $users->id,
                        'plate_no' => $plate_no,
                        'vehicle_type' => $type,
                        'quantity' => 1,
                    ]);
                }
            }

            return redirect()->route("RegisterPage")->with("success", "Create account successfully!");

        } catch (\Exception $e) {
            dd($e->getMessage(), $e->getLine(), $e->getFile());
        }
    }
}