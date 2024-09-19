<?php



namespace App\Http\Controllers\Auth;



use App\Http\Controllers\Controller;
use App\Permission;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller

{



    use AuthenticatesUsers;



    protected $redirectTo = '/';

    /**

     * Create a new controller instance.

     *

     * @return void

     */

    public function __construct()

    {

        $this->middleware('guest')->except('logout');
        $this->middleware('common');
    }



    /**

     * Create a new controller instance.

     *

     * @return void

     */
    public function showLoginForm()
    {

        return view('backend.auth.login');
    }

    public function login(Request $request)
    {

        $input = $request->all();

        $this->validate($request, [
            'name' => 'required',
            'password' => 'required',
        ]);

        $fieldType = filter_var($request->name, FILTER_VALIDATE_EMAIL) ? 'email' : 'name';

        if (auth()->attempt(array($fieldType => $input['name'], 'password' => $input['password']))) {

            $role_permissions = Permission::with([
                'role' => function ($query) {
                    $query->where('role_id', auth()->user()->role_id);
                }
            ])->get();

            Session::put('role_permissions', $role_permissions);
            return redirect('/');
        } else {
            return redirect()->route('login')->with('error', 'Username And Password Are Wrong.');
        }
    }
}
