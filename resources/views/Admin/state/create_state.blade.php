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
                                    <h4 class="card-title">{{isset($state )?'Edit ':'Create '}}State</h4>
                                </div>
                                <div class="card-body">
                                    <form class="form" action="{{--{{route('category.store')}}--}} {{isset($state)?route('state.update',[$state->id]):route('state.store')}}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        @if(isset($state))
                                             @method('put')
                                         @endif
                                        <div class="row">
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label for="state">State Name</label>
                                                    <input type="text"{{isset($state)?'':'required'}} value="{{isset($state)?$state->name:''}}" id="name"  class="form-control"  placeholder="Enter state name" name="name"/>
                                                </div>
                                            </div>
                                            {{-- <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label for="flag_image" class="form-label">Upload State Flag
                                                        Image</label>
                                                    <input class="form-control" type="file" name="image_url"
                                                        {{ isset($state) ? '' : '' }}
                                                        value="{{ isset($state) ? $state->image_url : '' }}"
                                                        id="image_url">
                                                </div>
                                            </div> --}}
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label for="flag_image" class="form-label">Upload State Flag Image</label>
                                                    <input class="form-control" type="file" name="image_url" id="image_url">
                                                    @if(isset($state) && $state->image_url)
                                                        <img src="{{ asset($state->image_url) }}" alt="State Flag Image" style="max-width: 100px;">
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label for="map_image" class="form-label">Upload State Map Image</label>
                                                    <input class="form-control" type="file" name="state_map_image" id="state_map_image">
                                                    @if(isset($state) && $state->map_url)
                                                        <img src="{{ asset($state->map_url) }}" alt="State Map Image" style="max-width: 100px;">
                                                    @endif
                                                </div>
                                            </div>                                            
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label for="state_image_url" class="form-label">Upload State Image</label>
                                                    <input class="form-control" type="file" name="state_image_url" id="state_image_url">
                                                    @if(isset($state) && $state->state_image_url)
                                                        <img src="{{ asset($state->state_image_url) }}" alt="State Image" style="max-width: 100px;">
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            <div class="col-12 text-right">
                                                <button class="btn btn-primary" type="submit" style="float:right;">{{isset($state)?'update':'create'}}</button>
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
