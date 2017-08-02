<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|



*/

use App\Http\Middleware\CheckUserLevel;

Auth::routes();
Route::get('/logout', 'Auth\LoginController@logout');

//Route::auth();


// API ROUTES - NOTE: NO LOCALIZATION AS IT WILL BREAK THE REQUEST DUE TO REDIRECTS (urls without a specified language get redirected, breaks POST requests to GET)
Route::group(['before' => 'auth', 'middleware' => CheckUserLevel::class, 'prefix' => '/education-programs/api'],
    function () {

        // "API" for edu programs routes
        Route::get('education-programs', 'EducationProgramsController@getEducationalPrograms');
        Route::post('education-program/{program}/entity', 'EducationProgramsController@createEntity');
        Route::post('education-program/entity/{entity}/delete', 'EducationProgramsController@deleteEntity');
        Route::put('education-program/entity/{entity}', 'EducationProgramsController@updateEntity');
        Route::put('education-program/{program}', 'EducationProgramsController@updateProgram');
        Route::post('education-program/{program}/competence-description',
            'EducationProgramsController@createCompetenceDescription');
        Route::get('editable-education-program/{program}', 'EducationProgramsController@getEditableProgram');


    }
);

// Register the localization routes (e.g. /nl/rapportage will switch the language to NL)
// Note: The localisation is saved in a session state.
Route::group([
        'before' => 'auth',
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => [ 'localizationRedirect', 'usernotifications' ],
        ], function () {

    Route::group(['middleware' => CheckUserLevel::class], function () {
        Route::get('/education-programs', 'EducationProgramsController@index')
            ->name('education-programs');
    });


                // Catch the stat registration post

                Route::post('/log', 'LogController@log');

                /** ADD ALL LOCALIZED ROUTES INSIDE THIS GROUP **/

                // User Creation and modification
                Route::get('profiel', 'ProfileController@show')->name('profile');
                Route::post('profiel/update', 'ProfileController@update');

                // Category updating
                Route::post('categorie/update/{id}', 'ProducingWorkplaceLearningController@updateCategories')->name('categories-update')->where('id', '[0-9]*');
                // Learning Goal updating
                Route::post('learninggoal/update/{id}', 'ActingWorkplaceLearningController@updateLearningGoals')->name('learninggoals-update')->where('id', '[0-9]*');

                // Calendar Creation
                Route::get('deadline', 'CalendarController@show')->name('deadline');
                Route::post('deadline/create', 'CalendarController@create')->name('deadline-create');
                Route::post('deadline/update', 'CalendarController@update')->name('deadline-update');

                // Bugreport
                Route::get('bugreport', 'HomeController@showBugReport')->name('bugreport');
                Route::post('bugreport/create', 'HomeController@createBugReport')->name('bugreport-create');

                Route::group([
                                'middleware' => [ 'taskTypeRedirect' ],
                            ], function () {
                                /* Add all middleware redirected urls here */
                                Route::get('/', 'HomeController@showHome')->name('default');
                                Route::get('home', 'HomeController@showHome')->name('home');
                                Route::get('process', 'ActingActivityController@show')->name('process');
                                Route::get('progress/{page}', 'ProducingActivityController@progress')->where('page', '[1-9]{1}[0-9]*')->name('progress');
                                Route::get('analysis', 'ProducingActivityController@show')->name('analysis');
                                Route::get('period/create', 'ProducingWorkplaceLearningController@show')->name('period');
                                Route::get('period/edit/{id}', 'ProducingWorkplaceLearningController@edit')->name('period-edit')->where('id', '[0-9]*');
                            });

                /* EP Type: Acting */
                Route::group([
                                'prefix' => "/acting",
                            ], function () {
                                Route::get('home', 'HomeController@showActingTemplate')->name('home-acting');
                                Route::get('process', 'ActingActivityController@show')->name('process-acting');
                                Route::post('process/create', 'ActingActivityController@create')->name('process-acting-create');
                                Route::get('process/edit/{id}', 'ActingActivityController@edit')->name('process-acting-edit');
                                Route::post('process/update/{id}', 'ActingActivityController@update')->name('process-acting-update');

                                Route::get('progress/{page}', 'ActingActivityController@progress')->where('page', '[1-9]{1}[0-9]*')->name('progress-acting');

                                // Internships & Internship Periods
                                Route::get('period/create', 'ActingWorkplaceLearningController@show')->name('period-acting');
                                Route::get('period/edit/{id}', 'ActingWorkplaceLearningController@edit')->name('period-acting-edit')->where('id', '[0-9]*');
                                Route::post('period/create', 'ActingWorkplaceLearningController@create')->name('period-acting-create');
                                Route::post('period/update/{id}', 'ActingWorkplaceLearningController@update')->name('period-acting-update')->where('id', '[0-9]*');

                                // Report Creation
                                Route::get('analysis', 'ActingAnalysisController@show')->name('analysis-acting-choice');

                    // Download competence description
                    Route::get('competence-description/{competenceDescription}',
                        function (\App\CompetenceDescription $competenceDescription) {
                            return response()->download(storage_path('app/' . $competenceDescription->file_name),
                                "competence-description.pdf");
                        })->name('competence-description');

                });

                /* EP Type: Producing */
                Route::group([
                                'prefix' => "/producing",
                            ], function () {
                                Route::get('home', 'HomeController@showProducingTemplate')->name('home-producing');
                                Route::get('process', 'ProducingActivityController@show')->name('process-producing');
                                Route::post('process/create', 'ProducingActivityController@create')->name('process-producing-create');
                                Route::get('process/edit/{id}', 'ProducingActivityController@edit')->name('process-producing-edit');
                                Route::post('process/update/{id}', 'ProducingActivityController@update')->name('process-producing-update');

                                // Progress
                                Route::get('progress/{page}', 'ProducingActivityController@progress')->where('page', '[1-9]{1}[0-9]*')->name('progress-producing');
                                Route::get('report/export', 'ProducingReportController@export')->name('report-producing-export');

                                // Report Creation
                                Route::get('analysis', 'ProducingAnalysisController@showChoiceScreen')->name('analysis-producing-choice');
                                Route::get('analysis/{year}/{month}', 'ProducingAnalysisController@showDetail')->name('analysis-producing-detail');

                                // Feedback
                                Route::get('feedback/{id}', 'ProducingActivityController@feedback')->where('id', '[0-9]*')->name('feedback-producing');
                                Route::post('feedback/update/{id}', 'ProducingActivityController@updateFeedback')->name('feedback-producing-update');

                                // Internships & Internship Periods
                                Route::get('period/create', 'ProducingWorkplaceLearningController@show')->name('period-producing');
                                Route::get('period/edit/{id}', 'ProducingWorkplaceLearningController@edit')->name('period-producing-edit')->where('id', '[0-9]*');
                                Route::post('period/create', 'ProducingWorkplaceLearningController@create')->name('period-producing-create');
                                Route::post('period/update/{id}', 'ProducingWorkplaceLearningController@update')->name('period-producing-update')->where('id', '[0-9]*');

                                //Route::get('report/export',                     'ReportController@export')->name('report-producing-export');
                            });
        });
