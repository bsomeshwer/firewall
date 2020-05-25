<?php

namespace Someshwer\Firewall\Commands;

use Illuminate\Console\Command;

/**
 * This class displays all the black listed ip addresses on console.
 *
 * Class BlackListCommand
 */
class BlackListCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'firewall:blacklist';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command fetches all ip addresses that are added to blacklist array in "firewall" config file.';

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
     * This command returns and displays all black listed ip addresses.
     *
     * @return void
     */
    public function handle()
    {
        $black_list = config('firewall.blacklist');
        $headers = ['SNO', 'IP Address', 'Blacklist'];
        $ip_list = $this->formTableHeaders($black_list);
        $this->table($headers, $ip_list);
        $this->info('Done!!');
    }

    /**
     * Decorate values as table with headers.
     *
     * @param array $black_list
     *
     * @return array $ip_list
     */
    private function formTableHeaders($black_list)
    {
        $ip_list = [];
        $i = 1;
        foreach ($black_list as $item) {
            $ip_list[] = [
                'sno'        => $i,
                'ip_address' => $item,
                'blacklist'  => 'true',
            ];
        }

        return $ip_list;
    }
}
