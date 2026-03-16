<?php

namespace App\Http\Controllers;

use App\Models\educational_tbl;
use App\Models\employee_history_tbl;
use App\Models\employee_membership_information;
use App\Models\Membergovern_ids_tbl;
use App\Models\Membervehi_tbl;
use App\Models\Otherinfo_tbl;
use App\Models\seminars_training_tbl;
use App\Models\special_awards_tbl;
use App\Models\Spouse_tbl;
use App\Models\tc_membership_history_tbl;
use App\Models\Users_tbl;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;


class UsersHandle extends Controller
{
    public function applicationFormButton(Request $request, $id)
    {
        // dd($request->all());
        try {
            $request->validate([
                // users_tbls
                'first_name' => 'nullable|string|max:255',
                'middle_name' => 'nullable|string|max:255',
                'last_name' => 'nullable|string|max:255',
                'email' => 'nullable|email|max:255',
                'date_of_birth' => 'nullable|string',
                'place_of_birth' => 'nullable|string|max:255',
                'contact_no' => 'nullable|string|max:20',
                'present_address' => 'nullable|string|max:255',
                'permanent_address' => 'nullable|string|max:255',
                'sex' => 'nullable|string|max:20',
                'civil_status' => 'nullable|string|max:50',
                'citizenship' => 'nullable|string|max:100',
                'height' => 'nullable|string|max:20',
                'weight' => 'nullable|string|max:20',
                'blood_type' => 'nullable|string|max:10',
                'tsc_name' => 'nullable|string|max:255',
                'skills' => 'nullable|string|max:255',
                'number_son' => 'nullable|integer',
                'number_daughter' => 'nullable|integer',
                'other_spec' => 'nullable|string|max:255',

                // spouse_tbls
                'spouse_name' => 'nullable|string|max:255',
                'spouse_date_birth' => 'nullable|date',
                'spouse_place_birth' => 'nullable|string|max:255',

                // membergovern_ids_tbls
                'sss_id' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
                'philhealth_id' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
                'pagibig_id' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
                'tin_id' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',

                // seminars_training_tbls
                'title_seminar' => 'nullable|string|max:255',
                'attendance_from' => 'nullable|date',
                'attendance_to' => 'nullable|date',
                'sponsored_by' => 'nullable|string|max:255',

                // employee_history_tbls
                'name_office' => 'nullable|string|max:255',
                'position_title' => 'nullable|string|max:255',
                'monthly_salary' => 'nullable|string|max:255',
                'employee_inclusive_from' => 'nullable|date',
                'employee_inclusive_to' => 'nullable|date',

                // employee_membership_informations
                'date_of_membership' => 'nullable|date',
                'date_of_cetos' => 'nullable|date',
                'membership_category' => 'nullable|string|max:255',
                'tc_member_id_no' => 'nullable|string|max:255',
                'no_units_owned' => 'nullable|string|max:255',
                'type_mode_unit' => 'nullable|string|max:255',
                'paid_up_capital' => 'nullable|string|max:255',
                'paid_up_price' => 'nullable|string|max:255',

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

                // tc_membership_history_tbls
                'members_inclusive_dates_from' => 'nullable|date',
                'members_inclusive_dates_to' => 'nullable|date',
                'membership_category_history' => 'nullable|string|max:255',
                'tc_held_inclusive_dates_from' => 'nullable|date',
                'tc_held_inclusive_dates_to' => 'nullable|date',
                'position_held' => 'nullable|string|max:255',
                'monthly_salary_allowance' => 'nullable|string|max:255',

                // special_awards_tbls
                'title_awards' => 'nullable|string|max:255',
                'awarded_by' => 'nullable|string|max:255',
                'membership_other_association' => 'nullable|string|max:255',

                // educational_tbls
                'educational_level' => 'nullable|array',
                'educational_level.*' => 'nullable|string|max:255',
                'edu_status' => 'nullable|array',
                'edu_status.*' => 'nullable|string|max:255',
                'edu_specify' => 'nullable|array',
                'edu_specify.*' => 'nullable|string|max:255',
            ]);

            Users_tbl::where('id', $id)->update($request->only([
                'first_name',
                'middle_name',
                'last_name',
                'email',
                'date_of_birth',
                'place_of_birth',
                'contact_no',
                'present_address',
                'permanent_address',
                'sex',
                'civil_status',
                'citizenship',
                'height',
                'weight',
                'blood_type',
                'tsc_name',
                'skills',
                'number_son',
                'number_daughter',
                'other_spec',
            ]));

            Spouse_tbl::updateOrCreate(
                ['member_id' => $id],
                [
                    'spouse_name' => $request->spouse_name,
                    'spouse_date_birth' => $request->spouse_date_birth,
                    'spouse_place_birth' => $request->spouse_place_birth,
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

            seminars_training_tbl::updateOrCreate(
                ['member_id' => $id],
                [
                    'title_seminar' => $request->title_seminar,
                    'attendance_from' => $request->attendance_from,
                    'attendance_to' => $request->attendance_to,
                    'sponsored_by' => $request->sponsored_by,
                ]
            );

            employee_history_tbl::updateOrCreate(
                ['member_id' => $id],
                [
                    'name_office' => $request->name_office,
                    'position_title' => $request->position_title,
                    'monthly_salary' => $request->monthly_salary,
                    'employee_inclusive_from' => $request->employee_inclusive_from,
                    'employee_inclusive_to' => $request->employee_inclusive_to,
                ]
            );

            employee_membership_information::updateOrCreate(
                ['member_id' => $id],
                [
                    'date_of_membership' => $request->date_of_membership,
                    'date_of_cetos' => $request->date_of_cetos,
                    'membership_category' => $request->membership_category,
                    'tc_member_id_no' => $request->tc_member_id_no,
                    'no_units_owned' => $request->no_units_owned,
                    'type_mode_unit' => $request->type_mode_unit,
                    'paid_up_capital' => $request->paid_up_capital,
                    'paid_up_price' => $request->paid_up_price,
                ]
            );

            tc_membership_history_tbl::updateOrCreate(
                ['member_id' => $id],
                [
                    'members_inclusive_dates_from' => $request->members_inclusive_dates_from,
                    'members_inclusive_dates_to' => $request->members_inclusive_dates_to,
                    'membership_category' => $request->membership_category_history,
                    'tc_held_inclusive_dates_from' => $request->tc_held_inclusive_dates_from,
                    'tc_held_inclusive_dates_to' => $request->tc_held_inclusive_dates_to,
                    'position_held' => $request->position_held,
                    'monthly_salary_allowance' => $request->monthly_salary_allowance,
                ]
            );

            special_awards_tbl::updateOrCreate(
                ['member_id' => $id],
                [
                    'title_awards' => $request->title_awards,
                    'awarded_by' => $request->awarded_by,
                    'membership_other_association' => $request->membership_other_association,
                ]
            );

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
            $education = educational_tbl::where('member_id', $id)->get();
            $seminar = seminars_training_tbl::where('member_id', $id)->first();
            $employeeHistory = employee_history_tbl::where('member_id', $id)->first();
            $membershipInfo = employee_membership_information::where('member_id', $id)->first();
            $membershipHistory = tc_membership_history_tbl::where('member_id', $id)->first();
            $specialAwards = special_awards_tbl::where('member_id', $id)->first();
            $governmentIds = Membergovern_ids_tbl::where('member_id', $id)->first();

            return view('members_components.application_form', compact(
                'user',
                'vehicles',
                'spouse',
                'education',
                'seminar',
                'employeeHistory',
                'membershipInfo',
                'membershipHistory',
                'specialAwards',
                'governmentIds' 
            ));

        } catch (\Exception $e) {
            dd($e->getMessage(), $e->getLine(), $e->getFile());
        }
    }

    public function MemberPortal()
    {
        $first_name = Auth::check() ? Auth::user()->first_name : null;

        return view(
            "members_components.member_portal",
            ["first_name" => $first_name]
        );
    }

    public function LoanApplication()
    {

        $first_name = Auth::check() ? Auth::user()->first_name : null;

        return view(
            "members_components.loan_application",
            ["first_name" => $first_name]
        );
    }

    public function Savings()
    {
        $first_name = Auth::check() ? Auth::user()->first_name : null;
        
        return view(
            "members_components.savings", ["first_name" => $first_name]
            );
    }

    public function ShareCapital()
    {
        $first_name = Auth::check() ? Auth::user()->first_name : null;
        
        return view(
            "members_components.share_capital", ["first_name" => $first_name]
            );
    }

    public function LoanStatus()
    {
        $first_name = Auth::check() ? Auth::user()->first_name : null;
        
        return view(
            "members_components.loan_status", ["first_name" => $first_name]
            );
    }

    public function ProfileMember()
    {
        $first_name = Auth::check() ? Auth::user()->first_name : null;
        
        return view(
            "members_components.profile", ["first_name" => $first_name]
            );
    }

    public function Navbar2()
    {
        $first_name = Auth::check() ? Auth::user()->first_name : null;

        return view(
            "components.navbar2",
            ["first_name" => $first_name]
        );
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route("LoginPage");
    }

    public function UserHandle()
    {
        $user = Auth::user();

        if ($user->role === "Member") {
            return redirect()->route("MemberPortal")->with("message", "Login successfully!");
        } else {
            return redirect()->route("AdminPage")->with("message", "Login successfully!");
        }
    }

    public function login(Request $request)
    {
        $incomingFields = $request->validate([
            "email" => "required",
            "password" => "required"
        ]);

        if (
            auth()->attempt([
                'email' => $incomingFields['email'],
                'password' => $incomingFields['password']
            ])
        ) {
            $request->session()->regenerate();
            return redirect()->route("UserHandle");
        } else {
            return redirect()->route("LoginPage");
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
                "tin_no" => "nullable",
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

                "other_info_specify" => "nullable",
                "are_you_willing_liability" => "nullable",
                "are_you_willing_abide_policy" => "nullable",
                "submitted_at" => "required|date",
                "signature" => "required",
            ]);

            // Profile picture
            $profilePicturePath = null;
            if ($request->hasFile('profile_picture')) {
                $profilePicturePath = $request->file('profile_picture')->store('profile_pictures', 'public');
            }

            // Create user
            $users = Users_tbl::create([
                "membership_category" => $request->membership_category,
                "first_name" => $request->first_name,
                "middle_name" => $request->middle_name,
                "last_name" => $request->last_name,
                "profile_picture" => $profilePicturePath,
                "email" => $request->email,
                "password" => bcrypt($request->password),
                "date_of_birth" => $request->date_of_birth,
                "place_of_birth" => $request->place_of_birth,
                "civil_status" => $request->civil_status,
                "number_son" => $request->number_son,
                "number_daughter" => $request->number_daughter,
                "other_spec" => $request->other_spec,
            ]);

            // Spouse
            Spouse_tbl::create([
                "member_id" => $users->id,
                "spouse_name" => $request->spouse_name,
                "spouse_date_birth" => $request->spouse_date_birth ?: null,
                "spouse_place_birth" => $request->spouse_place_birth,
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
                "is_member_other_coop" => $request->question_member ?? '',
                "are_you_willing_liability" => $request->are_you_willing_liability ?? 0,
                "are_you_willing_abide_policy" => $request->are_you_willing_abide_policy ?? 0,
                "submitted_at" => $request->submitted_at,
                "day_of" => $request->day_of ?? '',
                "signature" => $request->signature,
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