<?php

namespace App\Console\Commands;

use App\Models\vehicle_units\vehicle_units;
use App\Models\unitmovement\Unitmovement;
use App\Models\bufferstatus\BufferStatus;
use App\Models\productiontarget\Production_target;
use App\Models\shop\Shop;

use Carbon\Carbon;

use Illuminate\Console\Command;


class CheckBuffer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:checkbuffer';

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
    {$date = Carbon::today()->format('Y-m-d');

        $sectionnseries = Shop::where('id','=',8)->value('no_of_sections');
        $sectionfseries = Shop::where('id','=',10)->value('no_of_sections');

        $sectioncvtrim = Shop::where('id','=',5)->value('no_of_sections');
        $sectionlcvtrim = Shop::where('id','=',11)->value('no_of_sections');
        $sectionlcvinchess = Shop::where('id','=',12)->value('no_of_sections');

        $sectionriv = Shop::where('id','=',6)->value('no_of_sections');

        //Last schedule date
        $first = Carbon::now()->startOfMonth()->format('Y-m-d');
        $last = Carbon::now()->endOfMonth()->format('Y-m-d');
        $allschdates = Production_target::where('schedule_part','entire')->whereBetween('date', [$first, $last])->groupby('date')->get(['date']);
        foreach($allschdates as $schdt){ $allprodndays[] = $schdt->date; }
        $yesterday = carbon::yesterday()->format('Y-m-d');

        $yesterday1 = $yesterday;
        while(!in_array($yesterday1, $allprodndays)){
            $yesterday1 = Carbon::parse($yesterday1)->subDays(1)->format('Y-m-d');
        }

         $yestTrimBuff = BufferStatus::where('date','=',$yesterday1)->value('timline');
         $yestRivBuff = BufferStatus::where('date','=',$yesterday1)->value('riveting');
         $yestPaintBuff = BufferStatus::where('date','=',$yesterday1)->value('paintshop');

        //TRIMLINE BUFFER
        $outtrim1 = Unitmovement::where([['datetime_out','=',$date],['shop_id','=',5]])->count(); //trim out
        $outtrims1 = $yestTrimBuff + $outtrim1;
        $outinlineNS1 = Unitmovement::where([['datetime_out','=',$date],['shop_id','=',10]])->count();
        $outinlineFS1 = Unitmovement::where([['datetime_out','=',$date],['shop_id','=',8]])->count();
        //$outinlineNS1 = Unitmovement::where([['datetime_out','=',$date],['shop_id','=',11],['route_number','=',5]])->count();
        $allout1 = $outinlineNS1 + $outinlineFS1;// + $outinlineNS1;
        $trimbuff = $outtrims1 - $allout1;
        $trimbuff = ($trimbuff < 0)? 0 : $trimbuff;

        //PAINTSHOP BUFFER
        $unitsintrim = Unitmovement::where('current_shop','=',3)->count();//paintshop in
        $unitsinlcvtrim = Unitmovement::where('current_shop','=',11)->count();//paintshop in
        $intrim = $unitsintrim + $unitsinlcvtrim;
        $nosectionsintrim = Shop::where('id',4)->value('no_of_sections') + Shop::where('id',11)->value('no_of_sections');
        if($intrim > $nosectionsintrim){
            $paintbuff = $intrim- $nosectionsintrim;
        }else{
            $paintbuff = 0;
        }
        //$paint = $unitsintrim - $nosectionsintrim;

        //RIVETING BUFFER
        $alltrim = Unitmovement::where([['datetime_out','=',$date],['shop_id','=',6]])->count();//reveting out
        $outtrims = $yestRivBuff + $alltrim;
        $outinlineNS = Unitmovement::where([['datetime_out','=',$date],['shop_id','=',9]])->count();
        $outinlineFS = Unitmovement::where([['datetime_out','=',$date],['shop_id','=',7]])->count();
        $outinlineLCV = Unitmovement::where([['datetime_out','=',$date],['shop_id','=',11],['route_number','=',5]])->count();

        $allout = $outinlineNS + $outinlineFS + $outinlineLCV;
        $rivetbuff = $outtrims - $allout;
        $rivetbuff = ($rivetbuff < 0) ? 0 : $rivetbuff;

        if(in_array($date, $allprodndays)){
            $buff = new BufferStatus;
            $buff->date = $date;
            $buff->timline = $trimbuff;
            $buff->paintshop = $paintbuff;
            $buff->riveting = $rivetbuff;
            $buff->save();
        }


    }
}

