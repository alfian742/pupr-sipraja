<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\IkliSurvey\HomeController as IkliSurveyHomeController;
use Illuminate\Support\Facades\Route;

Route::middleware(['visitor'])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/development', [HomeController::class, 'development'])->name('development');
    Route::get('/public-services', [HomeController::class, 'publicServices'])->name('public-services');

    Route::name('organization-profiles.')->group(function () {
        Route::get('/organization-structure', [HomeController::class, 'organizationStructure'])->name('organization-structure');
        Route::get('/vision-and-mission', [HomeController::class, 'visionMission'])->name('vision-and-mission');
        Route::get('/personnel-profiles', [HomeController::class, 'personnelProfile'])->name('personnel-profiles');
    });

    Route::name('other-informations.')->group(function () {
        Route::get('/faqs', [HomeController::class, 'faq'])->name('faqs');
        Route::prefix('download-center')->name('download-center.')->group(function () {
            Route::get('/', [HomeController::class, 'downloadCenterIndex'])->name('index');
            Route::get('/{slug}', [HomeController::class, 'downloadCenterShow'])->name('show');
        });
    });

    Route::prefix('blog')->name('blog.')->group(function () {
        Route::get('/', [HomeController::class, 'blogIndex'])->name('index');
        Route::get('/category/{slug}', [HomeController::class, 'blogCategory'])->name('category');
        Route::get('/{slug}', [HomeController::class, 'blogShow'])->name('show');
    });

    Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
});


// Survei IKLI
Route::prefix('ikli-survey')->name('ikli-survey.')->group(function () {
    Route::get('/', [IkliSurveyHomeController::class, 'index'])->name('home');

    Route::middleware(['questionnaire.active'])->group(function () {
        Route::get('/finish', [IkliSurveyHomeController::class, 'finish'])->name('finish');

        Route::prefix('survey')->name('survey.')->group(function () {
            Route::get('/create', [IkliSurveyHomeController::class, 'surveyCreate'])->name('create');
            Route::post('/', [IkliSurveyHomeController::class, 'surveyStore'])->name('store');

            Route::get('/{uuid}/edit', [IkliSurveyHomeController::class, 'surveyEdit'])->name('edit');
            Route::put('/{uuid}', [IkliSurveyHomeController::class, 'surveyUpdate'])->name('update');

            Route::delete('/{uuid}', [IkliSurveyHomeController::class, 'surveyDestroy'])->name('destroy');
        });

        Route::prefix('questionnaire')->name('questionnaire.')->group(function () {
            Route::get('/{uuid}/physical-availability', [IkliSurveyHomeController::class, 'surveyQuestionnaireEdit'])
                ->defaults('surveyIndicator', 'physical-availability')
                ->name('physical-availability.edit');

            Route::put('/{uuid}/physical-availability', [IkliSurveyHomeController::class, 'surveyQuestionnaireUpdate'])
                ->defaults('surveyIndicator', 'physical-availability')
                ->name('physical-availability.update');

            Route::get('/{uuid}/quality', [IkliSurveyHomeController::class, 'surveyQuestionnaireEdit'])
                ->defaults('surveyIndicator', 'quality')
                ->name('quality.edit');

            Route::put('/{uuid}/quality', [IkliSurveyHomeController::class, 'surveyQuestionnaireUpdate'])
                ->defaults('surveyIndicator', 'quality')
                ->name('quality.update');

            Route::get('/{uuid}/suitability', [IkliSurveyHomeController::class, 'surveyQuestionnaireEdit'])
                ->defaults('surveyIndicator', 'suitability')
                ->name('suitability.edit');

            Route::put('/{uuid}/suitability', [IkliSurveyHomeController::class, 'surveyQuestionnaireUpdate'])
                ->defaults('surveyIndicator', 'suitability')
                ->name('suitability.update');

            Route::get('/{uuid}/utilization', [IkliSurveyHomeController::class, 'surveyQuestionnaireEdit'])
                ->defaults('surveyIndicator', 'utilization')
                ->name('utilization.edit');

            Route::put('/{uuid}/utilization', [IkliSurveyHomeController::class, 'surveyQuestionnaireUpdate'])
                ->defaults('surveyIndicator', 'utilization')
                ->name('utilization.update');

            Route::get('/{uuid}/expectation', [IkliSurveyHomeController::class, 'surveyQuestionnaireEdit'])
                ->defaults('surveyIndicator', 'expectation')
                ->name('expectation.edit');

            Route::put('/{uuid}/expectation', [IkliSurveyHomeController::class, 'surveyQuestionnaireUpdate'])
                ->defaults('surveyIndicator', 'expectation')
                ->name('expectation.update');
        });
    });
});
