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
                                    <h4 class="card-title">{{ isset($candidate) ? 'Edit' : 'Create' }}Candidate</h4>
                                </div>
                                <div class="card-body">
                                    <form class="form"
                                        action="{{ isset($candidate) ? route('candidate.update', [$candidate->id]) : route('candidate.store') }}"
                                        method="POST" enctype="multipart/form-data">
                                        @csrf
                                        @isset($candidate)
                                            @method('put')
                                        @endisset
                                        <div class="row">
                                            <div class="col-md-3 col-12">
                                                <div class="form-group">
                                                    <label for="candidate_name">Candidate Name</label>
                                                    <input type="text"{{ isset($candidate) ? '' : 'required' }}
                                                        value="{{ isset($candidate) ? $candidate->candidate_name : '' }}"
                                                        id="name" class="form-control" placeholder="Candidate Name"
                                                        name="name" />
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-12">
                                                <div class="form-group">
                                                    <label for="votter_party_id">Voter Party</label>
                                                    <select class="form-control" id="votter_party_id"
                                                        name="votter_party_id" required>
                                                        <option>Select Voter Party</option>
                                                        @foreach ($votter_party as $party)
                                                            {{-- <option value="{{ $party->id }}">{{ $party->party_name }}
                                                            </option> --}}
                                                            <option value="{{ $party->id }}" {{ isset($candidate) && $candidate->votter_party_id == $party->id ? 'selected' : '' }}>
                                                                {{ $party->party_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-12">
                                                <div class="form-group">
                                                    <label for="dob">Date of Birth</label>
                                                    <input type="text"{{ isset($candidate) ? '' : 'required' }}
                                                        value="{{ isset($candidate) ? $candidate->dob : '' }}"
                                                        id="dob" class="form-control" name="dob"
                                                        placeholder="Date of birth" />
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-12">
                                                <div class="form-group">
                                                    <label for="birth_place">Birth Place</label>
                                                    <input type="text"{{ isset($candidate) ? '' : 'required' }}
                                                        value="{{ isset($candidate) ? $candidate->birth_place : '' }}"
                                                        id="birth_place" class="form-control" name="birth_place"
                                                        placeholder="Enter Birth place please" />
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-12">
                                                <div class="form-group">
                                                    <label for="occupation">Occupation</label>
                                                    <input type="text"{{ isset($candidate) ? '' : 'required' }}
                                                        value="{{ isset($candidate) ? $candidate->occupation : '' }}"
                                                        id="occupation" class="form-control" name="occupation"
                                                        placeholder="Enter occupation please" />
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-12">
                                                <div class="form-group">
                                                    <label for="position">occupation1</label>
                                                    <input type="text"{{ isset($candidate) ? '' : '' }}
                                                        value="{{ isset($candidate) ? $candidate->occupation_1 : '' }}"
                                                        id="occupation_1" class="form-control" name="occupation_1"
                                                        placeholder="Enter position please" />
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-12">
                                                <div class="form-group">
                                                    <label for="position">Position</label>
                                                    <input type="text"
                                                        value="{{ isset($candidate) ? json_encode($candidate->position) : '' }}"
                                                        id="position" class="form-control" name="position"
                                                        placeholder="Enter position please" />
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-12">
                                                <div class="form-group">
                                                    <label for="position">Position1</label>
                                                    <input type="text"
                                                        value="{{ isset($candidate) ? json_encode($candidate->position_1) : '' }}"
                                                        id="position" class="form-control" name="position_1"
                                                        placeholder="Enter position 1 please" />
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-12">
                                                <div class="form-group">
                                                    <label for="position">Order</label>
                                                    <input type="text"{{ isset($candidate) ? '' : 'required' }}
                                                        value="{{ isset($candidate) ? $candidate->order : '' }}"
                                                        id="position" class="form-control" name="order"
                                                        placeholder="Enter Order please" />
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label for="candidate_image" class="form-label">Upload Candidate
                                                        Image</label>
                                                    <input class="form-control" type="file" name="candidate_image"
                                                        {{ isset($candidate) ? '' : '' }}
                                                        value="{{ isset($candidate) ? $candidate->candidate_image : '' }}"
                                                        id="candidate_image">
                                                    @if (isset($candidate) && $candidate->candidate_image)
                                                        <img src="{{ asset($candidate->candidate_image) }}"
                                                            alt="Candidate Image" style="max-width: 100px;">
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-12 text-right">
                                                <button class="btn btn-primary" type="submit"
                                                    style="float:right;">{{ isset($candidate) ? 'update' : 'create' }}</button>
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
