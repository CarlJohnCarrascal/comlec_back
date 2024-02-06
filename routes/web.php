<?php

use App\Http\Controllers\Api\AuthController;
use App\Models\Survey;
use App\Models\WebVisit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Illuminate\Support\Facades\Route;
use Symfony\Component\Mailer\Transport\Smtp\Auth\LoginAuthenticator;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
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


Route::get('/email/verify', function () {
  return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
  $request->fulfill();

  return redirect('/');
})->middleware(['auth', 'signed'])->name('verification.verify');
Route::post('/email/verification-notification', function (Request $request) {
  $request->user()->sendEmailVerificationNotification();

  return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');
Route::post('/email/resend', function (Request $request) {
  $request->user()->sendEmailVerificationNotification();

  return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.resend');


Route::group(['middleware' => ['auth']], function () {
  Route::get('/logout', [AuthController::class, 'logout']);
  
  Route::post('/update-account', [AuthController::class, 'update']);
});




Route::group(['middleware' => ['guest']], function () {

  Route::post('/register', [AuthController::class, 'register']);
  Route::get('/login', ['as' => 'login', 'uses' => function () {
    return view('login');
  }]);

  Route::get('/register', ['as' => 'register', 'uses' => function () {
    return view('register');
  }]);
  Route::post('/login', [AuthController::class, 'login']);
});

Route::get('/{any}', function (Request $request) {
  return appview();
})->where('any', '.*')->middleware(['auth', 'verified']);

function appview()
{
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
    ->whereDate('created_at', '=', date('Y-m-d'))->orderBy('created_at', 'desc')->get();
  if ($dd->count() <= 0) {
    WebVisit::create([
      'ip_address' => $clientIP
    ]);
  } else {
    $d = $dd[0]->created_at;
    $time = strtotime(date('Y-m-d H:i:s'));
    $d2 = date_create(date('Y-m-d H:i:s', $time));
    $df = date_diff($d2, $d);
    if ($df->i >= 1) {
      WebVisit::create([
        'ip_address' => $clientIP
      ]);
    }
    //echo json_encode([$df->m, $df]);
  }
  //echo json_encode([date_diff($d2,$d), $d, $d2]);

  //$s = FacadesRequest::segment(1);

  $isAuth = Auth::check();

  if($isAuth) {
    $user = Auth::user();
    if($user->name == '' 
      || $user->alias == ''
      || $user->color == ''
      || $user->type == ''){
        return view('setup');
    }
  }

  return view('welcome');

  // if($isAuth == true) return view('welcome');
  // else {
  //   if($s == 'register') return view('register');
  //   if($s !== 'login') return redirect('login');
  //   return view('login');
  // }

}
