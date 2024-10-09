<!DOCTYPE html>
<html lang="en">

<head>
    <title>Scholarship Overview</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('css/partial.css') }}">
    <link rel="stylesheet" href="{{ asset('css/scholar.css') }}">
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

    <!-- MAIN -->
    <div class="ctn-main">
        <a href="" class="goback">&lt Go back</a>

        <div class="lte-form">
            <div class="lteform-title text-center">
                <h3 class="form-heading fw-bold">LTE Submission</h3>
                <p class="form-desc"><i>You must submit within 3 days after the receipt of the letter</i></p>
            </div>
            <div class="form-body">
                <div class="richtextarea">
                    <p>Explanation Letter</p>

                </div>
                <p><b>Select your reason:</b></p>
                <div class="lte-reason">
                    <div class="reason1">
                        <div class="rad">
                            <input type="radio" id="medical" name="reason" value="Medical"
                                onclick="toggleFileInputs('medical')">
                            <label for="medical">Medical</label>
                        </div>
                        <div class="rad">
                            <input type="radio" id="academic" name="reason" value="Academic Activity"
                                onclick="toggleFileInputs('academic')">
                            <label for="academic">Academic Activity</label>
                        </div>
                    </div>
                    <div class="reason2">
                        <div class="rad">
                            <input type="radio" id="death" name="reason"
                                value="Death of an Immediate Family Member" onclick="toggleFileInputs('death')">
                            <label for="death">Death of an Immediate Family Member</label>
                        </div>
                        <div class="rad">
                            <input type="radio" id="disaster" name="reason"
                                value="Natural and Human induced disasters" onclick="toggleFileInputs('disaster')">
                            <label for="disaster">Natural and Human induced disasters</label>
                        </div>
                    </div>
                </div>

                <p><b>Please upload here the necessary document:</b></p>
                <div class="file-upload">
                    <div class="file1">
                        <div class="medfile">
                            <label for="medical-file">Photocopy of Medical or Doctor's Certificate</label><br>
                            <input type="file" id="medical-file" disabled>
                        </div>
                        <div class="acadfile">
                            <label for="academic-file">Duly Signed Letter<br>(School
                                Official/Chairperson/Professor)</label><br>
                            <input type="file" id="academic-file" disabled>
                        </div>
                    </div>
                    <div class="file2">
                        <div class="deathfile">
                            <label for="death-file">Photocopy of Death Certificate</label><br>
                            <input type="file" id="death-file" disabled>
                        </div>
                        <div class="disfile">
                            <label for="disaster-file">Proof of Calamity<br>(Photo/News Clipping/LGU
                                Declaration)</label><br>
                            <input type="file" id="disaster-file" disabled>
                        </div>
                    </div>
                </div>
            </div>
            <div class="agreement">
                <input type="checkbox" id="agreement">
                <label for="agreement"><i>I hereby attest that the information I have provided is true and correct.
                        I also give my consent to Tzu Chi Foundation to obtain, retain and verify my letter.</i></label>
            </div>

            <div class="submit-lte text-center">
                <button type="submit" class="btn-submit fw-bold m-4">Submit</button>
            </div>
        </div>


    </div>

    <script src="{{ asset('js/scholar.js') }}"></script>

    <script>
        function toggleFileInputs(reason) {
            // Disable all file inputs initially
            document.getElementById('medical-file').disabled = true;
            document.getElementById('academic-file').disabled = true;
            document.getElementById('death-file').disabled = true;
            document.getElementById('disaster-file').disabled = true;

            // Enable file inputs based on the selected reason
            if (reason === 'medical') {
                document.getElementById('medical-file').disabled = false;
            } else if (reason === 'academic') {
                document.getElementById('academic-file').disabled = false;
            } else if (reason === 'death') {
                document.getElementById('death-file').disabled = false;
            } else if (reason === 'disaster') {
                document.getElementById('disaster-file').disabled = false;
            }
        }
    </script>
</body>

</html>
