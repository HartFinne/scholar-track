<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\courses;
use App\Models\institutions;
use App\Models\applicants;
use App\Models\apceducation;
use App\Models\apeheducation;
use App\Models\apotherinfo;
use App\Models\apfamilyinfo;
use App\Models\apcasedetails;
use App\Models\applicationforms;
use App\Models\ApplicationInstruction;
use App\Models\approgress;
use App\Models\aprequirements;
use App\Models\criteria;
use Illuminate\Console\Application;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class ApplicationController extends Controller
{
    public function showapplicationinstruction()
    {
        $instruction = [
            'College' => ApplicationInstruction::where('schoollevel', 'College')->first(),
            'Senior High' => ApplicationInstruction::where('schoollevel', 'Senior High')->first(),
            'Junior High' => ApplicationInstruction::where('schoollevel', 'Junior High')->first(),
            'Elementary' => ApplicationInstruction::where('schoollevel', 'Elementary')->first(),
        ];
        $courses = courses::where('level', 'College')->get();
        $strands = courses::where('level', 'Senior High')->get();
        $institutions = [
            'College' => institutions::where('schoollevel', 'College')->get(),
            'Senior High' => institutions::where('schoollevel', 'Senior High')->get(),
            'Junior High' => institutions::where('schoollevel', 'Junior High')->get(),
            'Elementary' => institutions::where('schoollevel', 'Elementary')->get(),
        ];
        return view('applicant.appinstructions', compact('instruction', 'courses', 'strands', 'institutions'));
    }

    public function showcollegeapplication()
    {
        $courses = courses::where('level', 'College')->get();
        $institutions = institutions::where('schoollevel', 'College')->orderBy('schoolname', 'ASC')->get();
        $form = applicationforms::where('formname', 'College')->first();

        return view('applicant.applicationformC', compact('courses', 'institutions', 'form'));
    }

    public function showelemhsapplication($level)
    {
        $strands = courses::where('level', 'Senior High')->get();
        $schools = institutions::where('schoollevel', $level)->pluck('schoolname');
        // dd(institutions::where('schoollevel', 'Senior High')->pluck('schoolname'));
        $gradelevels = [
            'Senior High' => ['Grade 11', 'Grade 12'],
            'Junior High' => ['Grade 7', 'Grade 8', 'Grade 9', 'Grade 10'],
            'Elementary' => ['Grade 1', 'Grade 2', 'Grade 3', 'Grade 4', 'Grade 5', 'Grade 6'],
        ];

        $form = applicationforms::where('formname', $level)->first();

        return view('applicant.applicationformHE', compact('strands', 'schools', 'form', 'gradelevels', 'level'));
    }

    public function saveapplicant(Request $request)
    {
        // dd($request->all());
        $emailExists = applicants::where('email', $request->email)->exists();

        if ($emailExists) {
            return redirect()->back()->with('failure', 'Your application was unsuccessful because the email address is already in use. If you believe this is an error, please contact us at inquiriescholartrack@gmail.com.')->withInput();
        }

        DB::beginTransaction();
        try {
            $request->validate([
                // Applicant info
                'scholarname' => 'string|max:255',
                'chinesename' => 'string|max:255',
                'sex' => '',
                'age' => 'integer',
                'birthdate' => 'date',
                'homeaddress' => 'string|max:255',
                'region' => 'string|max:50',
                'barangay' => 'string|max:50',
                'city' => 'string|max:50',
                'email' => 'email|max:255',
                'phonenum' => 'digits_between:11,12',
                'occupation' => 'string|max:100',
                'income' => 'numeric|min:0',
                'fblink' => 'url',
                'isIndigenous' => 'required',
                'indigenousgroup' => 'required_if:isIndigenous,Yes|max:100',
                // Father info
                'fname' => 'string|max:255',
                'fage' => 'integer',
                'fsex' => 'required',
                'fbirthdate' => 'date',
                'freligion' => 'string|max:100',
                'fattainment' => 'string|max:100',
                'foccupation' => 'string|max:100',
                'fcompany' => 'string|max:100',
                'fincome' => 'numeric|min:0',
                // Mother info
                'mname' => 'string|max:255',
                'mage' => 'integer',
                'msex' => 'required',
                'mbirthdate' => 'date',
                'mreligion' => 'string|max:100',
                'mattainment' => 'string|max:100',
                'moccupation' => 'string|max:100',
                'mcompany' => 'string|max:100',
                'mincome' => 'numeric|min:0',
                // Other info
                'grant' => 'string|max:255',
                'talent' => 'string|max:255',
                'expectation' => 'string|max:255',
                // Required documents
                'idpic'          => ['mimes:jpeg,png', 'max:2048'],
                'reportcard'     => ['mimes:jpeg,png,pdf', 'max:2048'],
                'regiform'       => ['mimes:jpeg,png,pdf', 'max:2048'],
                'autobiography'  => ['mimes:pdf', 'max:2048'],
                'familypic'      => ['mimes:jpeg,png', 'max:2048'],
                'insidehouse'    => ['mimes:jpeg,png', 'max:2048'],
                'outsidehouse'   => ['mimes:jpeg,png', 'max:2048'],
                'utility'        => ['mimes:jpeg,png,pdf', 'max:2048'],
                'sketchmap'      => ['mimes:jpeg,png,pdf', 'max:2048'],
                'payslip'        => ['mimes:jpeg,png,pdf', 'max:2048'],
                'indigencycert'  => ['mimes:jpeg,png,pdf', 'max:2048'],
            ], [
                'scholarname.string' => 'The scholar name must be a valid string.',
                'scholarname.max' => 'The scholar name may not be greater than 255 characters.',
                'chinesename.string' => 'The Chinese name must be a valid string.',
                'chinesename.max' => 'The Chinese name may not be greater than 255 characters.',
                'age.integer' => 'The age must be a number.',
                'birthdate.date' => 'The birthdate must be a valid date.',
                'homeaddress.string' => 'The home address must be a valid string.',
                'homeaddress.max' => 'The home address may not be greater than 255 characters.',
                'barangay.string' => 'The barangay must be a valid string.',
                'barangay.max' => 'The barangay may not be greater than 50 characters.',
                'city.string' => 'The city must be a valid string.',
                'city.max' => 'The city may not be greater than 50 characters.',
                'email.email' => 'The email address must be a valid email address.',
                'email.max' => 'The email may not be greater than 255 characters.',
                'phonenum.string' => 'The phone number must be a valid string.',
                'phonenum.max' => 'The phone number may not be longer than 11 characters.',
                'occupation.string' => 'The occupation must be a valid string.',
                'occupation.max' => 'The occupation may not be greater than 100 characters.',
                'income.numeric' => 'The income must be a number.',
                'income.min' => 'The income must be at least 0.',
                'fblink.url' => 'The Facebook link must be a valid URL.',
                'indigenousgroup.required_if' => 'The indigenous group field is required when you are a member of an indigenous group.',
                'indigenousgroup.max' => 'The indigenous group name may not be greater than 100 characters.',
                // Father info
                'fname.string' => 'The father\'s name must be a valid string.',
                'fname.max' => 'The father\'s name may not be greater than 255 characters.',
                'fage.integer' => 'The father\'s age must be a number.',
                'fbirthdate.date' => 'The father\'s birthdate must be a valid date.',
                'freligion.string' => 'The father\'s religion must be a valid string.',
                'freligion.max' => 'The father\'s religion may not be greater than 100 characters.',
                'fattainment.string' => 'The father\'s educational attainment must be a valid string.',
                'fattainment.max' => 'The father\'s educational attainment may not be greater than 100 characters.',
                'foccupation.string' => 'The father\'s occupation must be a valid string.',
                'foccupation.max' => 'The father\'s occupation may not be greater than 100 characters.',
                'fcompany.string' => 'The father\'s company name must be a valid string.',
                'fcompany.max' => 'The father\'s company name may not be greater than 100 characters.',
                'fincome.numeric' => 'The father\'s income must be a number.',
                'fincome.min' => 'The father\'s income must be at least 0.',
                // Mother info
                'mname.string' => 'The mother\'s name must be a valid string.',
                'mname.max' => 'The mother\'s name may not be greater than 255 characters.',
                'mage.integer' => 'The mother\'s age must be a number.',
                'mbirthdate.date' => 'The mother\'s birthdate must be a valid date.',
                'mreligion.string' => 'The mother\'s religion must be a valid string.',
                'mreligion.max' => 'The mother\'s religion may not be greater than 100 characters.',
                'mattainment.string' => 'The mother\'s educational attainment must be a valid string.',
                'mattainment.max' => 'The mother\'s educational attainment may not be greater than 100 characters.',
                'moccupation.string' => 'The mother\'s occupation must be a valid string.',
                'moccupation.max' => 'The mother\'s occupation may not be greater than 100 characters.',
                'mcompany.string' => 'The mother\'s company name must be a valid string.',
                'mcompany.max' => 'The mother\'s company name may not be greater than 100 characters.',
                'mincome.numeric' => 'The mother\'s income must be a number.',
                'mincome.min' => 'The mother\'s income must be at least 0.',
                // Other info
                'grant.string' => 'The grant details must be a valid string.',
                'grant.max' => 'The grant details may not be greater than 255 characters.',
                'talent.string' => 'The talent details must be a valid string.',
                'talent.max' => 'The talent details may not be greater than 255 characters.',
                'expectation.string' => 'The expectations must be a valid string.',
                'expectation.max' => 'The expectations may not be greater than 255 characters.',
                // Custom error messages for documents with file size limits
                'idpic.mimes' => 'The ID picture must be a valid image file (jpeg, jpg, png).',
                'idpic.max' => 'The ID picture must not exceed 2 MB.',

                'reportcard.mimes' => 'The report card must be a valid file (jpeg, jpg, png, or pdf).',
                'reportcard.max' => 'The report card must not exceed 2 MB.',

                'regiform.mimes' => 'The registration form must be a valid file (jpeg, jpg, png, or pdf).',
                'regiform.max' => 'The registration form must not exceed 2 MB.',

                'autobiography.mimes' => 'The autobiography must be a PDF file.',
                'autobiography.max' => 'The autobiography must not exceed 2 MB.',

                'familypic.mimes' => 'The family picture must be a valid image file (jpeg, jpg, or png).',
                'familypic.max' => 'The family picture must not exceed 2 MB.',

                'insidehouse.mimes' => 'The image of the inside of the house must be a valid image file (jpeg, jpg, or png).',
                'insidehouse.max' => 'The image of the inside of the house must not exceed 2 MB.',

                'outsidehouse.mimes' => 'The image of the outside of the house must be a valid image file (jpeg, jpg, or png).',
                'outsidehouse.max' => 'The image of the outside of the house must not exceed 2 MB.',

                'utility.mimes' => 'The utility bill must be a valid file (jpeg, jpg, png, or pdf).',
                'utility.max' => 'The utility bill must not exceed 2 MB.',

                'sketchmap.mimes' => 'The sketch map must be a valid file (jpeg, jpg, png, or pdf).',
                'sketchmap.max' => 'The sketch map must not exceed 2 MB.',

                'payslip.mimes' => 'The payslip must be a valid file (jpeg, jpg, png, or pdf).',
                'payslip.max' => 'The payslip must not exceed 2 MB.',

                'indigencycert.mimes' => 'The indigency certificate must be a valid file (jpeg, jpg, png, or pdf).',
                'indigencycert.max' => 'The indigency certificate must not exceed 2 MB.',
            ]);

            if ($request->schoollevel != 'College') {
                $request->validate([
                    'schoollevel' => 'required|string|max:25',
                    'incomingyear' => 'required|string|max:15',
                    'schoolname' => 'required|string|max:255',
                    'gwa' => 'required|numeric|min:1|max:100',
                    'gwaconduct' => 'required|string|max:50',
                    'chinesegwa' => 'nullable|numeric|min:1|max:100',
                    'chinesegwaconduct' => 'nullable|string|max:50',
                ], [
                    // Required field validation messages
                    'schoollevel.required' => 'The school level is required.',
                    'incomingyear.required' => 'The incoming grade level is required.',
                    'gwa.required' => 'The General Average is required.',
                    'gwaconduct.required' => 'The conduct is required.',

                    // String validation messages
                    'schoollevel.string' => 'The school level must be a valid string.',
                    'incomingyear.string' => 'The incoming grade level must be a valid string.',
                    'schoolname.string' => 'The school name must be a valid string.',
                    'gwaconduct.string' => 'The conduct must be a valid string.',
                    'chinesegwaconduct.string' => 'The conduct for Chinese subject must be a valid string.',

                    // Max length validation messages
                    'schoollevel.max' => 'The school level must not exceed 25 characters.',
                    'incomingyear.max' => 'The incoming grade level must not exceed 15 characters.',
                    'schoolname.max' => 'The school name must not exceed 255 characters.',
                    'gwaconduct.max' => 'The conduct must not exceed 50 characters.',
                    'chinesegwaconduct.max' => 'The conduct for Chinese subject must not exceed 50 characters.',

                    // Numeric validation messages
                    'gwa.numeric' => 'The General Average must be a valid number.',
                    'gwa.min' => 'The General Average must be at least 1.',
                    'gwa.max' => 'The General Average may not be greater than 100.',

                    'chinesegwa.numeric' => 'The General Average for Chinese subject must be a valid number.',
                    'chinesegwa.min' => 'The General Average for Chinese subject must be at least 1.',
                    'chinesegwa.max' => 'The General Average for Chinese subject may not be greater than 100.',
                ]);

                if ($request->schoollevel == 'Senior High') {
                    $request->validate([
                        'strand' => 'required_if:schoollevel,Senior High|string|max:100',
                    ], [
                        'strand.max' => 'The strand must not exceed 100 characters.',
                        'strand.required' => 'The strand is required for Senior High.',
                        'strand.string' => 'The strand must be a valid string.',
                    ]);
                } else {
                    $request->validate([
                        'section' => 'required_if:schoollevel,Junior High,Elementary|string|max:50',
                    ], [
                        'section.max' => 'The section must not exceed 50 characters.',
                        'section.required' => 'The section is required for Junior High and Elementary.',
                        'section.string' => 'The section must be a valid string.',
                    ]);
                }
            } else {
                $request->validate([
                    'schoolname' => 'required|string|max:255',
                    'collegedept' => 'required|string|max:255',
                    'incomingyear' => 'required|string|max:15',
                    'course' => 'required|string|max:255',
                    'gwa' => 'required|numeric|min:1|max:5',
                ], [
                    'schoolname.string' => 'The school name must be a valid string.',
                    'schoolname.max' => 'The school name may not be greater than 255 characters.',
                    'collegedept.string' => 'The college department must be a valid string.',
                    'collegedept.max' => 'The college department may not be greater than 255 characters.',
                    'incomingyear.string' => 'The incoming year level must be a valid string.',
                    'course.string' => 'The course must be a valid string.',
                    'course.max' => 'The course may not be greater than 255 characters.',
                    'gwa.numeric' => 'The GWA must be a number.',
                    'gwa.min' => 'The GWA must be at least 1.',
                    'gwa.max' => 'The GWA may not be greater than 5.',
                ]);
            }

            // Sibling info
            if ($request->siblingcount > 0) {
                $rules = [];
                $messages = [];

                for ($i = 1; $i <= $request->siblingcount; $i++) {
                    // Base rules
                    $rules['sname.' . $i] = 'nullable|string|max:255';

                    // Other fields are required only if sname.# is not null
                    $rules['sage.' . $i] = 'required_with:sname.' . $i . '|integer';
                    $rules['sbirthdate.' . $i] = 'required_with:sname.' . $i . '|date';
                    $rules['sreligion.' . $i] = 'required_with:sname.' . $i . '|string|max:100';
                    $rules['sattainment.' . $i] = 'required_with:sname.' . $i . '|string|max:100';
                    $rules['soccupation.' . $i] = 'required_with:sname.' . $i . '|string|max:100';
                    $rules['scompany.' . $i] = 'required_with:sname.' . $i . '|string|max:100';
                    $rules['sincome.' . $i] = 'required_with:sname.' . $i . '|numeric|min:0';

                    // Custom messages
                    $messages['sname.' . $i . '.string'] = 'The name for sibling ' . $i . ' must be a valid string.';
                    $messages['sname.' . $i . '.max'] = 'The name for sibling ' . $i . ' may not be greater than 255 characters.';

                    $messages['sage.' . $i . '.required_with'] = 'The age for sibling ' . $i . ' is required when the name is provided.';
                    $messages['sage.' . $i . '.integer'] = 'The age for sibling ' . $i . ' must be a number.';

                    $messages['sbirthdate.' . $i . '.required_with'] = 'The birthdate for sibling ' . $i . ' is required when the name is provided.';
                    $messages['sbirthdate.' . $i . '.date'] = 'The birthdate for sibling ' . $i . ' must be a valid date.';

                    $messages['sreligion.' . $i . '.required_with'] = 'The religion for sibling ' . $i . ' is required when the name is provided.';
                    $messages['sreligion.' . $i . '.string'] = 'The religion for sibling ' . $i . ' must be a valid string.';
                    $messages['sreligion.' . $i . '.max'] = 'The religion for sibling ' . $i . ' may not be greater than 100 characters.';

                    $messages['sattainment.' . $i . '.required_with'] = 'The educational attainment for sibling ' . $i . ' is required when the name is provided.';
                    $messages['sattainment.' . $i . '.string'] = 'The educational attainment for sibling ' . $i . ' must be a valid string.';
                    $messages['sattainment.' . $i . '.max'] = 'The educational attainment for sibling ' . $i . ' may not be greater than 100 characters.';

                    $messages['soccupation.' . $i . '.required_with'] = 'The occupation for sibling ' . $i . ' is required when the name is provided.';
                    $messages['soccupation.' . $i . '.string'] = 'The occupation for sibling ' . $i . ' must be a valid string.';
                    $messages['soccupation.' . $i . '.max'] = 'The occupation for sibling ' . $i . ' may not be greater than 100 characters.';

                    $messages['scompany.' . $i . '.required_with'] = 'The company name for sibling ' . $i . ' is required when the name is provided.';
                    $messages['scompany.' . $i . '.string'] = 'The company name for sibling ' . $i . ' must be a valid string.';
                    $messages['scompany.' . $i . '.max'] = 'The company name for sibling ' . $i . ' may not be greater than 100 characters.';

                    $messages['sincome.' . $i . '.required_with'] = 'The income for sibling ' . $i . ' is required when the name is provided.';
                    $messages['sincome.' . $i . '.numeric'] = 'The income for sibling ' . $i . ' must be a number.';
                    $messages['sincome.' . $i . '.min'] = 'The income for sibling ' . $i . ' must be at least 0.';
                }


                $request->validate($rules, $messages);
            }

            $casecode = $this->generatecasecode($request->incomingyear);
            $sincome = array_sum($request->sincome);

            $prioritylevel = $this->determinePriorityLevel(
                $request->schoollevel,
                [
                    'applicant income' => $request->income,
                    'father income' => $request->fincome,
                    'mother income' => $request->mincome,
                    'sibling income' => $sincome,
                    'gwa' => $request->gwa
                ]
            );

            $parts = explode(' ', strtolower($request->scholarname));
            $password = end($parts) . '.tzuchi';

            $phoneNumber = $request->input('phonenum');

            if (str_starts_with($phoneNumber, '0')) {
                $phoneNumber = '63' . substr($phoneNumber, 1);
            }

            applicants::create([
                'casecode' => $casecode,
                'password' => Hash::make($password),
                'name' => $request->scholarname,
                'chinesename' => $request->chinesename,
                'sex' => $request->sex,
                'age' => $request->age,
                'birthdate' => $request->birthdate,
                'homeaddress' => $request->homeaddress,
                'region' => $request->region,
                'city' => $request->city,
                'barangay' => $request->barangay,
                'email' => $request->email,
                'phonenum' => $phoneNumber,
                'occupation' => $request->occupation,
                'income' => $request->income,
                'fblink' => $request->fblink,
                'isIndigenous' => $request->isIndigenous,
                'indigenousgroup' => $request->indigenousgroup,
                'applicationstatus' => 'Under Review',
                'prioritylevel' => $prioritylevel,
            ]);

            if ($request->schoollevel != 'College') {
                apeheducation::create([
                    'casecode' => $casecode,
                    'schoollevel' => $request->schoollevel,
                    'schoolname' => $request->schoolname,
                    'ingrade' => $request->incomingyear,
                    'strand' => $request->strand ?? NULL,
                    'section' => $request->section ?? NULL,
                    'gwa' => $request->gwa,
                    'gwaconduct' => $request->gwaconduct,
                    'chinsegwa' => $request->chinsegwa ?? NULL,
                    'chinsegwaconduct' => $request->chinsegwaconduct ?? NULL,
                ]);
            } else {
                apceducation::create([
                    'casecode' => $casecode,
                    'univname' => $request->schoolname,
                    'collegedept' => $request->collegedept,
                    'inyear' => $request->incomingyear,
                    'course' => $request->course,
                    'gwa' => $request->gwa,
                ]);
            }

            // Father and mother info
            apfamilyinfo::create([
                'casecode' => $casecode,
                'name' => $request->fname,
                'age' => $request->fage,
                'sex' => $request->fsex,
                'birthdate' => $request->fbirthdate,
                'relationship' => 'Father',
                'religion' => $request->freligion,
                'educattainment' => $request->fattainment,
                'occupation' => $request->foccupation,
                'company' => $request->fcompany,
                'income' => $request->fincome,
            ]);

            apfamilyinfo::create([
                'casecode' => $casecode,
                'name' => $request->mname,
                'age' => $request->mage,
                'sex' => $request->msex,
                'birthdate' => $request->mbirthdate,
                'relationship' => 'Mother',
                'religion' => $request->mreligion,
                'educattainment' => $request->mattainment,
                'occupation' => $request->moccupation,
                'company' => $request->mcompany,
                'income' => $request->mincome,
            ]);

            if (!empty($request->siblingcount) && $request->siblingcount > 0) {
                foreach ($request->sname as $index => $name) {
                    // Skip index 0
                    if ($index == 0) {
                        continue;
                    }

                    // Create new sibling record starting from index 1
                    apfamilyinfo::create([
                        'casecode' => $casecode,
                        'name' => $name,
                        'age' => $request->sage[$index],
                        'sex' => $request->ssex[$index],
                        'birthdate' => $request->sbirthdate[$index],
                        'relationship' => 'Sibling',
                        'religion' => $request->sreligion[$index],
                        'educattainment' => $request->sattainment[$index],
                        'occupation' => $request->soccupation[$index],
                        'company' => $request->scompany[$index],
                        'income' => $request->sincome[$index],
                    ]);
                }
            }

            apotherinfo::create([
                'casecode' => $casecode,
                'grant' => $request->grant,
                'talent' => $request->talent,
                'expectations' => $request->expectation,
            ]);

            approgress::create([
                'casecode' => $casecode,
                'phase' => 'Under Review',
                'status' => null,
                'remark' => null,
                'msg' => null,
            ]);

            // get the files from form
            $idpic = $request->file('idpic');
            $reportcard = $request->file('reportcard');
            $regiform = $request->file('regiform');
            $autobio = $request->file('autobiography');
            $familypic = $request->file('familypic');
            $houseinside = $request->file('insidehouse');
            $houseoutside = $request->file('outsidehouse');
            $utilitybill = $request->file('utility');
            $sketchmap = $request->file('sketchmap');
            $payslip = $request->file('payslip') ?? NULL;
            $indigencycert = $request->file('indigencycert');
            // Create a custom file name using casecode
            $filename_idpic = $casecode . '_' . 'idpic' . '.' . $idpic->extension();
            $filename_reportcard = $casecode . '_' . 'reportcard' . '.' . $reportcard->extension();
            $filename_regiform = $casecode . '_' . 'regiform' . '.' . $regiform->extension();
            $filename_autobio = $casecode . '_' . 'autobio' . '.' . $autobio->extension();
            $filename_familypic = $casecode . '_' . 'familypic' . '.' . $familypic->extension();
            $filename_houseinside = $casecode . '_' . 'houseinside' . '.' . $houseinside->extension();
            $filename_houseoutside = $casecode . '_' . 'houseoutside' . '.' . $houseoutside->extension();
            $filename_utilitybill = $casecode . '_' . 'utilitybill' . '.' . $utilitybill->extension();
            $filename_sketchmap = $casecode . '_' . 'sketchmap' . '.' . $sketchmap->extension();
            if ($payslip) {
                $filename_payslip = $casecode . '_' . 'payslip' . '.' . $payslip->extension();
            }
            $filename_indigencycert = $casecode . '_' . 'indigencycert' . '.' . $indigencycert->extension();
            // Store the file in the specified directory
            $path_idpic = $idpic->storeAs('uploads/application_requirements/id_pics', $filename_idpic, 'public');
            $path_reportcard = $reportcard->storeAs('uploads/application_requirements/report_cards', $filename_reportcard, 'public');
            $path_regiform = $regiform->storeAs('uploads/application_requirements/registration_forms', $filename_regiform, 'public');
            $path_autobio = $autobio->storeAs('uploads/application_requirements/autobiographies', $filename_autobio, 'public');
            $path_familypic = $familypic->storeAs('uploads/application_requirements/family_pics', $filename_familypic, 'public');
            $path_houseinside = $houseinside->storeAs('uploads/application_requirements/house_inside', $filename_houseinside, 'public');
            $path_houseoutside = $houseoutside->storeAs('uploads/application_requirements/house_outside', $filename_houseoutside, 'public');
            $path_utilitybill = $utilitybill->storeAs('uploads/application_requirements/utility_bills', $filename_utilitybill, 'public');
            $path_sketchmap = $sketchmap->storeAs('uploads/application_requirements/sketch_maps', $filename_sketchmap, 'public');
            if ($payslip) {
                $path_payslip = $payslip->storeAs('uploads/application_requirements/payslips', $filename_payslip, 'public');
            }
            $path_indigencycert = $indigencycert->storeAs('uploads/application_requirements/indigency_certs', $filename_indigencycert, 'public');

            aprequirements::create([
                'casecode' => $casecode,
                'idpic' => $path_idpic,
                'reportcard' => $path_reportcard,
                'regiform' => $path_regiform,
                'autobio' => $path_autobio,
                'familypic' => $path_familypic,
                'houseinside' => $path_houseinside,
                'houseoutside' => $path_houseoutside,
                'utilitybill' => $path_utilitybill,
                'sketchmap' => $path_sketchmap,
                'payslip' => $path_payslip ?? NULL,
                'indigencycert' => $path_indigencycert,
            ]);

            DB::commit();

            return redirect()->route('showconfirmation', ['casecode' => $casecode, 'password' => $password]);
        } catch (ValidationException $e) {
            DB::rollback();
            $errors = $e->errors();
            $errorMessages = '<ul>';
            foreach ($errors as $fieldErrors) {
                foreach ($fieldErrors as $errorMessage) {
                    $errorMessages .= '<li>' . $errorMessage . '</li>';
                }
            }
            $errorMessages .= '</ul>';
            return redirect()->back()->with('failure', 'Your application has failed due to the following errors: ' . $errorMessages)->withInput();
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('failure', 'Sorry, your application could not be processed at this time. Please try again later or contact support if the problem persists. ' . $e->getMessage())->withInput();
        }
    }

    public function showconfirmation($casecode, $password)
    {
        return view('applicant.appconfirmdialog', compact('casecode', 'password'));
    }

    public function generatecasecode($incomingyear)
    {
        $currentYear = date('y');
        $nextYear = date('y', strtotime('+1 year'));

        $levelCode = '';
        switch ($incomingyear) {
            case 'First Year':
            case 'Second Year':
            case 'Third Year':
            case 'Fourth Year':
            case 'Fifth Year':
                $levelCode = 'CLG';
                break;
            case 'Grade 11':
            case 'Grade 12':
                $levelCode = 'SHS';
                break;
            case 'Grade 7':
            case 'Grade 8':
            case 'Grade 9':
            case 'Grade 10':
                $levelCode = 'JHS';
                break;
            case 'Grade 1':
            case 'Grade 2':
            case 'Grade 3':
            case 'Grade 4':
            case 'Grade 5':
            case 'Grade 6':
                $levelCode = 'ELM';
                break;
            default:
                throw new \Exception("Invalid incoming year provided.");
        }

        $latestCase = DB::table('applicants')
            ->where('casecode', 'like', "{$currentYear}{$nextYear}-%")
            ->orderBy('casecode', 'desc')
            ->first();

        $sequenceNumber = 1;

        if ($latestCase) {
            $latestSequence = intval(explode('-', $latestCase->casecode)[1]);
            $sequenceNumber = $latestSequence + 1;
        }

        $formattedSequence = str_pad($sequenceNumber, 5, '0', STR_PAD_LEFT);

        return "{$currentYear}{$nextYear}-{$formattedSequence}-{$levelCode}";
    }

    public function determinePriorityLevel($schoolLevel, $userInputs)
    {
        // Step 1: Fetch all criteria from the database
        $criteria = Criteria::all(); // Assuming 'criteria' table contains all necessary rows

        // Step 2: Initialize priority level
        $priorityLevel = 0;

        // Step 3: Add the GWA key dynamically to criteria names
        $gwaKey = str_replace('_', ' ', strtoupper($schoolLevel) . ' gwa'); // Replace underscores with spaces

        if ($schoolLevel === 'College') {
            $gwaValue = $userInputs['College GWA'] ?? null;
        }

        if ($schoolLevel === 'Senior High') {
            $gwaValue = $userInputs['Senior High gwa'] ?? null;
        }

        if ($schoolLevel === 'Junior High') {
            $gwaValue = $userInputs['Junior High gwa'] ?? null;
        }

        if ($schoolLevel === 'Elementary') {
            $gwaValue = $userInputs['Elementary gwa'] ?? null;
        }


        // Step 4: Collect criteria names and prepare for batch processing
        $criteriaNames = $criteria->pluck('criteria_name')->toArray(); // Collect all criteria names
        $labels = array_keys($userInputs); // User inputs as candidate labels

        // Step 5: Call the batch NLP API
        $batchResponses = $this->useHuggingFaceNLPBatch($criteriaNames, $labels);



        // Step 6: Process each criterion with the batch response
        foreach ($criteria as $criterion) {
            $criteriaName = $criterion->criteria_name;
            $criteriaValue = $criterion->criteria_value;

            // General criteria matching using NLP
            $response = $batchResponses[$criteriaName] ?? null;

            if ($response && $response['score'] > 0.9) {
                $matchedLabel = $response['label'];
                $matchedInput = $userInputs[$matchedLabel] ?? null;

                // Check if the matched label corresponds to the GWA key
                if ($criteriaNames === $gwaKey && $gwaValue !== null && $gwaValue <= $criteriaValue) {
                    $priorityLevel++;
                    Log::info("NLP GWA Match Found: {$criteriaName}, Name: {$matchedLabel}, GWA: {$gwaValue}, Confidence Score: {$response['score']}, Priority Level: {$priorityLevel}");
                    continue; // Skip further processing for this criterion
                }

                // Process general criteria if not already matched as GWA
                if ($matchedInput !== null && $matchedInput <= $criteriaValue) {
                    $priorityLevel++;
                    Log::info("Batched NLP Match Found: {$criteriaName}, Name: {$matchedLabel}, Input: {$matchedInput}, Confidence Score: {$response['score']}, Priority Level: {$priorityLevel}");
                }
            }
        }

        return $priorityLevel;
    }

    private function useHuggingFaceNLPBatch($texts, $labels)
    {
        $apiUrl = 'https://api-inference.huggingface.co/models/facebook/bart-large-mnli';
        $headers = [
            'Authorization: Bearer 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',
            'Content-Type: application/json'
        ];

        $responses = [];
        foreach ($texts as $text) {
            $payload = json_encode([
                'inputs' => $text,
                'parameters' => [
                    'candidate_labels' => $labels
                ]
            ]);

            $ch = curl_init($apiUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

            $response = curl_exec($ch);
            curl_close($ch);

            if ($response) {
                $decoded = json_decode($response, true);
                if (!empty($decoded['labels'])) {
                    $responses[$text] = [
                        'label' => $decoded['labels'][0],
                        'score' => $decoded['scores'][0]
                    ];
                }
            }
        }

        return $responses;
    }


    public function cancelapplication($casecode)
    {
        DB::beginTransaction();
        try {
            $applicant = applicants::where('casecode', $casecode)->first();
            $applicant->applicationstatus = 'Withdrawn';
            $applicant->save();
            DB::commit();

            return redirect()->back()->with('success', "You have successfully withdrawn your application.");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('failure', 'Failed to cancel application. If the issue persists, please contact one of our social worker for assistance. ');
        }
    }



    public function testDeterminePriorityLevel()
    {
        $schoolLevel = 'College'; // Example school level
        $userInputs = [
            'mother income' => 100,
            'father income' => 100,
            'sibling income' => 100,
            'applicant income' => 100
        ];

        if ($schoolLevel === 'College') {
            $userInputs['College GWA'] = 1; // Add GWA key for college
        }

        if ($schoolLevel === 'Senior High') {
            $userInputs['Senior High gwa'] = 87; // Add GWA key for college
        }

        if ($schoolLevel === 'Junior High') {
            $userInputs['Junior High gwa'] = 97; // Add GWA key for college
        }

        if ($schoolLevel === 'Elementary') {
            $userInputs['Elementary gwa'] = 80; // Add GWA key for college
        }

        // Call the determinePriorityLevel function
        $priorityLevel = $this->determinePriorityLevel($schoolLevel, $userInputs);

        // Output the result
        dd($priorityLevel);
    }
}
