<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
   
public function login(Request $request)
{
    try {
        $request->validate([
            'user_name' => 'required|string',
            'U_Password' => 'required|string',
        ]);

        $user = User::where('user_name', $request->user_name)->first();

        if (!$user) {
            return response()->json(['message' => 'Username does not exist'], 401);
        }

        if (!Hash::check($request->U_Password, $user->U_Password)) {
            return response()->json(['message' => 'Your Password is invalid'], 401);
        }

        // Get login details
        $login_ip = $request->ip();
        $login_browser = $request->header('User-Agent');
        $login_os = $this->getOS($login_browser);
        $login_device = $this->getDeviceType($login_browser);
        $login_isp = 'Unknown ISP'; // You can use a package or API to get ISP info if needed

        // Log the successful login attempt into the login_logs table
        \DB::table('login_logs')->insert([
            'U_id' => $user->U_id,
            'U_Email' => $user->U_Email ?? '',
            'login_time' => now('Asia/Colombo'),
            'login_ip' => $login_ip,
            'login_isp' => $login_isp,
            'login_browser' => $login_browser,
            'login_device' => $login_device,
            'login_os' => $login_os,
        ]);

        if ($user->U_Status == '0') {
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json([
                'message' => 'Login successful',
                'user' => $user,
                'token' => $token
            ]);
        } else {
            return response()->json(['message' => 'Your account has been deactivated'], 403);
        }
    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json(['error' => $e->getMessage()], 422);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

/**
 * Extract user's operating system from the user agent string.
 */
private function getOS($user_agent)
{
    $os_array = [
        '/windows nt 10/i'      => 'Windows 10',
        '/windows nt 6.3/i'     => 'Windows 8.1',
        '/windows nt 6.2/i'     => 'Windows 8',
        '/windows nt 6.1/i'     => 'Windows 7',
        '/windows nt 6.0/i'     => 'Windows Vista',
        '/windows nt 5.2/i'     => 'Windows Server 2003/XP x64',
        '/windows nt 5.1/i'     => 'Windows XP',
        '/windows xp/i'         => 'Windows XP',
        '/macintosh|mac os x/i' => 'Mac OS X',
        '/mac_powerpc/i'        => 'Mac OS 9',
        '/linux/i'              => 'Linux',
        '/ubuntu/i'             => 'Ubuntu',
        '/iphone/i'             => 'iPhone',
        '/ipod/i'               => 'iPod',
        '/ipad/i'               => 'iPad',
        '/android/i'            => 'Android',
        '/blackberry/i'         => 'BlackBerry',
        '/webos/i'              => 'Mobile',
    ];

    foreach ($os_array as $regex => $value) {
        if (preg_match($regex, $user_agent)) {
            return $value;
        }
    }
    return 'Unknown OS';
}

/**
 * Determine device type from user agent.
 */
private function getDeviceType($user_agent)
{
    if (preg_match('/android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up.browser|up.link|webos|wos/i', $user_agent)) {
        return "Mobile";
    } elseif (preg_match('/ipad|ipod|iphone/i', $user_agent)) {
        return "Tablet";
    } elseif (preg_match('/macintosh|mac os x|mac_powerpc/i', $user_agent)) {
        return "Mac";
    } elseif (preg_match('/windows|win32/i', $user_agent)) {
        return "PC";
    } else {
        return "Unknown";
    }
}

public function logout(Request $request)
{
    $request->user()->tokens()->delete();
    return response()->json(['message' => 'Logged out']);
}
}