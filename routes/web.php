<?php

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
    return view('welcome');
});

Auth::routes();

// Principal
Route::get('/home', 'HomeController@index')->name('home');

// Evidencias
Route::get('/evidences/list', 'EvidenceController@list')->name('evidences.list');
Route::get('/evidences/comite', 'EvidenceController@comite')->name('evidences.comite');
Route::get('/evidences/export/{id}', 'EvidenceController@export')->name('evidences.export')->middleware('comite');
Route::get('/evidences/new', 'EvidenceController@new')->name('evidences.new');
Route::post('/evidences/create', 'EvidenceController@create')->name('evidences.create');
Route::get('/evidences/view/{id}', 'EvidenceController@id')->name('evidences.view');
Route::get('/evidences/comite/view/{id}', 'EvidenceController@id')->name('evidences.comite.view');
Route::get('/evidences/delete/{id}', 'EvidenceController@delete')->name('evidences.delete');
Route::get('/evidences/all', 'EvidenceController@all')->name('evidences.all');
Route::get('/evidences/check/{id}', 'EvidenceController@check')->name('evidences.check');
Route::get('/evidences/check/reject/{id}', 'EvidenceController@check_reject')->name('evidences.check.reject');

// Cuenta
Route::get('/account', 'AccountController@index')->name('account');
Route::post('/account/upload', 'AccountController@account_upload')->name('account.upload');

// Info de Jornadas
Route::post('/account/journeys/upload', 'AccountController@journeys_upload')->name('account.journeys.upload');
Route::get('/account/journeys', 'AccountController@journeys')->name('account.journeys');

// Subida de archivos
Route::get('/proof/download/{id}', 'ProofController@download')->name('proof.download');

// Reuniones
Route::get('/meetings/main', 'MeetingController@main')->name('meetings.main');

Route::post('/meetings/lists/create', 'MeetingController@lists_create')->name('meeting.lists.create');
Route::get('/meetings/lists/{id}', 'MeetingController@ids')->name('meeting.lists.ids');
Route::get('/meetings/lists', 'MeetingController@lists')->name('meetings.lists');
Route::get('/meetings/lists/delete/{id}', 'MeetingController@lists_delete')->name('meetings.lists.delete');
Route::get('/meetings/lists/edit/{id}', 'MeetingController@lists_edit')->name('meetings.lists.edit');
Route::post('/meetings/lists/update', 'MeetingController@lists_update')->name('meetings.lists.update');


Route::get('/meetings/list', 'MeetingController@list')->name('meetings.list');
Route::get('/meetings/list/delete/{id}', 'MeetingController@list_delete')->name('meetings.list.delete');
Route::get('/meetings/list/edit/{id}', 'MeetingController@list_edit')->name('meetings.list.edit');
Route::get('/meetings/new', 'MeetingController@new')->name('meetings.new');
Route::get('/meetings/ajax', 'MeetingController@ajax')->name('meetings.ajax');

Route::get('/meetings/list/attendees/{id}', 'MeetingController@attendees')->name('meetings.list.attendees');
Route::post('/meetings/list/attendees/update', 'MeetingController@attendee_update')->name('meeting.update');

Route::post('/meetings/create', 'MeetingController@create')->name('meeting.create');

// Asistencias
Route::get('/attendees/list/', 'AttendeeController@attendees')->name('attendees.list');

// Buscador
Route::get('/search', 'SearchController@search_home')->name('search');
Route::post('/evidences/all/search', 'SearchController@search')->name('search.administrator');
Route::post('/evidences/comite/search', 'SearchController@search')->name('search.comite');

// Perfiles de usuario
Route::get('/profile/{id}', 'ProfileController@id')->name('profile');
