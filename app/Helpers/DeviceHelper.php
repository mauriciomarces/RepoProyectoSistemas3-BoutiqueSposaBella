<?php

namespace App\Helpers;

class DeviceHelper
{
    /**
     * Get a unique device identifier based on request information
     * 
     * @return string
     */
    public static function getDeviceId()
    {
        $request = request();

        // Get IP address
        $ip = $request->ip();

        // Get user agent
        $userAgent = $request->userAgent() ?? 'unknown';

        // Create a hash based on IP and user agent
        $deviceId = md5($ip . $userAgent);

        return substr($deviceId, 0, 16); // Return first 16 characters
    }
}
