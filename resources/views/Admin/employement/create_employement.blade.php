<x-app-layout>
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body">
                <!-- Dashboard Ecommerce Starts -->
                <section id="multiple-column-form">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">{{isset($employement )?'Edit ':'Create '}}Employement</h4>
                                </div>
                                <div class="card-body">
                                    <form class="form" action="{{--{{route('category.store')}}--}} {{isset($employement)?route('employement.update',[$employement->id]):route('employement.store')}}" method="POST">
                                        @csrf
                                        @if(isset($employement))
                                             @method('put')
                                         @endif
                                        <div class="row">
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label for="state">Employement Status Name</label>
                                                    <input type="text"{{isset($employement)?'':'required'}} value="{{isset($employement)?$employement->employement_status:''}}" id="name"  class="form-control"  placeholder="Enter Employement Status name" name="name"/>
                                                </div>
                                            </div>
                                            <div class="col-12 text-right">
                                                <button class="btn btn-primary" type="submit" style="float:right;">{{isset($employement)?'update':'create'}}</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- Dashboard Ecommerce ends -->
            </div>
        </div>
    </div>
    @stack('script')
</x-app-layout>
