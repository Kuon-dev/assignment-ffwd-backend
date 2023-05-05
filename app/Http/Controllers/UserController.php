<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // get specific user
    public function index(Request $request) {
        return $request->user();
    }

    // check user permission

  public function checkPerms(Request $request) {
    $root = $request->user()->hasRole("root");
    $admin = $request->user()->hasRole("admin");
    $default = $request->user()->hasRole("user");

    if ($root) {
      return response()->json(["perm_level" => 3]);
    } elseif ($admin) {
      return response()->json(["perm_level" => 2]);
    } elseif ($default) {
      return response()->json(["perm_level" => 1]);
    } else {
      return response()->json(["perm_level" => 0]);
    }
  }
}
