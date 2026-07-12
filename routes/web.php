<?php

use App\Http\Controllers\BlogArticleController;
use App\Http\Controllers\BlogCategoryController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DownloadCenterController;
use App\Http\Controllers\FAQController;
use App\Http\Controllers\HeroCarouselController;
use App\Http\Controllers\IkliSurvey\DashboardController as IkliSurveyDashboardController;
use App\Http\Controllers\IkliSurvey\HomeController;
use App\Http\Controllers\IkliSurvey\InternetNetworkController;
use App\Http\Controllers\IkliSurvey\IrrigationController;
use App\Http\Controllers\IkliSurvey\PowerGridController;
use App\Http\Controllers\IkliSurvey\QuestionnaireQuestionController;
use App\Http\Controllers\IkliSurvey\RegionController;
use App\Http\Controllers\IkliSurvey\RespondentController;
use App\Http\Controllers\IkliSurvey\RoadController;
use App\Http\Controllers\IkliSurvey\TransportationTerminalController;
use App\Http\Controllers\IkliSurvey\WasteManagementSystemController;
use App\Http\Controllers\IkliSurvey\WastewaterManagementSystemController;
use App\Http\Controllers\IkliSurvey\WaterSupplySystemController;
use App\Http\Controllers\MainPerformanceIndicatorController;
use App\Http\Controllers\LsPaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrganizationProfileController;
use App\Http\Controllers\PersonnelProfileController;
use App\Http\Controllers\PublicInformationPortalController;
use App\Http\Controllers\RealizationController;
use App\Http\Controllers\RegionalPerformanceIndicatorController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'role:superadmin,admin,operator,head_of_department', 'active'])->prefix('dashboard')->name('dashboard.')->group(function () {
    // Dasbor
    Route::get('/', [DashboardController::class, 'index'])->name('index');

    // Tentang
    Route::get('/about', [DashboardController::class, 'about'])->name('about');

    Route::get('/development', [DashboardController::class, 'development'])->name('development');

    // Profil Organisasi
    Route::name('organization-profiles.')->group(function () {
        Route::get('/organization-structure', [OrganizationProfileController::class, 'structure'])->name('organization-structure');
        Route::post('/organization-structure', [OrganizationProfileController::class, 'upsertStructure'])->name('upsert-structure');
        Route::get('/vision-and-mission', [OrganizationProfileController::class, 'visionMission'])->name('vision-and-mission');
        Route::post('/vision-and-mission', [OrganizationProfileController::class, 'upsertVisionAndMission'])->name('upsert-vision-and-mission');

        Route::prefix('personnel-profiles')->name('personnel-profiles.')->group(function () {
            Route::get('/', [PersonnelProfileController::class, 'index'])->name('index');
            Route::get('/data', [PersonnelProfileController::class, 'getData'])->name('data');
            Route::post('/mass-destroy', [PersonnelProfileController::class, 'massDestroy'])->name('mass-destroy');
            Route::get('/create', [PersonnelProfileController::class, 'create'])->name('create');
            Route::post('/', [PersonnelProfileController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [PersonnelProfileController::class, 'edit'])->name('edit');
            Route::put('/{id}', [PersonnelProfileController::class, 'update'])->name('update');
        });

        Route::prefix('departments')->name('departments.')->group(function () {
            Route::get('/', [DepartmentController::class, 'index'])->name('index');
            Route::get('/data', [DepartmentController::class, 'getData'])->name('data');
            Route::post('/mass-destroy', [DepartmentController::class, 'massDestroy'])->name('mass-destroy');
            Route::get('/create', [DepartmentController::class, 'create'])->name('create');
            Route::post('/', [DepartmentController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [DepartmentController::class, 'edit'])->name('edit');
            Route::put('/{id}', [DepartmentController::class, 'update'])->name('update');
        });
    });

    // Indikator Kinerja
    Route::name('performance-indicators.')->group(function () {
        // Indikator Kinerja Utama 
        Route::prefix('main-indicators')->name('main-indicators.')->group(function () {
            Route::get('/', [MainPerformanceIndicatorController::class, 'index'])->name('index');
            Route::get('/data', [MainPerformanceIndicatorController::class, 'getData'])->name('data');
            Route::get('/data-chart', [MainPerformanceIndicatorController::class, 'getDataChart'])->name('chart');
            Route::get('/show-chart', [MainPerformanceIndicatorController::class, 'showChart'])->name('show-chart');
            Route::post('/mass-destroy', [MainPerformanceIndicatorController::class, 'massDestroy'])->name('mass-destroy');

            Route::get('/create', [MainPerformanceIndicatorController::class, 'create'])->name('create');
            Route::post('/', [MainPerformanceIndicatorController::class, 'store'])->name('store');

            Route::get('/{id}/edit', [MainPerformanceIndicatorController::class, 'edit'])->name('edit');
            Route::put('/{id}', [MainPerformanceIndicatorController::class, 'update'])->name('update');
        });

        // Indikator Kinerja Daerah
        Route::name('regional-indicators.')->group(function () {
            $indicators = [
                'geographical-and-demographic-aspects',
                'regional-competitiveness-aspects',
                'key-performance-indicators',
            ];

            foreach ($indicators as $indicator) {
                Route::prefix($indicator)->name($indicator . '.')->group(function () {
                    Route::get('/', [RegionalPerformanceIndicatorController::class, 'index'])->name('index');
                    Route::get('/data', [RegionalPerformanceIndicatorController::class, 'getData'])->name('data');
                    Route::get('/data-chart', [RegionalPerformanceIndicatorController::class, 'getDataChart'])->name('chart');
                    Route::get('/show-chart', [RegionalPerformanceIndicatorController::class, 'showChart'])->name('show-chart');
                    Route::post('/mass-destroy', [RegionalPerformanceIndicatorController::class, 'massDestroy'])->name('mass-destroy');
                    Route::get('/create', [RegionalPerformanceIndicatorController::class, 'create'])->name('create');
                    Route::post('/', [RegionalPerformanceIndicatorController::class, 'store'])->name('store');
                    Route::get('/{id}/edit', [RegionalPerformanceIndicatorController::class, 'edit'])->name('edit');
                    Route::put('/{id}', [RegionalPerformanceIndicatorController::class, 'update'])->name('update');
                });
            }
        });
    });

    // Monev Tahun Berjalan
    Route::name('monev.')->group(function () {
        Route::name('finances.')->group(function () {
            Route::prefix('ls-payments')->name('ls-payments.')->group(function () {
                Route::get('/', [LsPaymentController::class, 'index'])->name('index');
                Route::get('/list', [LsPaymentController::class, 'getList'])->name('list');
                Route::post('/data', [LsPaymentController::class, 'getData'])->name('data');
                Route::post('/mass-add-to-realization', [LsPaymentController::class, 'massAddToRealization'])->name('mass-add-to-realization');
                Route::post('/mass-destroy', [LsPaymentController::class, 'massDestroy'])->name('mass-destroy');
                Route::get('/download-template', [LsPaymentController::class, 'downloadTemplate'])->name('download-template');
                Route::get('/download-export/{token}', [LsPaymentController::class, 'downloadExport'])->name('download-export');
                Route::get('/check-export', [LsPaymentController::class, 'checkExport'])->name('check-export');
                Route::get('/export', [LsPaymentController::class, 'export'])->name('export');
                Route::get('/check-import', [LsPaymentController::class, 'checkImport'])->name('check-import');
                Route::post('/import', [LsPaymentController::class, 'import'])->name('import');
                Route::get('/create', [LsPaymentController::class, 'create'])->name('create');
                Route::post('/', [LsPaymentController::class, 'store'])->name('store');
                Route::get('/{id}/edit', [LsPaymentController::class, 'edit'])->name('edit');
                Route::put('/{id}', [LsPaymentController::class, 'update'])->name('update');
            });
            Route::prefix('contracts')->name('contracts.')->group(function () {
                Route::get('/', [ContractController::class, 'index'])->name('index');
                Route::get('/list', [ContractController::class, 'getList'])->name('list');
                Route::get('/recap', [ContractController::class, 'recap'])->name('recap');
                Route::get('/data-chart', [ContractController::class, 'getDataChart'])->name('data-chart');
                Route::post('/data', [ContractController::class, 'getData'])->name('data');
                Route::post('/mass-destroy', [ContractController::class, 'massDestroy'])->name('mass-destroy');
                Route::get('/download-template', [ContractController::class, 'downloadTemplate'])->name('download-template');
                Route::get('/download-export/{token}', [ContractController::class, 'downloadExport'])->name('download-export');
                Route::get('/check-export', [ContractController::class, 'checkExport'])->name('check-export');
                Route::get('/export', [ContractController::class, 'export'])->name('export');
                Route::get('/check-import', [ContractController::class, 'checkImport'])->name('check-import');
                Route::post('/import', [ContractController::class, 'import'])->name('import');
                Route::get('/create', [ContractController::class, 'create'])->name('create');
                Route::post('/', [ContractController::class, 'store'])->name('store');
                Route::get('/{id}/edit', [ContractController::class, 'edit'])->name('edit');
                Route::get('/{id}', [ContractController::class, 'show'])->name('show');
                Route::put('/{id}', [ContractController::class, 'update'])->name('update');
            });
            Route::prefix('realizations')->name('realizations.')->group(function () {
                Route::get('/', [RealizationController::class, 'index'])->name('index');
                Route::post('/data', [RealizationController::class, 'getData'])->name('data');
                Route::post('/mass-destroy', [RealizationController::class, 'massDestroy'])->name('mass-destroy');
                Route::post('/mass-verification', [RealizationController::class, 'massVerification'])->name('mass-verification');
                Route::get('/download-export/{token}', [RealizationController::class, 'downloadExport'])->name('download-export');
                Route::get('/check-export', [RealizationController::class, 'checkExport'])->name('check-export');
                Route::get('/export', [RealizationController::class, 'export'])->name('export');
                Route::get('/create', [RealizationController::class, 'create'])->name('create');
                Route::post('/', [RealizationController::class, 'store'])->name('store');
                Route::get('/{id}/edit', [RealizationController::class, 'edit'])->name('edit');
                Route::put('/{id}', [RealizationController::class, 'update'])->name('update');
            });
        });
    });

    // Informasi Lainnya
    Route::name('other-informations.')->group(function () {
        Route::prefix('faqs')->name('faqs.')->group(function () {
            Route::get('/', [FAQController::class, 'index'])->name('index');
            Route::get('/data', [FAQController::class, 'getData'])->name('data');
            Route::post('/mass-destroy', [FAQController::class, 'massDestroy'])->name('mass-destroy');
            Route::get('/create', [FAQController::class, 'create'])->name('create');
            Route::post('/', [FAQController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [FAQController::class, 'edit'])->name('edit');
            Route::put('/{id}', [FAQController::class, 'update'])->name('update');
        });

        Route::prefix('download-center')->name('download-center.')->group(function () {
            Route::get('/', [DownloadCenterController::class, 'index'])->name('index');
            Route::get('/data', [DownloadCenterController::class, 'getData'])->name('data');
            Route::post('/mass-destroy', [DownloadCenterController::class, 'massDestroy'])->name('mass-destroy');
            Route::get('/create', [DownloadCenterController::class, 'create'])->name('create');
            Route::post('/', [DownloadCenterController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [DownloadCenterController::class, 'edit'])->name('edit');
            Route::put('/{id}', [DownloadCenterController::class, 'update'])->name('update');
        });

        Route::middleware(['role:superadmin'])->group(function () {
            // Portal Informasi Publik hanya bisa diakses oleh superadmin
            Route::prefix('public-information-portals')->name('public-information-portals.')->group(function () {
                Route::get('/', [PublicInformationPortalController::class, 'index'])->name('index');
                Route::get('/data', [PublicInformationPortalController::class, 'getData'])->name('data');
                Route::post('/mass-destroy', [PublicInformationPortalController::class, 'massDestroy'])->name('mass-destroy');
                Route::get('/create', [PublicInformationPortalController::class, 'create'])->name('create');
                Route::post('/', [PublicInformationPortalController::class, 'store'])->name('store');
                Route::get('/{id}/edit', [PublicInformationPortalController::class, 'edit'])->name('edit');
                Route::put('/{id}', [PublicInformationPortalController::class, 'update'])->name('update');
            });

            // Kontak hanya bisa diakses oleh superadmin
            Route::prefix('contact')->name('contact.')->group(function () {
                Route::get('/', [ContactController::class, 'index'])->name('index');
                Route::put('/{id}', [ContactController::class, 'update'])->name('update');
            });
        });
    });

    // Blog
    Route::prefix('blog')->name('blog.')->group(function () {
        Route::get('/', function () {
            return redirect()->route('dashboard.blog.articles.index');
        })->name('index');

        Route::prefix('/categories')->name('categories.')->group(function () {
            Route::get('/', [BlogCategoryController::class, 'index'])->name('index');
            Route::get('/data', [BlogCategoryController::class, 'getData'])->name('data');
            Route::post('/mass-destroy', [BlogCategoryController::class, 'massDestroy'])->name('mass-destroy');
            Route::get('/create', [BlogCategoryController::class, 'create'])->name('create');
            Route::post('/', [BlogCategoryController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [BlogCategoryController::class, 'edit'])->name('edit');
            Route::put('/{id}', [BlogCategoryController::class, 'update'])->name('update');
        });

        Route::prefix('/articles')->name('articles.')->group(function () {
            Route::get('/', [BlogArticleController::class, 'index'])->name('index');
            Route::get('/data', [BlogArticleController::class, 'getData'])->name('data');
            Route::post('/mass-destroy', [BlogArticleController::class, 'massDestroy'])->name('mass-destroy');
            Route::get('/create', [BlogArticleController::class, 'create'])->name('create');
            Route::post('/', [BlogArticleController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [BlogArticleController::class, 'edit'])->name('edit');
            Route::put('/{id}', [BlogArticleController::class, 'update'])->name('update');
        });
    });

    Route::middleware(['role:superadmin,admin'])->group(function () {
        // Kelola Pengguna
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::get('/data', [UserController::class, 'getData'])->name('data');
            Route::get('/create', [UserController::class, 'create'])->name('create');
            Route::post('/', [UserController::class, 'store'])->name('store');
            Route::put('/{id}/set-status', [UserController::class, 'setStatus'])->name('set-status');
            Route::put('/{id}/reset-password', [UserController::class, 'resetPassword'])->name('reset-password');
            Route::get('/{id}/edit', [UserController::class, 'edit'])->name('edit');
            Route::get('/{id}', [UserController::class, 'show'])->name('show');
            Route::put('/{id}', [UserController::class, 'update'])->name('update');
            Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('hero-carousels')->name('hero-carousels.')->group(function () {
            Route::get('/', [HeroCarouselController::class, 'index'])->name('index');
            Route::get('/data', [HeroCarouselController::class, 'getData'])->name('data');
            Route::post('/mass-destroy', [HeroCarouselController::class, 'massDestroy'])->name('mass-destroy');
            Route::get('/create', [HeroCarouselController::class, 'create'])->name('create');
            Route::post('/', [HeroCarouselController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [HeroCarouselController::class, 'edit'])->name('edit');
            Route::put('/{id}', [HeroCarouselController::class, 'update'])->name('update');
        });
    });
});

Route::prefix('ikli-survey')->name('ikli-survey.')->group(function () {
    Route::middleware(['auth', 'verified', 'role:superadmin,admin,operator,head_of_department', 'active'])
        ->prefix('dashboard')
        ->name('dashboard.')
        ->group(function () {
            // Dasbor
            Route::get('/', [IkliSurveyDashboardController::class, 'index'])->name('index');

            // Tentang
            Route::get('/about', [IkliSurveyDashboardController::class, 'about'])->name('about');

            Route::prefix('questionnaire')->name('questionnaire.')->group(function () {

                /*
                |--------------------------------------------------------------------------
                | Statistik Kuesioner
                |--------------------------------------------------------------------------
                | Semua role boleh lihat
                */
                Route::get('/statistic', [IkliSurveyDashboardController::class, 'getQuestionnaireStatistic'])->name('statistic');
                Route::get('/district-statistic', [IkliSurveyDashboardController::class, 'getDistrictStatistic'])->name('district-statistic');
                Route::get('/respondent-statistic', [IkliSurveyDashboardController::class, 'getRespondentStatistic'])->name('respondent-statistic');
                Route::get('/priority-statistic', [IkliSurveyDashboardController::class, 'getPriorityStatistic'])->name('priority-statistic');

                /*
                |--------------------------------------------------------------------------
                | Pertanyaan
                |--------------------------------------------------------------------------
                */
                Route::prefix('question')->name('question.')->group(function () {
                    // Semua role boleh lihat
                    Route::get('/', [QuestionnaireQuestionController::class, 'index'])->name('index');
                    Route::get('/data', [QuestionnaireQuestionController::class, 'getData'])->name('data');

                    // Hanya superadmin
                    Route::middleware(['role:superadmin'])->group(function () {
                        Route::get('/template', [QuestionnaireQuestionController::class, 'getTemplate'])->name('template');
                        Route::post('/import', [QuestionnaireQuestionController::class, 'import'])->name('import');
                    });
                });

                /*
                |--------------------------------------------------------------------------
                | Responden
                |--------------------------------------------------------------------------
                */
                Route::prefix('respondent')->name('respondent.')->group(function () {
                    // Semua role boleh lihat
                    Route::get('/', [RespondentController::class, 'respondentSampel'])->name('index');
                    Route::get('/slovin-formula', [RespondentController::class, 'slovinFormula'])->name('slovin');
                    Route::get('/krejcie-morgan-formula', [RespondentController::class, 'krejcieMorganFormula'])->name('krejcie-morgan');

                    // Hanya superadmin
                    Route::middleware(['role:superadmin'])->group(function () {
                        Route::post('/mass-destroy', [RespondentController::class, 'massDestroy'])->name('mass-destroy');
                        Route::get('/{id}/{infrastructureType}', [RespondentController::class, 'edit'])->name('edit');
                        Route::put('/{id}/{infrastructureType}', [RespondentController::class, 'update'])->name('update');
                        Route::delete('/{id}/{infrastructureType}', [RespondentController::class, 'destroy'])->name('destroy');
                    });
                });

                /*
                |--------------------------------------------------------------------------
                | Monitoring
                |--------------------------------------------------------------------------
                | Semua role boleh lihat
                */
                Route::prefix('monitor')->name('monitor.')->group(function () {
                    Route::get('/by-date', [RespondentController::class, 'monitorByDate'])->name('date');
                    Route::get('/by-district', [RespondentController::class, 'monitorByDistrict'])->name('district');
                });

                /*
                |--------------------------------------------------------------------------
                | Hasil Survei - Infrastructure Improvement
                |--------------------------------------------------------------------------
                */
                Route::prefix('infrastructure-improvement')->name('infrastructure-improvement.')->group(function () {
                    Route::get('/', [RespondentController::class, 'index'])->name('index');
                    Route::get('/data', [RespondentController::class, 'getData'])->name('data');
                    Route::post('/export', [RespondentController::class, 'export'])->name('export');
                });

                /*
                |--------------------------------------------------------------------------
                | Jaringan Internet
                |--------------------------------------------------------------------------
                */
                Route::prefix('internet-network')->name('internet-network.')->group(function () {
                    Route::get('/', [InternetNetworkController::class, 'index'])->name('index');
                    Route::get('/data', [InternetNetworkController::class, 'getData'])->name('data');
                    Route::get('/recap', [InternetNetworkController::class, 'recap'])->name('recap');
                    Route::get('/recap-data', [InternetNetworkController::class, 'getRecapData'])->name('recap-data');
                    Route::post('/export', [InternetNetworkController::class, 'export'])->name('export');
                });

                /*
                |--------------------------------------------------------------------------
                | Irigasi
                |--------------------------------------------------------------------------
                */
                Route::prefix('irrigation')->name('irrigation.')->group(function () {
                    Route::get('/', [IrrigationController::class, 'index'])->name('index');
                    Route::get('/data', [IrrigationController::class, 'getData'])->name('data');
                    Route::get('/recap', [IrrigationController::class, 'recap'])->name('recap');
                    Route::get('/recap-data', [IrrigationController::class, 'getRecapData'])->name('recap-data');
                    Route::post('/export', [IrrigationController::class, 'export'])->name('export');
                });

                /*
                |--------------------------------------------------------------------------
                | Listrik
                |--------------------------------------------------------------------------
                */
                Route::prefix('power-grid')->name('power-grid.')->group(function () {
                    Route::get('/', [PowerGridController::class, 'index'])->name('index');
                    Route::get('/data', [PowerGridController::class, 'getData'])->name('data');
                    Route::get('/recap', [PowerGridController::class, 'recap'])->name('recap');
                    Route::get('/recap-data', [PowerGridController::class, 'getRecapData'])->name('recap-data');
                    Route::post('/export', [PowerGridController::class, 'export'])->name('export');
                });

                /*
                |--------------------------------------------------------------------------
                | Jalan
                |--------------------------------------------------------------------------
                */
                Route::prefix('road')->name('road.')->group(function () {
                    Route::get('/', [RoadController::class, 'index'])->name('index');
                    Route::get('/data', [RoadController::class, 'getData'])->name('data');
                    Route::get('/recap', [RoadController::class, 'recap'])->name('recap');
                    Route::get('/recap-data', [RoadController::class, 'getRecapData'])->name('recap-data');
                    Route::post('/export', [RoadController::class, 'export'])->name('export');
                });

                /*
                |--------------------------------------------------------------------------
                | Sistem Pengelolaan Air Limbah
                |--------------------------------------------------------------------------
                */
                Route::prefix('wastewater-management-system')->name('wastewater-management-system.')->group(function () {
                    Route::get('/', [WastewaterManagementSystemController::class, 'index'])->name('index');
                    Route::get('/data', [WastewaterManagementSystemController::class, 'getData'])->name('data');
                    Route::get('/recap', [WastewaterManagementSystemController::class, 'recap'])->name('recap');
                    Route::get('/recap-data', [WastewaterManagementSystemController::class, 'getRecapData'])->name('recap-data');
                    Route::post('/export', [WastewaterManagementSystemController::class, 'export'])->name('export');
                });

                /*
                |--------------------------------------------------------------------------
                | Sistem Pengelolaan Persampahan
                |--------------------------------------------------------------------------
                */
                Route::prefix('waste-management-system')->name('waste-management-system.')->group(function () {
                    Route::get('/', [WasteManagementSystemController::class, 'index'])->name('index');
                    Route::get('/data', [WasteManagementSystemController::class, 'getData'])->name('data');
                    Route::get('/recap', [WasteManagementSystemController::class, 'recap'])->name('recap');
                    Route::get('/recap-data', [WasteManagementSystemController::class, 'getRecapData'])->name('recap-data');
                    Route::post('/export', [WasteManagementSystemController::class, 'export'])->name('export');
                });

                /*
                |--------------------------------------------------------------------------
                | Sistem Penyediaan Air Minum
                |--------------------------------------------------------------------------
                */
                Route::prefix('water-supply-system')->name('water-supply-system.')->group(function () {
                    Route::get('/', [WaterSupplySystemController::class, 'index'])->name('index');
                    Route::get('/data', [WaterSupplySystemController::class, 'getData'])->name('data');
                    Route::get('/recap', [WaterSupplySystemController::class, 'recap'])->name('recap');
                    Route::get('/recap-data', [WaterSupplySystemController::class, 'getRecapData'])->name('recap-data');
                    Route::post('/export', [WaterSupplySystemController::class, 'export'])->name('export');
                });

                /*
                |--------------------------------------------------------------------------
                | Terminal
                |--------------------------------------------------------------------------
                */
                Route::prefix('transportation-terminal')->name('transportation-terminal.')->group(function () {
                    Route::get('/', [TransportationTerminalController::class, 'index'])->name('index');
                    Route::get('/data', [TransportationTerminalController::class, 'getData'])->name('data');
                    Route::get('/recap', [TransportationTerminalController::class, 'recap'])->name('recap');
                    Route::get('/recap-data', [TransportationTerminalController::class, 'getRecapData'])->name('recap-data');
                    Route::post('/export', [TransportationTerminalController::class, 'export'])->name('export');
                });
            });
        });

    Route::prefix('region')->name('region.')->group(function () {
        Route::get('/districts', [RegionController::class, 'districtList'])->name('districts');
        Route::get('/villages', [RegionController::class, 'villageList'])->name('villages');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
require __DIR__ . '/guest.php';
