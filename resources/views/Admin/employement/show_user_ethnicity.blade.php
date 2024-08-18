<x-app-layout>
    <!-- BEGIN: Vendor CSS-->
    @push('start-styles')
        <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/vendors.min.css') }}">
        <link rel="stylesheet" type="text/css"
            href="{{ asset('app-assets/vendors/css/tables/datatable/dataTables.bootstrap4.min.css') }}">
        <link rel="stylesheet" type="text/css"
            href="{{ asset('app-assets/vendors/css/tables/datatable/responsive.bootstrap4.min.css') }}">
        <link rel="stylesheet" type="text/css"
            href="{{ asset('app-assets/vendors/css/tables/datatable/buttons.bootstrap4.min.css') }}">
        <link rel="stylesheet" type="text/css"
            href="{{ asset('app-assets/vendors/css/tables/datatable/rowGroup.bootstrap4.min.css') }}">
        <link rel="stylesheet" type="text/css"
            href="{{ asset('app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css') }}">
    @endpush
    @push('end-styles')
        {{-- <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/core/menu/menu-types/vertical-menu.css')}}"> --}}
    @endpush

    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body">
                <!-- Dashboard Ecommerce Starts -->
                <!-- Row grouping -->
                <section id="row-grouping-datatable">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header border-bottom">
                                    <h4 class="card-title">Show Employement</h4>
                                </div>
                                <div class="card-datatable">
                                    <div style="width: 100%; padding-left: 20px; padding-right: 20px; ">
                                        <div class="table-responsive">
                                            <table class="table"{{-- class="table table-striped table-bordered nowrap" style="width:100%" --}} id="employement_table">
                                                <thead>
                                                    <tr>
                                                        <th>Id</th>
                                                        <th>name</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!--/ Row grouping -->
                <!-- Dashboard Ecommerce ends -->
            </div>
        </div>
    </div>
    <!-- BEGIN: Page Vendor JS-->
    @push('start-script')
        <script src="{{ asset('app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('app-assets/vendors/js/tables/datatable/datatables.bootstrap4.min.js') }}"></script>
        <script src="{{ asset('app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js') }}"></script>
        <script src="{{ asset('app-assets/vendors/js/tables/datatable/responsive.bootstrap4.js') }}"></script>
        <script src="{{ asset('app-assets/vendors/js/tables/datatable/datatables.checkboxes.min.js') }}"></script>
        <script src="{{ asset('app-assets/vendors/js/tables/datatable/datatables.buttons.min.js') }}"></script>
        <script src="{{ asset('app-assets/vendors/js/tables/datatable/jszip.min.js') }}"></script>
        <script src="{{ asset('app-assets/vendors/js/tables/datatable/pdfmake.min.js') }}"></script>
        <script src="{{ asset('app-assets/vendors/js/tables/datatable/vfs_fonts.js') }}"></script>
        <script src="{{ asset('app-assets/vendors/js/tables/datatable/buttons.html5.min.js') }}"></script>
        <script src="{{ asset('app-assets/vendors/js/tables/datatable/buttons.print.min.js') }}"></script>
        <script src="{{ asset('app-assets/vendors/js/tables/datatable/dataTables.rowGroup.min.js') }}"></script>
        <script src="{{ asset('app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>
        <!-- END: Page Vendor JS-->
    @endpush
    @push('end-script')
        <!-- BEGIN: Page JS-->
        <script src="{{ asset('app-assets/js/scripts/tables/table-datatables-basic.js') }}"></script>
        <!-- END: Page JS-->



        <script>
            var table = $('#employement_table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('get_all_employement') }}",
                method: 'GET',
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'employement_status'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    },
                ]
            });

            function delete_employement(id) {
                $.ajax({
                    method: 'post',
                    url: "{{ route('employement_delete') }}",
                    data: {
                        id: id,
                        _token: '{{ csrf_token() }}',
                    },
                    success: function() {
                        console.log('Employement deleted successfully');
                        table.ajax.reload();
                        setTimeout(() => {
                            feather.replace({
                                width: 14,
                                height: 14
                            })
                        }, 500)
                    }
                })
            }
        </script>

        <script>
            $(window).on('load', function() {
                if (feather) {
                    setTimeout(() => {
                        feather.replace({
                            width: 14,
                            height: 14
                        })
                    }, 500)
                }
            })
        </script>
    @endpush
</x-app-layout>
