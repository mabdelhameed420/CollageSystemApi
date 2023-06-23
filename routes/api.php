<?php

use App\Http\Controllers\StudentAffairController;
use App\Http\Controllers\StudentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LecturerController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\RealtimeController;
use App\Models\Realtime;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
//? 1==>========================Student Affairs===================
Route::group(['prefix' => 'student-affairs', 'namespace' => ''], function () {

    Route::post('add', 'StudentAffairController@store')->middleware('checkToken:api-admins');
    Route::post('update', 'StudentAffairController@update');
    Route::post('login', 'StudentAffairController@login');
    Route::get('logout', 'StudentAffairController@logout');
    Route::get('all', 'StudentAffairController@getAllStudentAffairs');
    Route::delete('delete/{id}', 'StudentAffairController@delete');
});
//? 2==>==============================Student============================
Route::group(['prefix' => 'student', 'namespace' => ''], function () {

    Route::post('add', 'StudentController@store');
    Route::post('update', 'StudentController@update');
    Route::post('delete', 'StudentController@delete');
    Route::get('get', 'StudentController@index');
    Route::post('login', 'StudentController@login');
    Route::post('logout', 'StudentController@logout');
    Route::get('getAllStudentByDepartmentId/{departmentId}', 'StudentController@getAllStudentByDepartmentId');
    Route::post('{student_id}/update-fcm-token', 'StudentController@updateFcmTokenByStudentId');
    Route::get('all', 'StudentController@getAllStudents');
    Route::delete('delete/{id}', 'StudentController@destroy');
});
//? 3==>==================Lecturer============================
Route::group(['prefix' => 'lecturer', 'namespace' => 'Api\Auth'], function () {

    Route::post('add', 'LecturerController@store');
    Route::post('update', 'LecturerController@update');
    Route::post('delete', 'LecturerController@delete');
    Route::get('get', 'LecturerController@index');
    Route::post('login', 'LecturerController@login');
    Route::get('logout', 'LecturerController@logout');
    Route::get('getLecturersById/{id}', 'LecturerController@getLecturerById');
    Route::get('getClassroomByLecturerId/{id}', 'LecturerController@getClassroomByLecturerId');
    Route::get('all', 'LecturerController@getAllLecturers');
    Route::delete('delete/{id}', 'LecturerController@destroy');
});
//? 4==>================= department ====================
Route::group(['prefix' => 'department', 'namespace' => ''], function () {

    Route::get('get', 'DepartmentController@index');
    Route::post('add', 'DepartmentController@store');
    Route::post('update', 'DepartmentController@update');
    Route::post('delete', 'DepartmentController@delete');
    Route::get('all', 'DepartmentController@allDepartments');
    Route::get('allCourses/{department_id}', 'DepartmentController@getAllCoursesByDepartmentId');
    Route::get('getClassroomIdByDepartmentId/{id}', 'DepartmentController@getClassroomIdByDepartmentId');
    Route::get('{id}', 'DepartmentController@getDepartmentById');

});
//? 5==>================= chat ====================
Route::group(['prefix' => 'chat', 'namespace' => ''], function () {

    Route::post('update', 'ChatController@update');
    Route::post('delete/{id}', 'ChatController@delete');
    Route::get('get', 'ChatController@index');
    Route::post('add', 'ChatController@store');
    Route::get('getMessages/{chat_id}', 'ChatController@getMessagesByChatId');
    Route::get('getChatsByStudentId/{student_id}', 'ChatController@getChatsByStudentId');
    Route::get('getChatsByLecturerId/{lecturer_id}', 'ChatController@getChatsByLecturerId');
    Route::get('getChatsByStudentAffairId/{student_affair_id}', 'ChatController@getChatsByStudentAffairId');
});

//? 6==>================= course ====================
Route::group(['prefix' => 'course', 'namespace' => ''], function () {

    Route::post('add', 'CourseController@store');
    Route::post('update', ' CourseController@update');
    Route::post('delete/{id}', 'CourseController@destroy');
    Route::get('get', 'CourseController@index');
    Route::get('all', 'CourseController@getAllCourses');
    Route::get('getCoursesByDepartmentId/{id}', 'CourseController@getCoursesByDepartmentId');
});

//? 7==>================= Message ====================
Route::post('/message/add', MessageController::class . '@store');
Route::post('/message/update', MessageController::class . '@update');
Route::post('/message/delete', MessageController::class . '@delete');
Route::get('/message/get', MessageController::class . '@index');
Route::get('/message/getMessagesByChatId/{chat_id}', MessageController::class . '@getMessagesByChatId');
Route::delete('/message/deleteMessageById/{id}', MessageController::class . '@deleteMessageById');
Route::get('/message/getMessagesByClassroomId/{classroom_id}', MessageController::class . '@getMessagesByClassroomId');
//? 8==>================= Admin ====================

Route::post('/admin/add', AdminController::class . '@store');
Route::post('/admin/update', AdminController::class . '@update');
Route::post('/admin/delete', AdminController::class . '@delete');
Route::get('/admin/get', AdminController::class . '@index');
Route::get('/admin/login/{national_id}/{password}', AdminController::class . '@login');

//? 9==>================= classroom ====================
Route::post('/classroom/add', ClassroomController::class . '@store');
Route::post('/classroom/update', ClassroomController::class . '@update');
Route::post('/classroom/delete', ClassroomController::class . '@delete');
Route::get('/classroom/get', ClassroomController::class . '@index');
Route::get('/classroom/getClassroomsByDepartmentId/{id}', ClassroomController::class . '@getClassroomsByDepartmentId');
Route::get('/classroom/getCourseNameByClassroomId/{classroom_id}', ClassroomController::class . '@getCourseNameByClassroomId');
Route::get('/classroom/getClassroomByLecturerId/{lecturer_id}', ClassroomController::class . '@getClassroomByLecturerId');
//? 10==>================= post ====================
Route::post('/post/add', PostController::class . '@store');
Route::post('/post/update', PostController::class . '@update');
Route::get('/posts/getAll', PostController::class . '@getAllPosts');
Route::get('/posts/student/{student_id}', PostController::class . '@getPostsByStudentId');
Route::get('/posts/lecturer/{lecturer_id}', PostController::class . '@getPostsByLecturerId');
Route::get('posts/student-affairs/{student_affairs_id}', PostController::class . '@getPostsByStudentAffairsId');
Route::get('/posts/{student_id}/{student_affairs_id}/{lecturer_id}', PostController::class . '@getPostsAndNameByStudentIdOrStudentAffairsIdOrLecturerId');
Route::get('/posts/{student_affairs_id}', PostController::class . '@getPostsByStudentAffairsId');
Route::delete('/posts/delete/{id}/{student_id}', PostController::class . '@deletePostByIdAndStudentId');
Route::delete('/posts/deletebystudentid/{id}/{student_id}', PostController::class . '@checkStudentIsPostStudentAndDelete');
Route::delete('/posts/deletebylecturerid/{id}/{lecturer_id}', PostController::class . '@checkLecturerIsPostLecturerAndDelete');
Route::delete('/posts/deletebystudentaffairsid/{id}/{student_affairs_id}', PostController::class . '@checkStudentAffairsIsPostStudentAffairsAndDelete');
Route::get('/posts/searchInPosts/{search}', PostController::class . '@searchInPosts');
//addRectOnPost
Route::post('/posts/{id}/react', PostController::class . '@addRectOnPost');
//? 11==>================= comment ====================
Route::post('/comment/add', CommentController::class . '@store');
Route::post('/comment/update', CommentController::class . '@update');
Route::post('/comment/delete', CommentController::class . '@delete');
Route::get('/comment/get', CommentController::class . '@index');
Route::get('/comment/getCommentsByPostId/{post_id}', CommentController::class . '@getCommentsByPostId');

//? 12==>================= reply comment ====================

Route::post('/reply-comment/add', ReplyCommentController::class . '@store');
Route::post('/reply-comment/update', ReplyCommentController::class . '@update');
Route::post('/reply-comment/delete', ReplyCommentController::class . '@delete');
Route::get('/reply-comment/get', ReplyCommentController::class . '@index');

//? 13==>================= quiz ====================
Route::post('/quiz/add', QuizController::class . '@store');
Route::post('/quiz/update', QuizController::class . '@update');
Route::post('/quiz/delete', QuizController::class . '@delete');
Route::get('/quiz/get', QuizController::class . '@index');

//? 14==>================= question ====================
Route::post('/question/add', QuestionController::class . '@store');
Route::post('/question/update', QuestionController::class . '@update');
Route::post('/question/delete', QuestionController::class . '@delete');
Route::get('/question/get', QuestionController::class . '@index');
Route::get('/question/getQuestionsByQuizId', QuestionController::class . '@getQuestionsByQuizId');
Route::get('/question/getQuestionsByQuizIdAndLecturerId/{quiz_id}/{lecturer_id}', QuestionController::class . '@getQuestionsByQuizIdAndLecturerId');


//? 15==>================= rating ======================
Route::post('/rating/add', RatingController::class . '@store');



//? 16==>================= notificatins ======================

Route::post('/notification/sendNotification', NotificationController::class . '@sendNotification');
Route::post('/notification/sendNotificationsForAllStudents', NotificationController::class . '@sendNotificationForAllUsersByFCMToken');
//? 17==>================= realtime ======================
Route::get('/realtime/livestrated/{classroom_id}', RealtimeController::class . '@stratLive');
Route::get('/realtime/finishLive/{classroom_id}', RealtimeController::class . '@closeLive');
Route::post('/realtime/updateStatus/{student_id}/{is_online}', RealtimeController::class . '@updateStatus');
Route::post('/realtime/isQuizStarted/{student_id}/{is_quiz_started}', RealtimeController::class . '@startQuiz');
Route::post('/realtime/finishLive/{student_id}/{is_live}', RealtimeController::class . '@finishLive');
Route::get('/realtime/isLive/{student_id}', RealtimeController::class . '@getIsLive');
Route::get('/realtime/isQuizStarted/{student_id}', RealtimeController::class . '@getIsQuizStarted');
Route::get('/realtime/isOnline/{student_id}', RealtimeController::class . '@getIsOnline');
Route::get('/realtime/push-quiz/{quiz_id}', RealtimeController::class . '@addQuiz');
Route::get('/realtime/end-quiz/{quiz_id}', RealtimeController::class . '@endQuiz');
