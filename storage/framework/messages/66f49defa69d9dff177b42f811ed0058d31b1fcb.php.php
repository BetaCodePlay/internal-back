<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SendEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:wallet';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return int
     */
    public function handle()
    {
        for ($i = 0; $i < 100; $i++) {
            sleep(1);
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'http://wallet.eborio.dotworkers.net/api/transactions/debit',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => array('amount' => '1','provider' => '4','action' => '1','wallet' => '211'),
                CURLOPT_HTTPHEADER => array(
                    'Accept: application/json',
                    'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiI1IiwianRpIjoiYTJjY2YyNGQxMjkxM2MwY2ZiZmEwZDE1YzY2NDRlMWU1MzA4NGU3MTMwN2JmODY2YWY1ZGQ0NGExZTRjMmY0NDg1OTYxM2Q2NDVjNmQyNjMiLCJpYXQiOjE2NDY5MzQwNzUsIm5iZiI6MTY0NjkzNDA3NSwiZXhwIjoxNjc4NDcwMDc1LCJzdWIiOiIyMjYiLCJzY29wZXMiOlsiZ2V0LXdhbGxldCIsImNyZWRpdC10cmFuc2FjdGlvbnMiLCJkZWJpdC10cmFuc2FjdGlvbnMiXX0.C9_5gS3PojGKD5mZLpyvmJBOp_6iNLSQASi6EPtqELf7HIcq_WPIc5J2dqw23hK_-tToZm_c0ONF8wVeuaiK0s9E5siz_WUfxOlHC_-fVu_wmhl8Ejwp1DF1xNK4R4U9YuifjjGllkS5Qcp5iawT2RIGGduZYsupCJNgHwLrXUSNlUhzdcwmRqzNuZdOvMvcID-1mbQFOjze4RXJwKwwfOK_mu4Z8socKWLpDom844es-NcmVU-qefqC65eeURP9ctzxdpGwBzYuIwmJcbnW7ouldStud-aAQMn7l4qYwYnVYTMw3cfsnPYEQw6rMK4gFQGTkVy5rN2IdkJRXxJ2KvsGRgw9JGcAtzM49ypSzFvVHDVsrJ1CqclsDHntqe9w1NOcBuA8dH4BcuEevFsMR7KMu2j3yyVPTe7Ti5XC9I-UkBLp6G1ielT6rMRjqBSHtcls_r2i3vonwnGXr_AjM141BhWSGdr3EcnkkYrMbEj0lo4OI_cRrS25lHknRhU6cwu0qyqLWFUejqPaBwxzItMMt7rKw3LEIVkk0690mH46QGxlqnLtNjzk7nyc4u1KxZhsfgh9sL136JF9WfE7OC1kHxnD5Iw2I9K-R6EYVtGKsGIB1i6o5yiTq1j9ZE28f7wuWlc5OwLg4VM2r6kdDy_54abPnIpolzNzRETHjY8'
                ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
        }
    }
}
