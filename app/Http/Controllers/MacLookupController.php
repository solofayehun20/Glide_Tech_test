<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MacLookupController extends Controller
{
    public function lookup($mac)
{
    // Clean and normalize the MAC address
    $mac = $this->cleanMacAddress($mac);

    // Check if the MAC address is randomized (second character is '2', '6', 'A', or 'E')
    if (in_array(substr($mac, 1, 1), ['2', '6', 'A', 'E'])) {
        return response()->json(['mac_address' => $mac, 'vendor' => 'Randomized MAC']);
    }

    // Query the database to find the vendor by MAC address
    $vendor = DB::table('oui')->where('Assignment', $mac)->value('Organization Name');

    return response()->json(['mac_address' => $mac, 'vendor' => $vendor]);
}


    public function lookupMultiple(Request $request)
    {
        $macAddresses = $request->input('mac_addresses');
        $result = [];

        foreach ($macAddresses as $mac) {
            // Clean and normalize the MAC address
            $mac = $this->cleanMacAddress($mac);

            // Check if the MAC address is randomized (second character is '2', '6', 'A', or 'E')
            if (in_array(substr($mac, 1, 1), ['2', '6', 'A', 'E'])) {
                $result[] = ['mac_address' => $mac, 'vendor' => 'Randomized MAC'];
            } else {
                // Query the database to find the vendor by MAC address
                $vendor = DB::table('oui')->where('Assignment', $mac)->value('Organization Name');

                $result[] = ['mac_address' => $mac, 'vendor' => $vendor];
            }
        }

        return response()->json($result);
    }

    private function cleanMacAddress($mac)
    {
        // Remove separators and convert to uppercase
        return strtoupper(preg_replace('/[^A-Fa-f0-9]/', '', $mac));
    }
}
