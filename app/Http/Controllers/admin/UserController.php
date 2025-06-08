<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController
{
   public function index(Request $request)
   {
      $sort = $request->get('sort', 'created_at');
      $order = $request->get('order', 'desc');
      $allowedSorts = ['full_name', 'created_at'];

      if (!in_array($sort, $allowedSorts)) {
         $sort = 'created_at';
      }
      if (!in_array($order, ['asc', 'desc'])) {
         $order = 'desc';
      }

      $users = User::orderBy($sort, $order)->paginate(5)->appends($request->all());
      return view('admin.users.index', compact('users'));
   }

   public function create()
   {
      $users = User::all();
      return view('admin.users.create', compact('users'));
   }

   public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'full_name'      => 'required|string|max:255',
        'email'          => 'required|email|unique:users,email',
        'phone_number'   => 'required|string|max:20',
        'password'       => 'required|string|min:6|confirmed',
        'avatar_url'     => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'account_status' => 'required|in:active,inactive,banned',
        'role'           => 'required|in:admin,user',
    ]);

    if ($validator->fails()) {
        return redirect()->back()
            ->withErrors($validator)
            ->withInput();
    }

    $user = new User();
    $user->full_name = $request->full_name;
    $user->email = $request->email;
    $user->phone_number = $request->phone_number;
    $user->role = $request->role;
    $user->account_status = $request->account_status;
    $user->password = bcrypt($request->password);

    // Upload avatar to public/images/users
    if ($request->hasFile('avatar_url')) {
        $file = $request->file('avatar_url');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('images/users'), $filename);
        $user->avatar_url = 'images/users/' . $filename;
    }

    $user->save();

    return redirect()->route('admin.users.index')->with('success', 'Thêm user thành công!');
}

   public function show($id)
   {
      $user = User::findOrFail($id);
      return view('admin.users.detail', compact('user'));
   }

   public function edit($id){
      $user = User::findOrFail($id);
      return view('admin.users.edit', compact('user'));
   }

   public function update(Request $request, $id)
{
    $user = User::findOrFail($id);

    $validator = Validator::make($request->all(), [
        'account_status' => 'required|in:active,inactive,banned',
        'role'           => 'required|in:admin,user',
    ]);

    if ($validator->fails()) {
        return redirect()->back()
            ->withErrors($validator)
            ->withInput();
    }

    $user->account_status = $request->account_status;
    $user->role = $request->role;
    $user->save();

    return redirect()->route('admin.users.index')->with('success', 'Cập nhật user thành công!');
}



   public function orderHistory($id)
   {
      $user = User::findOrFail($id);
      $orders = Order::where('user_id', $id)->orderBy('created_at', 'desc')->get();

      return view('admin.users.orderHistory', compact('user', 'orders'));
   }

}
