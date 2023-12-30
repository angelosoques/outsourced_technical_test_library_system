<?php

namespace App\Http\Controllers;

use App\Http\Resources\MemberResource;
use App\Models\Member;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class MemberController extends Controller
{
    public function getMembers($id = null)
    {
        if ($id) {
            $members = Member::where("library_id", $id)->get();
        } else {
            $members = Member::all();
        }

        return $members->is_Empty() ? PARENT::createResponse('Member not found', 404) : PARENT::createResponse('success', 200, MemberResource::collection($members));
    }

    public function getSpecificMember($id)
    {
        $member = Member::find($id);

        return $member->is_Empty() ? PARENT::createResponse('Member not found', 404) : PARENT::createResponse('success', 200, new MemberResource($member));
    }

    public function insertMember(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'email_address' => 'required|string|regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
                'password'      => 'required|string',
                'first_name'    => 'required|string|max:50',
                'last_name'     => 'required|string|max:50',
                'address'       => 'required|string|max:225',
                'contact_no'    => 'required|digits_between:11,13',
                'library_id'    => 'required|int|exists:libraries,id',
            ]);
    
            $validatedData['password'] = Hash::make($validatedData['password']);

            $member = Member::create($validatedData);

            $member ? PARENT::createResponse('Member inserted successfully', 200, new MemberResource($member)) : PARENT::createResponse('Member insertion failed', 500);
        }catch (Exception $e) {
            if ($e->getCode() !== 0) {
                $message = 'Member insertion failed';
            } else {
                $message = $e->getMessage();
            }
            return PARENT::createResponse($e->getMessage(), 500);
        }
    }

    public function updateMember(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'id'            => 'required|integer|exists:members,id',
                'email_address' => 'sometimes|string|regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
                'password'      => 'required|string',
                'first_name'    => 'sometimes|string|min:1|max:50',
                'last_name'     => 'sometimes|string|min:1|max:50',
                'address'       => 'sometimes|string|min:1|max:225',
                'contact_no'    => 'sometimes|digits_between:11,13',
                'new_password'  => 'sometimes|string'
            ]);

            $member = Member::find($validatedData['id']);

            if (Hash::check($validatedData['password'], $member['password'])) {
                array_key_exists('email_address', $validatedData) ? $member->email_address = $validatedData['email_address'] : "";
                array_key_exists('new_password', $validatedData) ? $member->password = Hash::make($validatedData['new_password']) : "";
                array_key_exists('first_name', $validatedData) ? $member->password = $validatedData['first_name'] : "";
                array_key_exists('last_name', $validatedData) ? $member->password = $validatedData['last_name'] : "";
                array_key_exists('address', $validatedData) ? $member->password = $validatedData['address'] : "";
                array_key_exists('contact_no', $validatedData) ? $member->password = $validatedData['contact_no'] : "";
            } else {
                return PARENT::createResponse('Passwords do not match', 500);
            }

            $member->save();

            return PARENT::createResponse('Member updated successfully', 200, new MemberResource($member));
        }catch (Exception $e) {
            if ($e->getCode() !== 0) {
                $message = 'Member update failed';
            } else {
                $message = $e->getMessage();
            }
            return PARENT::createResponse($message, 500);
        }
    }
}
