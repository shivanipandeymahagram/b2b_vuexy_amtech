<?php

namespace App\Providers;

use App\Models\Company;
use App\Models\Companydata;
use App\Models\Complaintsubject;
use App\Models\Link;
use App\Models\PortalSetting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        try {
            view()->composer('*', function ($view){
                $mydata['links']             = Link::get();
                $mydata['sessionOut']        = PortalSetting::where('code', 'sessionout')->first()->value;
                $mydata['complaintsubject']  = Complaintsubject::get();
                $mydata['topheadcolor']      = PortalSetting::where('code', "topheadcolor")->first();
                $mydata['sidebarlightcolor'] = PortalSetting::where('code', "sidebarlightcolor")->first();
                $mydata['sidebardarkcolor']  = PortalSetting::where('code', "sidebardarkcolor")->first();
                $mydata['sidebariconcolor']  = PortalSetting::where('code', "sidebariconcolor")->first();
                $mydata['sidebarchildhrefcolor'] = PortalSetting::where('code', "sidebarchildhrefcolor")->first();
                $mydata['schememanager'] = PortalSetting::where('code', "schememanager")->first();
            
                $mydata['company'] = Company::where('website', $_SERVER['HTTP_HOST'])->first();
               
               
             
                if($mydata['company']){
                    $news = Companydata::where('company_id', $mydata['company']->id)->first();

                }else{
                    $news = null;
                }
                
           
                if($news){
                    $mydata['news'] = $news->news;
                    $mydata['notice'] = $news->notice;
                    $mydata['billnotice'] = $news->billnotice;
                    $mydata['supportnumber'] = $news->number;
                    $mydata['supportemail'] = $news->email;
                }else{
                    $mydata['news'] = "";
                    $mydata['notice'] = "";
                    $mydata['billnotice'] = "";
                    $mydata['supportnumber'] = "";
                    $mydata['supportemail'] = "";
                }
              

                $view->with('mydata', $mydata);    
            }); 
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
