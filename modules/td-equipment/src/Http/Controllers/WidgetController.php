<?php
namespace Taskhub\Equipment\Http\Controllers;

use App\Http\Controllers\Controller;
use Taskhub\Equipment\Models\Equipment;
use Carbon\Carbon;

class WidgetController extends Controller
{
    public function equipmentsListWidget()
    {
        $currentMonth = Carbon::now()->month;
        $nextMonth = Carbon::now()->addMonth()->month;
        $totalEquipment = Equipment::count();
        $certifyThisMonth = Equipment::where('certification_month', $currentMonth)->count();
        $certifyNextMonth = Equipment::where('certification_month', $nextMonth)->count();
        $certifyLaterThisYear = Equipment::whereBetween('certification_month', [$currentMonth + 2, 12])->count();
        $certifiedEarlierThisYear = Equipment::whereBetween('certification_month', [1, $currentMonth - 1])->count();

        return view('widgets::equipmentsListWidget', compact('totalEquipment', 'certifyThisMonth', 'certifyNextMonth', 'certifyLaterThisYear', 'certifiedEarlierThisYear'));
    }
}