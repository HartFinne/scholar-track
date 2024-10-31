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
use App\Models\aprequirements;
use App\Models\criteria;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class ApplicationController extends Controller
{
    public function showcollegeapplication()
    {
        $courses = courses::where('level', 'College')->get();
        $institutions = institutions::get();
        $form = applicationforms::where('formname', 'College')->first();

        return view('applicant.applicationformC', compact('courses', 'institutions', 'form'));
    }
    public function showelemhsapplication($level)
    {
        $courses = courses::where('level', 'College')->get();
        $institutions = institutions::get();
        if ($level == 'elementary') {
            $form = applicationforms::where('formname', 'Elementary')->first();
        } else {
            $form = applicationforms::where('formname', 'High School')->first();
        }

        return view('applicant.applicationformC', compact('courses', 'institutions', 'form'));
    }

    public function saveapplicant(Request $request)
    {
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
                'barangay' => 'string|max:50',
                'city' => 'string|max:50',
                'email' => 'email|max:255',
                'phonenum' => 'digits_between:11,12',
                'occupation' => 'string|max:100',
                'income' => 'numeric|min:0',
                'fblink' => 'url',
                'isIndigenous' => '',
                'indigenousgroup' => 'required_if:isIndigenous,Yes|max:100',
                'schoolname' => 'string|max:255',
                'collegedept' => 'string|max:255',
                'incomingyear' => 'string',
                'course' => 'string|max:255',
                'gwa' => 'numeric|min:1|max:5',
                // Father info
                'fname' => 'string|max:255',
                'fage' => 'integer',
                'fsex' => '',
                'fbirthdate' => 'date',
                'freligion' => 'string|max:100',
                'fattainment' => 'string|max:100',
                'foccupation' => 'string|max:100',
                'fcompany' => 'string|max:100',
                'fincome' => 'numeric|min:0',
                // Mother info
                'mname' => 'string|max:255',
                'mage' => 'integer',
                'msex' => '',
                'mbirthdate' => 'date',
                'mreligion' => 'string|max:100',
                'mattainment' => 'string|max:100',
                'moccupation' => 'string|max:100',
                'mcompany' => 'string|max:100',
                'mincome' => 'numeric|min:0',
                // Sibling info
                'sname.*' => 'string|max:255',
                'sage.*' => 'integer',
                'ssex.*' => '',
                'sbirthdate.*' => 'date',
                'sreligion.*' => 'string|max:100',
                'sattainment.*' => 'string|max:100',
                'soccupation.*' => 'string|max:100',
                'scompany.*' => 'string|max:100',
                'sincome.*' => 'numeric|min:0',
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
                // Sibling info
                'sname.*.string' => 'Each sibling\'s name must be a valid string.',
                'sname.*.max' => 'Each sibling\'s name may not be greater than 255 characters.',
                'sage.*.integer' => 'Each sibling\'s age must be a number.',
                'sbirthdate.*.date' => 'Each sibling\'s birthdate must be a valid date.',
                'sreligion.*.string' => 'Each sibling\'s religion must be a valid string.',
                'sreligion.*.max' => 'Each sibling\'s religion may not be greater than 100 characters.',
                'sattainment.*.string' => 'Each sibling\'s educational attainment must be a valid string.',
                'sattainment.*.max' => 'Each sibling\'s educational attainment may not be greater than 100 characters.',
                'soccupation.*.string' => 'Each sibling\'s occupation must be a valid string.',
                'soccupation.*.max' => 'Each sibling\'s occupation may not be greater than 100 characters.',
                'scompany.*.string' => 'Each sibling\'s company name must be a valid string.',
                'scompany.*.max' => 'Each sibling\'s company name may not be greater than 100 characters.',
                'sincome.*.numeric' => 'Each sibling\'s income must be a number.',
                'sincome.*.min' => 'Each sibling\'s income must be at least 0.',
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

            $casecode = $this->generatecasecode($request->incomingyear);
            $sincome = array_sum($request->sincome);

            $prioritylevel = $this->determineprioritylevel($request->incomingyear, $request->income, $request->fincome, $request->mincome, $sincome, $request->gwa);

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
                'barangay' => $request->barangay,
                'city' => $request->city,
                'email' => $request->email,
                'phonenum' => $phoneNumber,
                'occupation' => $request->occupation,
                'income' => $request->income,
                'fblink' => $request->fblink,
                'isIndigenous' => $request->isIndigenous,
                'indigenousgroup' => $request->indigenousgroup,
                'applicationstatus' => "UNDER REVIEW",
                'prioritylevel' => $prioritylevel,
            ]);

            if (in_array($request->incomingyear, ['First Year', 'Second Year', 'Third Year'])) {
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

            // Sibling info
            foreach ($request->sname as $index => $name) {
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

            apotherinfo::create([
                'casecode' => $casecode,
                'grant' => $request->grant,
                'talent' => $request->talent,
                'expectations' => $request->expectation,
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
            $payslip = $request->file('payslip');
            $indigencycert = $request->file('indigencycert');
            // Create a custom file name using casecode
            $filename_idpic = $casecode . '_' . 'idpic' . '.' . $idpic->getClientOriginalExtension();
            $filename_reportcard = $casecode . '_' . 'reportcard' . '.' . $reportcard->getClientOriginalExtension();
            $filename_regiform = $casecode . '_' . 'regiform' . '.' . $regiform->getClientOriginalExtension();
            $filename_autobio = $casecode . '_' . 'autobio' . '.' . $autobio->getClientOriginalExtension();
            $filename_familypic = $casecode . '_' . 'familypic' . '.' . $familypic->getClientOriginalExtension();
            $filename_houseinside = $casecode . '_' . 'houseinside' . '.' . $houseinside->getClientOriginalExtension();
            $filename_houseoutside = $casecode . '_' . 'houseoutside' . '.' . $houseoutside->getClientOriginalExtension();
            $filename_utilitybill = $casecode . '_' . 'utilitybill' . '.' . $utilitybill->getClientOriginalExtension();
            $filename_sketchmap = $casecode . '_' . 'sketchmap' . '.' . $sketchmap->getClientOriginalExtension();
            $filename_payslip = $casecode . '_' . 'payslip' . '.' . $payslip->getClientOriginalExtension();
            $filename_indigencycert = $casecode . '_' . 'indigencycert' . '.' . $indigencycert->getClientOriginalExtension();
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
            $path_payslip = $payslip->storeAs('uploads/application_requirements/payslips', $filename_payslip, 'public');
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
                'payslip' => $path_payslip,
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
            return redirect()->back()->with('error', 'Your application has failed due to the following errors: ' . $errorMessages);
        } catch (\Exception $e) {
            DB::rollback();
            // Log::error("Application submission error: " . $e->getMessage());
            return redirect()->back()->with('error', 'Sorry, your application could not be processed at this time. Please try again later or contact support if the problem persists. ' . $e->getMessage());
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

    public function determineprioritylevel($incomingyear, $income, $fincome, $mincome, $sincome, $gwa)
    {
        $criteria = criteria::first();

        if ($fincome <= $criteria->fincome) {
            $fincomelvl = 1;
        } else {
            $fincomelvl = 2;
        }

        if ($mincome <= $criteria->mincome) {
            $mincomelvl = 1;
        } else {
            $mincomelvl = 2;
        }

        if ($sincome <= $criteria->sincome) {
            $sincomelvl = 1;
        } else {
            $sincomelvl = 2;
        }

        if ($income <= $criteria->aincome) {
            $incomelvl = 1;
        } else {
            $incomelvl = 2;
        }

        if (in_array($incomingyear, ['First Year', 'Second Year', 'Third Year'])) {
            if ($gwa <= $criteria->cgwa) {
                $cgwalvl = 1;
            } else {
                $cgwalvl = 2;
            }
            $prioritylevel = $fincomelvl + $mincomelvl + $sincomelvl + $incomelvl + $cgwalvl;
        } elseif (in_array($incomingyear, ['Grade 11', 'Grade 12'])) {
            if ($gwa <= $criteria->cgwa) {
                $shsgwalvl = 1;
            } else {
                $shsgwalvl = 2;
            }
            $prioritylevel = $fincomelvl + $mincomelvl + $sincomelvl + $incomelvl + $shsgwalvl;
        } elseif (in_array($incomingyear, ['Grade 7', 'Grade 8', 'Grade 9', 'Grade 10'])) {
            if ($gwa <= $criteria->cgwa) {
                $jhsgwalvl = 1;
            } else {
                $jhsgwalvl = 2;
            }
            $prioritylevel = $fincomelvl + $mincomelvl + $sincomelvl + $incomelvl + $jhsgwalvl;
        } elseif (in_array($incomingyear, ['Grade 1', 'Grade 2', 'Grade 3', 'Grade 4', 'Grade 5', 'Grade 6'])) {
            if ($gwa <= $criteria->cgwa) {
                $elemgwalvl = 1;
            } else {
                $elemgwalvl = 2;
            }
            $prioritylevel = $fincomelvl + $mincomelvl + $sincomelvl + $incomelvl + $elemgwalvl;
        }
        return $prioritylevel;
    }

    public function cancelapplication($casecode)
    {
        DB::beginTransaction();
        try {
            $applicant = applicants::where('casecode', $casecode)->first();
            $applicant->applicationstatus = 'WITHDRAWN';
            $applicant->save();
            DB::commit();

            return redirect()->back()->with('success', "You have successfully withdrawn your application.");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to cancel application. If the issue persists, please contact one of our social worker for assistance. ');
        }
    }
}
