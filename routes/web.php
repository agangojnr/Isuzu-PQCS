<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\scheduling\SchedulingController;
use App\Http\Controllers\appusers\AppUsersController;
use App\Http\Controllers\imports\ImportsController;
use App\Http\Controllers\qrcode\QrcodeController;
use App\Http\Controllers\routingquery\RoutingQueryController;
use App\Http\Controllers\shop\ShopController;
use App\Http\Controllers\vehicleunits\VehicleUnitsController;
use App\Http\Controllers\unitmodel\UnitModelController;
use App\Http\Controllers\unitmapping\UnitMappingController;
use App\Http\Controllers\workschedule\WorkScheduleController;
use App\Http\Controllers\querycategory\QuerycategoryController;
use App\Http\Controllers\Teamleader\TeamleaderController;
use App\Http\Controllers\vehicletype\VehicleTypeController;
use App\Http\Controllers\role\RoleController;
use App\Http\Controllers\unitroute\RoutesController;
use App\Http\Controllers\currentstage\CurrentStageController;
use App\Http\Controllers\checksheet\CheckSheetController;
use App\Http\Controllers\dashboard\DashboardController;
use App\Http\Controllers\productionreport\ProductionReportController;
use App\Http\Controllers\drrtarget\DrrTargetController;
use App\Http\Controllers\drltarget\DrlTargetController;
use App\Http\Controllers\floatsetting\FloatSettingController;
use App\Http\Controllers\drr\DrrController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\scheduledbatch\ScheduledBatchController;
use App\Http\Controllers\swapunit\SwapUnitController;


//People
use App\Http\Controllers\attendance\AttendanceController;
use App\Http\Controllers\employee\EmployeeController;
use App\Http\Controllers\stdworkinghrs\StdWorkingHrController;
use App\Http\Controllers\employeecategory\EmployeeCategoryController;
use App\Http\Controllers\attendancepreview\AttendancePreviewController;
use App\Http\Controllers\stafftitle\StaffTitleController;
use App\Http\Controllers\department\DepartmentController;
use App\Http\Controllers\division\DivisionController;
use App\Http\Controllers\systemusers\SystemUsersController;
use App\Http\Controllers\graph\GraphController;
use App\Http\Controllers\overtime\OvertimeController;
use App\Http\Controllers\gcascore\GcaScoreController;
use App\Http\Controllers\productiontarget\ProductiontargetController;
use App\Http\Controllers\reviewconversation\ReviewConversationController;
use App\Http\Controllers\outsource\OutsourceController;
use App\Http\Controllers\screenboard\ScreenboardController;
use App\Http\Controllers\Rerouting\ReroutingController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Route::get('clear-cache', function () {
    Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('view:clear');
});

Route::get('dashboard', [DashboardController::class,'dashboard'])->name('dashboard');
Auth::routes();
Route::middleware(['auth'])->group(function () {


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
 Route::resource('appusers', AppUsersController::class);
 Route::get('resetpassword/{id}', [AppUsersController::class,'resetpassword'])->name('resetpassword');
 Route::resource('scheduling', SchedulingController::class);
 Route::resource('attendance', AttendanceController::class);
 Route::resource('employee', EmployeeController::class);
 Route::resource('imports', ImportsController::class);
 Route::resource('qrcode', QrcodeController::class);
 Route::post('filterqrcode', [QrcodeController::class,'filterqrcode'])->name('filterqrcode');
 Route::get('qrcodefilterresult/{lot}/{job}/{model}', [QrcodeController::class,'qrcodefilterresult'])->name('qrcodefilterresult');
 
 Route::resource('routingquery', RoutingQueryController::class);

 //Route::post('load_options', [RoutingQueryController::class, 'load_query']);

 Route::post('load_options', [RoutingQueryController::class,'load_options'])->name('load_options');
 Route::post('load_quiz_options', [RoutingQueryController::class,'load_quiz_options'])->name('load_quiz_options');
 Route::post('load_total_answer', [RoutingQueryController::class,'load_total_answer'])->name('load_total_answer');




Route::resource('shops', ShopController::class);

 Route::resource('stdworkinghrs', StdWorkingHrController::class);

 Route::resource('vehicleunits', VehicleUnitsController::class);
 Route::get('get_units', [VehicleUnitsController::class,'get_units'])->name('get_units');
 Route::get('add_unit_row', [VehicleUnitsController::class,'add_unit_row'])->name('add_unit_row');
 Route::get('samples/{name}', [VehicleUnitsController::class,'samples'])->name('samples');
 Route::post('import_process', [VehicleUnitsController::class,'import_process'])->name('import_process');
 Route::get('vehicle_unit', [VehicleUnitsController::class,'vehicle_unit'])->name('vehicle_unit');
 Route::get('updateschedule/{id}', [VehicleUnitsController::class,'updateschedule'])->name('updateschedule');
 
 
 Route::resource('vehiclemodels', UnitModelController::class);
 Route::resource('unitmapping', UnitMappingController::class);
 Route::resource('unitroute', RoutesController::class);
 Route::resource('workschedule', WorkScheduleController::class);

 Route::resource('querycategory', QuerycategoryController::class);
 Route::get('addrouting/{name}', [QuerycategoryController::class,'addrouting'])->name('addrouting');
 Route::post('additionalquery', [QuerycategoryController::class,'additionalquery'])->name('additionalquery');
 Route::get('querylisting/{id}', [QuerycategoryController::class,'querylisting'])->name('querylisting');
 Route::post('loadlisting', [QuerycategoryController::class,'loadlisting'])->name('loadlisting');
 Route::get('changeanswer/{id}', [QuerycategoryController::class,'changeanswer'])->name('changeanswer');
 Route::get('editrouting/{id}', [QuerycategoryController::class,'editrouting'])->name('editrouting');
  Route::get('addquery/{id}', [QuerycategoryController::class,'addquery'])->name('addquery');
  Route::post('querysave', [QuerycategoryController::class,'querysave'])->name('querysave');
  Route::get('querybymodel', [QuerycategoryController::class,'querybymodel'])->name('querybymodel');
  Route::get('viewquerybymodel/{id}', [QuerycategoryController::class,'viewquerybymodel'])->name('viewquerybymodel');
  Route::get('quality_control_dashboard', [HomeController::class,'quality_control_dashboard'])->name('quality_control_dashboard');
  
  

  

  Route::post('saveanswer', [QuerycategoryController::class,'saveanswer'])->name('saveanswer');
  Route::post('updatequeryoption', [QuerycategoryController::class,'updatequeryoption'])->name('updatequeryoption');
 


 Route::delete('deletequeryoption/{id}', [QuerycategoryController::class,'deletequeryoption'])->name('deletequeryoption');
  Route::get('sortroutingquery', [QuerycategoryController::class, 'sortroutingquery'])->name('sortroutingquery');
    Route::post('reorder', [QuerycategoryController::class, 'reorder'])->name('reorder');
  Route::post('categoryreorder', [QuerycategoryController::class, 'categoryreorder'])->name('categoryreorder');

 

 Route::resource('roles', RoleController::class);

 Route::resource('teamleader', TeamleaderController::class);

 Route::resource('vehicletype', VehicleTypeController::class);
 Route::get('currentunitstage', [CurrentStageController::class,'currentunitstage'])->name('currentunitstage');
 Route::get('moveunit/{id}', [CurrentStageController::class,'moveunit'])->name('moveunit');
 Route::post('saveunitmovement', [CurrentStageController::class,'saveunitmovement'])->name('saveunitmovement');
 Route::get('markedunit', [CheckSheetController::class,'markedunit'])->name('markedunit');
 Route::get('checkmarkedsheet/{vid}', [CheckSheetController::class,'checkmarkedsheet'])->name('checkmarkedsheet');
 Route::get('checkdefects/{id}/{vid}/{shop_id}', [CheckSheetController::class,'checkdefects'])->name('checkdefects');
 Route::get('drl/{section}/{period}/{date}', [ProductionReportController::class,'drl'])->name('drl');
 Route::post('filtertoday', [ProductionReportController::class,'filtertoday'])->name('filtertoday');
 Route::resource('drltarget', DrlTargetController::class);
 Route::resource('drrtarget', DrrTargetController::class);
  Route::get('exportdrr/{section}/{from}/{to}/{target_id}', [ProductionReportController::class,'exportdrr'])->name('exportdrr');
  Route::get('exportdrl/{section}/{from}/{to}/{target_id}', [ProductionReportController::class,'exportdrl'])->name('exportdrl');
   Route::get('drr/{date}/{section}', [ProductionReportController::class,'drr'])->name('drr');
   Route::post('drrfiltertoday', [ProductionReportController::class,'drrfiltertoday'])->name('drrfiltertoday');
   Route::get('changedefect/{id}', [CheckSheetController::class,'changedefect'])->name('changedefect');
   Route::get('defectsummary{from}/{to}', [ProductionReportController::class,'defectsummary'])->name('defectsummary');
  Route::get('drrlist{from}/{to}', [ProductionReportController::class,'drrlist'])->name('drrlist');
 
   Route::post('updatedefect/{id}', [ProductionReportController::class,'updatedefect'])->name('updatedefect');
   Route::post('filterdefect', [ProductionReportController::class,'filterdefect'])->name('filterdefect');

   Route::resource('floatsetting', FloatSettingController::class);
 Route::resource('drrdata', DrrController::class);
 
  Route::post('filterdrrdefect', [ProductionReportController::class,'filterdrrdefect'])->name('filterdrrdefect');
   
 Route::resource('unitscheduled', ScheduledBatchController::class);
 Route::get('update_batch/{id}', [ScheduledBatchController::class,'update_batch'])->name('update_batch');
   Route::resource('swapunit', SwapUnitController::class);
  Route::post('swapunit', [SwapUnitController::class,'swapunit'])->name('swapunit');

  Route::post('swapunit/seach', [SwapUnitController::class,'search_swap_unit'])->name('search_swap_unit');
    Route::resource('rerouting', ReroutingController::class);
  Route::post('filtererouting', [ReroutingController::class, 'filtererouting'])->name('filtererouting');
  Route::get('completererouting/{id}', [ReroutingController::class, 'completererouting'])->name('completererouting');
  Route::post('saverouting', [ReroutingController::class, 'saverouting'])->name('saverouting');
    Route::post('updatedrr/{id}', [ProductionReportController::class,'updatedrr'])->name('updatedrr');





  
 //People
 Route::resource('systemusers', SystemUsersController::class);

 Route::resource('attendance', AttendanceController::class);

 Route::resource('employee', EmployeeController::class);

 Route::resource('stdworkinghrs', StdWorkingHrController::class);

 Route::resource('employeecategory', EmployeeCategoryController::class);

 Route::resource('stafftitle', StaffTitleController::class);

 Route::resource('department', DepartmentController::class);

 Route::resource('division', DivisionController::class);
 
  Route::resource('gcascore', GcaScoreController::class);
  
 Route::resource('outsource', OutsourceController::class);
  
  Route::resource('reviewconversation', ReviewConversationController::class);

 Route::resource('attendancepreview', AttendancePreviewController::class);

 Route::get('attendance_view', [AttendanceController::class, 'attendance_view'])->name('attendance_view');

 Route::get('confirmattendance', [AttendancePreviewController::class, 'confirmattendance']);

 Route::get('headcount', [AttendanceController::class, 'headcount']);

 Route::get('prodnoutput', [AttendanceController::class, 'prodnoutput']);

 Route::get('attendancereport', [AttendanceController::class, 'attendancereport']);

 Route::get('attendancesummary', [AttendanceController::class, 'attendacesummary']);

 Route::get('searchsummaryreport', [AttendanceController::class, 'searchsummaryreport']);

 Route::get('staffsummary', [EmployeeController::class, 'staffsummary']);

 Route::get('plantefficiency', [GraphController::class, 'plantefficiency'])->name('plantefficiency');

Route::get('markattencance', [AttendanceController::class, 'markattencance'])->name('markattencance');

Route::get('checkattendance', [AttendancePreviewController::class, 'checkattendance'])->name('checkattendance');

Route::get('resetpasswordsu/{id}', [SystemUsersController::class,'resetpasswordsu'])->name('resetpasswordsu');

Route::get('deleteUser/{id}', [SystemUsersController::class,'deleteUser'])->name('deleteUser');
Route::get('importemployee', [EmployeeController::class,'importemployee'])->name('importemployee');

Route::get('import_Employees', [EmployeeController::class,'import_Employees'])->name('import_Employees');

Route::post('import', [EmployeeController::class, 'import'])->name('import');

Route::get('sethours', [EmployeeController::class, 'sethours'])->name('sethours');

Route::post('setdefaulthrs', [EmployeeController::class, 'setdefaulthrs'])->name('setdefaulthrs');

Route::get('weeklystdhrs', [AttendanceController::class, 'weeklystdhrs'])->name('weeklystdhrs');

Route::get('weeklyactualhrs', [AttendanceController::class, 'weeklyactualhrs'])->name('weeklyactualhrs');

Route::get('peopleAttreport', [AttendanceController::class, 'peopleAttreport'])->name('peopleAttreport');

Route::get('settargets', [AttendanceController::class, 'settargets'])->name('settargets');

Route::get('createtargets', [AttendanceController::class, 'createtargets'])->name('createtargets');

Route::post('savetargets', [AttendanceController::class, 'savetargets'])->name('savetargets');

Route::get('reportsummary', [AttendanceController::class, 'reportsummary'])->name('reportsummary');

Route::get('yestreportsummary', [AttendanceController::class, 'yestreportsummary'])->name('yestreportsummary');

Route::get('plantattendancereg', [AttendanceController::class, 'plantattendancereg'])->name('plantattendancereg');

Route::resource('overtime', OvertimeController::class);

Route::get('markovertime', [OvertimeController::class, 'markovertime'])->name('markovertime');

Route::get('overtimereport', [OvertimeController::class, 'overtimereport'])->name('overtimereport');

Route::post('activate/{id}', [EmployeeController::class, 'activate'])->name('activate');

Route::get('otpreview', [OvertimeController::class, 'otpreview'])->name('otpreview');

Route::get('checkovertime', [OvertimeController::class, 'checkovertime'])->name('checkovertime');

Route::get('confirmovertime', [OvertimeController::class, 'confirmovertime'])->name('confirmovertime');

Route::post('previewstore', [OvertimeController::class, 'previewstore'])->name('previewstore');

Route::get('empsample/{name}', [EmployeeController::class,'empsample'])->name('empsample');

Route::post('attendancereport', [AttendanceController::class, 'attendancereport'])->name('attendancereport');

Route::post('settargets', [AttendanceController::class, 'settargets'])->name('settargets');

Route::post('destroytag/{id}', [AttendanceController::class, 'destroytag'])->name('destroytag');

Route::post('destroygcatag/{id}', [GcaScoreController::class, 'destroygcatag'])->name('destroygcatag');

Route::get('authorisedhrs', [OvertimeController::class, 'authorisedhrs'])->name('authorisedhrs');

Route::post('saveauthhrs', [OvertimeController::class, 'saveauthhrs'])->name('saveauthhrs');

Route::post('destroyauthhrs/{id}', [OvertimeController::class, 'destroyauthhrs'])->name('destroyauthhrs');

Route::get('trackperformance', [GraphController::class, 'trackperformance'])->name('trackperformance');

Route::get('checkloaned', [OvertimeController::class, 'checkloaned'])->name('checkloaned');

Route::get('approveloaned', [OvertimeController::class, 'approveloaned'])->name('approveloaned');

Route::get('assignshop', [SystemUsersController::class, 'assignshop'])->name('assignshop');

Route::get('assignsection', [SystemUsersController::class,'assignsection'])->name('assignsection');

Route::get('exporttoexcel', [OvertimeController::class,'exporttoexcel'])->name('exporttoexcel');

Route::get('productionschedule', [ProductiontargetController::class,'productionschedule'])->name('productionschedule');

Route::post('store', [ProductiontargetController::class,'store'])->name('store');

Route::post('update', [GcaScoreController::class,'update'])->name('update');

Route::get('trackperformance', [ProductiontargetController::class, 'trackperformance'])->name('trackperformance');

Route::get('gcalist', [GcaScoreController::class, 'gcalist'])->name('gcalist');

Route::get('exportstafftoexcel', [EmployeeController::class,'exportstafftoexcel'])->name('exportstafftoexcel');

Route::get('comments', [ProductiontargetController::class, 'comments'])->name('comments');

Route::get('editchedule/{id}', [ProductiontargetController::class, 'editchedule'])->name('editchedule');

Route::post('/destroyschedule/{id}', [ProductiontargetController::class, 'destroyschedule'])->name('destroyschedule');

Route::post('updateschedule', [ProductiontargetController::class, 'updateschedule'])->name('updateschedule');

Route::get('attendceregister', [AttendanceController::class, 'attendceregister'])->name('attendceregister');

Route::get('actualproduction', [ProductiontargetController::class, 'actualproduction'])->name('actualproduction');

Route::get('exportActualprodn', [ProductiontargetController::class,'exportActualprodn'])->name('exportActualprodn');

Route::get('exportattendRegister', [AttendanceController::class,'exportattendRegister'])->name('exportattendRegister');

Route::get('fcwschedule', [ProductiontargetController::class,'fcwschedule'])->name('fcwschedule');

Route::post('storefcwschedule', [ProductiontargetController::class,'storefcwschedule'])->name('storefcwschedule');

Route::get('bufferstatus', [ProductiontargetController::class,'bufferstatus'])->name('bufferstatus');

Route::get('sections', [ShopController::class,'sections'])->name('sections');

Route::post('savesections', [ShopController::class, 'savesections'])->name('savesections');

Route::get('overtimepdf', [OvertimeController::class,'overtimepdf'])->name('overtimepdf');

Route::get('attendRegisterpdf', [AttendanceController::class,'attendRegisterpdf'])->name('attendRegisterpdf');

Route::get('gcatarget', [GcaScoreController::class, 'gcatarget'])->name('gcatarget');

Route::get('mangcatarget', [GcaScoreController::class, 'mangcatarget'])->name('mangcatarget');

Route::get('delayedunits', [ProductiontargetController::class,'delayedunits'])->name('delayedunits');
});


Route::get('screenboard', [ScreenboardController::class,'screenboard'])->name('screenboard');

Route::get('screenboardindex', [ScreenboardController::class,'screenboardindex'])->name('screenboardindex');

Route::get('screenboardpershop', [ScreenboardController::class,'screenboardpershop'])->name('screenboardpershop');

Route::get('screenboarddefects/{shopid}', [ScreenboardController::class,'screenboarddefects'])->name('screenboarddefects');

Route::get('screenboardpershopReload', [ScreenboardController::class,'screenboardpershopReload'])->name('screenboardpershopReload');

Route::get('screenboardindexReload', [ScreenboardController::class,'screenboardindexReload'])->name('screenboardindexReload');

Route::get('screenboardall', [ScreenboardController::class,'screenboardall'])->name('screenboardall');
Route::post('load_defects', [ScreenboardController::class,'load_defects'])->name('load_defects');
Route::post('load_datable_defects', [ScreenboardController::class,'load_datable_defects'])->name('load_datable_defects');






