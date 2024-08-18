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
                                    <h4 class="card-title">{{isset($ucb )?'Edit ':'Create '}}User Country Birth</h4>
                                </div>
                                <div class="card-body">
                                    <form class="form" action="{{--{{route('category.store')}}--}} {{isset($ucb)?route('ucb.update',[$ucb->id]):route('ucb.store')}}" method="POST">
                                        @csrf
                                        @if(isset($ucb))
                                             @method('put')
                                         @endif
                                        <div class="row">
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label for="state">Country Name</label>
                                                    <input type="text"{{isset($ucb)?'':'required'}} value="{{isset($ucb)?$ucb->name:''}}" id="name"  class="form-control"  placeholder="Enter country name for user born" name="name"/>
                                                </div>
                                            </div>
                                            <div class="col-12 text-right">
                                                <button class="btn btn-primary" type="submit" style="float:right;">{{isset($ucb)?'update':'create'}}</button>
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
