

## setup 1
php artisan install:api

## setup 2
composer require tymon/jwt-auth

## setup 3
php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"

## setup 4
php artisan jwt:secret

## setup 5
JWT_SECRET=your_secret_key //optional add manually in .env file
if not add manually then add this in config/auth.php file

## setup 6
add this in config/auth.php file
'guards' => [
    'api' => [
        'driver' => 'jwt',
        'provider' => 'users',
    ],
],

## setup 7

add this in User.php file

use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    protected $fillable = ['name', 'email', 'password'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array{
        return [
            'password' => 'hashed',
        ];
    }

    public function getJWTIdentifier(){
        return $this->getKey();
    }

    public function getJWTCustomClaims(){
        return [];
    }
}


## setup 8

routes/api.php file

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;

Route::post("/user/register", [ApiController::class, "userRegister"]);
Route::post("/user/login", [ApiController::class, "userLogin"]);

Route::middleware('auth:api')->group(function () {
    Route::get("/user/profile", [ApiController::class, "profile"]);
});


## ApiController.php file

 $user = User::create([
        'name' => $req->name,
        'email' => $req->email,
        'password' => Hash::make($req->password),
    ]);

    $token = auth('api')->login($user);


## passsing
Authorization: Bearer YOUR_TOKEN
Accept: application/json




        