<!DOCTYPE html>

<html lang="en">

<head>
    <title>Penalty</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    <link rel="stylesheet" href="{{ asset('css/penalty.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <!-- PAGE HEADER -->
    @include('partials._pageheader')

    <div class="ctnmain">
        <div class="header">
            <span class="pagetitle">Penalty</span>
            <button type="button" id="btnaddpenalty" onclick="togglepenaltyform()">Create Penalty</button>
        </div>
        <div class="ctnfilter">
            <form action="#" class="filterform">
                <span class="filtertitle">Filter Result</span>
                <div class="filtermenu">
                    <span class="filterlabel">School Level</span>
                    <div class="filteroptions">
                        <label class="lbloptions">
                            <input type="checkbox" id="inyearall" checked>
                            All
                        </label>
                        <label class="lbloptions">
                            <input type="checkbox" id="incollege">
                            College
                        </label>
                        <label class="lbloptions">
                            <input type="checkbox" id="inseniorhigh">
                            Senior High
                        </label>
                        <label class="lbloptions">
                            <input type="checkbox" id="injuniorhigh">
                            Junior High
                        </label>
                        <label class="lbloptions">
                            <input type="checkbox" id="inelementary">
                            Elementary
                        </label>
                    </div>
                </div>
                <div class="filtermenu">
                    <span class="filterlabel">Remark</span>
                    <div class="filteroptions">
                        <label class="lbloptions">
                            <input type="radio" id="inremarkall" name="remark" checked>
                            All
                        </label>
                        <label class="lbloptions">
                            <input type="radio" id="inoffense1" name="remark">
                            1st Offense
                        </label>
                        <label class="lbloptions">
                            <input type="radio" id="inoffense2" name="remark">
                            2nd Offense
                        </label>
                        <label class="lbloptions">
                            <input type="radio" id="inoffense3" name="remark">
                            3rd Offense
                        </label>
                        <label class="lbloptions">
                            <input type="radio" id="inoffense4" name="remark">
                            4th Offense
                        </label>
                    </div>
                </div>
                <button type="submit" id="btnapply">Apply</button>
            </form>
        </div>
        <div class="ctntable table-responsive">
            <table class="table table-bordered" id="tblpenalty">
                <thead>
                    <tr>
                        <th class="text-center align-middle">#</th>
                        <th class="text-center align-middle">Scholar's Name</th>
                        <th class="text-center align-middle">Condition</th>
                        <th class="text-center align-middle">Remark</th>
                        <th class="text-center align-middle">Date</th>
                        <th class="text-center align-middle">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($penalties as $index => $penalty)
                        <tr>
                            <td class="text-center align-middle">{{ $index + 1 }}</td>
                            @foreach ($scholars as $scholar)
                                @if ($letter->caseCode == $scholar->caseCode)
                                    <td class="text-center align-middle">{{ $scholar->basicInfo->scLastname }},
                                        {{ $scholar->basicInfo->scFirstname }} {{ $scholar->basicInfo->scMiddlename }}
                                    </td>
                                @endif
                            @endforeach
                            <td class="text-center align-middle">{{ $penalty->condition }}</td>
                            <td class="text-center align-middle">{{ $penalty->remark }}</td>
                            <td class="text-center align-middle">{{ $penalty->dateofpenalty }}</td>
                            <td class="text-center align-middle">
                                <a href="#" class="btn btn-primary">View</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="ctnpenaltyform" id="ctnpenaltyform">
        <div class="groupA">
            <span class="formtitle">New penalty</span>
            <button type="button" id="btnclose" onclick="togglepenaltyform()">
                <i class="fas fa-xmark"></i>
            </button>
        </div>
        <div class="groupB">
            <form action="#">
                <div class="groupB1">
                    <label class="formlabel">Scholar ID</label>
                    <input list="scholarid" id="inscholarid" required>
                    <datalist id="scholarid"></datalist>
                    <label class="formlabel">Condition</label>
                    <select id="incondition" required>
                        <option value="" disabled selected>Select a Condition</option>
                        <option value="Lost Cash Card">Lost Cash Card</option>
                        <option value="Dress Code Violation">Dress Code Violation</option>
                    </select>
                    <label class="formlabel">Remark</label>
                    <select id="inremark" required>
                        <option value="" disabled selected>Select a Remark</option>
                        <option value="1st Offense">1st Offense</option>
                        <option value="2nd Offense">2nd Offense</option>
                        <option value="3rd Offense">3rd Offense</option>
                        <option value="4th Offense">4th Offense</option>
                    </select>
                    <label class="formlabel">Date</label>
                    <input type="date" id="indate" required>
                </div>
                <span class="formnote">The scholar will be notified of this penalty once submitted.</span>
                <button type="submit" id="btnsubmit">Submit</button>
            </form>
        </div>
    </div>

    <script src="{{ asset('js/headercontrol.js') }}"></script>
    <script src="{{ asset('js/togglepenaltyform.js') }}"></script>
</body>

</html>
