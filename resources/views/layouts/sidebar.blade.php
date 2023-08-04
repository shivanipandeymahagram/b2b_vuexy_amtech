<!-- Menu -->
<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="index.html" class="app-brand-link">
            <span class="app-brand-logo demo">
                <svg width="32" height="22" viewBox="0 0 32 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M0.00172773 0V6.85398C0.00172773 6.85398 -0.133178 9.01207 1.98092 10.8388L13.6912 21.9964L19.7809 21.9181L18.8042 9.88248L16.4951 7.17289L9.23799 0H0.00172773Z" fill="#7367F0" />
                    <path opacity="0.06" fill-rule="evenodd" clip-rule="evenodd" d="M7.69824 16.4364L12.5199 3.23696L16.5541 7.25596L7.69824 16.4364Z" fill="#161616" />
                    <path opacity="0.06" fill-rule="evenodd" clip-rule="evenodd" d="M8.07751 15.9175L13.9419 4.63989L16.5849 7.28475L8.07751 15.9175Z" fill="#161616" />
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M7.77295 16.3566L23.6563 0H32V6.88383C32 6.88383 31.8262 9.17836 30.6591 10.4057L19.7824 22H13.6938L7.77295 16.3566Z" fill="#7367F0" />
                </svg>
            </span>
            <span class="app-brand-text demo menu-text fw-bold">Vuexy</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="ti menu-toggle-icon d-none d-xl-block ti-sm align-middle"></i>
            <i class="ti ti-x d-block d-xl-none ti-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">


        @if(Auth::user()->kyc == "verified")
        <li class="menu-item active ">
            <a href="{{route('home')}}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-smart-home"></i>
                <div data-i18n="Dashboards">Dashboards</div>
            </a>

        </li>

        @if (Myhelper::hasNotRole('admin'))
        @if (Myhelper::can(['recharge_service']))
        <li class="menu-item ">
            <a href="#menu-design" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-file-dollar"></i>
                <div data-i18n="Utility Recharge">Utility Recharge</div>
            </a>
            <ul class="menu-sub" id="menu-design">
                @if (Myhelper::can('recharge_service'))
                <li class="menu-item ">
                    <a href="{{route('recharge' , ['type' => 'mobile'])}}" class="menu-link">
                        <div data-i18n="Mobile">Mobile</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{route('recharge' , ['type' => 'dth'])}}" class="menu-link">
                        <div data-i18n="DTH">DTH</div>
                    </a>
                </li>
                @endif
            </ul>
        </li>
        @endif

        @if (Myhelper::can(['billpayment_service']))
        <li class="menu-item ">
            <a href="#userinfo" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-smart-home"></i>
                <div data-i18n="Bill Payment">Bill Payment</div>
            </a>
            <ul class="menu-sub" id="userinfo">
                @if (Myhelper::can('billpayment_service'))
                <li class="menu-item ">
                    <a href="{{route('bill' , ['type' => 'electricity'])}}" class="menu-link">
                        <div data-i18n="Electricity">Electricity</div>
                    </a>
                </li>
                <li class="menu-item ">
                    <a href="{{route('bill' , ['type' => 'postpaid'])}}" class="menu-link">
                        <div data-i18n="Postpaid">Postpaid</div>
                    </a>
                </li>
                <li class="menu-item ">
                    <a href="{{route('bill' , ['type' => 'water'])}}" class="menu-link">
                        <div data-i18n="Water">Water</div>
                    </a>
                </li>
                <li class="menu-item ">
                    <a href="{{route('bill' , ['type' => 'broadband'])}}" class="menu-link">
                        <div data-i18n="Broadband">Broadband</div>
                    </a>
                </li>
                <li class="menu-item ">
                    <a href="{{route('bill' , ['type' => 'lpg'])}}" class="menu-link">
                        <div data-i18n="LPG Gas">LPG Gas</div>
                    </a>
                </li>
                <li class="menu-item ">
                    <a href="{{route('bill' , ['type' => 'gas'])}}" class="menu-link">
                        <div data-i18n="Piped Gas">Piped Gas</div>
                    </a>
                </li>
                <li class="menu-item ">
                    <a href="{{route('bill' , ['type' => 'landline'])}}" class="menu-link">
                        <div data-i18n="Landline">Landline</div>
                    </a>
                </li>
                <li class="menu-item ">
                    <a href="{{route('bill' , ['type' => 'schoolfees'])}}" class="menu-link">
                        <div data-i18n="Education Fees">Education Fees</div>
                    </a>
                </li>
                @endif
            </ul>
        </li>
        @endif

        @if (Myhelper::can(['licbillpay_service']))
        <li class="menu-item ">
            <a href="{{route('lic')}}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-file-description"></i>
                <div data-i18n="LIC Billpay">LIC Billpay</div>
            </a>

        </li>
        @endif

        @if (Myhelper::can(['billpayment_service']))
        <li class="menu-item ">
            <a href="{{route('bill' , ['type' => 'fasttag'])}}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-user"></i>
                <div data-i18n="FASTag">FASTag</div>
            </a>
        </li>
        @endif

        <li class="menu-item ">
            <a href="#finance" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-file"></i>
                <div data-i18n="Financial & Taxes">Financial & Taxes</div>
            </a>
            <ul class="menu-sub" id="finance">
                @if (Myhelper::can('billpayment_service'))
                <li class="menu-item ">
                    <a href="{{route('bill' , ['type' => 'loanrepay'])}}" class="menu-link">
                        <div data-i18n="Loan Repayment">Loan Repayment</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{route('bill' , ['type' => 'insurance'])}}" class="menu-link">
                        <div data-i18n="LIC/Insurance">LIC/Insurance</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{route('bill' , ['type' => 'muncipal'])}}" class="menu-link">
                        <div data-i18n="Municipal Tax">Municipal Tax</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{route('bill' , ['type' => 'housing'])}}" class="menu-link">
                        <div data-i18n="Housing Tax">Housing Tax</div>
                    </a>
                </li>
                @endif
            </ul>
        </li>

        @if (Myhelper::can(['utipancard_service', 'nsdl_service']))
        <li class="menu-item ">
            <a href="#utiPan" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-id"></i>
                <div data-i18n="PAN Card">PAN Card</div>
            </a>
            <ul class="menu-sub" id="utiPan">
                @if (Myhelper::can('utipancard_service'))
                <li class="menu-item ">
                    <a href="{{ route('pancard', ['type' => 'uti']) }}" class="menu-link">
                        <div data-i18n="UTI">UTI</div>
                    </a>
                </li>

                @endif
            </ul>
        </li>
        @endif

        @if (Myhelper::can(['dmt1_service', 'aeps_service']))
        <li class="menu-item ">
            <a href="#bankingService" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-file-dollar"></i>
                <div data-i18n="Banking Service">Banking Service</div>
            </a>
            <ul class="menu-sub" id="bankingService">
                @if (Myhelper::can('dmt1_service'))
                <li class="menu-item ">
                    <a href="{{route('dmt1')}}" class="menu-link">
                        <div data-i18n="DMT">DMT</div>
                    </a>
                </li>
                @endif

                @if (Myhelper::can('aeps_service'))
                <li class="menu-item ">
                    <a href="{{route('aeps')}}" class="menu-link">
                        <div data-i18n="AEPS">AEPS</div>
                    </a>
                </li>
                @endif
            </ul>
        </li>
        @endif

        <li class="menu-item ">
            <a href="#serviceLink" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-link"></i>
                <div data-i18n="Service Links">Service Links</div>
            </a>
            <ul class="menu-sub" id="serviceLink">
                @if(sizeof($mydata['links']) > 0)
                @foreach($mydata['links'] as $link)
                <li class="menu-item">
                    <a href="{{$link->value}}" class="menu-link" target="_blank">
                        <div data-i18n="{{$link->name}}">{{$link->name}}</div>
                    </a>
                </li>
                @endforeach
                @endif
            </ul>
        </li>
        @endif

        @if ((Myhelper::can(['company_manager', 'change_company_profile'])) || (Myhelper::hasNotRole('retailer') && isset($mydata['schememanager']) && $mydata['schememanager']->value == "all"))
        <li class="menu-item ">
            <a href="#tables" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-file"></i>
                <div data-i18n="Resources">Resources</div>
            </a>
            <ul class="menu-sub" id="tables">
                @if (Myhelper::hasNotRole('retailer') && isset($mydata['schememanager']) && $mydata['schememanager']->value == "all")
                <li class="menu-item">
                    <a href="{{route('resource', ['type' => 'package'])}}" class="menu-link">
                        <div data-i18n="Scheme Manager">Scheme Manager</div>
                    </a>
                </li>
                @elseif (Myhelper::hasRole('admin'))
                <li class="menu-item">
                    <a href="{{route('resource', ['type' => 'scheme'])}}" class="menu-link">
                        <div data-i18n="Scheme Manager">Scheme Manager</div>
                    </a>
                </li>
                @endif
                @if (Myhelper::can('company_manager'))
                <li class="menu-item">
                    <a href="{{route('resource', ['type' => 'company'])}}" class="menu-link">
                        <div data-i18n="Company Manager">Company Manager</div>
                    </a>
                </li>
                @endif

                @if (Myhelper::can('change_company_profile'))
                <li class="menu-item">
                    <a href="{{route('resource', ['type' => 'companyprofile'])}}" class="menu-link">
                        <div data-i18n="Company Profile">Company Profile</div>
                    </a>
                </li>
                @endif
            </ul>
        </li>
        @endif

        @if (Myhelper::can(['view_whitelable', 'view_md', 'view_distributor', 'view_retailer', 'view_apiuser', 'view_other', 'view_kycpending', 'view_kycsubmitted', 'view_kycrejected']))
        <li class="menu-item ">
            <a href="#member" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-users"></i>
                <div data-i18n="Member">Member</div>
            </a>
            <ul class="menu-sub" id="member">
                @if (Myhelper::can(['view_whitelable']))
                <li class="menu-item">
                    <a href="{{route('member', ['type' => 'whitelable'])}}" class="menu-link">
                        <div data-i18n="Whitelabel">Whitelabel</div>
                    </a>
                </li>
                @endif
                @if (Myhelper::can(['view_md']))
                <li class="menu-item">
                    <a href="{{route('member', ['type' => 'md'])}}" class=" menu-link">
                        <div data-i18n="Master Distributor">Master Distributor</div>
                    </a>
                </li>
                @endif
                @if (Myhelper::can(['view_distributor']))
                <li class="menu-item">
                    <a href="{{route('member', ['type' => 'distributor'])}}" class="menu-link">
                        <div data-i18n="Distributor">Distributor</div>
                    </a>
                </li>
                @endif
                @if (Myhelper::can(['view_retailer']))
                <li class="menu-item">
                    <a href="{{route('member', ['type' => 'retailer'])}}" class="menu-link">
                        <div data-i18n="Retailer">Retailer</div>
                    </a>
                </li>
                @endif
                @if (Myhelper::hasRole('admin') || Myhelper::hasRole('subadmin'))
                <li class="menu-item">
                    <a href="{{route('member', ['type' => 'web'])}}" class="menu-link">
                        <div data-i18n="All Member">All Member</div>
                    </a>
                </li>
                @endif
                @if (Myhelper::hasRole('admin') || Myhelper::hasRole('subadmin'))
                <li class="menu-item">
                    <a href="{{route('member', ['type' => 'kycsubmitted'])}}" class="menu-link">
                        <div data-i18n="Kycsubmited User">Kycsubmited User</div>
                    </a>
                </li>
                @endif
                @if (Myhelper::hasRole('admin') || Myhelper::hasRole('subadmin'))
                <li class="menu-item">
                    <a href="{{route('member', ['type' => 'kycrejected'])}}" class="menu-link">
                        <div data-i18n="Kyc Rejected User">Kyc Rejected User</div>
                    </a>
                </li>
                @endif
                @if (Myhelper::hasRole('admin') || Myhelper::hasRole('subadmin'))
                <li class="menu-item">
                    <a href="{{route('member', ['type' => 'kycpending'])}}" class="menu-link">
                        <div data-i18n="Kyc Pending User">Kyc Pending User</div>
                    </a>
                </li>
                @endif
                @if (Myhelper::can(['view_other']))
                <li class="menu-item">
                    <a href="{{route('member', ['type' => 'other'])}}" class="menu-link">
                        <div data-i18n="Other User">Other User</div>
                    </a>
                </li>
                @endif
                @if (Myhelper::can(['view_employee']))
                <li class="menu-item">
                    <a href="{{route('member', ['type' => 'employee'])}}" class="menu-link">
                        <div data-i18n="Employee">Employee</div>
                    </a>
                </li>
                @endif
            </ul>
        </li>
        @endif

        @if (Myhelper::can(['fund_transfer', 'fund_return', 'fund_request_view', 'fund_report', 'fund_request']))
        <li class="menu-item ">
            <a href="#funds" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-id"></i>
                <div data-i18n="Fund">Fund</div>
            </a>
            <ul class="menu-sub" id="funds">
                @if (Myhelper::can(['fund_transfer', 'fund_return']))
                <li class="menu-item">
                    <a href="{{route('fund', ['type' => 'tr'])}}" class="menu-link">
                        <div data-i18n="Transfer/Return">Transfer/Return</div>
                    </a>
                </li>
                @endif

                @if (Myhelper::can(['setup_bank']))
                <li class="menu-item">
                    <a href="{{route('fund', ['type' => 'requestview'])}}" class="menu-link">
                        <div data-i18n="Request">Request</div>
                    </a>
                </li>
                @endif

                @if (Myhelper::hasNotRole('admin') && Myhelper::can('fund_request'))
                <li class="menu-item">
                    <a href="{{route('fund', ['type' => 'request'])}}" class="menu-link">
                        <div data-i18n="Load Main Wallet">Load Main Wallet</div>
                    </a>
                </li>
                @endif

                @if (Myhelper::can(['fund_report']))
                <li class="menu-item">
                    <a href="{{route('fund', ['type' => 'requestviewall'])}}" class="menu-link">
                        <div data-i18n="Request Report">Request Report</div>
                    </a>
                </li>

                <li class="menu-item">
                    <a href="{{route('fund', ['type' => 'statement'])}}" class="menu-link">
                        <div data-i18n="All Fund Report">All Fund Report</div>
                    </a>
                </li>
                @endif
            </ul>
        </li>
        @endif

        @if (Myhelper::can(['aeps_fund_request', 'aeps_fund_view', 'aeps_fund_report']))
        <li class="menu-item ">
            <a href="#aepsfund" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-square"></i>
                <div data-i18n="AEPS Fund">AEPS Fund</div>
            </a>
            <ul class="menu-sub" id="aepsfund">
                @if (Myhelper::can(['aeps_fund_request']) && Myhelper::hasNotRole('admin'))
                <li class="menu-item">
                    <a href="{{route('fund', ['type' => 'aeps'])}}" class="menu-link">
                        <div data-i18n="Request">Request</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{route('fund', ['type' => 'addaccount'])}}" class="menu-link">
                        <div data-i18n="Add Bank">Add Bank</div>
                    </a>
                </li>
                @endif

                @if (Myhelper::can(['aeps_fund_view']))
                <li class="menu-item">
                    <a href="{{route('fund', ['type' => 'aepsrequest'])}}" class="menu-link">
                        <div data-i18n="Pending Request">Pending Request</div>
                    </a>
                </li>

                <li class="menu-item">
                    <a href="{{route('fund', ['type' => 'payoutrequest'])}}" class="menu-link">
                        <div data-i18n="Pending Payout Request">Pending Payout Request</div>
                    </a>
                </li>

                @endif

                @if (Myhelper::can(['aeps_fund_report']))
                <li class="menu-item">
                    <a href="{{route('fund', ['type' => 'aepsrequestall'])}}" class=" menu-link">
                        <div data-i18n="Request Report">Request Report</div>
                    </a>
                </li>
                @endif
            </ul>
        </li>
        @endif

        @if (Myhelper::can(['microatm_fund_request', 'microatm_fund_view', 'microatm_fund_report']))
        <li class="menu-item ">
            <a href="#matmfund" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-file-description"></i>
                <div data-i18n="MATM Fund ">MATM Fund </div>
            </a>
            <ul class="menu-sub" id="matmfund">

                @if (Myhelper::can(['microatm_fund_request']))
                <li class="menu-item">
                    <a href="{{route('fund', ['type' => 'microatm'])}}" class="menu-link">
                        <div data-i18n="Request">Request</div>
                    </a>
                </li>
                @endif

                @if (Myhelper::can(['microatm_fund_view']))
                <li class="menu-item">
                    <a href="{{route('fund', ['type' => 'microatmrequest'])}}" class="menu-link">
                        <div data-i18n="Pending Request">Pending Request</div>
                    </a>
                </li>
                @endif

                @if (Myhelper::can(['microatm_fund_report']))
                <li class="menu-item">
                    <a href="{{route('fund', ['type' => 'microatmrequestall'])}}" class="menu-link">
                        <div data-i18n="Request Report">Request Report</div>
                    </a>
                </li>
                @endif
            </ul>
        </li>
        @endif

        @if (Myhelper::can(['utiid_statement', 'aepsid_statement']))
        <li class="menu-item ">
            <a href="#agentList" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-id"></i>
                <div data-i18n="Agent List">Agent List</div>
            </a>
            <ul class="menu-sub" id="agentList">

                @if (Myhelper::can('aepsid_statement'))
                <li class="menu-item">
                    <a href="{{route('statement', ['type' => 'aepsid'])}}" class="menu-link">
                        <div data-i18n="AePS">AePS</div>
                    </a>
                </li>
                @endif

                @if (Myhelper::can('utiid_statement'))
                <li class="menu-item">
                    <a href="{{ route('statement', ['type' => 'utiid']) }} class=" menu-link">
                        <div data-i18n="UTI">UTI</div>
                    </a>
                </li>
                @endif
            </ul>
        </li>
        @endif

        @if (Myhelper::can(['account_statement', 'utiid_statement', 'utipancard_statement', 'recharge_statement', 'billpayment_statement']))
        <li class="menu-item ">
            <a href="#txnreport" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-file"></i>
                <div data-i18n="Transaction Report">Transaction Report</div>
            </a>
            <ul class="menu-sub" id="txnreport">

                @if (Myhelper::can('aeps_statement'))
                <li class="menu-item">
                    <a href="{{route('statement', ['type' => 'aeps'])}}" class="menu-link">
                        <div data-i18n="AePS Statement">AePS Statement</div>
                    </a>
                </li>
                @endif

                @if (Myhelper::can('billpayment_statement'))
                <li class="menu-item">
                    <a href="{{route('statement', ['type' => 'billpay'])}}" class="menu-link">
                        <div data-i18n="Billpay Statement">Billpay Statement</div>
                    </a>
                </li>
                @endif

                @if (Myhelper::can('money_statement'))
                <li class="menu-item">
                    <a href="{{route('statement', ['type' => 'money'])}}" class="menu-link">
                        <div data-i18n="DMT Statement">DMT Statement</div>
                    </a>
                </li>
                @endif

                @if (Myhelper::can('matm_fund_report'))
                <li class="menu-item">
                    <a href="{{route('statement', ['type' => 'matm'])}}" class="menu-link">
                        <div data-i18n="Micro ATM Statement">Micro ATM Statement</div>
                    </a>
                </li>
                @endif

                @if (Myhelper::can('recharge_statement'))
                <li class="menu-item">
                    <a href="{{route('statement', ['type' => 'recharge'])}}" class="menu-link">
                        <div data-i18n="Recharge Statement">Recharge Statement</div>
                    </a>
                </li>
                @endif

                @if (Myhelper::can('utipancard_statement'))
                <li class="menu-item">
                    <a href="{{ route('statement', ['type' => 'utipancard']) }}" class="menu-link">
                        <div data-i18n="Uti Pancard Statement">Uti Pancard Statement</div>
                    </a>
                </li>
                @endif

                <li class="menu-item">
                    <a href="{{route('statement', ['type' => 'loanenquiry'])}}" class="menu-link">
                        <div data-i18n="Loanenquiry Statement">Loanenquiry Statement</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{route('statement', ['type' => 'cmsreport'])}}" class="menu-link">
                        <div data-i18n="CMS Report">CMS Report</div>
                    </a>
                </li>
            </ul>
        </li>
        @endif

        @if (Myhelper::can(['account_statement', 'awallet_statement']))
        <li class="menu-item ">
            <a href="#walletreport" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-id"></i>
                <div data-i18n="Wallet History">Wallet History</div>
            </a>
            <ul class="menu-sub" id="walletreport">

                @if (Myhelper::can('account_statement'))
                <li class="menu-item">
                    <a href="{{route('statement', ['type' => 'account'])}}" class="menu-link">
                        <div data-i18n="Main Wallet">Main Wallet</div>
                    </a>
                </li>
                @endif
                @if (Myhelper::can('awallet_statement'))
                <li class="menu-item">
                    <a href="{{route('statement', ['type' => 'awallet'])}}" class="menu-link">
                        <div data-i18n="Aeps Wallet">Aeps Wallet</div>
                    </a>
                </li>
                @endif
            </ul>
        </li>
        @endif

        @if (Myhelper::can('Complaint'))

        <li class="menu-item">
            <a href="{{route('complaint')}}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-layout-navbar"></i>
                <div data-i18n="Complaints">Complaints</div>
            </a>
        </li>
        @endif

        @if (Myhelper::hasRole('retailer'))
        <li class="menu-item">
            <a href="{{route('loanform')}}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-smart-home"></i>
                <div data-i18n="Loan Enquiry">Loan Enquiry</div>
            </a>
        </li>
        @endif

        @if (Myhelper::can(['setup_bank', 'api_manager', 'setup_operator']))
        <li class="menu-item ">
            <a href="#setuptools" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-text-wrap-disabled"></i>
                <div data-i18n="Setup Tools">Setup Tools</div>
            </a>
            <ul class="menu-sub" id="setuptools">
                @if (Myhelper::hasRole('admin') || Myhelper::hasRole('subadmin'))
                <li class="menu-item">
                    <a href="{{route('securedata')}}" class="menu-link">
                        <div data-i18n="Mobile User Logout">Mobile User Logout</div>
                    </a>
                </li>
                @endif
                @if (Myhelper::can('api_manager'))
                <li class="menu-item">
                    <a href="{{route('setup', ['type' => 'api'])}}" class="menu-link">
                        <div data-i18n="API Manager">API Manager</div>
                    </a>
                </li>
                @endif
                @if (Myhelper::can('setup_bank'))
                <li class="menu-item">
                    <a href="{{route('setup', ['type' => 'bank'])}}" class="menu-link">
                        <div data-i18n="Bank Account">Bank Account</div>
                    </a>
                </li>
                @endif
                @if (Myhelper::can('complaint_subject'))
                <li class="menu-item">
                    <a href="{{route('setup', ['type' => 'complaintsub'])}}" class="menu-link">
                        <div data-i18n="Complaint Subject">Complaint Subject</div>
                    </a>
                </li>
                @endif
                @if (Myhelper::can('setup_operator'))
                <li class="menu-item">
                    <a href="{{route('setup', ['type' => 'operator'])}}" class="menu-link">
                        <div data-i18n="Operator Manager">Operator Manager</div>
                    </a>
                </li>
                @endif
                @if (Myhelper::hasRole('admin'))
                <li class="menu-item">
                    <a href="{{route('setup', ['type' => 'portalsetting'])}}" class="menu-link">
                        <div data-i18n="Portal Setting">Portal Setting</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a Href="{{route('setup', ['type' => 'links'])}}" class="menu-link">
                        <div data-i18n="Quick Links">Quick Links</div>
                    </a>
                </li>
                @endif
            </ul>
        </li>
        @endif


        @if (Myhelper::can(['mapping_manager']))
        <li class="menu-item ">
            <a href="#mappingManager" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-map"></i>
                <div data-i18n="Mapping Manager">Mapping Manager</div>
            </a>
            <ul class="menu-sub" id="mappingManager">
                <li class="menu-item">
                    <a href="{{route('setup', ['type' => 'mappingid'])}}" class="menu-link">
                        <div data-i18n="Mapping Manager">Mapping Manager</div>
                    </a>
                </li>
            </ul>
        </li>
        @endif

        <li class="menu-item ">
            <a href="#accountSetting" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-file-description"></i>
                <div data-i18n="Account Settings">Account Settings</div>
            </a>
            <ul class="menu-sub" id="accountSetting">
                <li class="menu-item">
                    <a href="{{route('profile')}}" class="menu-link">
                        <div data-i18n="Profile Setting">Profile Setting</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{route('certificate')}}" class="menu-link">
                        <div data-i18n="Certificate">Certificate</div>
                    </a>
                </li>
            </ul>
        </li>

        @if (Myhelper::hasRole('apiuser') && Myhelper::can('apiuser_acc_manager'))
        <li class="menu-item ">
            <a href="#apiSetting" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-settings"></i>
                <div data-i18n="Api Settings">Api Settings</div>
            </a>
            <ul class="menu-sub" id="apiSetting">
                <li class="menu-item">
                    <a href="{{route('apisetup', ['type' => 'setting'])}}" class="menu-link">
                        <div data-i18n="Callback & Token">Callback & Token</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{route('apisetup', ['type' => 'operator'])}}" class="menu-link">
                        <div data-i18n="Operator Code">Operator Code</div>
                    </a>
                </li>
            </ul>
        </li>
        @endif

        <li class="menu-item ">
            <a href="#driverLink" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti  ti-layout-grid"></i>
                <div data-i18n="Driver Links">Driver Links</div>
            </a>
            <ul class="menu-sub" id="driverLink">

                <li class="menu-item">
                    <a href="https://drive.google.com/drive/folders/10RF-h2b9lVoa_d692e5CUVnpi7Gxwr7R?usp=sharing" target="_blank" class="menu-link">
                        <div data-i18n="Mantra">Mantra</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="https://drive.google.com/open?id=13FbVSOuplWlJNhwKMjTmKHkyA5CZPkh0" target="_blank" class="menu-link">
                        <div data-i18n="Morpho">Morpho</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="https://drive.google.com/open?id=1-LJfFXIvgE3ZLIm5fmYGjz95IvUnQYk4" target="_blank" class="menu-link">
                        <div data-i18n="Tatvik TMF20">Tatvik TMF20</div>
                    </a>
                </li>
            </ul>
        </li>

        @if (Myhelper::hasRole('admin'))
        <li class="menu-item ">
            <a href="#roles" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-file"></i>
                <div data-i18n="Roles & Permissions">Roles & Permissions</div>
            </a>
            <ul class="menu-sub" id="roles">

                <li class="menu-item">
                    <a href="{{route('tools' , ['type' => 'roles'])}}" class="menu-link">
                        <div data-i18n="Roles">Roles</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{route('tools' , ['type' => 'permissions'])}}" class="menu-link">
                        <div data-i18n="Permission">Permission</div>
                    </a>
                </li>
            </ul>
        </li>
        @endif

        @endif
    </ul>
</aside>
<!-- / Menu -->
