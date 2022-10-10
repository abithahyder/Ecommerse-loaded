<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\setting;
use App\member_master;
use App\notification_model;
use App\cross_country;
use Carbon\Carbon;

class notification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notification Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $setting = setting::first();
        if ($setting->notification_day > 0) {
            $date = Carbon::now()->add($setting->notification_day, 'day')->Format('Y-m-d');
            $member = member_master::whereDate('me_expire_date',$date)->select('me_id', 'me_first_name', 'me_last_name')->get()->toArray();
            $cross_member = cross_country::whereDate('cc_eff_date',$date)->select('cc_me_id', 'me_first_name', 'me_last_name')->leftJoin('member_master', 'member_master.me_id', '=', 'cross_country.cc_me_id')->get()->toArray();
            if (!empty($member)) {
                    foreach ($member as $key) {
                        $noti[] = array(
                            'noti_message'  => 'Membership for ' . $key['me_first_name'] . ' ' . $key['me_last_name'] . ' will be expire after ' . $setting->notification_day . ' days',
                            'noti_type'     => 'membership',
                            'noti_me_id'    => $key['me_id'],
                        );
                    }
                    if (!empty($noti)) {
                        notification_model::insert($noti);
                    }
            }
            if (!empty($cross_member)) {
                    foreach ($cross_member as $key) {
                        $cross_noti[] = array(
                            'noti_message'  => 'Cross country for ' . $key['me_first_name'] . ' ' . $key['me_last_name'] . ' will be expire after ' . $setting->notification_day . ' days',
                            'noti_type'     => 'cross_country',
                            'noti_me_id'    => $key['cc_me_id'],
                        );
                    }
                    if (!empty($noti)) {
                        notification_model::insert($cross_noti);
                    }
            }
            $this->info('Notification send successfully! '. $setting->notification_day.'');
        }else{
            $this->info('Notification day not set! ');
        }
    }
}
