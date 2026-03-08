<?php

namespace App\Http\Controllers;

use App\Models\Spouse_tbl;
use App\Models\Membergovern_ids_tbl;
use App\Models\Membervehi_tbl;
use App\Models\Otherinfo_tbl;
use App\Models\Users_tbl;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;


class UsersHandle extends Controller
{
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
        // dd($request->all());

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

            $profilePicturePath = null;
            if ($request->hasFile('profile_picture')) {
                $profilePicturePath = $request->file('profile_picture')->store('profile_pictures', 'public');
            }

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

            Spouse_tbl::create([
                "member_id" => $users->id,
                "spouse_name" => $request->spouse_name,
                "spouse_date_birth" => $request->spouse_date_birth ?: null,
                "spouse_place_birth" => $request->spouse_place_birth,
            ]);

            Membergovern_ids_tbl::create([
                "member_id" => $users->id,
                "sss_id" => $request->sss_id,
                "philhealth_id" => $request->philhealth_id,
                "pagibig_id" => $request->pagibig_id,
                "tin_id" => $request->tin_id,
            ]);

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

            $vehicleTypes = [
                'UV' => [
                    'plate_name' => 'uv_plate_no', 'qty_name' => 'total_uv'
                ],
                'TAXI' => [
                    'plate_name' => 'taxi_plate_no', 'qty_name' => 'total_taxi'
                ],
                'BUS' => [
                    'plate_name' => 'bus_plate_no', 'qty_name' => 'total_bus'
                ],
                'MINI BUS' => [
                    'plate_name' => 'mini_bus_plate_no', 'qty_name' => 'total_mini_bus'
                ],
                'JEEP' => [
                    'plate_name' => 'jeep_plate_no', 'qty_name' => 'total_jeep'
                ],
                'MULTI-CAB' => [
                    'plate_name' => 'multi_cab_plate_no', 'qty_name' => 'total_multi_cab'
                ],
                'TRICYCLE' => [
                    'plate_name' => 'tricycle_plate_no', 'qty_name' => 'total_tricycle'
                ],
            ];

            foreach ($vehicleTypes as $type => $fields) {
                $quantity = (int) $request->input($fields['qty_name'], 0);
                $plates = $request->input($fields['plate_name']);

                if ($quantity <= 0 || empty($plates) || !is_array($plates)) {
                    continue;
                }

                foreach ($plates as $plate_no) {
                    $plate_no = trim((string) ($plate_no ?? ''));

                    if ($plate_no === '') {
                        continue;
                    }

                    Membervehi_tbl::create([
                        'member_id' => $users->id,
                        'plate_no' => $plate_no,
                        'vehicle_type' => $type,
                        'quantity' => 1,
                    ]);
                }
            }

            session()->flash("success", "Create account successfully!");
            return redirect()->route("LoginPage");

        } catch (\Exception $e) {
            dd($e->getMessage(), $e->getLine(), $e->getFile());
        }
    }
}