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
                                    <h4 class="card-title">{{ isset($previous_election) ? 'Edit ' : 'Create' }}Previous
                                        Election Data</h4>
                                </div>
                                <div class="card-body">
                                    <form class="form"
                                        action="{{ isset($previous_election) ? route('previous_election.update', [$previous_election->id]) : route('previous_election.store') }}"
                                        method="POST" enctype="multipart/form-data">
                                        @csrf
                                        @isset($previous_election)
                                            @method('put')
                                        @endisset
                                        <div class="row">
                                            <div class="col-md-3 col-12">
                                                <div class="form-group">
                                                    <label for="votter_party_id">Voter Party</label>
                                                    <select class="form-control" id="votter_party_id"
                                                        name="votter_party_id" required>
                                                        <option>Select Voter Party</option>
                                                        @foreach ($votter_party as $party)
                                                            {{-- <option value="{{ $party->id }}">{{ $party->party_name }}
                                                            </option> --}}
                                                            <option value="{{ $party->id }}" {{ isset($previous_election) && $previous_election->votter_party_id == $party->id ? 'selected' : '' }}>
                                                                {{ $party->party_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-12">
                                                <div class="form-group">
                                                    <label for="state">State</label>
                                                    <select class="form-control" id="user_state_id" name="user_state_id"
                                                        required>
                                                        <option>Select State</option>
                                                        @foreach ($states as $state)
                                                            {{-- <option value="{{ $state->id }}">{{ $state->name }}
                                                            </option> --}}
                                                            <option value="{{ $state->id }}" {{ isset($previous_election) && $previous_election->user_state_id == $state->id ? 'selected' : '' }}>
                                                                {{ $state->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-12">
                                                <div class="form-group">
                                                    <label for="election_year">Election Year</label>
                                                    <input
                                                        type="text"{{ isset($previous_election) ? '' : 'required' }}
                                                        value="{{ isset($previous_election) ? $previous_election->election_year : '' }}"
                                                        id="election_year" class="form-control" name="election_year"
                                                        placeholder="enter election year" />
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-12">
                                                <div class="form-group">
                                                    <label for="age_range">Age Range</label>
                                                    <input type="text"{{ isset($previous_election) ? '' : '' }}
                                                        value="{{ isset($previous_election) ? $previous_election->age_range : '' }}"
                                                        id="age_range" class="form-control" name="age_range"
                                                        placeholder="Enter Age Range" />
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-12">
                                                <div class="form-group">
                                                    <label for="voter_percentage">Voter Percentage</label>
                                                    {{-- <input type="decimal"{{ isset($previous_election) ? '' : 'required' }}
                                                        value="{{ isset($previous_election) ? $previous_election->vote_percentage : '' }}"
                                                        id="voter_percentage" class="form-control" name="voter_percentage"
                                                        placeholder="enter voter percentage " /> --}}
                                                    <input type="number" step="0.01" min="0" max="100"
                                                        {{ isset($previous_election) ? '' : 'required' }}
                                                        value="{{ isset($previous_election) ? $previous_election->vote_percentage : '' }}"
                                                        id="voter_percentage" class="form-control"
                                                        name="voter_percentage"
                                                        placeholder="Enter voter percentage" />
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-12">
                                                <div class="form-group">
                                                    <label for="male_ratio">Male Ratio</label>
                                                    <input type="number" {{ isset($previous_election) ? '' : '' }}
                                                        value="{{ isset($previous_election) ? $previous_election->male_ratio : '' }}"
                                                        id="male_ratio" class="form-control" name="male_ratio"
                                                        placeholder="Enter male ratio in voting" />
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-12">
                                                <div class="form-group">
                                                    <label for="female_ratio">Female Ratio</label>
                                                    <input type="number" {{ isset($previous_election) ? '' : '' }}
                                                        value="{{ isset($previous_election) ? $previous_election->female_ratio : '' }}"
                                                        id="female_ratio" class="form-control" name="female_ratio"
                                                        placeholder="Enter female ratio in voting" />
                                                </div>
                                            </div>

                                            <div class="col-12 text-right">
                                                <button class="btn btn-primary" type="submit"
                                                    style="float:right;">{{ isset($previous_election) ? 'update' : 'create' }}</button>
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
