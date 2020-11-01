<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\User;
use App\Http\Resources\UserResource;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::withCount('posts')->paginate(10);

        //check if it's an API request
        if (request()->wantsJson()) {
            return UserResource::collection($users);
        }
        return view('admin.users.index', compact('users'));
    }

    /**
     * API Register
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        request()->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6',
            'is_admin' => 'boolean'
        ]);
        $name = $request->name;
        $email    = $request->email;
        $password = $request->password;

        $user = User::create(['name' => $name, 'email' => $email, 'password' => Hash::make($password), 'is_admin' => $request->is_admin ? $request->is_admin : false]);

        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if (auth()->user() == $user) {
            flash()->overlay("You can't delete yourself.");

            return redirect('/admin/users');
        }

        $user->delete();

        //check if it's an API request
        if (request()->wantsJson()) {
            return response()->json([
                'data' => [
                    'message' => 'User deleted successfully.',
                ],
            ]);
        }
        flash()->overlay('User deleted successfully.');

        return redirect('/admin/users');
    }
}
