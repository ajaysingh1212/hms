<?php

Route::group(['prefix' => 'v1', 'as' => 'api.', 'namespace' => 'Api\V1\Admin', 'middleware' => ['auth:sanctum']], function () {
    // Department Name
    Route::post('department-names/media', 'DepartmentNameApiController@storeMedia')->name('department-names.storeMedia');
    Route::apiResource('department-names', 'DepartmentNameApiController');

    // Add Doctors
    Route::post('add-doctors/media', 'AddDoctorsApiController@storeMedia')->name('add-doctors.storeMedia');
    Route::apiResource('add-doctors', 'AddDoctorsApiController');

    // Appointment Slots
    Route::post('appointment-slots/media', 'AppointmentSlotsApiController@storeMedia')->name('appointment-slots.storeMedia');
    Route::apiResource('appointment-slots', 'AppointmentSlotsApiController');

    // Appointment
    Route::post('appointments/media', 'AppointmentApiController@storeMedia')->name('appointments.storeMedia');
    Route::apiResource('appointments', 'AppointmentApiController');

    // Opd Visits
    Route::post('opd-visits/media', 'OpdVisitsApiController@storeMedia')->name('opd-visits.storeMedia');
    Route::apiResource('opd-visits', 'OpdVisitsApiController');

    // Opd Prescriptions
    Route::post('opd-prescriptions/media', 'OpdPrescriptionsApiController@storeMedia')->name('opd-prescriptions.storeMedia');
    Route::apiResource('opd-prescriptions', 'OpdPrescriptionsApiController');

    // Lab Test
    Route::post('lab-tests/media', 'LabTestApiController@storeMedia')->name('lab-tests.storeMedia');
    Route::apiResource('lab-tests', 'LabTestApiController');

    // Opd Tests
    Route::post('opd-tests/media', 'OpdTestsApiController@storeMedia')->name('opd-tests.storeMedia');
    Route::apiResource('opd-tests', 'OpdTestsApiController');

    // Opd Billing
    Route::post('opd-billings/media', 'OpdBillingApiController@storeMedia')->name('opd-billings.storeMedia');
    Route::apiResource('opd-billings', 'OpdBillingApiController');

    // Ipd Rooms
    Route::post('ipd-rooms/media', 'IpdRoomsApiController@storeMedia')->name('ipd-rooms.storeMedia');
    Route::apiResource('ipd-rooms', 'IpdRoomsApiController');

    // Ipd Beds
    Route::post('ipd-beds/media', 'IpdBedsApiController@storeMedia')->name('ipd-beds.storeMedia');
    Route::apiResource('ipd-beds', 'IpdBedsApiController');

    // Ipd Admissions
    Route::post('ipd-admissions/media', 'IpdAdmissionsApiController@storeMedia')->name('ipd-admissions.storeMedia');
    Route::apiResource('ipd-admissions', 'IpdAdmissionsApiController');

    // Ipd Treatments
    Route::post('ipd-treatments/media', 'IpdTreatmentsApiController@storeMedia')->name('ipd-treatments.storeMedia');
    Route::apiResource('ipd-treatments', 'IpdTreatmentsApiController');

    // Ipd Medications
    Route::post('ipd-medications/media', 'IpdMedicationsApiController@storeMedia')->name('ipd-medications.storeMedia');
    Route::apiResource('ipd-medications', 'IpdMedicationsApiController');

    // Ipd Vitals
    Route::post('ipd-vitals/media', 'IpdVitalsApiController@storeMedia')->name('ipd-vitals.storeMedia');
    Route::apiResource('ipd-vitals', 'IpdVitalsApiController');

    // Ipd Test
    Route::post('ipd-tests/media', 'IpdTestApiController@storeMedia')->name('ipd-tests.storeMedia');
    Route::apiResource('ipd-tests', 'IpdTestApiController');

    // Ipd Billing
    Route::post('ipd-billings/media', 'IpdBillingApiController@storeMedia')->name('ipd-billings.storeMedia');
    Route::apiResource('ipd-billings', 'IpdBillingApiController');

    // Medicine
    Route::post('medicines/media', 'MedicineApiController@storeMedia')->name('medicines.storeMedia');
    Route::apiResource('medicines', 'MedicineApiController');

    // Ipd Discharge Summary
    Route::post('ipd-discharge-summaries/media', 'IpdDischargeSummaryApiController@storeMedia')->name('ipd-discharge-summaries.storeMedia');
    Route::apiResource('ipd-discharge-summaries', 'IpdDischargeSummaryApiController');
});
