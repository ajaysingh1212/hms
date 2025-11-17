<div id="sidebar" class="c-sidebar c-sidebar-fixed c-sidebar-lg-show">

    <div class="c-sidebar-brand d-md-down-none">
        <a class="c-sidebar-brand-full h4" href="#">
            {{ trans('panel.site_title') }}
        </a>
    </div>

    <ul class="c-sidebar-nav">
        <li class="c-sidebar-nav-item">
            <a href="{{ route("admin.home") }}" class="c-sidebar-nav-link">
                <i class="c-sidebar-nav-icon fas fa-fw fa-tachometer-alt">

                </i>
                {{ trans('global.dashboard') }}
            </a>
        </li>
        @can('user_management_access')
            <li class="c-sidebar-nav-dropdown {{ request()->is("admin/permissions*") ? "c-show" : "" }} {{ request()->is("admin/roles*") ? "c-show" : "" }} {{ request()->is("admin/users*") ? "c-show" : "" }} {{ request()->is("admin/audit-logs*") ? "c-show" : "" }}">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw fas fa-users c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.userManagement.title') }}
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                    @can('permission_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.permissions.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/permissions") || request()->is("admin/permissions/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-unlock-alt c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.permission.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('role_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.roles.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/roles") || request()->is("admin/roles/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-briefcase c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.role.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('user_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.users.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/users") || request()->is("admin/users/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-user c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.user.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('audit_log_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.audit-logs.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/audit-logs") || request()->is("admin/audit-logs/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-file-alt c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.auditLog.title') }}
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan
        @can('appointment_booking_access')
            <li class="c-sidebar-nav-dropdown {{ request()->is("admin/appointments*") ? "c-show" : "" }}">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw fab fa-accusoft c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.appointmentBooking.title') }}
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                    @can('appointment_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.appointments.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/appointments") || request()->is("admin/appointments/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-address-card c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.appointment.title') }}
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan
        @can('opd_ipd_access')
            <li class="c-sidebar-nav-dropdown {{ request()->is("admin/*") ? "c-show" : "" }} {{ request()->is("admin/*") ? "c-show" : "" }}">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw far fa-eye c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.opdIpd.title') }}
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                    @can('opd_access')
                        <li class="c-sidebar-nav-dropdown {{ request()->is("admin/opd-visits*") ? "c-show" : "" }} {{ request()->is("admin/opd-prescriptions*") ? "c-show" : "" }} {{ request()->is("admin/opd-tests*") ? "c-show" : "" }} {{ request()->is("admin/opd-billings*") ? "c-show" : "" }}">
                            <a class="c-sidebar-nav-dropdown-toggle" href="#">
                                <i class="fa-fw fas fa-h-square c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.opd.title') }}
                            </a>
                            <ul class="c-sidebar-nav-dropdown-items">
                                @can('opd_visit_access')
                                    <li class="c-sidebar-nav-item">
                                        <a href="{{ route("admin.opd-visits.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/opd-visits") || request()->is("admin/opd-visits/*") ? "c-active" : "" }}">
                                            <i class="fa-fw fas fa-street-view c-sidebar-nav-icon">

                                            </i>
                                            {{ trans('cruds.opdVisit.title') }}
                                        </a>
                                    </li>
                                @endcan
                                @can('opd_prescription_access')
                                    <li class="c-sidebar-nav-item">
                                        <a href="{{ route("admin.opd-prescriptions.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/opd-prescriptions") || request()->is("admin/opd-prescriptions/*") ? "c-active" : "" }}">
                                            <i class="fa-fw fas fa-prescription-bottle-alt c-sidebar-nav-icon">

                                            </i>
                                            {{ trans('cruds.opdPrescription.title') }}
                                        </a>
                                    </li>
                                @endcan
                                @can('opd_test_access')
                                    <li class="c-sidebar-nav-item">
                                        <a href="{{ route("admin.opd-tests.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/opd-tests") || request()->is("admin/opd-tests/*") ? "c-active" : "" }}">
                                            <i class="fa-fw fas fa-flask c-sidebar-nav-icon">

                                            </i>
                                            {{ trans('cruds.opdTest.title') }}
                                        </a>
                                    </li>
                                @endcan
                                @can('opd_billing_access')
                                    <li class="c-sidebar-nav-item">
                                        <a href="{{ route("admin.opd-billings.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/opd-billings") || request()->is("admin/opd-billings/*") ? "c-active" : "" }}">
                                            <i class="fa-fw fas fa-file-invoice c-sidebar-nav-icon">

                                            </i>
                                            {{ trans('cruds.opdBilling.title') }}
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    @endcan
                    @can('ipd_access')
                        <li class="c-sidebar-nav-dropdown {{ request()->is("admin/ipd-admissions*") ? "c-show" : "" }} {{ request()->is("admin/ipd-treatments*") ? "c-show" : "" }} {{ request()->is("admin/ipd-medications*") ? "c-show" : "" }} {{ request()->is("admin/ipd-vitals*") ? "c-show" : "" }} {{ request()->is("admin/ipd-tests*") ? "c-show" : "" }} {{ request()->is("admin/ipd-billings*") ? "c-show" : "" }} {{ request()->is("admin/ipd-discharge-summaries*") ? "c-show" : "" }}">
                            <a class="c-sidebar-nav-dropdown-toggle" href="#">
                                <i class="fa-fw fas fa-warehouse c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.ipd.title') }}
                            </a>
                            <ul class="c-sidebar-nav-dropdown-items">
                                @can('ipd_admission_access')
                                    <li class="c-sidebar-nav-item">
                                        <a href="{{ route("admin.ipd-admissions.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/ipd-admissions") || request()->is("admin/ipd-admissions/*") ? "c-active" : "" }}">
                                            <i class="fa-fw fas fa-toolbox c-sidebar-nav-icon">

                                            </i>
                                            {{ trans('cruds.ipdAdmission.title') }}
                                        </a>
                                    </li>
                                @endcan
                                @can('ipd_treatment_access')
                                    <li class="c-sidebar-nav-item">
                                        <a href="{{ route("admin.ipd-treatments.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/ipd-treatments") || request()->is("admin/ipd-treatments/*") ? "c-active" : "" }}">
                                            <i class="fa-fw fas fa-bed c-sidebar-nav-icon">

                                            </i>
                                            {{ trans('cruds.ipdTreatment.title') }}
                                        </a>
                                    </li>
                                @endcan
                                @can('ipd_medication_access')
                                    <li class="c-sidebar-nav-item">
                                        <a href="{{ route("admin.ipd-medications.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/ipd-medications") || request()->is("admin/ipd-medications/*") ? "c-active" : "" }}">
                                            <i class="fa-fw fas fa-pills c-sidebar-nav-icon">

                                            </i>
                                            {{ trans('cruds.ipdMedication.title') }}
                                        </a>
                                    </li>
                                @endcan
                                @can('ipd_vital_access')
                                    <li class="c-sidebar-nav-item">
                                        <a href="{{ route("admin.ipd-vitals.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/ipd-vitals") || request()->is("admin/ipd-vitals/*") ? "c-active" : "" }}">
                                            <i class="fa-fw fas fa-heartbeat c-sidebar-nav-icon">

                                            </i>
                                            {{ trans('cruds.ipdVital.title') }}
                                        </a>
                                    </li>
                                @endcan
                                @can('ipd_test_access')
                                    <li class="c-sidebar-nav-item">
                                        <a href="{{ route("admin.ipd-tests.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/ipd-tests") || request()->is("admin/ipd-tests/*") ? "c-active" : "" }}">
                                            <i class="fa-fw fas fa-vials c-sidebar-nav-icon">

                                            </i>
                                            {{ trans('cruds.ipdTest.title') }}
                                        </a>
                                    </li>
                                @endcan
                                @can('ipd_billing_access')
                                    <li class="c-sidebar-nav-item">
                                        <a href="{{ route("admin.ipd-billings.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/ipd-billings") || request()->is("admin/ipd-billings/*") ? "c-active" : "" }}">
                                            <i class="fa-fw fas fa-file-invoice c-sidebar-nav-icon">

                                            </i>
                                            {{ trans('cruds.ipdBilling.title') }}
                                        </a>
                                    </li>
                                @endcan
                                @can('ipd_discharge_summary_access')
                                    <li class="c-sidebar-nav-item">
                                        <a href="{{ route("admin.ipd-discharge-summaries.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/ipd-discharge-summaries") || request()->is("admin/ipd-discharge-summaries/*") ? "c-active" : "" }}">
                                            <i class="fa-fw fas fa-calendar-check c-sidebar-nav-icon">

                                            </i>
                                            {{ trans('cruds.ipdDischargeSummary.title') }}
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan
        @can('department_access')
            <li class="c-sidebar-nav-dropdown {{ request()->is("admin/department-names*") ? "c-show" : "" }}">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw fas fa-id-card-alt c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.department.title') }}
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                    @can('department_name_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.department-names.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/department-names") || request()->is("admin/department-names/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-bezier-curve c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.departmentName.title') }}
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan
        @can('doctor_access')
            <li class="c-sidebar-nav-dropdown {{ request()->is("admin/add-doctors*") ? "c-show" : "" }} {{ request()->is("admin/appointment-slots*") ? "c-show" : "" }}">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw fas fa-user-md c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.doctor.title') }}
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                    @can('add_doctor_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.add-doctors.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/add-doctors") || request()->is("admin/add-doctors/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-user-md c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.addDoctor.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('appointment_slot_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.appointment-slots.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/appointment-slots") || request()->is("admin/appointment-slots/*") ? "c-active" : "" }}">
                                <i class="fa-fw far fa-clock c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.appointmentSlot.title') }}
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan
        @can('master_access')
            <li class="c-sidebar-nav-dropdown {{ request()->is("admin/lab-tests*") ? "c-show" : "" }} {{ request()->is("admin/ipd-rooms*") ? "c-show" : "" }} {{ request()->is("admin/ipd-beds*") ? "c-show" : "" }} {{ request()->is("admin/medicines*") ? "c-show" : "" }}">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.master.title') }}
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                    @can('lab_test_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.lab-tests.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/lab-tests") || request()->is("admin/lab-tests/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-flask c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.labTest.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('ipd_room_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.ipd-rooms.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/ipd-rooms") || request()->is("admin/ipd-rooms/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-hospital-alt c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.ipdRoom.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('ipd_bed_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.ipd-beds.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/ipd-beds") || request()->is("admin/ipd-beds/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-bed c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.ipdBed.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('medicine_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.medicines.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/medicines") || request()->is("admin/medicines/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-pills c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.medicine.title') }}
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan
        @can('user_alert_access')
            <li class="c-sidebar-nav-item">
                <a href="{{ route("admin.user-alerts.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/user-alerts") || request()->is("admin/user-alerts/*") ? "c-active" : "" }}">
                    <i class="fa-fw fas fa-bell c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.userAlert.title') }}
                </a>
            </li>
        @endcan
        @php($unread = \App\Models\QaTopic::unreadCount())
            <li class="c-sidebar-nav-item">
                <a href="{{ route("admin.messenger.index") }}" class="{{ request()->is("admin/messenger") || request()->is("admin/messenger/*") ? "c-active" : "" }} c-sidebar-nav-link">
                    <i class="c-sidebar-nav-icon fa-fw fa fa-envelope">

                    </i>
                    <span>{{ trans('global.messages') }}</span>
                    @if($unread > 0)
                        <strong>( {{ $unread }} )</strong>
                    @endif

                </a>
            </li>
            @if(file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php')))
                @can('profile_password_edit')
                    <li class="c-sidebar-nav-item">
                        <a class="c-sidebar-nav-link {{ request()->is('profile/password') || request()->is('profile/password/*') ? 'c-active' : '' }}" href="{{ route('profile.password.edit') }}">
                            <i class="fa-fw fas fa-key c-sidebar-nav-icon">
                            </i>
                            {{ trans('global.change_password') }}
                        </a>
                    </li>
                @endcan
            @endif
            <li class="c-sidebar-nav-item">
                <a href="#" class="c-sidebar-nav-link" onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                    <i class="c-sidebar-nav-icon fas fa-fw fa-sign-out-alt">

                    </i>
                    {{ trans('global.logout') }}
                </a>
            </li>
    </ul>

</div>