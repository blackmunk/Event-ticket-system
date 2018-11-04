<?php
use App\Newsletter;
use Intervention\Image\Image;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;


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

//route to send mail
Route::get('/sendmail', 'ContactsController@sendMail');

//about us page route
Route::get('aboutus', function(){
 return view('aboutus');
})->name('aboutus');


//Route for index
Route::get('/', 'IndexController')->name('/');

Route::get('/posts/{id}', 'BlogController@show');

//Route for Error page
Route::get('404', 'UsersController@errorpage');

//Route for contactus query message
Route::match(['get', 'post'], '/contactus', 'ContactsController@store')->name('contactus');

//Route for newsletter subscription
Route::post('/newsletter', 'NewslettersController@saveNewsletterSubscriber');

//Route for comments
Route::post('/add-comment-event', 'EventscommentController@store');

//Route for category
Route::get('/category/{id}', 'CategoryController@index');

//Authentication routes
Auth::routes();

//home route
Route::get('/home', 'HomeController@index')->name('home');


//Event routes
Route::group(['prefix' => 'events'], function() {

    Route::get('', 'EventsController@index')->name('events');
    //Route for single page events
    Route::get('{id}', 'EventsController@show');

});

//Admin Routes
Route::group(['prefix' => 'system-admin', 'as' => 'system-admin.', 'middleware' => ['auth', 'isAdmin', 'can:is-Admin']], function() {

    //Resourceful routes
    Route::resource('admin/categories', 'Admin\CategoryController', ['except' => 'show']);
    Route::resource('admin/events', 'Admin\EventsController', ['except' => 'show']);
    Route::resource('admin/posts', 'Admin\BlogsController', ['except' => 'show']);
    Route::resource('admin/users', 'Admin\UsersController');
    Route::resource('admin/subscribers', 'Admin\NewslettersController');
    Route::resource('admin/messages', 'Admin\ContactsController', ['only' => ['index', 'destroy']]);
    Route::resource('admin/profile', 'Admin\ProfileController');
    Route::resource('admin/notification', 'Admin\notificationController');
    Route::get('system-admin/admin/delete-notification', 'Admin\NotificationController@deleteNotification')->name('admin.delete-notification');
    Route::get('system-admin/admin/view-notifications', 'Admin\NotificationController@viewNotifications')->name('admin.view-notifications');

    //Admin password controller routes
    Route::get('admin/change-password', 'Admin\PasswordController@index');
    Route::post('admin/update-password', 'Admin\PasswordController@update');
    
    Route::get('admin/compose-mail', function() {
            return view('admin.subscribers.composemail');
    });

    //activate and de-activate event's route
    Route::get('admin/activate/{id}', 'Admin\EventsController@activate');
    Route::get('admin/de-activate/{id}', 'Admin\EventsController@deActivate');

    //events comment route
    Route::get('admin/view-comments/{id}', 'Admin\EventsController@viewComments');
    Route::get('admin/delete-comment/{id}', 'Admin\EventsController@deleteComment');
    Route::get('admin/activate-comment/{id}', 'Admin\EventsController@activateComment');
    Route::get('admin/de-activate-comment/{id}', 'Admin\EventsController@deactivateComment');

    Route::get('admin/eventsimagesliders/create', 'Admin\EventsliderimagesController@create')->name('eventsimagesliders.create');
    Route::get('admin/eventsimagesliders', 'Admin\EventsliderimagesController@index')->name('eventsimagesliders.index');
    Route::post('admin/eventsimagesliders/edit', 'Admin\EventsliderimagesController@edit')->name('eventsimagesliders.edit');
    Route::post('admin/eventsimagesliders/destroy', 'Admin\EventsliderimagesController@destroy')->name('eventsimagesliders.destroy');
    Route::post('admin/eventsimagesliders', 'Admin\EventsliderimagesController@store')->name('eventsimagesliders.store');

    //all transactions in the system route
    Route::get('admin/transactions', 'Admin\TransactionController@index')->name('admin.transactions');

});

//User's Routes
Route::group(['prefix' => 'user', 'as' => 'user.', 'middleware' => ['isUser', 'auth', 'can:is-User']], function(){
     Route::resource('profile', 'user\ProfileController');
     Route::resource('events', 'user\EventsController');
});

//User's Routes
Route::group(['middleware' => ['isUser', 'auth', 'can:is-User']], function() {
     Route::get('change-password', 'user\PasswordController@index')->name('user.password');
     Route::post('change-password', 'user\PasswordController@update')->name('user.password.update');
     Route::get('user/delete-account/{id}', 'user\ProfileController@deleteAccount')->name('user.account.delete');
     Route::get('user/read-notification', function(){
        Auth::user()->unreadNotifications->markAsRead();
        return back();
    });
    Route::get('user/transactions', 'user\TransactionController@index')->name('user.transaction');
});


Route::group(['middleware' => 'auth'], function() {
    //paymentcontroller routes
    Route::post('/makepayment', 'PaymentController@redirectToProvider');
    Route::get('/payment/callback', 'PaymentController@handleGatewayCallback');    
});


Route::get('test-author', function() {
    return "hey";
})->middleware('can:is-Admin');








