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
use App\Models\aprequirements;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ApplicationController extends Controller
{
    public function showcollegeapplication()
    {
        $courses = courses::where('level', 'College')->get();
        $institutions = institutions::get();

        return view('applicant.applicationformC', compact('courses', 'institutions'));
    }

    public function saveapplicant(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                // applicant info
                'scholarname' => 'required|string|max:255',
                'chinesename' => 'required|string|max:255',
                'sex' => 'required',
                'age' => 'required|integer',
                'birthdate' => 'required',
                'homeaddress' => 'required|string|max:255',
                'barangay' => 'required|string|max:50',
                'city' => 'required|string|max:50',
                'email' => 'required|string|max:255',
                'phonenum' => 'required|string|max:11',
                'occupation' => 'required|string|max:100',
                'income' => 'required|integer',
                'fblink' => 'required',
                'isIndigenous' => 'required',
                'indigenousgroup' => 'required_if:isIndigenous,Yes|max:100',
                'schoolname' => 'required',
                'collegedept' => 'required|string|max:255',
                'incomingyear' => 'required',
                'course' => 'required',
                'gwa' => 'required|float|min:1',
                // father info
                'fname' => 'required|string|max:255',
                'fage' => 'required|integer',
                'fsex' => 'required',
                'fbirthdate' => 'required',
                'freligion' => 'required|string|max:100',
                'fattainment' => 'required|string|max:100',
                'foccupation' => 'required|string|max:100',
                'fcompany' => 'required|string|max:100',
                'fincome' => 'required|integer',
                // mother info
                'mname' => 'required|string|max:255',
                'mage' => 'required|integer',
                'msex' => 'required',
                'mbirthdate' => 'required',
                'mreligion' => 'required|string|max:100',
                'mattainment' => 'required|string|max:100',
                'moccupation' => 'required|string|max:100',
                'mcompany' => 'required|string|max:100',
                'mincome' => 'required|integer',
                // siblings info
                'sname' => 'required|string|max:255',
                'sage' => 'required|integer',
                'ssex' => 'required',
                'sbirthdate' => 'required',
                'sreligion' => 'required|string|max:100',
                'sattainment' => 'required|string|max:100',
                'soccupation' => 'required|string|max:100',
                'scompany' => 'required|string|max:100',
                'sincome' => 'required|integer',
                // other info
                'grant' => 'required|string|max:255',
                'talent' => 'required|string|max:255',
                'expectation' => 'required|string|max:255',
                // required documents
                'idpic' => 'required|mimes:jpeg,jpg,png|max:5120',
                'reportcard' => 'required|mimes:jpeg,jpg,png,pdf|max:5120',
                'regiform' => 'required|mimes:jpeg,jpg,png,pdf|max:5120',
                'autobiography' => 'required|mimes:pdf|max:5120',
                'familypic' => 'required|mimes:jpeg,jpg,png|max:5120',
                'insidehouse' => 'required|mimes:jpeg,jpg,png|max:5120',
                'outsidehouse' => 'required|mimes:jpeg,jpg,png|max:5120',
                'utility' => 'required|mimes:jpeg,jpg,png,pdf|max:5120',
                'sketchmap' => 'required|mimes:jpeg,jpg,png,pdf|max:5120',
                'payslip' => 'required|mimes:jpeg,jpg,png,pdf|max:5120',
                'indigencycert' => 'required|mimes:jpeg,jpg,png,pdf|max:5120',
            ]);

            $casecode = $this->generatecasecode($request->incomingyear);
            $sincome = 0;
            foreach ($request->sincome as $index => $value) {
                $sincome += $value;
            }
            $prioritylevel = $this->determineprioritylevel($request->incomingyear, $request->income, $request->fincome, $request->mincome, $sincome, $request->gwa);

            applicants::create([
                'casecode' => $casecode,
                'name' => $request->scholarname,
                'chinesename' => $request->chinesename,
                'sex' => $request->sex,
                'age' => $request->age,
                'birthdate' => $request->birthdate,
                'homeaddress' => $request->homeaddress,
                'barangay' => $request->barangay,
                'city' => $request->city,
                'email' => $request->email,
                'phonenum' => $request->phonenum,
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

            apfamilyinfo::create(
                // father
                [
                    'casecode' => $casecode,
                    'fathername' => $request->fname,
                    'age' => $request->fage,
                    'sex' => $request->fsex,
                    'birthdate' => $request->fbirthdate,
                    'relationship' => $request->frelationship,
                    'religion' => $request->freligion,
                    'educattainment' => $request->fattainment,
                    'occupation' => $request->foccupation,
                    'company' => $request->fcompany,
                    'income' => $request->fincome,
                ],
                // mother
                [
                    'casecode' => $casecode,
                    'fathername' => $request->mname,
                    'age' => $request->mage,
                    'sex' => $request->msex,
                    'birthdate' => $request->mbirthdate,
                    'relationship' => $request->mrelationship,
                    'religion' => $request->mreligion,
                    'educattainment' => $request->mattainment,
                    'occupation' => $request->moccupation,
                    'company' => $request->mcompany,
                    'income' => $request->mincome,
                ]
            );

            // sibling
            foreach ($request->sname as $index => $name) {
                apfamilyinfo::create(
                    [
                        'casecode' => $casecode,
                        'siblingname' => $request->name,
                        'age' => $request->sage[$index],
                        'sex' => $request->ssex[$index],
                        'birthdate' => $request->sbirthdate[$index],
                        'relationship' => $request->srelationship[$index],
                        'religion' => $request->sreligion[$index],
                        'educattainment' => $request->sattainment[$index],
                        'occupation' => $request->soccupation[$index],
                        'company' => $request->scompany[$index],
                        'income' => $request->sincome[$index],
                    ]
                );
            }

            apotherinfo::create([
                'casecode' => $casecode,
                'grant' => $request->grant,
                'talent' => $request->talent,
                'expectations' => $request->expectations,
            ]);

            aprequirements::create([
                'casecode' => $casecode,
                'idpic' => $request->idpic,
                'reportcard' => $request->reportcard,
                'regiform' => $request->regiform,
                'autobio' => $request->autobiography,
                'familypic' => $request->familypic,
                'houseinside' => $request->insidehouse,
                'houseoutside' => $request->outsidehouse,
                'utilitybill' => $request->utility,
                'sketchmap' => $request->sketchmap,
                'payslip' => $request->payslip,
                'indigencycert' => $request->indigencycert,
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Your application has been submitted.');
        } catch (ValidationException $e) {
            // Validation exceptions will be handled by Laravel and return to form with errors
            throw $e;
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error', 'Sorry, your application could not be processed at this time. Please try again later or contact support if the problem persists.');
        }
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

        $latestCase = DB::table('applicants') // Replace with your table name
            ->where('casecode', 'like', "{$currentYear}{$nextYear}-%")
            ->orderBy('casecode', 'desc')
            ->first();

        $sequenceNumber = 1;

        if ($latestCase) {
            $latestSequence = intval(explode('-', $latestCase->casecode)[1]);
            $sequenceNumber = $latestSequence + 1;
        }

        $formattedSequence = str_pad($sequenceNumber, 5, '0', STR_PAD_LEFT);

        $casecode = "{$currentYear}{$nextYear}-{$formattedSequence}-{$levelCode}";

        return $casecode;
    }

    public function determineprioritylevel($incomingyear, $income, $fincome, $mincome, $sincome, $gwa) {}
}
