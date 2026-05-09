<?php

namespace App\Http\Controllers;

use App\Services\DeviceService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class DeviceController extends Controller
{
    public function __construct(private DeviceService $deviceService) {}

    public function index()
    {
        try {
            $devices = $this->deviceService->getAll();
            return response()->json(['success' => true, 'data' => $devices]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function assign(Request $request)
    {
        try {
            $validated = $request->validate([
                'device_id' => 'required|exists:devices,id',
                'user_id'   => 'required|exists:users,id',
            ]);

            $device = $this->deviceService->assign($validated, $request->user()->id);
            return response()->json(['success' => true, 'data' => $device, 'message' => 'Dispositivo asignado']);

        } catch (ValidationException $e) {
            return response()->json(['success' => false, 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
