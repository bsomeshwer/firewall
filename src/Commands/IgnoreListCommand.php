<?php namespace Someshwer\Firewall\src\Commands;

use Illuminate\Console\Command;

/**
 * @author Someshwer<bsomeshwer89@gmail.com>
 * Date: 11-08-2018
 * Time: 20:42 IST
 *
 * This command returns all ignore listed ip addresses
 */
class IgnoreListCommand extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'firewall:ignore-list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command returns all ip addresses that are added to ignore list array in "firewall" config file.';

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
     * This command returns and displays all ignore listed ip addresses.
     *
     * @return void
     */
    public function handle()
    {
        $ignore_list = config('firewall.ignore');
        $headers = ['SNO', 'IP Address', 'Ignore List'];
        $ip_list = $this->formTableHeaders($ignore_list);
        $this->table($headers, $ip_list);
        $this->info('Done!!');
    }

    /**
     * Returns the data in a table view.
     *
     * @param array $ignore_list
     * @return array $ip_list
     */
    private function formTableHeaders($ignore_list)
    {
        $ip_list = [];
        $i = 1;
        foreach ($ignore_list as $item) {
            $ip_list[] = [
                'sno' => $i,
                'ip_address' => $item,
                'ignored' => 'true',
            ];
        }
        return $ip_list;
    }

}