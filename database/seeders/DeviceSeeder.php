<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Device;

class DeviceSeeder extends Seeder
{
    public function run(): void
    {
        Device::create(['name' => 'Laptop Dell XPS', 'type' => 'laptop', 'serial_number' => 'DELL-001', 'status' => 'available']);
        Device::create(['name' => 'PC HP ProDesk', 'type' => 'pc', 'serial_number' => 'HP-002', 'status' => 'available']);
        Device::create(['name' => 'iPhone 14', 'type' => 'mobile', 'serial_number' => 'IPH-003', 'status' => 'available']);
    }
}