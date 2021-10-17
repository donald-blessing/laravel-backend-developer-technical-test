<?php

namespace App\Http\Controllers;

use App\Repositories\Interface\User\UserRepositoryInterface;
use App\Traits\ControllerTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    use ControllerTrait;

    protected $users;

    public function __construct(UserRepositoryInterface $users)
    {
        $this->users = $users;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->isAdmin) {
            $users = $this->users->where('type', 'admin', '<>');
        }
        if (Auth::user()->isCustomer) {
            $users = $this->users->where('type', 'service');
        }
        if (Auth::user()->isServiceProvider) {
            $users = $this->users->where('type', 'customer');
        }
        $users = $users->where('id', auth()->user()->id, '<>')->paginate();

        return view('admin.index', ['users' => $users]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $this->users->delete($id);
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->responseRedirectBack($th->getMessage(), 'error', true, true);
        }
        DB::commit();
        return $this->responseRedirectBack('User deleted successfully', 'success');
    }
}
