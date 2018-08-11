<?php

namespace Someshwer\Firewall\Commands;

use Illuminate\Console\Command;

class WhitelistCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'firewall:whitelist';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command fetches all ip addresses that are added to whitelist array in "firewall" config file.';

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
     * This command returns and displays all white listed ip addresses.
     *
     * @return void
     */
    public function handle()
    {
        $white_list = config('firewall.whitelist');
        $headers = ['SNO', 'IP Address', 'Blacklist'];
        $ip_list = $this->formTableHeaders($white_list);
        $this->table($headers, $ip_list);
        $this->info('Done!!');
    }

    /**
     * Decorate values as table with headers
     *
     * @param array $white_list
     * @return array $ip_list
     */
    private function formTableHeaders($white_list)
    {
        $ip_list = [];
        $i = 1;
        foreach ($white_list as $item) {
            $ip_list[] = [
                'sno' => $i,
                'ip_address' => $item,
                'blacklist' => 'true',
            ];
        }
        return $ip_list;
    }

}
