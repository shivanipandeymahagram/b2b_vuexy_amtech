<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    // Dashboard
    function home()
    {
        return view('home');
    }

    // User End Controller Function

    // Resource
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

    // Member
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

    // fund
    function loadwallet()
    {
        return view('fund.loadwallet');
    }
    function runpaisa()
    {
        return view('fund.runpaisa');
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

    // Investment Fund
    function fundrequest()
    {
        return view('investmentfund.request');
    }
    function fundreport()
    {
        return view('investmentfund.allfundreport');
    }

    // Investment Service
    function banner()
    {
        return view('investmentservice.banner');
    }
    function video()
    {
        return view('investmentservice.video');
    }
    function investment()
    {
        return view('investmentservice.investment');
    }

    // Aeps funds'
    function req()
    {
        return view('aepsfund.req');
    }

    function pendingreq()
    {
        return view('aepsfund.pendingreq');
    }
    function pendingpayoutreq()
    {
        return view('aepsfund.pendingpayoutreq');
    }
    function reqreport()
    {
        return view('aepsfund.reqreport');
    }

    // Matm funds
    function mrequest()
    {
        return view('matmfund.request');
    }
    function mpendingreq()
    {
        return view('matmfund.pendingreq');
    }
    function mreqreport()
    {
        return view('matmfund.reqreport');
    }

    // Agent List
    function aeps()
    {
        return view('agentlist.aeps');
    }
    function uti()
    {
        return view('agentlist.uti');
    }

    // Transaction Report
    function aepsstatement()
    {
        return view('transactionreport.aepsstatement');
    }
    function billpaystatement()
    {
        return view('transactionreport.billpaystatement');
    }
    function cmsreport()
    {
        return view('transactionreport.cmsreport');
    }
    function dmtstatement()
    {
        return view('transactionreport.dmtstatement');
    }
    function loanstatement()
    {
        return view('transactionreport.loanstatement');
    }
    function matmstatement()
    {
        return view('transactionreport.matmstatement');
    }
    function panstatement()
    {
        return view('transactionreport.panstatement');
    }
    function rechargestatement()
    {
        return view('transactionreport.rechargestatement');
    }

    // Wallet History
    function mwallet()
    {
        return view('wallethistory.mwallet');
    }
    function awallet()
    {
        return view('wallethistory.awallet');
    }

    function complaints()
    {
        return view('complaints');
    }

    // Setup tools
    function muserlogout()
    {
        return view('setuptools.muserlogout');
    }
    function bankaccount()
    {
        return view('setuptools.bankaccount');
    }
    function apimanager()
    {
        return view('setuptools.apimanager');
    }
    function complaintsub()
    {
        return view('setuptools.complaintsub');
    }
    function operator()
    {
        return view('setuptools.operator');
    }
    function portalsetting()
    {
        return view('setuptools.portalsetting');
    }
    function quicklink()
    {
        return view('setuptools.quicklink');
    }

    // Account Setting

    function certificate()
    {
        return view('accountsetting.certificate');
    }
    function profile()
    {
        return view('accountsetting.profile');
    }

    // Roles And Permission
    function roles()
    {
        return view('permissionallot.roles');
    }
    function permission()
    {
        return view('permissionallot.permission');
    }

    // User End Controller Function

    function mobile()
    {
        return view('utilityrecharge.mobile');
    }
    function dth()
    {
        return view('utilityrecharge.dth');
    }
}
