<?php

use App\Http\Controllers\Admin\AppointmentController;

Route::redirect('/', '/login');
Route::get('/home', function () {
    if (session('status')) {
        return redirect()->route('admin.home')->with('status', session('status'));
    }

    return redirect()->route('admin.home');
});

Auth::routes();

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth']], function () {
    Route::get('/', 'HomeController@index')->name('home');
    // Permissions
    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    Route::resource('permissions', 'PermissionsController');

    // Roles
    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
    Route::resource('roles', 'RolesController');

    // Users
    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
    Route::resource('users', 'UsersController');

    // Audit Logs
    Route::resource('audit-logs', 'AuditLogsController', ['except' => ['create', 'store', 'edit', 'update', 'destroy']]);

    // User Alerts
    Route::delete('user-alerts/destroy', 'UserAlertsController@massDestroy')->name('user-alerts.massDestroy');
    Route::get('user-alerts/read', 'UserAlertsController@read');
    Route::resource('user-alerts', 'UserAlertsController', ['except' => ['edit', 'update']]);

    // Department Name
    Route::delete('department-names/destroy', 'DepartmentNameController@massDestroy')->name('department-names.massDestroy');
    Route::post('department-names/media', 'DepartmentNameController@storeMedia')->name('department-names.storeMedia');
    Route::post('department-names/ckmedia', 'DepartmentNameController@storeCKEditorImages')->name('department-names.storeCKEditorImages');
    Route::post('department-names/parse-csv-import', 'DepartmentNameController@parseCsvImport')->name('department-names.parseCsvImport');
    Route::post('department-names/process-csv-import', 'DepartmentNameController@processCsvImport')->name('department-names.processCsvImport');
    Route::resource('department-names', 'DepartmentNameController');

    // Add Doctors
    Route::delete('add-doctors/destroy', 'AddDoctorsController@massDestroy')->name('add-doctors.massDestroy');
    Route::post('add-doctors/media', 'AddDoctorsController@storeMedia')->name('add-doctors.storeMedia');
    Route::post('add-doctors/ckmedia', 'AddDoctorsController@storeCKEditorImages')->name('add-doctors.storeCKEditorImages');
    Route::post('add-doctors/parse-csv-import', 'AddDoctorsController@parseCsvImport')->name('add-doctors.parseCsvImport');
    Route::post('add-doctors/process-csv-import', 'AddDoctorsController@processCsvImport')->name('add-doctors.processCsvImport');
    Route::resource('add-doctors', 'AddDoctorsController');

    // Appointment Slots
    Route::delete('appointment-slots/destroy', 'AppointmentSlotsController@massDestroy')->name('appointment-slots.massDestroy');
    Route::post('appointment-slots/media', 'AppointmentSlotsController@storeMedia')->name('appointment-slots.storeMedia');
    Route::post('appointment-slots/ckmedia', 'AppointmentSlotsController@storeCKEditorImages')->name('appointment-slots.storeCKEditorImages');
    Route::post('appointment-slots/parse-csv-import', 'AppointmentSlotsController@parseCsvImport')->name('appointment-slots.parseCsvImport');
    Route::post('appointment-slots/process-csv-import', 'AppointmentSlotsController@processCsvImport')->name('appointment-slots.processCsvImport');
    Route::resource('appointment-slots', 'AppointmentSlotsController');

    // Appointment
    Route::delete('appointments/destroy', 'AppointmentController@massDestroy')->name('appointments.massDestroy');
    Route::post('appointments/media', 'AppointmentController@storeMedia')->name('appointments.storeMedia');
    Route::post('appointments/ckmedia', 'AppointmentController@storeCKEditorImages')->name('appointments.storeCKEditorImages');
    Route::post('appointments/parse-csv-import', 'AppointmentController@parseCsvImport')->name('appointments.parseCsvImport');
    Route::post('appointments/process-csv-import', 'AppointmentController@processCsvImport')->name('appointments.processCsvImport');
    Route::resource('appointments', 'AppointmentController');

    // Opd Visits
    Route::delete('opd-visits/destroy', 'OpdVisitsController@massDestroy')->name('opd-visits.massDestroy');
    Route::post('opd-visits/media', 'OpdVisitsController@storeMedia')->name('opd-visits.storeMedia');
    Route::post('opd-visits/ckmedia', 'OpdVisitsController@storeCKEditorImages')->name('opd-visits.storeCKEditorImages');
    Route::post('opd-visits/parse-csv-import', 'OpdVisitsController@parseCsvImport')->name('opd-visits.parseCsvImport');
    Route::post('opd-visits/process-csv-import', 'OpdVisitsController@processCsvImport')->name('opd-visits.processCsvImport');
    Route::resource('opd-visits', 'OpdVisitsController');

    // Opd Prescriptions
    Route::get('opd-prescriptions/opd-detail', [App\Http\Controllers\Admin\OpdPrescriptionsController::class, 'opdDetails'])
        ->name('opd-prescriptions.opdDetails');
    Route::delete('opd-prescriptions/destroy', 'OpdPrescriptionsController@massDestroy')->name('opd-prescriptions.massDestroy');
    Route::post('opd-prescriptions/media', 'OpdPrescriptionsController@storeMedia')->name('opd-prescriptions.storeMedia');
    Route::post('opd-prescriptions/ckmedia', 'OpdPrescriptionsController@storeCKEditorImages')->name('opd-prescriptions.storeCKEditorImages');
    Route::post('opd-prescriptions/parse-csv-import', 'OpdPrescriptionsController@parseCsvImport')->name('opd-prescriptions.parseCsvImport');
    Route::post('opd-prescriptions/process-csv-import', 'OpdPrescriptionsController@processCsvImport')->name('opd-prescriptions.processCsvImport');
    Route::resource('opd-prescriptions', 'OpdPrescriptionsController');

    // Lab Test
    Route::delete('lab-tests/destroy', 'LabTestController@massDestroy')->name('lab-tests.massDestroy');
    Route::post('lab-tests/media', 'LabTestController@storeMedia')->name('lab-tests.storeMedia');
    Route::post('lab-tests/ckmedia', 'LabTestController@storeCKEditorImages')->name('lab-tests.storeCKEditorImages');
    Route::post('lab-tests/parse-csv-import', 'LabTestController@parseCsvImport')->name('lab-tests.parseCsvImport');
    Route::post('lab-tests/process-csv-import', 'LabTestController@processCsvImport')->name('lab-tests.processCsvImport');
    Route::resource('lab-tests', 'LabTestController');

    // Opd Tests
    Route::get('opd-tests/opd-details', [App\Http\Controllers\Admin\OpdTestsController::class, 'opdDetails'])
        ->name('opd-tests.opdDetails');
    Route::delete('opd-tests/destroy', 'OpdTestsController@massDestroy')->name('opd-tests.massDestroy');
    Route::post('opd-tests/media', 'OpdTestsController@storeMedia')->name('opd-tests.storeMedia');
    Route::post('opd-tests/ckmedia', 'OpdTestsController@storeCKEditorImages')->name('opd-tests.storeCKEditorImages');
    Route::post('opd-tests/parse-csv-import', 'OpdTestsController@parseCsvImport')->name('opd-tests.parseCsvImport');
    Route::post('opd-tests/process-csv-import', 'OpdTestsController@processCsvImport')->name('opd-tests.processCsvImport');
    Route::resource('opd-tests', 'OpdTestsController');

    // Opd Billing
    Route::get('opd-billings/opd-details', 
    [App\Http\Controllers\Admin\OpdBillingController::class, 'opdDetails']
    )->name('opd-billings.opdDetails');

    Route::delete('opd-billings/destroy', 'OpdBillingController@massDestroy')->name('opd-billings.massDestroy');
    Route::post('opd-billings/media', 'OpdBillingController@storeMedia')->name('opd-billings.storeMedia');
    Route::post('opd-billings/ckmedia', 'OpdBillingController@storeCKEditorImages')->name('opd-billings.storeCKEditorImages');
    Route::post('opd-billings/parse-csv-import', 'OpdBillingController@parseCsvImport')->name('opd-billings.parseCsvImport');
    Route::post('opd-billings/process-csv-import', 'OpdBillingController@processCsvImport')->name('opd-billings.processCsvImport');
    Route::resource('opd-billings', 'OpdBillingController');

    // Ipd Rooms
    Route::delete('ipd-rooms/destroy', 'IpdRoomsController@massDestroy')->name('ipd-rooms.massDestroy');
    Route::post('ipd-rooms/media', 'IpdRoomsController@storeMedia')->name('ipd-rooms.storeMedia');
    Route::post('ipd-rooms/ckmedia', 'IpdRoomsController@storeCKEditorImages')->name('ipd-rooms.storeCKEditorImages');
    Route::post('ipd-rooms/parse-csv-import', 'IpdRoomsController@parseCsvImport')->name('ipd-rooms.parseCsvImport');
    Route::post('ipd-rooms/process-csv-import', 'IpdRoomsController@processCsvImport')->name('ipd-rooms.processCsvImport');
    Route::resource('ipd-rooms', 'IpdRoomsController');

    // Ipd Beds
    Route::delete('ipd-beds/destroy', 'IpdBedsController@massDestroy')->name('ipd-beds.massDestroy');
    Route::post('ipd-beds/media', 'IpdBedsController@storeMedia')->name('ipd-beds.storeMedia');
    Route::post('ipd-beds/ckmedia', 'IpdBedsController@storeCKEditorImages')->name('ipd-beds.storeCKEditorImages');
    Route::post('ipd-beds/parse-csv-import', 'IpdBedsController@parseCsvImport')->name('ipd-beds.parseCsvImport');
    Route::post('ipd-beds/process-csv-import', 'IpdBedsController@processCsvImport')->name('ipd-beds.processCsvImport');
    Route::resource('ipd-beds', 'IpdBedsController');

    // Ipd Admissions
    Route::delete('ipd-admissions/destroy', 'IpdAdmissionsController@massDestroy')->name('ipd-admissions.massDestroy');
    Route::post('ipd-admissions/media', 'IpdAdmissionsController@storeMedia')->name('ipd-admissions.storeMedia');
    Route::post('ipd-admissions/ckmedia', 'IpdAdmissionsController@storeCKEditorImages')->name('ipd-admissions.storeCKEditorImages');
    Route::post('ipd-admissions/parse-csv-import', 'IpdAdmissionsController@parseCsvImport')->name('ipd-admissions.parseCsvImport');
    Route::post('ipd-admissions/process-csv-import', 'IpdAdmissionsController@processCsvImport')->name('ipd-admissions.processCsvImport');
    Route::resource('ipd-admissions', 'IpdAdmissionsController');

    // Ipd Treatments
    Route::delete('ipd-treatments/destroy', 'IpdTreatmentsController@massDestroy')->name('ipd-treatments.massDestroy');
    Route::post('ipd-treatments/media', 'IpdTreatmentsController@storeMedia')->name('ipd-treatments.storeMedia');
    Route::post('ipd-treatments/ckmedia', 'IpdTreatmentsController@storeCKEditorImages')->name('ipd-treatments.storeCKEditorImages');
    Route::post('ipd-treatments/parse-csv-import', 'IpdTreatmentsController@parseCsvImport')->name('ipd-treatments.parseCsvImport');
    Route::post('ipd-treatments/process-csv-import', 'IpdTreatmentsController@processCsvImport')->name('ipd-treatments.processCsvImport');
    Route::resource('ipd-treatments', 'IpdTreatmentsController');

    // Ipd Medications
    Route::delete('ipd-medications/destroy', 'IpdMedicationsController@massDestroy')->name('ipd-medications.massDestroy');
    Route::post('ipd-medications/media', 'IpdMedicationsController@storeMedia')->name('ipd-medications.storeMedia');
    Route::post('ipd-medications/ckmedia', 'IpdMedicationsController@storeCKEditorImages')->name('ipd-medications.storeCKEditorImages');
    Route::post('ipd-medications/parse-csv-import', 'IpdMedicationsController@parseCsvImport')->name('ipd-medications.parseCsvImport');
    Route::post('ipd-medications/process-csv-import', 'IpdMedicationsController@processCsvImport')->name('ipd-medications.processCsvImport');
    Route::resource('ipd-medications', 'IpdMedicationsController');

    // Ipd Vitals
    Route::delete('ipd-vitals/destroy', 'IpdVitalsController@massDestroy')->name('ipd-vitals.massDestroy');
    Route::post('ipd-vitals/media', 'IpdVitalsController@storeMedia')->name('ipd-vitals.storeMedia');
    Route::post('ipd-vitals/ckmedia', 'IpdVitalsController@storeCKEditorImages')->name('ipd-vitals.storeCKEditorImages');
    Route::post('ipd-vitals/parse-csv-import', 'IpdVitalsController@parseCsvImport')->name('ipd-vitals.parseCsvImport');
    Route::post('ipd-vitals/process-csv-import', 'IpdVitalsController@processCsvImport')->name('ipd-vitals.processCsvImport');
    Route::resource('ipd-vitals', 'IpdVitalsController');

    // Ipd Test
    Route::delete('ipd-tests/destroy', 'IpdTestController@massDestroy')->name('ipd-tests.massDestroy');
    Route::post('ipd-tests/media', 'IpdTestController@storeMedia')->name('ipd-tests.storeMedia');
    Route::post('ipd-tests/ckmedia', 'IpdTestController@storeCKEditorImages')->name('ipd-tests.storeCKEditorImages');
    Route::post('ipd-tests/parse-csv-import', 'IpdTestController@parseCsvImport')->name('ipd-tests.parseCsvImport');
    Route::post('ipd-tests/process-csv-import', 'IpdTestController@processCsvImport')->name('ipd-tests.processCsvImport');
    Route::resource('ipd-tests', 'IpdTestController');

    // Ipd Billing
    Route::delete('ipd-billings/destroy', 'IpdBillingController@massDestroy')->name('ipd-billings.massDestroy');
    Route::post('ipd-billings/media', 'IpdBillingController@storeMedia')->name('ipd-billings.storeMedia');
    Route::post('ipd-billings/ckmedia', 'IpdBillingController@storeCKEditorImages')->name('ipd-billings.storeCKEditorImages');
    Route::post('ipd-billings/parse-csv-import', 'IpdBillingController@parseCsvImport')->name('ipd-billings.parseCsvImport');
    Route::post('ipd-billings/process-csv-import', 'IpdBillingController@processCsvImport')->name('ipd-billings.processCsvImport');
    Route::resource('ipd-billings', 'IpdBillingController');

    // Medicine
    Route::delete('medicines/destroy', 'MedicineController@massDestroy')->name('medicines.massDestroy');
    Route::post('medicines/media', 'MedicineController@storeMedia')->name('medicines.storeMedia');
    Route::post('medicines/ckmedia', 'MedicineController@storeCKEditorImages')->name('medicines.storeCKEditorImages');
    Route::post('medicines/parse-csv-import', 'MedicineController@parseCsvImport')->name('medicines.parseCsvImport');
    Route::post('medicines/process-csv-import', 'MedicineController@processCsvImport')->name('medicines.processCsvImport');
    Route::resource('medicines', 'MedicineController');

    // Ipd Discharge Summary
    Route::delete('ipd-discharge-summaries/destroy', 'IpdDischargeSummaryController@massDestroy')->name('ipd-discharge-summaries.massDestroy');
    Route::post('ipd-discharge-summaries/media', 'IpdDischargeSummaryController@storeMedia')->name('ipd-discharge-summaries.storeMedia');
    Route::post('ipd-discharge-summaries/ckmedia', 'IpdDischargeSummaryController@storeCKEditorImages')->name('ipd-discharge-summaries.storeCKEditorImages');
    Route::post('ipd-discharge-summaries/parse-csv-import', 'IpdDischargeSummaryController@parseCsvImport')->name('ipd-discharge-summaries.parseCsvImport');
    Route::post('ipd-discharge-summaries/process-csv-import', 'IpdDischargeSummaryController@processCsvImport')->name('ipd-discharge-summaries.processCsvImport');
    Route::resource('ipd-discharge-summaries', 'IpdDischargeSummaryController');

    Route::get('messenger', 'MessengerController@index')->name('messenger.index');
    Route::get('messenger/create', 'MessengerController@createTopic')->name('messenger.createTopic');
    Route::post('messenger', 'MessengerController@storeTopic')->name('messenger.storeTopic');
    Route::get('messenger/inbox', 'MessengerController@showInbox')->name('messenger.showInbox');
    Route::get('messenger/outbox', 'MessengerController@showOutbox')->name('messenger.showOutbox');
    Route::get('messenger/{topic}', 'MessengerController@showMessages')->name('messenger.showMessages');
    Route::delete('messenger/{topic}', 'MessengerController@destroyTopic')->name('messenger.destroyTopic');
    Route::post('messenger/{topic}/reply', 'MessengerController@replyToTopic')->name('messenger.reply');
    Route::get('messenger/{topic}/reply', 'MessengerController@showReply')->name('messenger.showReply');

    // custom routes can be placed here
    Route::get('get-doctors-by-department', [AppointmentController::class, 'getDoctors'])->name('getDoctorsByDepartment');

    Route::get('get-doctor-details', [AppointmentController::class, 'getDoctorDetails'])->name('getDoctorDetails');

    Route::get('get-available-slots', [AppointmentController::class, 'getAvailableSlots'])->name('getAvailableSlots');
    // Appointment details used by AJAX when selecting "patient" (i.e. an appointment record)
    Route::get('opd-visits/appointment-details/{appointment}', [App\Http\Controllers\Admin\OpdVisitsController::class, 'appointmentDetails'])
        ->name('opd-visits.appointmentDetails');
    // AJAX endpoint to fetch OPD details


});
Route::group(['prefix' => 'profile', 'as' => 'profile.', 'namespace' => 'Auth', 'middleware' => ['auth']], function () {
    // Change password
    if (file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php'))) {
        Route::get('password', 'ChangePasswordController@edit')->name('password.edit');
        Route::post('password', 'ChangePasswordController@update')->name('password.update');
        Route::post('profile', 'ChangePasswordController@updateProfile')->name('password.updateProfile');
        Route::post('profile/destroy', 'ChangePasswordController@destroy')->name('password.destroyProfile');
    }
});
