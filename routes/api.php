<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::middleware('protect.api')->group(function () {
    // Protected API routes

    //? 1==>========================Student Affairs===================
    Route::group(['prefix' => 'student-affairs','namespace' => 'Api\StudentAffair'], function () {
        Route::middleware('checkToken:api-admins')->group(function () {
            Route::delete('delete/{id}', 'StudentAffairController@delete');
            Route::post('add', 'StudentAffairController@store');
            Route::get('all', 'StudentAffairController@getAllStudentAffairs');
        });
        Route::post('update', 'StudentAffairController@update');
        Route::post('login', 'StudentAffairController@login');
        Route::get('logout', 'StudentAffairController@logout')->middleware(/*'checkToken:api-affairs'*/);
    });
    //? 2==>==============================Student============================
    Route::group(['prefix' => 'student', 'namespace' => 'Api\Student'], function () {

        Route::middleware('checkToken:api-affairs')->group(function () {
            Route::post('add', 'StudentController@store');
            Route::post('delete', 'StudentController@delete');
            Route::get('get', 'StudentController@index');
            Route::get('getAllStudentByDepartmentId/{departmentId}', 'StudentController@getAllStudentByDepartmentId');
            Route::post('{student_id}/update-fcm-token', 'StudentController@updateFcmTokenByStudentId');
            Route::delete('delete/{id}', 'StudentController@destroy');
            Route::get('all', 'StudentController@getAllStudents');
        });
        Route::post('update', 'StudentController@update');
        Route::post('login', 'StudentController@login');
        Route::post('logout', 'StudentController@logout')->middleware(/*'checkToken:api-students'*/);
    });
    //? 3==>==================Lecturer============================
    Route::group(['prefix' => 'lecturer','namespace' => 'Api\Lecturer'], function () {
        Route::group(['middleware' => 'checkToken:api-affairs'], function () {

            Route::post('add', 'LecturerController@store');
            Route::post('delete', 'LecturerController@delete');
            Route::get('getLecturersById/{id}', 'LecturerController@getLecturerById');
            Route::get('getClassroomByLecturerId/{id}', 'LecturerController@getClassroomByLecturerId');
            Route::delete('delete/{id}', 'LecturerController@destroy');
            Route::get('all', 'LecturerController@getAllLecturers');
            Route::get('get', 'LecturerController@index');
        });
        Route::get('logout', 'LecturerController@logout')->middleware('checkToken:api-lecturers');
        Route::post('update', 'LecturerController@update');
        Route::post('login', 'LecturerController@login');
    });
    //? 4==>================= department ====================
    Route::group(['prefix' => 'department','namespace' => 'Api\Department'], function () {
        Route::group(['middleware' => 'checkToken:api-affairs'], function () {

            Route::get('get', 'DepartmentController@index');
            Route::post('add', 'DepartmentController@store');
            Route::post('update', 'DepartmentController@update');
            Route::post('delete', 'DepartmentController@delete');
        });
        Route::get('all', 'DepartmentController@allDepartments');
        Route::get('allCourses/{department_id}', 'DepartmentController@getAllCoursesByDepartmentId');
        Route::get('getClassroomIdByDepartmentId/{id}', 'DepartmentController@getClassroomIdByDepartmentId');
        Route::get('{id}', 'DepartmentController@getDepartmentById');
    });
    //? 5==>================= chat ====================
    Route::group(['prefix' => 'chat','namespace' => 'Api\Chat'], function () {

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
    Route::group(['prefix' => 'course','namespace' => 'Api\Course'], function () {
        Route::group(['middleware' => 'checkToken:api-affairs'], function () {

            Route::post('add', 'CourseController@store');
            Route::post('update', ' CourseController@update');
            Route::post('delete/{id}', 'CourseController@destroy');
        });
        Route::get('get', 'CourseController@index');
        Route::get('all', 'CourseController@getAllCourses');
        Route::get('getCoursesByDepartmentId/{id}', 'CourseController@getCoursesByDepartmentId');
    });

    //? 7==>================= Message ====================
    Route::group(['prefix' => 'message','namespace' => 'Api\Message'], function () {

        Route::post('add', 'MessageController@store');
        Route::post('update', 'MessageController@update');
        Route::post('delete', 'MessageController@delete');
        Route::get('get', 'MessageController@index');
        Route::get('getMessagesByChatId/{chat_id}', 'MessageController@getMessagesByChatId');
        Route::delete('deleteMessageById', 'MessageController@deleteMessageById');
        Route::delete('deleteMessageById/{id}', 'MessageController@deleteMessageById');
        Route::get('getMessagesByClassroomId/{classroom_id}', 'MessageController@getMessagesByClassroomId');
    });
    //? 8==>================= Admin ====================
    Route::group(['prefix' => 'admin','namespace' => 'Api\Admin'], function () {

        Route::put('update', 'AdminController@update');
        Route::delete('delete', 'AdminController@delete');
        Route::get('get', 'AdminController@index');
        Route::post('login', 'AdminController@login');
        Route::post('logout', 'AdminController@logout');
    });
    //? 9==>================= classroom ====================
    Route::group(['prefix' => 'classroom','namespace' => 'Api\Classroom'], function () {
        Route::group(['middleware' => 'checkToken:api-affairs',], function () {

            Route::post('add', 'ClassroomController@store');
            Route::post('update', 'ClassroomController@update');
            Route::post('delete', 'ClassroomController@delete');
        });
        Route::get('get', 'ClassroomController@index');
        Route::get('getClassroomsByDepartmentId/{id}', 'ClassroomController@getClassroomsByDepartmentId');
        Route::get('getCourseNameByClassroomId/{classroom_id}', 'ClassroomController@getCourseNameByClassroomId');
        Route::get('getClassroomByLecturerId/{lecturer_id}', 'ClassroomController@getClassroomByLecturerId');
    });
    //? 10==>================= post ====================
    Route::group(['prefix' => 'post','namespace' => 'Api\Post'], function () {

        Route::post('add', 'PostController@store');
        Route::post('update', 'PostController@update');
        Route::get('getAll', 'PostController@getAllPosts');
        Route::get('student/{student_id}', 'PostController@getPostsByStudentId');
        Route::get('lecturer/{lecturer_id}', 'PostController@getPostsByLecturerId');
        Route::get('student-affairs/{student_affairs_id}', 'PostController@getPostsByStudentAffairsId');
        Route::get('getpostbyid/{student_id}/{student_affairs_id}/{lecturer_id}', 'PostController@getPostsAndNameByStudentIdOrStudentAffairsIdOrLecturerId');
        Route::get('{getpost/student_affair/{student_affairs_id}', 'PostController@getPostsByStudentAffairsId');
        Route::delete('delete/student/delete/{id}/{student_id}', 'PostController@deletePostByIdAndStudentId');
        Route::delete('deletebystudentid/{id}/{student_id}', 'PostController@checkStudentIsPostStudentAndDelete');
        Route::delete('deletebylecturerid/{id}/{lecturer_id}', 'PostController@checkLecturerIsPostLecturerAndDelete');
        Route::delete('deletebystudentaffairsid/{id}/{student_affairs_id}', 'PostController@checkStudentAffairsIsPostStudentAffairsAndDelete');
        Route::get('searchInPosts/{search}', 'PostController@searchInPosts');
        //addRectOnPost
        Route::post('{id}/react', 'PostController@addRectOnPost');
    });
    //? 11==>================= comment ====================

    Route::group(['prefix' => 'comment','namespace' => 'Api\Post'], function () {

        Route::post('add', 'CommentController@store');
        Route::post('update', 'CommentController@update');
        Route::post('delete', 'CommentController@delete');
        Route::get('get', 'CommentController@index');
        Route::get('getCommentsByPostId/{post_id}', 'CommentController@getCommentsByPostId');
    });
    //? 12==>================= reply comment ====================

    Route::group(['prefix' => 'reply-comment','namespace' => 'Api\Post'], function () {

        Route::post('add', 'ReplyCommentController@store');
        Route::post('update', 'ReplyCommentController@update');
        Route::post('delete', 'ReplyCommentController@delete');
        Route::get('get', 'ReplyCommentController@index');
    });
    //? 13==>================= quiz ====================
    Route::group(['prefix' => 'quiz','namespace' => 'Api\Quiz','middleware' => 'checkToken:api-lecturers'], function () {

        Route::post('add', 'QuizController@store');
        Route::post('update', 'QuizController@update');
        Route::post('delete', 'QuizController@delete');
    });
    Route::get('quiz/get', 'QuizController@index');
    //? 14==>================= question ====================
    Route::group(['prefix' => 'question','namespace' => 'Api\Quiz'], function () {
    Route::group(['middleware' =>'checkToken:api-lecturers'], function () {

            Route::post('add', 'QuestionController@store');
            Route::post('update', 'QuestionController@update');
            Route::post('delete', 'QuestionController@delete');
        });
        Route::get('get', 'QuestionController@index');
        Route::get('getQuestionsByQuizId', 'QuestionController@getQuestionsByQuizId');
        Route::get('getQuestionsByQuizIdAndLecturerId/{quiz_id}/{lecturer_id}', 'QuestionController@getQuestionsByQuizIdAndLecturerId');
    });

    //? 15==>================= rating ======================
    Route::namespace('Api\Quiz')->post('/rating/add', 'RatingController@store');

    //? 16==>================= notificatins ======================
    Route::group(['prefix' => 'notification','namespace' => 'Api\Notification'], function () {
        Route::post('sendNotification', 'NotificationController@sendNotification');
        Route::post('sendNotificationsForAllStudents', 'NotificationController@sendNotificationForAllUsersByFCMToken');
    });
    //? 17==>================= realtime ======================
    Route::group(['prefix' => 'realtime','namespace' => 'Api\Realtime'], function () {

        Route::get('livestrated/{classroom_id}', 'RealtimeController@stratLive');
        Route::get('finishLive/{classroom_id}', 'RealtimeController@closeLive');
        Route::post('updateStatus/{student_id}/{is_online}', 'RealtimeController@updateStatus');
        Route::post('isQuizStarted/{student_id}/{is_quiz_started}', 'RealtimeController@startQuiz');
        Route::post('finishLive/{student_id}/{is_live}', 'RealtimeController@finishLive');
        Route::get('isLive/{student_id}', 'RealtimeController@getIsLive');
        Route::get('isQuizStarted/{student_id}', 'RealtimeController@getIsQuizStarted');
        Route::get('isOnline/{student_id}', 'RealtimeController@getIsOnline');
        Route::get('push-quiz/{quiz_id}', 'RealtimeController@addQuiz');
        Route::get('end-quiz/{quiz_id}', 'RealtimeController@endQuiz');
    });
});
