<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use App\Models\lte;
use App\Models\penalty;
use App\Models\scholarshipinfo;

class UpdateLTEStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-l-t-e-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update LTE status to Unexcused if the submission deadline has passed and no response submitted';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::now('Asia/Manila')->toDateString();

        $noresponseltes = lte::where('deadline', '<', $today)
            ->where('ltestatus', 'No Response')->get();

        foreach ($noresponseltes as $lte) {
            $lte->ltestatus = 'Unexcused';
            $lte->save();

            $condition = $lte->violation . ' in ' . $lte->eventtype;

            $currentpenalty = penalty::where('caseCode', $lte->caseCode)
                ->where('condition', $condition)
                ->orderBy('remark', 'desc')
                ->first();

            if ($currentpenalty) {
                $offenses = [
                    '1st Offense' => '2nd Offense',
                    '2nd Offense' => '3rd Offense',
                    '3rd Offense' => '4th Offense',
                ];

                $remark = $offenses[$currentpenalty->remark] ?? null;
            } else {
                $remark = '1st Offense';
            }

            penalty::create([
                'caseCode' => $lte->caseCode,
                'condition' => $condition,
                'conditionid' => $lte->lid,
                'remark' => $remark,
                'dateofpenalty' => $today,
            ]);

            if (
                ($remark == '3rd Offense' && $lte->violation == 'Absent' && $lte->eventtype == 'Humanities Class') ||
                ($remark == '4th Offense' && in_array($lte->violation, ['Late', 'Left Early']) && $lte->eventtype == 'Humanities Class')
            ) {
                $scinfo = scholarshipinfo::where('caseCode', $lte->caseCode)->first();
                if ($scinfo) {
                    $scinfo->scholarshipstatus = 'On-Hold';
                    $scinfo->save();
                }
            }
        }

        $this->info('LTEs with no response has been successfully marked as "Unexcused".');
    }
}
