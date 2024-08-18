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
                                    <h4 class="card-title">{{isset($voter_party )?'Edit ':'Create '}} Voter Party</h4>
                                </div>
                                <div class="card-body">
                                    <form class="form" action="{{--{{route('category.store')}}--}} {{isset($voter_party)?route('parties.update',[$voter_party->id]):route('parties.store')}}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        @if(isset($voter_party))
                                             @method('put')
                                         @endif
                                        <div class="row">
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label for="category">Party Name</label>
                                                    <input type="text"{{isset($voter_party)?'':'required'}} value="{{isset($voter_party)?$voter_party->party_name:''}}" id="name"  class="form-control"  placeholder="Enter party name" name="name"/>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label for="party_badge" class="form-label">Upload Party Badge</label>
                                                    <input class="form-control" type="file" name="party_badge" id="party_badge">
                                                    @if(isset($voter_party) && $voter_party->party_badge)
                                                        <img src="{{ asset($voter_party->party_badge) }}" alt="Party Badge" style="max-width: 100px;">
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-12 text-right">
                                                <button class="btn btn-primary" type="submit" style="float:right;">{{isset($voter_party)?'update':'create'}}</button>
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
