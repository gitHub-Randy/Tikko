<?php

namespace App\Http\Controllers;

use App\BankAccount;
use App\Group;
use App\GroupMember;
use App\Tikko;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class GroupsController extends Controller
{

    public function index(){
        $user_id = Auth::id();
        $groups = Group::where('user_id', $user_id)->get();
        return view('groups.groups',compact('groups'));
    }

    function create(){
        $user_id = Auth::id();
        $users = User::where('id', '!=',$user_id)->get();
        return view('groups.addGroup',compact('users'));
    }

    public function store(Request $request)
    {
        $user_id = Auth::id();

        $group = new Group([
            'name' => $request->groupName,
            'user_id' => $user_id,
        ]);
        $group->save();
        $newGroupId = Group::select('id')->orderBy('id','DESC')->first();
//         iterate through user check for name and add to usergroup
        for ($i = 0; $i<count($request->all());$i++) {
            if($request->has("receiver_$i")){
                $keyName = "receiver_$i";
                $memberName = $request->$keyName;
                $newMember = User::select('id')->where('name', "$memberName" )->first();
                $groupMember = new GroupMember([
                   'group_id' => $newGroupId->id,
                   'user_id' => $newMember->id
                ]);

                $groupMember->save();
            }
        }
        return $this->index();
    }

    public function edit($id)
    {
        $group = Group::where('id',$id)->first();
        $groupMembers = GroupMember::where('group_id',$group->id)->get();
//        $users = [];

        $users = User::where('id','!=',Auth::id())->get();
//        return dd($u);
//        foreach ($groupMembers as $user){
//            $u = User::where('id','!=',Auth::id())->first();
//
//            array_push($users,$u);
//        }

        return view('groups.editGroup',compact('group','groupMembers','users'));
    }

    public function update(Request $request, $id)
    {

        $oldGroup = GroupMember::where('group_id', $id)->delete();

        for ($i = 0; $i<count($request->all());$i++) {
            if($request->has("receiver_$i")){
                $keyName = "receiver_$i";
                $memberName = $request->$keyName;
                $newMember = User::select('id')->where('name', "$memberName" )->first();
                $groupMember = new GroupMember([
                    'group_id' => $id,
                    'user_id' => $newMember->id
                ]);

                $groupMember->save();
            }
        }

        return $this->index();
    }


    public function show($id)
    {

    }

    public function destroy($id)
    {
        $group = Group::where('id',$id)->first();
        $groupMemebers = GroupMember::where('group_id',$group->id)->delete();
        $group->delete();
        return $this->index();

    }

}
