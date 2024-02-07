<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Services\UserService;

class UserController extends Controller
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function pendingUsers()
    {
        try {
            return $this->userService->fetchData();
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }

    }

    public function accept(Request $request)
    {
        try {
            if($this->userService->accept($request->accepted_user_id)) {
                return redirect()->back()->with('success', 'User accepted successfully');
            }
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function decline(Request $request)
    {
        try {
            if($this->userService->decline($request->deleted_user_id)) {
                return redirect()->back()->with('success', 'User declined successfully');
            }
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }
}
