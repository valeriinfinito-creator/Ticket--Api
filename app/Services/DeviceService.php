<?php
namespace App\Services;
use App\Models\Device;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Log;

class DeviceService
{
    public function getAll(): \Illuminate\Database\Eloquent\Collection
    {
        return Device::with('assignedUser')->latest()->get();
    }

    public function assign(array $data, int $userId): Device
    {
        $device = Device::findOrFail($data['device_id']);
        $device->update([
            'status'      => 'assigned',
            'assigned_to' => $data['user_id'],
            'assigned_at' => now(),
        ]);
        ActivityLog::create([
            'user_id'    => $userId,
            'action'     => 'device_assigned',
            'model_type' => Device::class,
            'model_id'   => $device->id,
            'data'       => $data,
        ]);
        Log::info('Dispositivo asignado', [
            'device_id'   => $device->id,
            'device_name' => $device->name,
            'assigned_to' => $data['user_id'],
            'assigned_by' => $userId,
        ]);
        return $device;
    }
}