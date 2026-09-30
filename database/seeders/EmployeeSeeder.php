<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $employees = [
            ['e_code' => 'EMP001', 'e_name' => 'Andi Pratama', 'e_d_code' => 'DIV001'],
            ['e_code' => 'EMP002', 'e_name' => 'Budi Santoso', 'e_d_code' => 'DIV001'],
            ['e_code' => 'EMP003', 'e_name' => 'Citra Dewi', 'e_d_code' => 'DIV002'],
            ['e_code' => 'EMP004', 'e_name' => 'Dewi Lestari', 'e_d_code' => 'DIV002'],
            ['e_code' => 'EMP005', 'e_name' => 'Eko Prasetyo', 'e_d_code' => 'DIV003'],
            ['e_code' => 'EMP006', 'e_name' => 'Fitri Handayani', 'e_d_code' => 'DIV003'],
            ['e_code' => 'EMP007', 'e_name' => 'Gilang Ramadhan', 'e_d_code' => 'DIV004'],
            ['e_code' => 'EMP008', 'e_name' => 'Hana Putri', 'e_d_code' => 'DIV004'],
            ['e_code' => 'EMP009', 'e_name' => 'Indra Gunawan', 'e_d_code' => 'DIV005'],
            ['e_code' => 'EMP010', 'e_name' => 'Joko Susilo', 'e_d_code' => 'DIV005'],
            ['e_code' => 'EMP011', 'e_name' => 'Kartika Sari', 'e_d_code' => 'DIV006'],
            ['e_code' => 'EMP012', 'e_name' => 'Lukman Hakim', 'e_d_code' => 'DIV006'],
            ['e_code' => 'EMP013', 'e_name' => 'Maya Indah', 'e_d_code' => 'DIV007'],
            ['e_code' => 'EMP014', 'e_name' => 'Nanda Putra', 'e_d_code' => 'DIV007'],
            ['e_code' => 'EMP015', 'e_name' => 'Olivia Wulandari', 'e_d_code' => 'DIV008'],
            ['e_code' => 'EMP016', 'e_name' => 'Pratama Aditya', 'e_d_code' => 'DIV008'],
            ['e_code' => 'EMP017', 'e_name' => 'Qori Amalia', 'e_d_code' => 'DIV009'],
            ['e_code' => 'EMP018', 'e_name' => 'Rizky Maulana', 'e_d_code' => 'DIV009'],
            ['e_code' => 'EMP019', 'e_name' => 'Siti Nurhaliza', 'e_d_code' => 'DIV010'],
            ['e_code' => 'EMP020', 'e_name' => 'Taufik Hidayat', 'e_d_code' => 'DIV010'],
            ['e_code' => 'EMP021', 'e_name' => 'Umar Faruq', 'e_d_code' => 'DIV011'],
            ['e_code' => 'EMP022', 'e_name' => 'Vina Melati', 'e_d_code' => 'DIV011'],
            ['e_code' => 'EMP023', 'e_name' => 'Wahyu Setiawan', 'e_d_code' => 'DIV012'],
            ['e_code' => 'EMP024', 'e_name' => 'Yuni Safitri', 'e_d_code' => 'DIV012'],
            ['e_code' => 'EMP025', 'e_name' => 'Zaki Anwar', 'e_d_code' => 'DIV013'],
            ['e_code' => 'EMP026', 'e_name' => 'Aulia Rahma', 'e_d_code' => 'DIV013'],
            ['e_code' => 'EMP027', 'e_name' => 'Bambang Widodo', 'e_d_code' => 'DIV014'],
            ['e_code' => 'EMP028', 'e_name' => 'Cindy Permata', 'e_d_code' => 'DIV014'],
            ['e_code' => 'EMP029', 'e_name' => 'Doni Kurniawan', 'e_d_code' => 'DIV015'],
            ['e_code' => 'EMP030', 'e_name' => 'Eka Putri', 'e_d_code' => 'DIV015'],
        ];

        foreach ($employees as $employee) {
            Employee::create($employee);
        }
    }
}