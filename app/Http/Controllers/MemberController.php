<?php

namespace App\Http\Controllers;

use App\Http\Resources\MemberResource;
use App\Models\Member;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class MemberController extends Controller
{    
    /**
     * Method getMembers
     *
     * @param $id $id [explicite description]
     *
     * @return void
     */
    public function getMembers($id)
    {
        $members = Member::where("library_id", $id)->get();

        return $members->isEmpty() ? PARENT::createResponse('Member not found', 404) : PARENT::createResponse('success', 200, MemberResource::collection($members));
    }
    
    /**
     * Method getSpecificMember
     *
     * @param $id $id [explicite description]
     *
     * @return void
     */
    public function getSpecificMember($id)
    {
        $member = Member::find($id);

        return $member === null ? PARENT::createResponse('Member not found', 404) : PARENT::createResponse('success', 200, new MemberResource($member));
    }
    
    /**
     * Method insertMember
     *
     * @param Request $request [explicite description]
     *
     * @return void
     */
    public function insertMember(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'email_address' => 'required|string|regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/|unique:members,email_address,NULL,id,library_id,' . $request->input('library_id'),
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
    
    /**
     * Method updateMember
     *
     * @param Request $request [explicite description]
     *
     * @return void
     */
    public function updateMember(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'id'            => 'required|integer|exists:members,id',
                'email_address' => 'sometimes|string|regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/|unique:members,email_address,' . $request->input('id') . ',id,library_id,' . $request->input('library_id'),
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
                array_key_exists('new_password', $validatedData)  ? $member->password = Hash::make($validatedData['new_password']) : "";
                array_key_exists('first_name', $validatedData)    ? $member->first_name = $validatedData['first_name'] : "";
                array_key_exists('last_name', $validatedData)     ? $member->last_name = $validatedData['last_name'] : "";
                array_key_exists('address', $validatedData)       ? $member->address = $validatedData['address'] : "";
                array_key_exists('contact_no', $validatedData)    ? $member->contact_no = $validatedData['contact_no'] : "";
            } else {
                return PARENT::createResponse('Wrong password', 500);
            }

            $member->save();
            return PARENT::createResponse('Member updated successfully', 200);
        }catch (Exception $e) {
            if ($e->getCode() !== 0) {
                $message = 'Member update failed';
            } else {
                $message = $e->getMessage();
            }
            return PARENT::createResponse($e->getMessage(), 500);
        }
    }
}
