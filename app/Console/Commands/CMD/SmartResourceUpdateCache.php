<?php

namespace App\Console\Commands\CMD;

use App\SmartResource\Binders\AbstractBinder;
use Cron\CronExpression;
use Illuminate\Console\Command;
use Symfony\Component\Console\Output\OutputInterface;

class SmartResourceUpdateCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sr:update {--f|force : Force cache all}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update cache for smart resource';

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
        $force = $this->option('force');
        $classes = $this->getBinders();
        foreach ($classes as $class){
            $this->updateCache( $class, $force);
        }
    }
    
    protected function getBinders(){
        $binders  = [];
        $files = glob( base_path("app/SmartResource/Binders/*.php"));
        foreach ($files as $file){
            $file_name = basename( $file, ".php");
            if($file_name != 'AbstractBinder'){
                $binders[] = "App\\SmartResource\\Binders\\" . $file_name;
            }
        }
        return $binders;
    }
    
    protected function updateCache($class, $force = false){
        $this->info( "Processing " . $class, OutputInterface::VERBOSITY_VERBOSE);
        $config = $class::$cache_config;
        if(empty( $config )){
            $this->info( "No cache config", OutputInterface::VERBOSITY_VERBOSE);
            return;
        }
        foreach ($config as $function_cache){
            $cron = CronExpression::factory( $function_cache['cron'] );
            if($cron->isDue() || $force){
                $this->warn( "Running " . $function_cache['function'], OutputInterface::VERBOSITY_VERBOSE);
                app($class)->{$function_cache['function']}(...$function_cache['arguments']);
            }
        }
    }
    
}
