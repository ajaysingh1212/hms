<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            [
                'id'    => 1,
                'title' => 'user_management_access',
            ],
            [
                'id'    => 2,
                'title' => 'permission_create',
            ],
            [
                'id'    => 3,
                'title' => 'permission_edit',
            ],
            [
                'id'    => 4,
                'title' => 'permission_show',
            ],
            [
                'id'    => 5,
                'title' => 'permission_delete',
            ],
            [
                'id'    => 6,
                'title' => 'permission_access',
            ],
            [
                'id'    => 7,
                'title' => 'role_create',
            ],
            [
                'id'    => 8,
                'title' => 'role_edit',
            ],
            [
                'id'    => 9,
                'title' => 'role_show',
            ],
            [
                'id'    => 10,
                'title' => 'role_delete',
            ],
            [
                'id'    => 11,
                'title' => 'role_access',
            ],
            [
                'id'    => 12,
                'title' => 'user_create',
            ],
            [
                'id'    => 13,
                'title' => 'user_edit',
            ],
            [
                'id'    => 14,
                'title' => 'user_show',
            ],
            [
                'id'    => 15,
                'title' => 'user_delete',
            ],
            [
                'id'    => 16,
                'title' => 'user_access',
            ],
            [
                'id'    => 17,
                'title' => 'department_access',
            ],
            [
                'id'    => 18,
                'title' => 'audit_log_show',
            ],
            [
                'id'    => 19,
                'title' => 'audit_log_access',
            ],
            [
                'id'    => 20,
                'title' => 'user_alert_create',
            ],
            [
                'id'    => 21,
                'title' => 'user_alert_show',
            ],
            [
                'id'    => 22,
                'title' => 'user_alert_delete',
            ],
            [
                'id'    => 23,
                'title' => 'user_alert_access',
            ],
            [
                'id'    => 24,
                'title' => 'department_name_create',
            ],
            [
                'id'    => 25,
                'title' => 'department_name_edit',
            ],
            [
                'id'    => 26,
                'title' => 'department_name_show',
            ],
            [
                'id'    => 27,
                'title' => 'department_name_delete',
            ],
            [
                'id'    => 28,
                'title' => 'department_name_access',
            ],
            [
                'id'    => 29,
                'title' => 'doctor_access',
            ],
            [
                'id'    => 30,
                'title' => 'add_doctor_create',
            ],
            [
                'id'    => 31,
                'title' => 'add_doctor_edit',
            ],
            [
                'id'    => 32,
                'title' => 'add_doctor_show',
            ],
            [
                'id'    => 33,
                'title' => 'add_doctor_delete',
            ],
            [
                'id'    => 34,
                'title' => 'add_doctor_access',
            ],
            [
                'id'    => 35,
                'title' => 'appointment_slot_create',
            ],
            [
                'id'    => 36,
                'title' => 'appointment_slot_edit',
            ],
            [
                'id'    => 37,
                'title' => 'appointment_slot_show',
            ],
            [
                'id'    => 38,
                'title' => 'appointment_slot_delete',
            ],
            [
                'id'    => 39,
                'title' => 'appointment_slot_access',
            ],
            [
                'id'    => 40,
                'title' => 'appointment_booking_access',
            ],
            [
                'id'    => 41,
                'title' => 'appointment_create',
            ],
            [
                'id'    => 42,
                'title' => 'appointment_edit',
            ],
            [
                'id'    => 43,
                'title' => 'appointment_show',
            ],
            [
                'id'    => 44,
                'title' => 'appointment_delete',
            ],
            [
                'id'    => 45,
                'title' => 'appointment_access',
            ],
            [
                'id'    => 46,
                'title' => 'opd_ipd_access',
            ],
            [
                'id'    => 47,
                'title' => 'master_access',
            ],
            [
                'id'    => 48,
                'title' => 'opd_visit_create',
            ],
            [
                'id'    => 49,
                'title' => 'opd_visit_edit',
            ],
            [
                'id'    => 50,
                'title' => 'opd_visit_show',
            ],
            [
                'id'    => 51,
                'title' => 'opd_visit_delete',
            ],
            [
                'id'    => 52,
                'title' => 'opd_visit_access',
            ],
            [
                'id'    => 53,
                'title' => 'opd_prescription_create',
            ],
            [
                'id'    => 54,
                'title' => 'opd_prescription_edit',
            ],
            [
                'id'    => 55,
                'title' => 'opd_prescription_show',
            ],
            [
                'id'    => 56,
                'title' => 'opd_prescription_delete',
            ],
            [
                'id'    => 57,
                'title' => 'opd_prescription_access',
            ],
            [
                'id'    => 58,
                'title' => 'lab_test_create',
            ],
            [
                'id'    => 59,
                'title' => 'lab_test_edit',
            ],
            [
                'id'    => 60,
                'title' => 'lab_test_show',
            ],
            [
                'id'    => 61,
                'title' => 'lab_test_delete',
            ],
            [
                'id'    => 62,
                'title' => 'lab_test_access',
            ],
            [
                'id'    => 63,
                'title' => 'opd_test_create',
            ],
            [
                'id'    => 64,
                'title' => 'opd_test_edit',
            ],
            [
                'id'    => 65,
                'title' => 'opd_test_show',
            ],
            [
                'id'    => 66,
                'title' => 'opd_test_delete',
            ],
            [
                'id'    => 67,
                'title' => 'opd_test_access',
            ],
            [
                'id'    => 68,
                'title' => 'opd_billing_create',
            ],
            [
                'id'    => 69,
                'title' => 'opd_billing_edit',
            ],
            [
                'id'    => 70,
                'title' => 'opd_billing_show',
            ],
            [
                'id'    => 71,
                'title' => 'opd_billing_delete',
            ],
            [
                'id'    => 72,
                'title' => 'opd_billing_access',
            ],
            [
                'id'    => 73,
                'title' => 'ipd_room_create',
            ],
            [
                'id'    => 74,
                'title' => 'ipd_room_edit',
            ],
            [
                'id'    => 75,
                'title' => 'ipd_room_show',
            ],
            [
                'id'    => 76,
                'title' => 'ipd_room_delete',
            ],
            [
                'id'    => 77,
                'title' => 'ipd_room_access',
            ],
            [
                'id'    => 78,
                'title' => 'ipd_bed_create',
            ],
            [
                'id'    => 79,
                'title' => 'ipd_bed_edit',
            ],
            [
                'id'    => 80,
                'title' => 'ipd_bed_show',
            ],
            [
                'id'    => 81,
                'title' => 'ipd_bed_delete',
            ],
            [
                'id'    => 82,
                'title' => 'ipd_bed_access',
            ],
            [
                'id'    => 83,
                'title' => 'ipd_access',
            ],
            [
                'id'    => 84,
                'title' => 'ipd_admission_create',
            ],
            [
                'id'    => 85,
                'title' => 'ipd_admission_edit',
            ],
            [
                'id'    => 86,
                'title' => 'ipd_admission_show',
            ],
            [
                'id'    => 87,
                'title' => 'ipd_admission_delete',
            ],
            [
                'id'    => 88,
                'title' => 'ipd_admission_access',
            ],
            [
                'id'    => 89,
                'title' => 'ipd_treatment_create',
            ],
            [
                'id'    => 90,
                'title' => 'ipd_treatment_edit',
            ],
            [
                'id'    => 91,
                'title' => 'ipd_treatment_show',
            ],
            [
                'id'    => 92,
                'title' => 'ipd_treatment_delete',
            ],
            [
                'id'    => 93,
                'title' => 'ipd_treatment_access',
            ],
            [
                'id'    => 94,
                'title' => 'ipd_medication_create',
            ],
            [
                'id'    => 95,
                'title' => 'ipd_medication_edit',
            ],
            [
                'id'    => 96,
                'title' => 'ipd_medication_show',
            ],
            [
                'id'    => 97,
                'title' => 'ipd_medication_delete',
            ],
            [
                'id'    => 98,
                'title' => 'ipd_medication_access',
            ],
            [
                'id'    => 99,
                'title' => 'ipd_vital_create',
            ],
            [
                'id'    => 100,
                'title' => 'ipd_vital_edit',
            ],
            [
                'id'    => 101,
                'title' => 'ipd_vital_show',
            ],
            [
                'id'    => 102,
                'title' => 'ipd_vital_delete',
            ],
            [
                'id'    => 103,
                'title' => 'ipd_vital_access',
            ],
            [
                'id'    => 104,
                'title' => 'ipd_test_create',
            ],
            [
                'id'    => 105,
                'title' => 'ipd_test_edit',
            ],
            [
                'id'    => 106,
                'title' => 'ipd_test_show',
            ],
            [
                'id'    => 107,
                'title' => 'ipd_test_delete',
            ],
            [
                'id'    => 108,
                'title' => 'ipd_test_access',
            ],
            [
                'id'    => 109,
                'title' => 'ipd_billing_create',
            ],
            [
                'id'    => 110,
                'title' => 'ipd_billing_edit',
            ],
            [
                'id'    => 111,
                'title' => 'ipd_billing_show',
            ],
            [
                'id'    => 112,
                'title' => 'ipd_billing_delete',
            ],
            [
                'id'    => 113,
                'title' => 'ipd_billing_access',
            ],
            [
                'id'    => 114,
                'title' => 'medicine_create',
            ],
            [
                'id'    => 115,
                'title' => 'medicine_edit',
            ],
            [
                'id'    => 116,
                'title' => 'medicine_show',
            ],
            [
                'id'    => 117,
                'title' => 'medicine_delete',
            ],
            [
                'id'    => 118,
                'title' => 'medicine_access',
            ],
            [
                'id'    => 119,
                'title' => 'ipd_discharge_summary_create',
            ],
            [
                'id'    => 120,
                'title' => 'ipd_discharge_summary_edit',
            ],
            [
                'id'    => 121,
                'title' => 'ipd_discharge_summary_show',
            ],
            [
                'id'    => 122,
                'title' => 'ipd_discharge_summary_delete',
            ],
            [
                'id'    => 123,
                'title' => 'ipd_discharge_summary_access',
            ],
            [
                'id'    => 124,
                'title' => 'opd_access',
            ],
            [
                'id'    => 125,
                'title' => 'profile_password_edit',
            ],
        ];

        Permission::insert($permissions);
    }
}
