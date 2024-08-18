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
                                    <h4 class="card-title">{{isset($ethnicity )?'Edit ':'Create '}}Ethnicity</h4>
                                </div>
                                <div class="card-body">
                                    <form class="form" action="{{--{{route('category.store')}}--}} {{isset($ethnicity)?route('ethnicity.update',[$ethnicity->id]):route('ethnicity.store')}}" method="POST">
                                        @csrf
                                        @if(isset($ethnicity))
                                             @method('put')
                                         @endif
                                        <div class="row">
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label for="state">Ethnicity Name</label>
                                                    <input type="text"{{isset($ethnicity)?'':'required'}} value="{{isset($ethnicity)?$ethnicity->name:''}}" id="name"  class="form-control"  placeholder="Enter ethnicity name" name="name"/>
                                                </div>
                                            </div>
                                            <div class="col-12 text-right">
                                                <button class="btn btn-primary" type="submit" style="float:right;">{{isset($ethnicity)?'update':'create'}}</button>
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
