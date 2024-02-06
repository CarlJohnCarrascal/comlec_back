<?php

use App\Http\Controllers\Api\AuthController;
use App\Models\WebVisit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Illuminate\Support\Facades\Route;
use Symfony\Component\Mailer\Transport\Smtp\Auth\LoginAuthenticator;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/v2', function () {
//   return view('v2');
// });


Route::group(['middleware' => ['auth']], function() {

  Route::get('/logout', [AuthController::class, 'logout']);

});

Route::get('/{any}', function (Request $request) {
  date_default_timezone_set('Asia/Manila');
  $clientIP = $_SERVER['REMOTE_ADDR'];

  // $alreadyVisited = WebVisit::where('ip_address', $clientIP)
  //   ->whereDate('created_at', '=', date('Y-m-d'))->count();
  // if ($alreadyVisited == 0) {
  //   WebVisit::create([
  //     'ip_address' => $clientIP
  //   ]);
  // }
  $dd = WebVisit::where('ip_address', $clientIP)
  ->whereDate('created_at', '=', date('Y-m-d'))->orderBy('created_at','desc')->get();
  if ($dd->count() <= 0) {
    WebVisit::create([
      'ip_address' => $clientIP
    ]);
  }else{
    $d = $dd[0]->created_at;
    $time = strtotime(date('Y-m-d H:i:s'));
    $d2 = date_create(date('Y-m-d H:i:s', $time));
    $df = date_diff($d2,$d);
    if ($df->i >= 1) {
      WebVisit::create([
        'ip_address' => $clientIP
      ]);
    }
    //echo json_encode([$df->m, $df]);
  }
  //echo json_encode([date_diff($d2,$d), $d, $d2]);

  $s = FacadesRequest::segment(1);

  if(Auth::check()) return view('welcome');
  else {
    if($s == 'register') return view('register');
    if($s !== 'login') return redirect('login');
    return view('login');
  }

})->where('any', '.*');


Route::group(['middleware' => ['guest']], function() {

  Route::post('/register', [AuthController::class, 'register']);
  Route::get('login', [ 'as' => 'login', 'uses' => function(){
    return view('login');
  }]);
  Route::post('/login', [AuthController::class, 'login']);
});
