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
    <!-- END: Vendor CSS-->


    <!-- BEGIN: Theme CSS-->
    {{--    <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/bootstrap.css')}}"> --}}
    {{--    <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/bootstrap-extended.css')}}"> --}}
    {{--    <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/colors.css')}}"> --}}
    {{--    <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/components.css')}}"> --}}
    {{--    <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/themes/dark-layout.css')}}"> --}}
    {{--    <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/themes/bordered-layout.css')}}"> --}}
    {{--    <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/themes/semi-dark-layout.css')}}"> --}}

    <!-- BEGIN: Page CSS-->
    @push('end-styles')
        {{-- <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/core/menu/menu-types/vertical-menu.css')}}"> --}}
    @endpush
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}"> --}}
    <!-- END: Custom CSS-->

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
                                    <h4 class="card-title">Show Candidates</h4>
                                </div>
                                <div class="card-datatable">
                                    <div style="width: 100%; padding-left: 20px; padding-right: 20px; ">
                                        <div class="table-responsive">
                                            <table class="table"{{-- class="table table-striped table-bordered nowrap" style="width:100%" --}} id="candidate_table">
                                                <thead>
                                                    <tr>
                                                        <th>Id</th>
                                                        <th>Candidate Name</th>
                                                        <th>Candidate Image</th>
                                                        <th>Votter Party</th>
                                                        <th>Dob</th>
                                                        <th>Birth Place</th>
                                                        <th>Occupation</th>
                                                        <th>Position</th>
                                                        <th>Position_1</th>
                                                        <th>Order</th>
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
    {{-- <!-- BEGIN: Vendor JS-->
    <script src="{{asset('app-assets/vendors/js/vendors.min.js')}}"></script>
   <!-- BEGIN Vendor JS--> --}}
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
            var table = $('#candidate_table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('get_all_candidates') }}",
                method: 'GET',
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'candidate_name',
                        name: 'candidate_name'
                    },
                    {
                        data: 'candidate_image',
                        name: 'candidate_image'
                    },
                    {
                        data: 'votter_party_id',
                        name: 'votter_party_id'
                    },
                    {
                        data: 'dob',
                        name: 'dob'
                    },
                    {
                        data: 'birth_place',
                        name: 'birth_place'
                    },
                    {
                        data: 'occupation',
                        name: 'occupation'
                    },
                    {
                        data: 'position',
                        name: 'position'
                    },
                    {
                        data: 'position_1',
                        name: 'position_1'
                    },
                    {
                        data: 'order',
                        name: 'order'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    },
                ]
            });

            function delete_candidate(id) {
                $.ajax({
                    method: 'post',
                    url: "{{ route('candidate_delete') }}",
                    data: {
                        id: id,
                        _token: '{{ csrf_token() }}',
                    },
                    success: function() {
                        console.log('candidate deleted successfully');
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
