<!DOCTYPE html>
<html lang="en">

<head>
    <title>Profile</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
    <link rel="stylesheet" href="{{ asset('css/partial.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>
    <!-- Include Sidebar -->
    @include('partials._sidebar')

    <!-- Include Navbar -->
    @include('partials._navbar')
    <x-alert />
    <div class="ctn-main container">
        <a href="{{ route('schome') }}" class="goback">&lt Go back</a>

        <form action="{{ route('manageprofile.post') }}" method="POST">
            @csrf
            <div class="row d-flex justify-content-between align-items-center my-2">
                <div class="col">
                    <h1 class="h1 fw-bold">My Profile</h1>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn-edit fw-bold">Save Profile</button>
                </div>
            </div>
            <div class="card bg-light border border-success p-4">
                <div class="profile-info">
                    <h5 class="mb-3">SCHOLARSHIP INFORMATION</h5>
                    <div class="container">
                        <div class="row mb-2">
                            <div class="col-md-2">Case Code</div>
                            <span class="col-md-10">{{ $data->caseCode }}</span>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-2">Assigned Area</div>
                            <span class="col-md-10">{{ $data->scholarshipinfo->area }}</span>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-2">Scholar Type</div>
                            <span class="col-md-10">{{ $data->scholarshipinfo->scholartype }}</span>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-2">Start of Scholarship</div>
                            <span class="col-md-10">
                                {{ \Carbon\Carbon::parse($data->scholarshipinfo->startdate)->format('F j, Y') }}</span>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-2">End of Scholarship</div>
                            <span class="col-md-10">
                                {{ \Carbon\Carbon::parse($data->scholarshipinfo->enddate)->format('F j, Y') }}</span>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-2">Scholarship Status</div>
                            <span class="col-md-10">{{ $data->scholarshipinfo->scholarshipstatus }}</span>
                        </div>
                    </div>
                </div>

                <div class="profile-info">
                    <h5 class="mb-3">BASIC INFORMATION</h5>
                    <div class="container">
                        <div class="row mb-2">
                            <div class="col-md-2">Name</div>
                            <span class="col-md-10">{{ $data->basicInfo->scLastname }},
                                {{ $data->basicInfo->scFirstname }} {{ $data->basicInfo->scMiddlename }}</span>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-2">Chinese Name</div>
                            <span class="col-md-10">{{ $data->basicInfo->scChinesename }}</span>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-2">Date of Birth</div>
                            <span class="col-md-10">
                                {{ \Carbon\Carbon::parse($data->basicInfo->scDateOfBirth)->format('F j, Y') }}</span>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-2">Age</div>
                            <span class="col-md-10">{{ $data->basicInfo->scAge }}</span>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-2">Sex</div>
                            <span class="col-md-10">{{ $data->basicInfo->scSex }}</span>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-2">Is Indigenous?</div>
                            <span class="col-md-10">{{ $data->basicInfo->scIsIndigenous }}</span>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-2">Indigenous Group</div>
                            <span class="col-md-10">{{ $data->basicInfo->scIndigenousgroup }}</span>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-2">Occupation</div>
                            <div class="col-md-10">
                                <input type="text" name="scOccupation" class="form-control"
                                    value="{{ old('scOccupation', $data->basicInfo->scOccupation) }}">
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-2">Income</div>
                            <div class="col-md-10">
                                <input type="number" min="0" step="0.01" name="scIncome" class="form-control"
                                    value="{{ old('scIncome', $data->basicInfo->scIncome) }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="profile-info">
                    <h5 class="mb-3">CONTACT INFORMATION</h5>
                    <div class="container">
                        <div class="row mb-2">
                            <div class="col-md-2">Facebook Profile Link</div>
                            <div class="col-md-10">
                                <input type="text" name="scFblink" class="form-control"
                                    value="{{ old('scFblink', $data->basicInfo->scFblink) }}">
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-2">Email Address</div>
                            <div class="col-md-10">
                                <input type="email" name="scEmail" class="form-control"
                                    value="{{ old('scEmail', $data->scEmail) }}">
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-2">Contact Number</div>
                            <div class="col-md-10">
                                <input type="tel" minlength="11" maxlength="12" class="form-control"
                                    pattern="^(09\d{9}|63\d{10})$" name="scPhoneNum"
                                    value="{{ old('scPhoneNum', $data->scPhoneNum) }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="profile-info">
                    <h5 class="mb-3">CLOTHING INFORMATION</h5>
                    <div class="container">
                        <div class="row mb-2">
                            <div class="col-md-2">T-Shirt Size</div>
                            <div class="col-md-10">{{ $data->clothingSize->scTShirtSize }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-2">Shoe Size</div>
                            <div class="col-md-10">{{ $data->clothingSize->scShoesSize }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-2">Slippers Size</div>
                            <div class="col-md-10">{{ $data->clothingSize->scSlipperSize }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-2">Pants Size</div>
                            <div class="col-md-10">{{ $data->clothingSize->scPantsSize }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-2">Jogging Pants Size</div>
                            <div class="col-md-10">{{ $data->clothingSize->scJoggingPantSize }}</div>
                        </div>
                    </div>
                </div>

                {{-- <div class="profile-info">
                    <h5>ACCOUNT INFORMATION</h5>
                    <div class="info">
                        <div class="label">Account Number</div>
                        <div class="value">: <span>wala eto sa database na ginawa ko pasensya na</span></div>

                        <div class="label">Card Number</div>
                        <div class="value">: <span>wala eto sa database na ginawa ko pasensya na</span></div>
                    </div>
                </div> --}}

                <div class="profile-info">
                    <h5 class="mb-3">ADDRESS INFORMATION</h5>
                    <div class="container">
                        <div class="row mb-2">
                            <div class="col-md-2">Home Address</div>
                            <div class="col-md-10">
                                <input type="text" name="scResidential" class="form-control"
                                    value="{{ old('scResidential', $data->addressinfo->scResidential) }}">
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-2">Region</div>
                            <div class="col-md-10">
                                <select id="region" name="scRegion" class="form-select" required>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-2">City/Municipality</div>
                            <div class="col-md-10">
                                <select id="city" name="scCity" required class="form-select">
                                </select>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-2">Barangay</div>
                            <div class="col-md-10">
                                <select id="barangay" name="scBarangay" class="form-select" required>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const regionSelect = document.getElementById('region');
                        const citySelect = document.getElementById('city');
                        const barangaySelect = document.getElementById('barangay');
                        const selectedRegion = "{{ $data->addressinfo->scRegion ?? '' }}";
                        const selectedCity = "{{ $data->addressinfo->scCity ?? '' }}";
                        const selectedBarangay = "{{ $data->addressinfo->scBarangay ?? '' }}";

                        // Populate regions on page load
                        loadRegions();

                        // Event listener for region change to load cities
                        regionSelect.addEventListener('change', function() {
                            const regionCode = regionSelect.value; // Corrected `RegionSelect` to `regionSelect`
                            loadCities(regionCode);
                        });

                        // Event listener for city change to load barangays
                        citySelect.addEventListener('change', function() {
                            const cityCode = citySelect.value;
                            loadBarangays(cityCode);
                        });

                        // Function to load regions from the PSGC API
                        function loadRegions() {
                            fetch('https://psgc.gitlab.io/api/regions/')
                                .then(response => response.json())
                                .then(data => {
                                    // Sort regions alphabetically by name
                                    data.sort((a, b) => a.name.localeCompare(b.name));

                                    // Populate the region dropdown
                                    data.forEach(region => {
                                        const option = document.createElement('option');
                                        option.value = region.code; // Use code as value
                                        option.textContent = region.name; // Display name

                                        // Check if this is the selected region
                                        if (region.code === selectedRegion) {
                                            option.selected = true;
                                        }

                                        regionSelect.appendChild(option);
                                    });

                                    // Trigger the city load if a region is preselected
                                    if (selectedRegion) {
                                        loadCities(selectedRegion);
                                    }
                                })
                                .catch(error => console.error('Error loading regions:', error));
                        }

                        // Function to load cities based on selected region code from the PSGC API
                        function loadCities(regionCode) {
                            // Clear existing city options
                            citySelect.innerHTML = '<option value="" disabled selected>Select City/Municipality</option>';
                            barangaySelect.innerHTML =
                                '<option value="" disabled selected>Select Barangay</option>'; // Clear barangays as well

                            fetch(`https://psgc.gitlab.io/api/regions/${regionCode}/cities-municipalities/`)
                                .then(response => response.json())
                                .then(data => {
                                    // Sort cities alphabetically by name
                                    data.sort((a, b) => a.name.localeCompare(b.name));

                                    // Populate the city dropdown
                                    data.forEach(city => {
                                        const option = document.createElement('option');
                                        option.value = city.code; // Use code as value
                                        option.textContent = city.name; // Display name

                                        // Check if this is the selected city
                                        if (city.code === selectedCity) {
                                            option.selected = true;
                                        }

                                        citySelect.appendChild(option);
                                    });

                                    // Trigger the barangay load if a city is preselected
                                    if (selectedCity) {
                                        loadBarangays(selectedCity);
                                    }
                                })
                                .catch(error => console.error('Error loading cities:', error));
                        }

                        // Function to load barangays based on selected city code from the PSGC API
                        function loadBarangays(cityCode) {
                            // Clear existing barangay options
                            barangaySelect.innerHTML = '<option value="" disabled selected>Select Barangay</option>';

                            fetch(`https://psgc.gitlab.io/api/cities-municipalities/${cityCode}/barangays/`)
                                .then(response => response.json())
                                .then(data => {
                                    // Sort barangays alphabetically by name
                                    data.sort((a, b) => a.name.localeCompare(b.name));

                                    // Populate the barangay dropdown
                                    data.forEach(barangay => {
                                        const option = document.createElement('option');
                                        option.value = barangay.code; // Use code as value
                                        option.textContent = barangay.name; // Display name

                                        // Check if this is the selected barangay
                                        if (barangay.code === selectedBarangay) {
                                            option.selected = true;
                                        }

                                        barangaySelect.appendChild(option);
                                    });
                                })
                                .catch(error => console.error('Error loading barangays:', error));
                        }
                    });
                </script>


                <div class="profile-info">
                    <h5 class="mb-3">EMERGENCY CONTACT</h5>
                    <div class="container">
                        {{-- change --}}
                        <div class="row mb-2">
                            <div class="col-md-2">Name</div>
                            <div class="col-md-10">
                                <input type="text" maxlength="50" name="scGuardianName" class="form-control"
                                    value="{{ old('scGuardianName', $data->basicInfo->scGuardianName) }}">
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-2">Relation</div>
                            <div class="col-md-10">
                                <input type="text" maxlength="50" name="scRelationToGuardian"
                                    class="form-control"
                                    value="{{ old('scRelationToGuardian', $data->basicInfo->scRelationToGuardian) }}">
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-2">Email Address</div>
                            <div class="col-md-10">
                                <input type="email" maxlength="255" name="scGuardianEmailAddress"
                                    class="form-control"
                                    value="{{ old('scGuardianEmailAddress', $data->basicInfo->scGuardianEmailAddress) }}">
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-2">Contact Number</div>
                            <div class="col-md-10">
                                <input type="tel" minlength="11" maxlength="12" name="scGuardianPhoneNumber"
                                    class="form-control" pattern="^(09\d{9}|63\d{10})$"
                                    value="{{ old('scGuardianPhoneNumber', $data->basicInfo->scGuardianPhoneNumber) }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="profile-info">
                    <h5 class="mb-3">EDUCATIONAL BACKGROUND</h5>
                    <div class="container">
                        <div class="row mb-2">
                            <div class="col-md-2">School Level</div>
                            <span class="col-md-10">
                                {{ $data->education->scSchoolLevel }}
                            </span>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-2">School Name</div>
                            <span class="col-md-10">
                                {{ $data->education->scSchoolName }}
                            </span>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-2">
                                @if ($data->education->scSchoolLevel == 'College')
                                    Year
                                @else
                                    Grade
                                @endif
                                Level
                            </div>
                            <span class="col-md-10">
                                {{ $data->education->scYearGrade }}
                            </span>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-2">
                                @if ($data->education->scSchoolLevel == 'College')
                                    Course
                                @elseif ($data->education->scSchoolLevel == 'Senior High')
                                    Strand
                                @else
                                    Section
                                @endif
                            </div>
                            <span class="col-md-10">
                                {{ $data->education->scCourseStrandSec }}
                            </span>
                        </div>


                        @if ($data->education->scSchoolLevel == 'College')
                            <div class="row mb-2">
                                <div class="col-md-2">College Department</div>
                                <span class="col-md-10">
                                    {{ $data->education->scCollegedept }}
                                </span>
                            </div>
                        @endif
                        <div class="row mb-2">
                            <div class="col-md-2">Academic Year</div>
                            <span class="col-md-10">
                                {{ $data->education->scAcademicYear }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </form>


        <script src="{{ asset('js/scholar.js') }}"></script>
</body>

</html>
