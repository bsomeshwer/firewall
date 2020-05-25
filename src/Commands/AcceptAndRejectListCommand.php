<?php

namespace Someshwer\Firewall\src\Commands;

use Illuminate\Console\Command;

/**
 * @author Someshwer<bsomeshwer89@gmail.com>
 * Date: 11-08-2018
 * Time: 20:42 IST
 *
 * This command returns all accept listed and reject listed
 * ip addresses based on the option provided.
 */
class AcceptAndRejectListCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ip:list {--option=none}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command returns all ip addresses that are added to accept list array 
    and reject list array in "firewall" config file.';

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
     * This command returns and displays all accept listed and
     * reject listed ip addresses based on the option value.
     *
     * @return void
     */
    public function handle()
    {
        $option = $this->option('option');
        $headers = [];
        $list = [];
        if ($option == 'none' || $option == null) {
            $this->warn('No option is provided!');
            $this->warn('Try to use following available options:');
            $this->warn('php artisan ip:list --option=accept (or)');
            $this->warn('php artisan ip:list --option=reject');
        }
        if ($option == 'accept') {
            $list = config('firewall.accept');
            $headers = ['SNO', 'IP Address', 'Accept Listed'];
        }
        if ($option == 'reject') {
            $list = config('firewall.reject');
            $headers = ['SNO', 'IP Address', 'Reject Listed'];
        }
        $ip_list = $this->formTableHeaders($list, $option);
        $this->table($headers, $ip_list);
        (($option == 'accept') || ($option == 'reject')) ? $this->info('Done!!') : null;
    }

    /**
     * Returns the data in a table view.
     *
     * @param array $list
     * @param $option
     *
     * @return array $ip_list
     */
    private function formTableHeaders($list, $option)
    {
        $option_key = ($option == 'accept') ? 'accepted' : (($option == 'reject') ? 'rejected' : null);
        $ip_list = [];
        $i = 1;
        foreach ($list as $item) {
            $ip_list[] = [
                'sno'        => $i,
                'ip_address' => $item,
                $option_key  => 'true',
            ];
        }

        return $ip_list;
    }
}
