<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    function home()
    {
        return view('home');
    }

    function scheme()
    {
        return view('resource.scheme');
    }

    function company()
    {
        return view('resource.company');
    }

    function companyprofile()
    {
        return view('resource.companyprofile');
    }

    function whitelabel()
    {
        return view('member.index');
    }
    function create()
    {
        return view('member.create');
    }
    function md()
    {
        return view('member.md');
    }
    function mdcreate()
    {
        return view('member.mdcreate');
    }
    function distributor()
    {
        return view('member.distributor');
    }
    function dcreate()
    {
        return view('member.dcreate');
    }
    function retailer()
    {
        return view('member.retailer');
    }
    function rcreate()
    {
        return view('member.rcreate');
    }
    function allmember()
    {
        return view('member.allmember');
    }
    function allmcreate()
    {
        return view('member.allmcreate');
    }
    function kycsubmit()
    {
        return view('member.kycsubmit');
    }
    function kycsubmitcreate()
    {
        return view('member.kycsubmitcreate');
    }
    function kycreject()
    {
        return view('member.kycreject');
    }
    function kycrejectcreate()
    {
        return view('member.kycrejectcreate');
    }
    function kycpending()
    {
        return view('member.kycpending');
    }
    function kycpendingcreate()
    {
        return view('member.kycpendingcreate');
    }
    function tr()
    {
        return view('fund.tr');
    }
    function request()
    {
        return view('fund.request');
    }
    function requestreport()
    {
        return view('fund.requestreport');
    }
    function allfundreport()
    {
        return view('fund.allfundreport');
    }
    function fundrequest()
    {
        return view('investmentfund.request');
    }
    function fundreport()
    {
        return view('investmentfund.allfundreport');
    }
}
