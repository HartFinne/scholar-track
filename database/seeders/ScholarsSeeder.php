<?php

namespace Database\Seeders;

use App\Models\ScAddressInfo;
use App\Models\ScBasicInfo;
use App\Models\ScClothingSize;
use App\Models\ScEducation;
use App\Models\scholarshipinfo;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;


class ScholarsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        for ($i = 1; $i <= 25; $i++) {
            DB::transaction(function () use ($i) {
                do {
                    // Generate a unique caseCode
                    $timestamp = now()->format('ymdHis'); // Shortened timestamp: ymdHis (12 characters)
                    $suffix = str_pad($i, 3, '0', STR_PAD_LEFT); // Ensure 3-digit suffix for uniqueness
                    $caseCode = substr('C' . $timestamp . $suffix, 0, 15); // Ensure caseCode fits within 15 characters
                } while (User::where('caseCode', $caseCode)->exists()); // Check for uniqueness

                $startDate = now()->subYears(rand(1, 5))->format('Y-m-d');
                $academicYear = (now()->year - 1) . '-' . now()->year;

                $user = User::create([
                    'caseCode' => $caseCode,
                    'scEmail' => "scholar$i@example.com",
                    'password' => Hash::make('Password123!'),
                    'scPhoneNum' => '63917' . rand(1000000, 9999999),
                    'scStatus' => 'Active',
                ]);

                scholarshipinfo::create([
                    'caseCode' => $caseCode,
                    'area' => 'Area ' . rand(1, 3),
                    'scholartype' => rand(0, 1) ? 'New Scholar' : 'Old Scholar',
                    'startdate' => $startDate,
                    'enddate' => now()->addYear()->format('Y-m-d'),
                    'scholarshipstatus' => 'Continuing',
                ]);

                ScBasicInfo::create([
                    'caseCode' => $caseCode,
                    'scFirstname' => 'First' . $i,
                    'scLastname' => 'Last' . $i,
                    'scMiddlename' => 'Middle' . $i,
                    'scChinesename' => 'Chinese' . $i,
                    'scDateOfBirth' => now()->subYears(rand(15, 25))->format('Y-m-d'),
                    'scAge' => rand(18, 25),
                    'scSex' => rand(0, 1) ? 'Male' : 'Female',
                    'scOccupation' => rand(0, 1) ? 'Student' : 'Unemployed',
                    'scIncome' => rand(1000, 100000),
                    'scFblink' => 'https://facebook.com/scholar' . $i,
                    'scGuardianName' => 'Guardian' . $i,
                    'scRelationToGuardian' => 'Parent',
                    'scGuardianEmailAddress' => 'guardian' . $i . '@example.com',
                    'scGuardianPhoneNumber' => '63918' . rand(1000000, 9999999),
                    'scIsIndigenous' => rand(0, 1) ? 'Yes' : 'No',
                    'scIndigenousgroup' => rand(0, 1) ? 'Group' . $i : 'Not Applicable',
                ]);

                ScAddressInfo::create([
                    'caseCode' => $caseCode,
                    'scResidential' => 'Address ' . $i,
                    'scRegion' => 'Region ' . rand(1, 5),
                    'scCity' => 'City ' . rand(1, 5),
                    'scBarangay' => 'Barangay ' . rand(1, 10),
                ]);

                ScClothingSize::create([
                    'caseCode' => $caseCode,
                    'scTShirtSize' => 'M',
                    'scShoesSize' => rand(6, 12),
                    'scSlipperSize' => rand(6, 12),
                    'scPantsSize' => 'L',
                    'scJoggingPantSize' => 'XL',
                ]);

                ScEducation::create([
                    'caseCode' => $caseCode,
                    'scSchoolLevel' => 'College',
                    'scSchoolName' => 'School ' . $i,
                    'scYearGrade' => '1st Year',
                    'scCourseStrandSec' => 'Course' . rand(1, 5),
                    'scCollegedept' => 'Engineering',
                    'scAcademicYear' => $academicYear,
                ]);
            });
        }
    }
}
