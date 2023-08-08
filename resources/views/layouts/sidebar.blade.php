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



        <li class="menu-item active ">
            <a href="{{route('dashboard')}}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-smart-home"></i>
                <div data-i18n="Dashboards">Dashboards</div>
            </a>

        </li>

        <!-- <li class="menu-item ">
            <a href="#menu-design" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-file-dollar"></i>
                <div data-i18n="Utility Recharge">Utility Recharge</div>
            </a>
            <ul class="menu-sub" id="menu-design">

                <li class="menu-item ">
                    <a href="" class="menu-link">
                        <div data-i18n="Mobile">Mobile</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="" class="menu-link">
                        <div data-i18n="DTH">DTH</div>
                    </a>
                </li>

            </ul>
        </li>
        <li class="menu-item ">
            <a href="#userinfo" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-smart-home"></i>
                <div data-i18n="Bill Payment">Bill Payment</div>
            </a>
            <ul class="menu-sub" id="userinfo">
                <li class="menu-item ">
                    <a href="" class="menu-link">
                        <div data-i18n="Electricity">Electricity</div>
                    </a>
                </li>
                <li class="menu-item ">
                    <a href="" class="menu-link">
                        <div data-i18n="Postpaid">Postpaid</div>
                    </a>
                </li>
                <li class="menu-item ">
                    <a href="" class="menu-link">
                        <div data-i18n="Water">Water</div>
                    </a>
                </li>
                <li class="menu-item ">
                    <a href="" class="menu-link">
                        <div data-i18n="Broadband">Broadband</div>
                    </a>
                </li>
                <li class="menu-item ">
                    <a href="" class="menu-link">
                        <div data-i18n="LPG Gas">LPG Gas</div>
                    </a>
                </li>
                <li class="menu-item ">
                    <a href="" class="menu-link">
                        <div data-i18n="Piped Gas">Piped Gas</div>
                    </a>
                </li>
                <li class="menu-item ">
                    <a href="" class="menu-link">
                        <div data-i18n="Landline">Landline</div>
                    </a>
                </li>
                <li class="menu-item ">
                    <a href="" class="menu-link">
                        <div data-i18n="Education Fees">Education Fees</div>
                    </a>
                </li>

            </ul>
        </li>
        <li class="menu-item ">
            <a href="" class="menu-link">
                <i class="menu-icon tf-icons ti ti-file-description"></i>
                <div data-i18n="LIC Billpay">LIC Billpay</div>
            </a>

        </li>
        <li class="menu-item ">
            <a href="" class="menu-link">
                <i class="menu-icon tf-icons ti ti-user"></i>
                <div data-i18n="FASTag">FASTag</div>
            </a>
        </li>

        <li class="menu-item ">
            <a href="#finance" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-file"></i>
                <div data-i18n="Financial & Taxes">Financial & Taxes</div>
            </a>
            <ul class="menu-sub" id="finance">
                <li class="menu-item ">
                    <a href="" class="menu-link">
                        <div data-i18n="Loan Repayment">Loan Repayment</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="" class="menu-link">
                        <div data-i18n="LIC/Insurance">LIC/Insurance</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="" class="menu-link">
                        <div data-i18n="Municipal Tax">Municipal Tax</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="" class="menu-link">
                        <div data-i18n="Housing Tax">Housing Tax</div>
                    </a>
                </li>
            </ul>
        </li>

        <li class="menu-item ">
            <a href="#utiPan" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-id"></i>
                <div data-i18n="PAN Card">PAN Card</div>
            </a>
            <ul class="menu-sub" id="utiPan">
                <li class="menu-item ">
                    <a href="" class="menu-link">
                        <div data-i18n="UTI">UTI</div>
                    </a>
                </li>

            </ul>
        </li>
        <li class="menu-item ">
            <a href="#bankingService" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-file-dollar"></i>
                <div data-i18n="Banking Service">Banking Service</div>
            </a>
            <ul class="menu-sub" id="bankingService">
                <li class="menu-item ">
                    <a href="" class="menu-link">
                        <div data-i18n="DMT">DMT</div>
                    </a>
                </li>
                <li class="menu-item ">
                    <a href="" class="menu-link">
                        <div data-i18n="AEPS">AEPS</div>
                    </a>
                </li>
            </ul>
        </li>

        <li class="menu-item ">
            <a href="#serviceLink" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-link"></i>
                <div data-i18n="Service Links">Service Links</div>
            </a>
            <ul class="menu-sub" id="serviceLink">
                <li class="menu-item">
                    <a href="" class="menu-link" target="_blank">
                        <div data-i18n="link">link</div>
                    </a>
                </li>
            </ul>
        </li> -->

        <li class="menu-item ">
            <a href="#tables" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-file"></i>
                <div data-i18n="Resources">Resources</div>
            </a>
            <ul class="menu-sub" id="tables">
                <li class="menu-item">
                    <a href="{{route('scheme')}}" class="menu-link">
                        <div data-i18n="Scheme Manager">Scheme Manager</div>
                    </a>
                </li>

                <li class="menu-item">
                    <a href="{{route('company')}}" class="menu-link">
                        <div data-i18n="Company Manager">Company Manager</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{route('companyprofile')}}" class="menu-link">
                        <div data-i18n="Company Profile">Company Profile</div>
                    </a>
                </li>

            </ul>
        </li>

        <li class="menu-item ">
            <a href="#member" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-users"></i>
                <div data-i18n="Member">Member</div>
            </a>
            <ul class="menu-sub" id="member">
                <li class="menu-item">
                    <a href="{{route('whitelabel')}}" class="menu-link">
                        <div data-i18n="Whitelabel">Whitelabel</div>
                    </a>
                </li>

                <li class="menu-item">
                    <a href="{{route('md')}}" class="menu-link">
                         <div data-i18n="Master Distributor">Master Distributor</div>
                    </a>
                </li>

                <li class="menu-item">
                    <a href="{{route('distributor')}}" class="menu-link"> <div data-i18n=" Distributor"> Distributor</div>
                    </a>
                </li>

                <li class="menu-item">
                    <a href="{{route('retailer')}}" class="menu-link">
                        <div data-i18n="Retailer">Retailer</div>
                    </a>
                </li>

                <li class="menu-item">
                    <a href="{{route('allmember')}}" class="menu-link">
                        <div data-i18n="All Member">All Member</div>
                    </a>
                </li>

                <li class="menu-item">
                    <a href="{{route('kycsubmit')}}" class="menu-link">
                        <div data-i18n="Kycsubmited User">Kycsubmited User</div>
                    </a>
                </li>

                <li class="menu-item">
                    <a href="{{route('kycreject')}}" class="menu-link">
                        <div data-i18n="Kyc Rejected User">Kyc Rejected User</div>
                    </a>
                </li>

                <li class="menu-item">
                    <a href="{{route('kycpending')}}" class="menu-link">
                        <div data-i18n="Kyc Pending User">Kyc Pending User</div>
                    </a>
                </li>

                <!-- <li class="menu-item">
                    <a href="" class="menu-link">
                        <div data-i18n="Other User">Other User</div>
                    </a>
                </li>

                <li class="menu-item">
                    <a href="" class="menu-link">
                        <div data-i18n="Employee">Employee</div>
                    </a>
                </li> -->

            </ul>
        </li>

        <li class="menu-item ">
            <a href="#funds" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-id"></i>
                <div data-i18n="Fund">Fund</div>
            </a>
            <ul class="menu-sub" id="funds">
                <li class="menu-item">
                    <a href="" class="menu-link">
                        <div data-i18n="Transfer/Return">Transfer/Return</div>
                    </a>
                </li>

                <li class="menu-item">
                    <a href="" class="menu-link">
                        <div data-i18n="Request">Request</div>
                    </a>
                </li>

                <!-- <li class="menu-item">
                    <a href="" class="menu-link">
                        <div data-i18n="Load Main Wallet">Load Main Wallet</div>
                    </a>
                </li> -->


                <li class="menu-item">
                    <a href="" class="menu-link">
                        <div data-i18n="Request Report">Request Report</div>
                    </a>
                </li>

                <li class="menu-item">
                    <a href="" class="menu-link">
                        <div data-i18n="All Fund Report">All Fund Report</div>
                    </a>
                </li>

            </ul>
        </li>

        <li class="menu-item ">
            <a href="#funds" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-id"></i>
                <div data-i18n="Investment Fund">Investment Fund</div>
            </a>
            <ul class="menu-sub" id="funds">

                <li class="menu-item">
                    <a href="" class="menu-link">
                        <div data-i18n="Request">Request</div>
                    </a>
                </li>

                <li class="menu-item">
                    <a href="" class="menu-link">
                        <div data-i18n="All Fund Report">All Fund Report</div>
                    </a>
                </li>

            </ul>
        </li>
        
        <li class="menu-item ">
            <a href="#funds" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-id"></i>
                <div data-i18n="Investment Service">Investment Service</div>
            </a>
            <ul class="menu-sub" id="funds">

                <li class="menu-item">
                    <a href="" class="menu-link">
                        <div data-i18n="Banner">Banner</div>
                    </a>
                </li>

                <li class="menu-item">
                    <a href="" class="menu-link">
                        <div data-i18n="Video">Video</div>
                    </a>
                </li>

                <li class="menu-item">
                    <a href="" class="menu-link">
                        <div data-i18n="Investment">Investment</div>
                    </a>
                </li>
            </ul>
        </li>

        <li class="menu-item ">
            <a href="#aepsfund" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-square"></i>
                <div data-i18n="AEPS Fund">AEPS Fund</div>
            </a>
            <ul class="menu-sub" id="aepsfund">
                <!-- <li class="menu-item">
                    <a href="" class="menu-link">
                        <div data-i18n="Request">Request</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="" class="menu-link">
                        <div data-i18n="Add Bank">Add Bank</div>
                    </a>
                </li> -->


                <li class="menu-item">
                    <a href="" class="menu-link">
                        <div data-i18n="Pending Request">Pending Request</div>
                    </a>
                </li>

                <li class="menu-item">
                    <a href="" class="menu-link">
                        <div data-i18n="Pending Payout Request">Pending Payout Request</div>
                    </a>
                </li>



                <li class="menu-item">
                    <a href="" class="menu-link">
                         <div data-i18n="Request Report">Request Report</div>
                    </a>
                </li>

            </ul>
        </li>
        <li class="menu-item ">
            <a href="#matmfund" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-file-description"></i>
                <div data-i18n="MATM Fund ">MATM Fund </div>
            </a>
            <ul class="menu-sub" id="matmfund">

                <li class="menu-item">
                    <a href="" class="menu-link">
                        <div data-i18n="Request">Request</div>
                    </a>
                </li>


                <li class="menu-item">
                    <a href="" class="menu-link">
                        <div data-i18n="Pending Request">Pending Request</div>
                    </a>
                </li>


                <li class="menu-item">
                    <a href="" class="menu-link">
                        <div data-i18n="Request Report">Request Report</div>
                    </a>
                </li>

            </ul>
        </li>
        <li class="menu-item ">
            <a href="#agentList" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-id"></i>
                <div data-i18n="Agent List">Agent List</div>
            </a>
            <ul class="menu-sub" id="agentList">

                <li class="menu-item">
                    <a href="" class="menu-link">
                        <div data-i18n="AePS">AePS</div>
                    </a>
                </li>


                <li class="menu-item">
                    <a href="" class="menu-link"> <div data-i18n="UTI">UTI</div>
                    </a>
                </li>

            </ul>
        </li>
        <li class="menu-item ">
            <a href="#txnreport" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-file"></i>
                <div data-i18n="Transaction Report">Transaction Report</div>
            </a>
            <ul class="menu-sub" id="txnreport">

                <li class="menu-item">
                    <a href="" class="menu-link">
                        <div data-i18n="AePS Statement">AePS Statement</div>
                    </a>
                </li>


                <li class="menu-item">
                    <a href="" class="menu-link">
                        <div data-i18n="Billpay Statement">Billpay Statement</div>
                    </a>
                </li>


                <li class="menu-item">
                    <a href="" class="menu-link">
                        <div data-i18n="DMT Statement">DMT Statement</div>
                    </a>
                </li>


                <li class="menu-item">
                    <a href="" class="menu-link">
                        <div data-i18n="Micro ATM Statement">Micro ATM Statement</div>
                    </a>
                </li>


                <li class="menu-item">
                    <a href="" class="menu-link">
                        <div data-i18n="Recharge Statement">Recharge Statement</div>
                    </a>
                </li>


                <li class="menu-item">
                    <a href="" class="menu-link">
                        <div data-i18n="Uti Pancard Statement">Uti Pancard Statement</div>
                    </a>
                </li>


                <li class="menu-item">
                    <a href="" class="menu-link">
                        <div data-i18n="Loanenquiry Statement">Loanenquiry Statement</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="" class="menu-link">
                        <div data-i18n="CMS Report">CMS Report</div>
                    </a>
                </li>
            </ul>
        </li>
        <li class="menu-item ">
            <a href="#walletreport" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-id"></i>
                <div data-i18n="Wallet History">Wallet History</div>
            </a>
            <ul class="menu-sub" id="walletreport">

                <li class="menu-item">
                    <a href="" class="menu-link">
                        <div data-i18n="Main Wallet">Main Wallet</div>
                    </a>
                </li>

                <li class="menu-item">
                    <a href="" class="menu-link">
                        <div data-i18n="Aeps Wallet">Aeps Wallet</div>
                    </a>
                </li>

            </ul>
        </li>

        <li class="menu-item">
            <a href="" class="menu-link">
                <i class="menu-icon tf-icons ti ti-layout-navbar"></i>
                <div data-i18n="Complaints">Complaints</div>
            </a>
        </li>

        <!-- <li class="menu-item">
            <a href="" class="menu-link">
                <i class="menu-icon tf-icons ti ti-smart-home"></i>
                <div data-i18n="Loan Enquiry">Loan Enquiry</div>
            </a>
        </li> -->

        <li class="menu-item ">
            <a href="#setuptools" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-text-wrap-disabled"></i>
                <div data-i18n="Setup Tools">Setup Tools</div>
            </a>
            <ul class="menu-sub" id="setuptools">
                <li class="menu-item">
                    <a href="" class="menu-link">
                        <div data-i18n="Mobile User Logout">Mobile User Logout</div>
                    </a>
                </li>

                <li class="menu-item">
                    <a href="" class="menu-link">
                        <div data-i18n="API Manager">API Manager</div>
                    </a>
                </li>

                <li class="menu-item">
                    <a href="" class="menu-link">
                        <div data-i18n="Bank Account">Bank Account</div>
                    </a>
                </li>

                <li class="menu-item">
                    <a href="" class="menu-link">
                        <div data-i18n="Complaint Subject">Complaint Subject</div>
                    </a>
                </li>

                <li class="menu-item">
                    <a href="" class="menu-link">
                        <div data-i18n="Operator Manager">Operator Manager</div>
                    </a>
                </li>

                <li class="menu-item">
                    <a href="" class="menu-link">
                        <div data-i18n="Portal Setting">Portal Setting</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="" class="menu-link">
                        <div data-i18n="Quick Links">Quick Links</div>
                    </a>
                </li>

            </ul>
        </li>

        <!-- <li class="menu-item ">
            <a href="#mappingManager" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-map"></i>
                <div data-i18n="Mapping Manager">Mapping Manager</div>
            </a>
            <ul class="menu-sub" id="mappingManager">
                <li class="menu-item">
                    <a href="" class="menu-link">
                        <div data-i18n="Mapping Manager">Mapping Manager</div>
                    </a>
                </li>
            </ul>
        </li> -->

        <li class="menu-item ">
            <a href="#accountSetting" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-file-description"></i>
                <div data-i18n="Account Settings">Account Settings</div>
            </a>
            <ul class="menu-sub" id="accountSetting">
                <li class="menu-item">
                    <a href="" class="menu-link">
                        <div data-i18n="Profile Setting">Profile Setting</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="" class="menu-link">
                        <div data-i18n="Certificate">Certificate</div>
                    </a>
                </li>
            </ul>
        </li>
        <!-- <li class="menu-item ">
            <a href="#apiSetting" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-settings"></i>
                <div data-i18n="Api Settings">Api Settings</div>
            </a>
            <ul class="menu-sub" id="apiSetting">
                <li class="menu-item">
                    <a href="" class="menu-link">
                        <div data-i18n="Callback & Token">Callback & Token</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="" class="menu-link">
                        <div data-i18n="Operator Code">Operator Code</div>
                    </a>
                </li>
            </ul>
        </li> -->

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
        <li class="menu-item ">
            <a href="#roles" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-file"></i>
                <div data-i18n="Roles & Permissions">Roles & Permissions</div>
            </a>
            <ul class="menu-sub" id="roles">

                <li class="menu-item">
                    <a href="" class="menu-link">
                        <div data-i18n="Roles">Roles</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="" class="menu-link">
                        <div data-i18n="Permission">Permission</div>
                    </a>
                </li>
            </ul>
        </li>

    </ul>
</aside>
<!-- / Menu -->