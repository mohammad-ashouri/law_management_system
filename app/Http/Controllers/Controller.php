<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\EquipmentLog;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Jenssegers\Agent\Agent;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function logActivity($activity, $ip_address, $user_agent, $user_id = null)
    {
        $agent = new Agent();
        ActivityLog::create([
            'user_id' => $user_id,
            'activity' => $activity,
            'ip_address' => $ip_address,
            'user_agent' => $user_agent,
            'device' => $agent->device(),
        ]);
    }

    public function logEquipmentChanges($title, $equipment_id, $equipment_type,$property_number, $operator,$personal_code=null)
    {
        EquipmentLog::create([
            'equipment_id' => $equipment_id,
            'equipment_type' => $equipment_type,
            'title' => $title,
            'personal_code' => $personal_code,
            'property_number' => $property_number,
            'operator' => $operator,
        ]);
    }

    public function alerts($state,$errorVariable,$errorText)
    {
        return response()->json([
            'success' => $state,
            'errors' => [
                $errorVariable => [$errorText]
            ]
        ]);
    }

    public function success($state,$messageVariable,$messageText)
    {
        return response()->json([
            'success' => $state,
            'message' => [
                $messageVariable => [$messageText]
            ]
        ]);
    }

    public function compareText(Request $request){
        $body1=$request->input('text1');
        $body2=$request->input('text2');
        if ($body1!=null and $body2!=null){
            $htmlDiff = new \Caxy\HtmlDiff\HtmlDiff($body1, $body2);
            return $content = $htmlDiff->build();
        }
    }
}
