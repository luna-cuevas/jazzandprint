%<%php
    global $Site;
    $Site->loadObject("compat", "compat");
    $CanvasBase = new CanvasBase();

    $hubInfo = $Site->settings->getHubInfo(CurrentHubID);
    $isPrinterBridgeHub = $Site->settings->doesCurrentHubMatch('fa6a0ae6f6731a627c0a2a598f04d371', $hubInfo);

    $designButton = $Site->store->checkDesignButton();
    $menus = $Site->core->listMenus();

    $onlineDesignPortal = '';
    $portal = $Site->current->portal ?? null;

    if ($designButton->type === 'CanvasBase' && !isset($_SESSION['cbPortal'])) {
        
        $portal = $Site->current->onlineDesigner;

        if ($portal !== null) {
            $portalHashedId = $portal->hash_id;
            $onlineDesignPortal = '&portal=' . $portalHashedId;
        }
    }

    $customerName = 'Guest';
    if ($Site->isUserLoggedIn()) {
        $customerName = $Site->current->user->defaultEmail->email ?? $customerName;
        if ($Site->current->user->cBillFname !== '' && $Site->current->user->cBillFname !== 'New') {
            $customerName = $Site->current->user->cBillFname;
        }
    }

    if (isset($portal->minimal) && $portal->minimal === 1) { %>%
        <style>#place-order, #product-menu, .container.footer-container{display:none !important;}</style>
    %<% } if (isset($Site->current->userAgent->outOfDate) && $Site->current->userAgent->outOfDate === true) { %>%
        <script>
            $(document).ready(function () {
                if (typeof localStorage.getItem('disregardBrowserError') == 'undefined' || localStorage.getItem('disregardBrowserError') == null || localStorage.getItem('disregardBrowserError') == false) {
                    swal({
                        title: 'Web browser out of date!',
                        text: "Would you like to upgrade your browser now for a better experience?",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, Upgrade!',
                        cancelButtonText: 'Don\'t bother me',
                        confirmButtonClass: 'btn btn-success',
                        cancelButtonClass: 'btn btn-danger',
                        buttonsStyling: true
                    }).then(function () {
                        window.open("%<%=$Site->current->userAgent->upgradeURL%>%");
                        localStorage.setItem('disregardBrowserError', true);
                    }, function (dismiss) {
                        if (dismiss === 'cancel') {
                            localStorage.setItem('disregardBrowserError', true);
                        }
                    })
                }
            });
        </script>
        %<%
    }
%>%
%<% if ($Site->theme->getThemeSetting("header_options") == 1) { %>%
    <!-- COMPACT HEADER -->
    <div class="navbar navbar-default navbar-static-top" id="compact-header">
        <div class="content-block header-content">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span>
                    <span class="icon-bar"></span> <span class="icon-bar"></span>
                </button>
                <div class="compact-logo">
                    <a href="%<%= $Site->core->getCartDomain() %>%">
                        %<%
                            if (isset($_SESSION['cbPortal'])) {
                                if (isset($_SESSION['cbPortal']['portalThemeActive']) && $_SESSION['cbPortal']['portalThemeActive'] != 0) {
                                    $logoUrl = $Site->theme->getThemeSetting("logo_url");

                                    if ($logoUrl == '' || $logoUrl == null) {
                                        $logoUrl = $Site->core->getDbSetting("dbVar_logoUrl");
                                    }
                                } else {
                                    $logoUrl = $Site->core->getDbSetting("dbVar_logoUrl");
                                }
                            } else {
                                $logoUrl = $Site->core->getDbSetting("dbVar_logoUrl");
                            }

                            if (($Site->core->getDbSetting("dbVar_companyName") == '') && ($logoUrl == '')) {
                                $logoUrl = asset('themes/general/images/misc/default-logo.jpg');
                            }
                            if (stripos($Site->core->getDbSetting("dbVar_logoUrl"),
                                    'default-logo') === false && ($logoUrl != '')) {
                                %>%
                                <img alt="%<%= $Site->core->getDbSetting("dbVar_companyName") %>%" src="%<%= $logoUrl %>%"
                                     onerror="this.src='/themes/general/images/misc/no_image.gif'" height="59" width="331"/>
                            %<% } elseif (stripos($Site->core->getDbSetting("dbVar_logoUrl"),
                                    'default-logo') !== false && ($logoUrl != '')) { %>%
                                     src="%<%= $logoUrl %>%" height="59" width="331"/>
                            %<% } else { %>%
                                <h1>%<%= ($Site->core->getDbSetting("dbVar_companyName") != '') ? $Site->core->getDbSetting("dbVar_companyName") : $Site->getServer('SERVER_NAME') %>%</h1>
                            %<% }
                        %>%
                    </a>
                    %<% if (empty($portal->minimal)) { %>%
                        <a href="#" class="btn btn-default navbar-btn product-menu"
                           id="place-order">%<% if ($Site->current->site->isNotBasicSite()) { %>%Place Order &#x2193;%<% } else { %>%Browse Catalog &#x2193;%<% } %>%</a>
                    %<% } %>%
                </div>
            </div>
            <div class="navbar-collapse collapse">
                %<% if (empty($portal->minimal)) { %>%
                    <button type="button" class="btn btn-default navbar-btn product-menu" id="product-menu">
                        %<% if ($Site->current->site->isNotBasicSite()) { %>%
                            Place Order &#x2193;
                        %<% } else { %>%
                            Browse Catalog &#x2193;
                        %<% } %>%
                    </button>
                %<% } %>%
                <ul class="nav navbar-nav"></ul>
                <ul class="nav navbar-nav navbar-right">
                    %<% if (($Site->core->getDbSetting("dbVar_gcsEnabled") === "Yes") && ($Site->core->getDbSetting("dbVar_gcsUID") != "")) { %>%
                        %<% if ($Site->theme->getThemeSetting("header_display_contact") == 1) { %>%
                            <li>
                                %<% if ($Site->core->getDbSetting("dbvar_displayPhoneInHeader") === "Yes") { %>%
                                    <a href="tel:%<%= $Site->core->getDbSetting("dbVar_tollfreeNumber") ?: $Site->core->getDbSetting("dbVar_directNumber") %>%">
                                        <span class="nav-text click-to-call">%<%= $Site->core->getDbSetting("dbVar_tollfreeNumber") ?: $Site->core->getDbSetting("dbVar_directNumber") %>%</span>
                                    </a>
                                %<% } else { %>%
                                    <a href="%<%= $Site->core->getCartDomain() %>%help/index.html">
                                        <span class="nav-text click-to-call">Help</span>
                                    </a>
                                %<% } %>%
                                <!--<span class="nav-subtext">Customer Support</span>-->
                                <ul class="dropdown-menu">
                                    <li><a href="%<%= $Site->core->getCartDomain() %>%help/search-site.html">Search
                                            Site</a></li>
                                </ul>
                            </li>
                        %<% } %>%
                    %<% } else { %>%
                        %<% if ($Site->theme->getThemeSetting("header_display_contact") == 1) { %>%
                            <li>
                                %<% if ($Site->core->getDbSetting("dbvar_displayPhoneInHeader") === "Yes") { %>%
                                    <a href="tel:%<%= $Site->core->getDbSetting("dbVar_tollfreeNumber") ?: $Site->core->getDbSetting("dbVar_directNumber") %>%">
                                        <span class="nav-text click-to-call">%<%= $Site->core->getDbSetting("dbVar_tollfreeNumber") ?: $Site->core->getDbSetting("dbVar_directNumber") %>%</span>
                                    </a>
                                %<% } else { %>%
                                    <a href="%<%= $Site->core->getCartDomain() %>%help/index.html">
                                        <span class="nav-text click-to-call">Help</span>
                                    </a>
                                %<% } %>%
                            </li>
                        %<% } %>%
                    %<% } %>%

                    %<%
                        if (count($menus) == 0 && empty($portal->minimal)) {
                            %>%
                            <li id="quick-link-cart-header" class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <span class="nav-text">Quick Links <span class="caret"></span></span>
                                    <!--<span class="nav-subtext">More Info.</span> -->
                                </a>
                                <ul class="dropdown-menu">
                                    %<% if ($Site->core->getDbSetting("dbVar_requestSamplesEnabled") !== 'No') { %>%
                                        <li>
                                            <a href="%<%= $Site->core->getCartDomain() %>%store/request-sample.html">Request
                                                Samples</a>
                                        </li>
                                    %<% } %>%
                                    %<% if ($Site->current->site->isNotBasicSite()) { %>%
                                        %<% if (!$isPrinterBridgeHub && (!isset($_SESSION['variables']) || (isset($_SESSION['user_info']['username']) && ($_SESSION['user_info']['username'] != '')))) { %>%
                                            <li>
                                                <a href="%<%= $Site->core->getCartDomain() %>%products/view-product-prices.html">Pricing</a>
                                            </li>
                                        %<% } %>%%<%
                                        if (isset($_SESSION['user_info']['username'])) {
                                            %>%
                                            <li>
                                                <a rel="nofollow" href="%<%= $Site->core->getCartDomain() %>%orders/view-my-orders.html">Order
                                                    Status</a>
                                            </li>
                                            %<%
                                        } else {
                                            %>%%<% if (!isset($_SESSION['variables']) || (isset($_SESSION['user_info']['username']) && ($_SESSION['user_info']['username'] != ''))) { %>%
                                                <li>
                                                    <a rel="nofollow" href="%<%= $Site->core->getCartDomain() %>%home/dashboard.html">My
                                                        Account</a>
                                                </li>
                                            %<% } %>%
                                            %<%
                                        }
                                        %>%%<% } %>%
                                    <li><a href="%<%= $Site->core->getCartDomain() %>%help/index.html">Customer
                                            Support</a></li>
                                    %<% if ($Site->current->site->isNotBasicSite()) { %>%%<% if ($Site->core->getDbSetting("dbVar_quotetoOrder") === "On") { %>%
                                        <li>
                                            <a href="%<%= $Site->core->getCartDomain() %>%quote/create-quote.html">Custom
                                                Quotes</a>
                                        </li>
                                    %<% } %>%%<% } %>%
                                </ul>
                            </li>
                            %<% if (empty($portal->minimal)) { %>%
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                        <span class="nav-text">Services <span class="caret"></span></span>
                                        <!--<span class="nav-subtext">About Us</span> -->
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a href="%<%= $Site->core->getCartDomain() %>%services/printing-services.html">Printing
                                                Services</a>
                                        </li>
                                        <li>
                                            <a href="%<%= $Site->core->getCartDomain() %>%services/design-services.html">Design
                                                Services</a>
                                        </li>
                                        <li class="divider"></li>
                                        <li>
                                            <a href="%<%= $Site->core->getCartDomain() %>%services/mailing-services.html">Mailing
                                                Services</a>
                                        </li>
                                        <li>
                                            <a href="%<%= $Site->core->getCartDomain() %>%services/mailing-lists.html">Mailing
                                                Lists</a>
                                        </li>
                                    </ul>
                                </li>
                            %<% } %>%

                        %<% } else {
                            foreach ($menus as $menu) {
                                                                if (isset($portal->minimal) && $portal->minimal === 1 && (strpos($menu['url'], 'home.html') === false && strpos($menu['template'], 'home.html') === false)) {
                                    continue;
                                }

                                                                if (isset($_SESSION['variables']) && ($Site->isUserNotLoggedIn())) {
                                    if (($menu['site'] === 'Customer') && ($menu['template'] === 'custom_quote.html')) {
                                        continue;
                                    }
                                    if (($menu['site'] === 'Customer') && ($menu['template'] === 'home.html')) {
                                        continue;
                                    }
                                }

                                $menuItems = $Site->core->listMenuItems($menu['id']);

                                if ($menu['url'] == '') {
                                    if ($menu['site'] === 'Store') {
                                        $url = "{$Site->core->getCartDomain()}{$menu['library']}/{$menu['template']}";
                                    } else {
                                        $url = "{$Site->core->getCartDomain()}{$menu['library']}/{$menu['template']}";
                                    }
                                } else {
                                    $url = $menu['url'];
                                }

                                $dropdown = "";
                                $caret = "";
                                %>%%<%
                                if (count($menuItems) > 0) {
                                    %>%
                                    <li class="dropdown">
                                        <a href="#" class="dropdown-toggle"
                                           data-toggle="dropdown">%<%= $menu['label'] %>%
                                            <span class="caret"></span></a>
                                        <ul class="dropdown-menu">
                                            %<%
                                                foreach ($menuItems as $menuItem) {
                                                    if ($menuItem['url'] == '') {
                                                        if ($menuItem['site'] === 'Store') {
                                                            $url = "{$Site->core->getCartDomain()}{$menuItem['library']}/{$menuItem['template']}";
                                                        } else {
                                                            $url = "{$Site->core->getCartDomain()}{$menuItem['library']}/{$menuItem['template']}";
                                                        }
                                                    } else {
                                                        $url = $menuItem['url'];
                                                    }
                                                    %>%
                                                    <li>
                                                        <a href="%<%= $url %>%" class="menu-link"
                                                           target="%<%= $menuItem['target'] %>%">%<%= $menuItem['label'] %>%</a>
                                                    </li>
                                                    %<%
                                                }
                                            %>%
                                        </ul>
                                    </li>
                                %<% } else { %>%
                                    <li>
                                        <a href="%<%= $url %>%"
                                           target="%<%= $menu['target'] %>%">%<%= $menu['label'] %>%</a>
                                    </li>
                                    %<%
                                }
                            }

                        }

                        if (!isset($_SESSION['user_info']['username'])) { %>%
                            %<% if ($Site->current->site->isNotBasicSite()) { %>%
                                <li>
                                    <a href="%<%= $Site->core->getCartDomain() %>%account/login.html">
                                        <span class="nav-text">Login</span>
                                        <!--<span class="nav-subtext">Create Account</span> -->
                                    </a>
                                </li>
                            %<% } %>%
                            %<%
                        } else {
                            $organizations = null;

                            if ($Site->isUserLoggedIn()) {
                                
                                $organizations = $Site->current->user->organizations()->excludeOnlineDesignerOrganization()->get() ?? null;
                            }

                            if (isset($organizations) && count($organizations) > 0) {
                                $canUseSiteAsSelf = false;
                                if (isset($_SESSION['user_info']['organizationID'])) {
                                    $organization = $Site->current->organization;

                                    if (!empty($organization)) {
                                        $member = $organization->getMember($Site->current->user->cID);

                                        if (!empty($member) && $member->check('member.canUseSiteAsSelf')) {
                                            $canUseSiteAsSelf = true;
                                        }
                                    }
                                }

                                %>%
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"> <span class="nav-text">My Account <span
                                                    class="caret"></span></span>
                                        <!--<span class="nav-subtext organization-profile-title">%<%php 
                                            %>%</span>-->
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a rel="nofollow" href="%<%= $Site->core->getCartDomain(false, true) %>%home/dashboard.html"><span
                                                        class="glyphicon glyphicon-user"></span> View My Account</a>
                                        </li>

                                        %<% if ($canUseSiteAsSelf) { %>%
                                            <li class="%<%php echo (!isset($_SESSION['user_info']['organizationID']) || ($_SESSION['user_info']['organizationID'] == 0)) ? 'active' : ''; %>%">
                                                <a style="margin-right: 0;"
                                                   href="/store/set-organization.html?organizationID=0%<%= $onlineDesignPortal %>%&manualSet=1"><span
                                                            class="glyphicon glyphicon-user"></span>%<%php echo (!isset($_SESSION['user_info']['organizationID']) || ($_SESSION['user_info']['organizationID'] == 0)) ? ' Using Site as Myself' : ' Switch to Myself'; %>%
                                                </a>
                                            </li>
                                        %<% } %>%

                                        %<% foreach ($organizations as $organization) { %>%
                                            <li class="%<%php echo (isset($_SESSION['user_info']['organizationID']) && ($_SESSION['user_info']['organizationID'] == $organization->organizationID)) ? 'active' : ''; %>%">
                                                <a style="margin-right: 0;"
                                                   href="/store/set-organization.html?organizationID=%<%= $organization->organizationID %>%&manualSet=1"><span
                                                            class="glyphicon glyphicon-globe"></span>%<%php echo (isset($_SESSION['user_info']['organizationID']) && ($_SESSION['user_info']['organizationID'] == $organization->organizationID)) ? ' Using site as the ' . $organization->name . ' Organization' : ' Switch to the ' . $organization->name . ' Organization'; %>%
                                                </a>
                                            </li>
                                        %<% } %>%

                                        <li>
                                            <a href='%<%= $Site->core->getCartDomain(false, true) %>%account/logout.html'
                                               class="login-last"><span class="glyphicon glyphicon-off"></span>
                                                Logout</a>
                                        </li>

                                    </ul>
                                </li>
                            %<% } else { %>%
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"> <span class="nav-text">My Account <span
                                                    class="caret"></span></span>
                                        <!--<span class="nav-subtext">%<%  %>%</span>-->
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a rel="nofollow" href="%<%= $Site->core->getCartDomain(false, true) %>%home/dashboard.html"><span
                                                        class="glyphicon glyphicon-user"></span> View My Account</a>
                                        </li>
                                        <li>
                                            <a href='%<%= $Site->core->getCartDomain(false, true) %>%account/logout.html'
                                               class="login-last"><span class="glyphicon glyphicon-off"></span>
                                                Logout</a>
                                        </li>
                                    </ul>
                                </li>

                            %<% } %>%
                        %<% } %>%

                    %<% if ($Site->current->site->isNotBasicSite()){ %>%
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <span class="nav-text"><i class="fa fa-shopping-cart" aria-hidden="true"></i> Cart <span
                                        class="caret"></span> <span
                                        class="cart-count badge">%<%= cartProduct::totalItemsInCart() %>%</span></span>
                        </a>
                        %<% } %>%
                        <ul class="dropdown-menu cart-dn">
                            %<% if (($CanvasBase->designs_in_progress_count() > 0 && cartProduct::totalItemsInCart() > 0) && $Site->isUserLoggedIn()) { %>%
                                <li class="switchCartItem view-cart">
                                    <a href="#">View %<%= $CanvasBase->designs_in_progress_count() %>% Design(s) In
                                        Progress</a>
                                </li>
                            %<% } %>%
                            <li>
                                %<% if (cartProduct::totalItemsInCart() > 0) { %>%%<%
                                    if (!isset($_REQUEST['paymentDomain'])) {
                                        %>%
                                        <span class="view-cart">
                                            <a href="%<%= $Site->core->getCartDomain(false,
                                                true) %>%store/cart-view.html">%<%= cartProduct::totalItemsInCart() === 1 ? cartProduct::totalItemsInCart() . ' Cart Item' : cartProduct::totalItemsInCart() . ' Cart Items' %>%</a>
                                            <a class="remove-all-items glyphicon glyphicon-trash"
                                               onclick="return confirm('Are you sure you want to empty your cart?');"
                                               href="%<%= $Site->core->getCartDomain(false,
                                                   true) %>%store/clear-cart.html"></a>
                                        </span>
                                        %<%
                                    } else {
                                        %>%
                                        <span class="view-cart">
                                            <a href="%<%= $Site->core->getCartDomain(false,
                                                true) %>%store/redirectBackToOriginalSite.html?redirectBackUrl=%<%= $Site->core->getCartDomain(false,
                                                true) . 'store/cart-view.html' %>%">%<%= cartProduct::totalItemsInCart() === 1 ? cartProduct::totalItemsInCart() . ' Cart Item' : cartProduct::totalItemsInCart() . ' Cart Items' %>%</a>
                                            <a class="remove-all-items"
                                               onclick="return confirm('Are you sure you want to empty your cart?');"
                                               href="%<%= $Site->core->getCartDomain(false,
                                                   true) %>%store/redirectBackToOriginalSite.html?redirectBackUrl=%<%= $Site->core->getCartDomain(false,
                                                   true) . 'store/clear-cart.html' %>%"></a>
                                        </span>
                                        %<%
                                    }
                                    %>%
                                    <p class="cart-total">Cart
                                        Total: %<%= I18nNumberFormatter::currencyFormatter($Site->store->get_total(false)) %>%</p>
                                %<% } else { %>%
                                    <p class="view-cart">Your Shopping Cart Is Empty!</p>
                                %<% } %>%
                            </li>

                            <li class="unsetPortal view-cart">
                                %<% if ($Site->isUsingPortal() && $Site->current->portal->isNotMainOnlineDesigner()) { %>%
                                    <a href='#'>Exit Portal</a>
                                %<% } %>%
                            </li>

                            <li>
                                %<% if ($Site->theme->getThemeSetting("header_display_balance") == 1) { %>%
                                    <p class="owes-money">
                                        <a rel='nofollow' href='%<%= $Site->core->getCartDomain(false,
                                            true) %>%orders/view-my-orders.html'>%<%= $Site->core->thisCustOwes() %>%</a>
                                    </p>
                                %<% } %>%
                            </li>
                        </ul>
                    </li>
                </ul>
                %<% if ($Site->theme->getThemeSetting("header_display_search") == 1) { %>%
                    <form class="navbar-form navbar-right compact-header-search">
                        <div class="form-group search-tool-group">
                            <input type="text"
                                   class="form-control cat-product-search-input header-search productCategorySearch"
                                   placeholder="Search Products" name="productCategorySearch"
                                   id="productCategorySearch">
                            <label for="productCategorySearch"><i class="fa fa-search" aria-hidden="true"></i><span
                                        class="sr-only">Search Products</span></label>
                        </div>
                    </form>
                %<% } %>%
            </div><!--/.nav-collapse -->
        </div>
    </div><!--COMPACT HEADER END-->
%<% } elseif ($Site->theme->getThemeSetting("header_options") == 2) { %>%
    <!-- STANDARD HEADER START-->
    <div class="header %<%= $Site->theme->getThemeSetting("border_radius") %>%">
        <div class="container header-container">
            <!-- HEADER LEFT START -->
            <div class="header-top-group">
                <div class="header-left">
                    <div class="logo">
                        <a href="%<%= $Site->core->getCartDomain() %>%">
                            %<%
                                if (isset($_SESSION['cbPortal'])) {
                                    if (isset($_SESSION['cbPortal']['portalThemeActive']) && $_SESSION['cbPortal']['portalThemeActive'] != 0) {
                                        $logoUrl = $Site->theme->getThemeSetting("logo_url");
                                    } else {
                                        $logoUrl = $Site->core->getDbSetting("dbVar_logoUrl");
                                    }
                                } else {
                                    $logoUrl = $Site->core->getDbSetting("dbVar_logoUrl");
                                }

                                if (($Site->core->getDbSetting("dbVar_companyName") == '') && ($logoUrl == '')) {
                                    $logoUrl = asset('themes/general/images/misc/default-logo.jpg');
                                }
                                if (stripos($Site->core->getDbSetting("dbVar_logoUrl"),
                                        'default-logo') === false && ($logoUrl != '')) {
                                    %>%
                                    <img alt="%<%= $Site->core->getDbSetting("dbVar_companyName") %>%"
                                         src="%<%= $logoUrl %>%"
                                         onerror="this.src='/themes/general/images/misc/no_image.gif'" height="59" width="331"/>
                                %<% } elseif (stripos($Site->core->getDbSetting("dbVar_logoUrl"),
                                        'default-logo') !== false && ($logoUrl != '')) { %>%
                                    <img alt="%<%= $Site->core->getDbSetting("dbVar_companyName") %>%"
                                         src="%<%= $logoUrl %>%" height="59" width="331"/>
                                %<% } else { %>%
                                    <h1>%<%= ($Site->core->getDbSetting("dbVar_companyName") != '') ? $Site->core->getDbSetting("dbVar_companyName") : $Site->getServer('SERVER_NAME') %>%</h1>
                                %<% }
                            %>%
                        </a>
                    </div>
                </div>
                <!-- HEADER RIGHT END -->
                <!-- HEADER CENTER START -->
                <div class="header-center">
                    %<% if ($Site->theme->getThemeSetting("header_display_contact") == 1) { %>%
                        <div id="phone-number" class="click-to-call">
                            %<% if ($Site->core->getDbSetting("dbvar_displayPhoneInHeader") === "Yes") { %>%
                                <span class="glyphicon glyphicon-earphone"></span> <a
                                        href="tel:%<%= $Site->core->getDbSetting("dbVar_tollfreeNumber") ?: $Site->core->getDbSetting("dbVar_directNumber") %>%">%<%= $Site->core->getDbSetting("dbVar_tollfreeNumber") ?: $Site->core->getDbSetting("dbVar_directNumber") %>%</a>
                            %<% } else { %>%
                                <a href="%<%= $Site->core->getCartDomain() %>%help/index.html"><span
                                            class="glyphicon glyphicon-earphone"></span> Customer Support</a>
                            %<% } %>%
                        </div>
                    %<% } %>%
                    %<% if ((empty($portal->minimal) && $Site->theme->getThemeSetting("header_display_social") == 1) && (($Site->core->getDbSetting("dbVar_facebookURL") != "") || ($Site->core->getDbSetting("dbVar_googleplusURL") != "") || ($Site->core->getDbSetting("dbVar_linkedinURL") != "") || ($Site->core->getDbSetting("dbVar_twitterURL") != "") || ($Site->core->getDbSetting("dbVar_youtubeURL") != "") || ($Site->core->getDbSetting("dbVar_pintrestURL") != "") || ($Site->core->getDbSetting("dbVar_instagramURL") != ""))) { %>%
                        <div class="social">
                            %<%= $Site->core->getDbSetting("dbVar_facebookURL") != "" ? "<a target=\"_blank\" class=\"facebook\" title=\"Please click here to visit our facebook page\" href=\"" . $Site->core->getDbSetting("dbVar_facebookURL") . "\"></a>" : "" %>%
                            %<%= $Site->core->getDbSetting("dbVar_googleplusURL") != "" ? "<a target=\"_blank\" class=\"google\" title=\"Please click here to visit our Google+ page\" href=\"" . $Site->core->getDbSetting("dbVar_googleplusURL") . "\"></a>" : "" %>%
                            %<%= $Site->core->getDbSetting("dbVar_linkedinURL") != "" ? "<a target=\"_blank\" class=\"linkedin\" title=\"Please click here to visit our Linkedin page\" href=\"" . $Site->core->getDbSetting("dbVar_linkedinURL") . "\"></a>" : "" %>%
                            %<%= $Site->core->getDbSetting("dbVar_twitterURL") != "" ? "<a target=\"_blank\" class=\"twitter\"  title=\"Please click here to visit our Twitter page\" href=\"" . $Site->core->getDbSetting("dbVar_twitterURL") . "\"></a>" : "" %>%
                            %<%= $Site->core->getDbSetting("dbVar_youtubeURL") != "" ? "<a target=\"_blank\" class=\"youtube\"  title=\"Please click here to visit our Youtube page\" href=\"" . $Site->core->getDbSetting("dbVar_youtubeURL") . "\"></a>" : "" %>%
                            %<%= $Site->core->getDbSetting("dbVar_instagramURL") != "" ? "<a target=\"_blank\" class=\"instagram\"  title=\"Please click here to visit our Instagram page\" href=\"" . $Site->core->getDbSetting("dbVar_instagramURL") . "\"></a>" : "" %>%
                            %<%= $Site->core->getDbSetting("dbVar_pintrestURL") != "" ? "<a target=\"_blank\" class=\"pintrest\"  title=\"Please click here to visit our Pintrest page\" href=\"" . $Site->core->getDbSetting("dbVar_pintrestURL") . "\"></a>" : "" %>%
                        </div>
                    %<% } %>%
                </div>
                <!-- HEADER CENTER END -->
                <!-- HEADER RIGHT START -->
                <div class="header-right">
                    %<% if ($Site->current->site->isNotBasicSite()) { %>%
                        <div id="login-nav" class="dropdown">
                            %<%
                                if (!isset($_SESSION['user_info']['username'])) {
                                    if ($Site->theme->getThemeSetting("header_login_style") == 2) {
                                        %>%
                                        <a href="%<%= $Site->core->getCartDomain() %>%account/login.html"><i
                                                    class="fa fa-key" aria-hidden="true"></i> Login/Create Account</a>
                                        <a href="%<%= $Site->core->getCartDomain() %>%store/track-order.html"
                                           class="login-last">Order Status</a>
                                        %<%
                                    } else {
                                        %>%
                                        <a href="%<%= $Site->core->getCartDomain() %>%account/login.html"
                                           class="login-last"><i class="fa fa-key" aria-hidden="true"></i> Login</a>
                                        %<%
                                    }
                                } else {
                                    if ($Site->isUserLoggedIn()) {
                                        
                                        $organizations = $Site->current->user->organizations()->excludeOnlineDesignerOrganization()->get();
                                    }

                                    if (isset($organizations) && count($organizations) > 0) {
                                        $canUseSiteAsSelf = false;

                                        if (isset($_SESSION['user_info']['organizationID'])) {
                                            $organization = $Site->current->organization;
                                        }

                                        if (isset($organization)) {
                                            $Site->current->setOrganization($organization);
                                            $member = $Site->current->organization->getMember($Site->current->user->cID);

                                            if ($member !== null && $member->check('member.canUseSiteAsSelf')) {
                                                $canUseSiteAsSelf = true;
                                            }
                                        }

                                        %>%
                                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                            Welcome %<%php echo (!isset($_SESSION['user_info']['organizationID']) || ($_SESSION['user_info']['organizationID'] == 0)) ? $customerName . ' <span class="glyphicon glyphicon-user"></span>' : $_SESSION['user_info']['organizationName'] . ' Organization <span class="glyphicon glyphicon-globe"></span>'; %>%
                                            <span class="caret"></span></a>
                                        <ul class="dropdown-menu pull-right">
                                            %<% if ($canUseSiteAsSelf) { %>%
                                                <li class="%<%php echo (!isset($_SESSION['user_info']['organizationID']) || ($_SESSION['user_info']['organizationID'] == 0)) ? 'active' : ''; %>%">
                                                    <a style="margin-right: 0;"
                                                       href="/store/set-organization.html?organizationID=0%<%= $onlineDesignPortal %>%&manualSet=1">
                                                        <span class="glyphicon glyphicon-user"></span>%<%php echo (!isset($_SESSION['user_info']['organizationID']) || ($_SESSION['user_info']['organizationID'] == 0)) ? ' Using Site as Myself' : ' Switch to Myself'; %>%
                                                    </a>
                                                </li>
                                            %<% } %>%
                                            %<%
                                                foreach ($organizations as $organization) {
                                                    %>%
                                                    <li class="%<%php echo (isset($_SESSION['user_info']['organizationID']) && ($_SESSION['user_info']['organizationID'] == $organization->organizationID)) ? 'active' : ''; %>%">
                                                        <a style="margin-right: 0;"
                                                           href="/store/set-organization.html?organizationID=%<%= $organization->organizationID %>%&manualSet=1"><span
                                                                    class="glyphicon glyphicon-globe"></span>%<%php echo (isset($_SESSION['user_info']['organizationID']) && ($_SESSION['user_info']['organizationID'] == $organization->organizationID)) ? ' Using site as the ' . $organization->name . ' Organization' : ' Switch to the ' . $organization->name . ' Organization'; %>%
                                                        </a>
                                                    </li>
                                                    %<%
                                                }
                                            %>%

                                        </ul>
                                        <a href='%<%= $Site->core->getCartDomain(false, true) %>%account/logout.html'
                                           class="login-last">Logout</a>
                                        %<%
                                    } elseif ($Site->theme->getThemeSetting("header_login_style") == 2) {
                                        %>%
                                        <a rel="nofollow" href="%<%= $Site->core->getCartDomain(false, true) %>%home/dashboard.html">Welcome %<%= $customerName %>%</a>
                                        <a href='%<%= $Site->core->getCartDomain(false, true) %>%account/logout.html'
                                           class="login-last">Logout</a>
                                        %<%
                                    } else {
                                        %>%
                                        <a rel="nofollow" href="%<%= $Site->core->getCartDomain(false, true) %>%home/dashboard.html">%<%= $customerName %>%</a>
                                        <a href='%<%= $Site->core->getCartDomain(false, true) %>%account/logout.html'
                                           class="login-last">Logout</a>
                                        %<%
                                    }
                                }
                            %>%
                        </div>
                        <div class="cart-links">
                            %<% if ($Site->theme->getThemeSetting("header_cart_item") == 1) { %>%
                                <a href="%<%= $Site->core->getCartDomain(false, true) %>%store/cart-view.html"
                                   class="view-cart">
                                    <i class="fa fa-shopping-cart" aria-hidden="true"></i> Cart <span
                                            class="cart-count badge">%<%= cartProduct::totalItemsInCart() %>%</span>
                                </a>
                            %<% } elseif (cartProduct::totalItemsInCart() > 0) { %>%%<%
                                if (!isset($_REQUEST['paymentDomain'])) {
                                    %>%
                                    <span class="view-cart">
                                    <a href="%<%= $Site->core->getCartDomain(false, true) %>%store/cart-view.html"><span
                                                class="glyphicon glyphicon-shopping-cart"></span> View %<%= cartProduct::totalItemsInCart() === 1 ? cartProduct::totalItemsInCart() . ' Cart Item' : cartProduct::totalItemsInCart() . ' Cart Items' %>%
                                    </a>
                               </span>
                                    <a class="remove-all-items glyphicon glyphicon-trash"
                                       onclick="return confirm('Are you sure you want to empty your cart?');"
                                       href="%<%= $Site->core->getCartDomain(false, true) %>%store/clear-cart.html"></a>
                                    %<%
                                } else {
                                    %>%
                                    <span class="view-cart">
                                    <a href="%<%= $Site->core->getCartDomain(false,
                                        true) %>%store/redirectBackToOriginalSite.html?redirectBackUrl=%<%= $Site->core->getCartDomain(false,
                                        true) . 'store/cart-view.html' %>%"><span
                                                class="glyphicon glyphicon-shopping-cart"></span> View %<%= cartProduct::totalItemsInCart() === 1 ? cartProduct::totalItemsInCart() . ' Cart Item' : cartProduct::totalItemsInCart() . ' Cart Items' %>%
                                    </a>
                                </span>
                                    <a class="remove-all-items"
                                       onclick="return confirm('Are you sure you want to empty your cart?');"
                                       href="%<%= $Site->core->getCartDomain(false,
                                           true) %>%store/redirectBackToOriginalSite.html?redirectBackUrl=%<%= $Site->core->getCartDomain(false,
                                           true) . 'store/clear-cart.html' %>%"></a>
                                    %<%
                                }
                                %>%
                                <span class="cart-total">| Total: %<%= I18nNumberFormatter::currencyFormatter($Site->store->get_total(false)) %>%</span>
                            %<% } else { %>%
                                <span class="glyphicon glyphicon-shopping-cart"></span>
                                <span class="view-cart">Cart Empty!</span>
                            %<% } %>%
                        </div>

                        %<% if ($Site->theme->getThemeSetting("header_display_balance") == 1) { %>%
                            <div class="owes-money">
                                <a rel='nofollow' href='%<%= $Site->core->getCartDomain(false,
                                    true) %>%orders/view-my-orders.html'>%<%= $Site->core->thisCustOwes() %>%</a>
                            </div>
                        %<% } %>%
                    %<% } %>%
                </div>
                <!-- HEADER RIGHT END -->
            </div>
        </div>
        <div class="container navbar-container">

            <nav class="navbar navbar-default main-menu  %<%=$Site->theme->getThemeSetting("accent_color")%>% %<%=$Site->theme->getThemeSetting("border_color")%>% %<%=$Site->theme->getThemeSetting("border_radius")%>%">
                <div class="container-fluid">
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse"
                                data-target="#auto-print-nav-collapse">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        %<% if (empty($portal->minimal)) { %>%
                            <a class="navbar-brand product-menu" href="#" id="product-menu">
                                %<% if ($Site->current->site->isNotBasicSite()) { %>%
                                    Place Order &#x2193;
                                %<% } else { %>%
                                    Browse Catalog &#x2193;
                                %<% } %>%
                            </a>
                        %<% } %>%
                    </div>

                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse" id="auto-print-nav-collapse">
                        <ul class="nav navbar-nav">
                            %<%
                                if (count($menus) === 0) {
                                    %>%
                                    <li><a href="%<%= $Site->core->getCartDomain() %>%">Home</a></li>
                                    %<% if ($Site->current->site->isNotBasicSite()) { %>%
                                        %<% if (!isset($_SESSION['variables']) || (isset($_SESSION['user_info']['username']) && ($_SESSION['user_info']['username'] != ''))) { %>%
                                            <li><a rel="nofollow" href="%<%= $Site->core->getCartDomain() %>%home/dashboard.html">My
                                                    Account</a></li>
                                        %<% } %>%
                                    %<% }

                                    if (empty($portal->minimal)) { %>%
                                        <li class="dropdown">
                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Services
                                                <span class="caret"></span></a>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a href="%<%= $Site->core->getCartDomain() %>%services/printing-services.html">Printing
                                                        Services</a>
                                                </li>
                                                <li>
                                                    <a href="%<%= $Site->core->getCartDomain() %>%services/design-services.html">Design
                                                        Services</a>
                                                </li>
                                                <li class="divider"></li>
                                                <li>
                                                    <a href="%<%= $Site->core->getCartDomain() %>%services/mailing-services.html">Mailing
                                                        Services</a>
                                                </li>
                                                <li>
                                                    <a href="%<%= $Site->core->getCartDomain() %>%services/mailing-lists.html">Mailing
                                                        Lists</a>
                                                </li>
                                            </ul>
                                        </li>
                                        <li><a href="%<%= $Site->core->getCartDomain() %>%help/index.html">Customer
                                                Support</a></li>
                                    %<% }
                                }                                else {
                                    foreach ($menus as $menu) {
                                                                                if (isset($portal->minimal) && $portal->minimal === 1 && (strpos($menu['url'], 'home.html') === false && strpos($menu['url'], 'dashboard.html') === false && strpos($menu['template'], 'home.html') === false && strpos($menu['template'], 'dashboard.html') === false)) {
                                            continue;
                                        }
                                                                                if (isset($_SESSION['variables']) && ($Site->isUserNotLoggedIn())) {
                                            if (($menu['site'] === 'Customer') && ($menu['template'] === 'custom_quote.html')) {
                                                continue;
                                            }
                                            if (($menu['site'] === 'Customer') && ($menu['template'] === 'home.html')) {
                                                continue;
                                            }
                                        }

                                        $menuItems = $Site->core->listMenuItems($menu['id']);

                                        if ($menu['url'] == '') {
                                            if ($menu['site'] === 'Store') {
                                                $url = "{$Site->core->getCartDomain()}{$menu['library']}/{$menu['template']}";
                                            } else {
                                                $url = "{$Site->core->getCartDomain()}{$menu['library']}/{$menu['template']}";
                                            }
                                        } else {
                                            $url = $menu['url'];
                                        }

                                        $dropdown = "";
                                        $caret = "";
                                        %>%%<%
                                        if (count($menuItems) > 0 && empty($portal->minimal)) {
                                            %>%
                                            <li class="dropdown">
                                                <a href="#" class="dropdown-toggle"
                                                   data-toggle="dropdown">%<%= $menu['label'] %>%
                                                    <span class="caret"></span></a>
                                                <ul class="dropdown-menu">
                                                    %<%
                                                        foreach ($menuItems as $menuItem) {
                                                            if ($menuItem['url'] == '') {
                                                                if ($menuItem['site'] === 'Store') {
                                                                    $url = "{$Site->core->getCartDomain()}{$menuItem['library']}/{$menuItem['template']}";
                                                                } else {
                                                                    $url = "{$Site->core->getCartDomain()}{$menuItem['library']}/{$menuItem['template']}";
                                                                }
                                                            } else {
                                                                $url = $menuItem['url'];
                                                            }
                                                            %>%
                                                            <li>
                                                                <a href="%<%= $url %>%" class="menu-link"
                                                                   target="%<%= $menuItem['target'] %>%">%<%= $menuItem['label'] %>%</a>
                                                            </li>
                                                            %<%
                                                        }
                                                    %>%
                                                </ul>
                                            </li>
                                        %<% } else { %>%
                                            <li>
                                                <a href="%<%= $url %>%"
                                                   target="%<%= $menu['target'] %>%">%<%= $menu['label'] %>%</a>
                                            </li>
                                            %<%
                                        }
                                        %>%%<%
                                    }
                                }
                            %>%
                        </ul>

                        %<% if ($Site->theme->getThemeSetting("header_display_search") == 1) { %>%
                            <form class="navbar-form navbar-left">
                                <div class="form-group search-tool-group">
                                    <input type="text"
                                           class="form-control cat-product-search-input header-search productCategorySearch"
                                           placeholder="Search Products" name="productCategorySearch"
                                           id="productCategorySearch">
                                    <label for="productCategorySearch"><i class="fa fa-search"
                                                                          aria-hidden="true"></i><span class="sr-only">Search Products</span></label>
                                </div>
                            </form>
                        %<% } %>%

                        %<% if ($Site->current->portal) { %>%
                            <ul class="nav navbar-nav  pull-right">
                                %<% if ($Site->current->portal->isNotMainOnlineDesigner()) { %>%
                                    <li>
                                        <a href='#' class="unsetPortal">Exit Portal</a>
                                    </li>
                                %<% } %>%
                                %<% if (($CanvasBase->designs_in_progress_count() > 0 && cartProduct::totalItemsInCart() > 0) && $Site->isUserLoggedIn()) { %>%
                                    <li class="switchCartItem">
                                        <a href="#">View %<%= $CanvasBase->designs_in_progress_count() %>% Design(s)</a>
                                    </li>
                                %<% } %>%
                            </ul>
                        %<% } %>%
                    </div><!-- /.navbar-collapse -->
                </div>
            </nav>
        </div>
    </div><!--HEADER END-->


%<% } else { %>%
    <!-- Centered Header with navigation bar -->
    <div class="top-bar">
        <div class="top-bar-content">
            <!-- TOP BAR LEFT START -->
            <div class="header-left">
                %<% if ($Site->theme->getThemeSetting("header_display_contact") == 1) { %>%
                    <div id="phone-number" class="click-to-call">
                        %<% if ($Site->core->getDbSetting("dbvar_displayPhoneInHeader") === "Yes") { %>%
                            <span class="glyphicon glyphicon-earphone"></span> <a
                                    href="tel:%<%= $Site->core->getDbSetting("dbVar_tollfreeNumber") ?: $Site->core->getDbSetting("dbVar_directNumber") %>%">%<%= $Site->core->getDbSetting("dbVar_tollfreeNumber") ?: $Site->core->getDbSetting("dbVar_directNumber") %>%</a>
                        %<% } else { %>%
                            <a href="%<%= $Site->core->getCartDomain() %>%help/index.html"><span
                                        class="glyphicon glyphicon-earphone"></span> Customer Support</a>
                        %<% } %>%
                    </div>
                %<% } %>%
                %<% if ((empty($portal->minimal) && $Site->theme->getThemeSetting("header_display_social") == 1) && (($Site->core->getDbSetting("dbVar_facebookURL") != "") || ($Site->core->getDbSetting("dbVar_googleplusURL") != "") || ($Site->core->getDbSetting("dbVar_linkedinURL") != "") || ($Site->core->getDbSetting("dbVar_twitterURL") != "") || ($Site->core->getDbSetting("dbVar_youtubeURL") != "") || ($Site->core->getDbSetting("dbVar_pintrestURL") != "") || ($Site->core->getDbSetting("dbVar_instagramURL") != ""))) { %>%
                    <div class="social">
                        %<%= $Site->core->getDbSetting("dbVar_facebookURL") != "" ? "<a target=\"_blank\" class=\"facebook\" title=\"Please click here to visit our facebook page\" href=\"" . $Site->core->getDbSetting("dbVar_facebookURL") . "\"></a>" : "" %>%
                        %<%= $Site->core->getDbSetting("dbVar_googleplusURL") != "" ? "<a target=\"_blank\" class=\"google\" title=\"Please click here to visit our Google+ page\" href=\"" . $Site->core->getDbSetting("dbVar_googleplusURL") . "\"></a>" : "" %>%
                        %<%= $Site->core->getDbSetting("dbVar_linkedinURL") != "" ? "<a target=\"_blank\" class=\"linkedin\" title=\"Please click here to visit our Linkedin page\" href=\"" . $Site->core->getDbSetting("dbVar_linkedinURL") . "\"></a>" : "" %>%
                        %<%= $Site->core->getDbSetting("dbVar_twitterURL") != "" ? "<a target=\"_blank\" class=\"twitter\"  title=\"Please click here to visit our Twitter page\" href=\"" . $Site->core->getDbSetting("dbVar_twitterURL") . "\"></a>" : "" %>%
                        %<%= $Site->core->getDbSetting("dbVar_youtubeURL") != "" ? "<a target=\"_blank\" class=\"youtube\"  title=\"Please click here to visit our Youtube page\" href=\"" . $Site->core->getDbSetting("dbVar_youtubeURL") . "\"></a>" : "" %>%
                        %<%= $Site->core->getDbSetting("dbVar_instagramURL") != "" ? "<a target=\"_blank\" class=\"instagram\"  title=\"Please click here to visit our Instagram page\" href=\"" . $Site->core->getDbSetting("dbVar_instagramURL") . "\"></a>" : "" %>%
                        %<%= $Site->core->getDbSetting("dbVar_pintrestURL") != "" ? "<a target=\"_blank\" class=\"pintrest\"  title=\"Please click here to visit our Pintrest page\" href=\"" . $Site->core->getDbSetting("dbVar_pintrestURL") . "\"></a>" : "" %>%
                    </div>
                %<% } %>%
            </div>
            <!-- TOP BAR LEFT END -->

            <!-- TOP BAR RIGHT START -->
            <div class="header-right">
                %<% if ($Site->theme->getThemeSetting("header_display_balance") == 1) { %>%
                    <div class="owes-money">
                        <a rel='nofollow' href='%<%= $Site->core->getCartDomain(false,
                            true) %>%orders/view-my-orders.html'>%<%= $Site->core->thisCustOwes() %>%</a>
                    </div>
                %<% } %>%

                <div class="cart-links">
                    %<% if ($Site->theme->getThemeSetting("header_cart_item") == 1) { %>%
                        <a href="%<%= $Site->core->getCartDomain(false, true) %>%store/cart-view.html" class="view-cart">
                            <i class="fa fa-shopping-cart" aria-hidden="true"></i> Cart <span
                                    class="cart-count badge">%<%= cartProduct::totalItemsInCart() %>%</span>
                        </a>
                    %<% } elseif (cartProduct::totalItemsInCart() > 0) { %>%%<%
                        if (!isset($_REQUEST['paymentDomain'])) {
                            %>%
                            <span class="view-cart">
                                        <a href="%<%= $Site->core->getCartDomain(false,
                                            true) %>%store/cart-view.html"><span
                                                    class="glyphicon glyphicon-shopping-cart"></span> View %<%= cartProduct::totalItemsInCart() === 1 ? cartProduct::totalItemsInCart() . ' Cart Item' : cartProduct::totalItemsInCart() . ' Cart Items' %>%
                                        </a>
                                   </span>
                            <a class="remove-all-items glyphicon glyphicon-trash"
                               onclick="return confirm('Are you sure you want to empty your cart?');"
                               href="%<%= $Site->core->getCartDomain(false, true) %>%store/clear-cart.html"></a>
                            %<%
                        } else {
                            %>%
                            <span class="view-cart">
                                        <a href="%<%= $Site->core->getCartDomain(false,
                                            true) %>%store/redirectBackToOriginalSite.html?redirectBackUrl=%<%= $Site->core->getCartDomain(false,
                                            true) . 'store/cart-view.html' %>%"><span
                                                    class="glyphicon glyphicon-shopping-cart"></span> View %<%= cartProduct::totalItemsInCart() === 1 ? cartProduct::totalItemsInCart() . ' Cart Item' : cartProduct::totalItemsInCart() . ' Cart Items' %>%
                                        </a>
                                    </span>
                            <a class="remove-all-items"
                               onclick="return confirm('Are you sure you want to empty your cart?');"
                               href="%<%= $Site->core->getCartDomain(false,
                                   true) %>%store/redirectBackToOriginalSite.html?redirectBackUrl=%<%= $Site->core->getCartDomain(false,
                                   true) . 'store/clear-cart.html' %>%"></a>
                            %<%
                        }
                        %>%
                        <span class="cart-total">| Total: %<%= I18nNumberFormatter::currencyFormatter($Site->store->get_total(false)) %>%</span>
                    %<% } else { %>%
                        <span class="glyphicon glyphicon-shopping-cart"></span>
                        <span class="view-cart">Cart Empty!</span>
                    %<% } %>%
                </div>

                %<% if ($Site->current->site->isNotBasicSite()) { %>%
                    <div id="login-nav" class="dropdown">
                        %<%
                            if (!isset($_SESSION['user_info']['username'])) {
                                if ($Site->theme->getThemeSetting("header_login_style") == 2) {
                                    %>%
                                    <a href="%<%= $Site->core->getCartDomain() %>%account/login.html"><i class="fa fa-key"
                                                                                                       aria-hidden="true"></i>
                                        Login/Create Account</a>
                                    <a href="%<%= $Site->core->getCartDomain() %>%store/track-order.html"
                                       class="login-last">Order Status</a>
                                    %<%
                                } else {
                                    %>%
                                    <a href="%<%= $Site->core->getCartDomain() %>%account/login.html"
                                       class="login-last"><i class="fa fa-key" aria-hidden="true"></i> Login</a>
                                    %<%
                                }
                            } elseif ($Site->isUserLoggedIn()) {
                                
                                $organizations = $Site->current->user->organizations()->excludeOnlineDesignerOrganization()->get();

                                if (isset($organizations) && count($organizations) > 0) {
                                    $canUseSiteAsSelf = false;

                                    if (isset($_SESSION['user_info']['organizationID'])) {
                                        $organization = $Site->current->organization;
                                    }

                                    if (!empty($organization)) {
                                        $Site->current->setOrganization($organization);
                                        $member = $Site->current->organization->getMember($Site->current->user->cID);

                                        if (!empty($member) && $member->check('member.canUseSiteAsSelf')) {
                                            $canUseSiteAsSelf = true;
                                        }
                                    }
                                    %>%
                                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                        Welcome %<%php echo (!isset($_SESSION['user_info']['organizationID']) || ($_SESSION['user_info']['organizationID'] == 0)) ? $customerName . ' <span class="glyphicon glyphicon-user"></span>' : $_SESSION['user_info']['organizationName'] . ' Organization <span class="glyphicon glyphicon-globe"></span>'; %>%
                                        <span class="caret"></span></a>
                                    <ul class="dropdown-menu pull-right">
                                        %<% if ($canUseSiteAsSelf) { %>%
                                            <li class="%<%php echo (!isset($_SESSION['user_info']['organizationID']) || ($_SESSION['user_info']['organizationID'] == 0)) ? 'active' : ''; %>%">
                                                <a style="margin-right: 0;"
                                                   href="/store/set-organization.html?organizationID=0"><span
                                                            class="glyphicon glyphicon-user"></span>%<%php echo (!isset($_SESSION['user_info']['organizationID']) || ($_SESSION['user_info']['organizationID'] == 0)) ? ' Using Site as Myself' : ' Switch to Myself'; %>%
                                                </a>
                                            </li>
                                        %<% } %>%
                                        %<%
                                            foreach ($organizations as $organization) {
                                                %>%
                                                <li class="%<%php echo (isset($_SESSION['user_info']['organizationID']) && ($_SESSION['user_info']['organizationID'] == $organization->organizationID)) ? 'active' : ''; %>%">
                                                    <a style="margin-right: 0;"
                                                       href="/store/set-organization.html?organizationID=%<%= $organization->organizationID %>%"><span
                                                                class="glyphicon glyphicon-globe"></span>%<%php echo (isset($_SESSION['user_info']['organizationID']) && ($_SESSION['user_info']['organizationID'] == $organization->organizationID)) ? ' Using site as the ' . $organization->name . ' Organization' : ' Switch to the ' . $organization->name . ' Organization'; %>%
                                                    </a>
                                                </li>
                                                %<%
                                            }
                                        %>%

                                    </ul>
                                    <a href='%<%= $Site->core->getCartDomain(false, true) %>%account/logout.html'
                                       class="login-last">Logout</a>
                                    %<%
                                } elseif ($Site->theme->getThemeSetting("header_login_style") == 2) {
                                    %>%
                                    <a rel="nofollow" href="%<%= $Site->core->getCartDomain(false, true) %>%home/dashboard.html">Welcome %<%= $customerName %>%</a>
                                    <a href='%<%= $Site->core->getCartDomain(false, true) %>%account/logout.html'
                                       class="login-last">Logout</a>
                                    %<%
                                } else {
                                    %>%
                                    <a rel="nofollow" href="%<%= $Site->core->getCartDomain(false, true) %>%home/dashboard.html">%<%= $customerName %>%</a>
                                    <a href='%<%= $Site->core->getCartDomain(false, true) %>%account/logout.html'
                                       class="login-last">Logout</a>
                                    %<%
                                }
                            }
                        %>%
                    </div>
                %<% } %>%
            </div>
            <!-- TOP BAR RIGHT END -->
        </div>
    </div>


    <div class="header %<%= $Site->theme->getThemeSetting("border_radius") %>%" id="header-centered">

        <div class="container header-container">
            <!-- HEADER CENTER START -->
            <div class="header-center">
                <div class="logo">
                    <a href="%<%= $Site->core->getCartDomain() %>%">
                        %<%
                            if (isset($_SESSION['cbPortal'], $_SESSION['cbPortal']['portalThemeActive']) && $_SESSION['cbPortal']['portalThemeActive'] != 0) {
                                $logoUrl = $Site->theme->getThemeSetting("logo_url");
                            } else {
                                $logoUrl = $Site->core->getDbSetting("dbVar_logoUrl");
                            }

                            if (($Site->core->getDbSetting("dbVar_companyName") == '') && ($logoUrl == '')) {
                                $logoUrl = asset('themes/general/images/misc/default-logo.jpg');
                            }
                            if (stripos($Site->core->getDbSetting("dbVar_logoUrl"),
                                    'default-logo') === false && ($logoUrl != '')) {
                                %>%
                                <img alt="%<%= $Site->core->getDbSetting("dbVar_companyName") %>%" src="%<%= $logoUrl %>%"
                                     onerror="this.src='/themes/general/images/misc/no_image.gif'" height="59" width="331"/>
                            %<% } elseif (stripos($Site->core->getDbSetting("dbVar_logoUrl"),
                                    'default-logo') !== false && ($logoUrl != '')) { %>%
                                <img alt="%<%= $Site->core->getDbSetting("dbVar_companyName") %>%"
                                     src="%<%= $logoUrl %>%" height="59" width="331"/>
                            %<% } else { %>%
                                <h1>%<%= ($Site->core->getDbSetting("dbVar_companyName") != '') ? $Site->core->getDbSetting("dbVar_companyName") : $Site->getServer('SERVER_NAME') %>%</h1>
                            %<% }
                        %>%
                    </a>
                </div>
            </div>
            <!-- HEADER CENTER END -->
        </div>

        <div class="container navbar-container">

            <nav class="navbar navbar-default main-menu  %<%=$Site->theme->getThemeSetting("accent_color")%>% %<%=$Site->theme->getThemeSetting("border_color")%>% %<%=$Site->theme->getThemeSetting("border_radius")%>%">
                <div class="container-fluid">
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse"
                                data-target="#auto-print-nav-collapse">
                            <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span>
                            <span class="icon-bar"></span> <span class="icon-bar"></span>
                        </button>
                    </div>

                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse" id="auto-print-nav-collapse">
                        <ul class="nav navbar-nav">
                            %<% if (empty($portal->minimal)) { %>%
                            <li>
                                <a class="navbar-brand product-menu" href="#" id="product-menu">
                                    %<% if ($Site->current->site->isNotBasicSite()) { %>%
                                        Place Order &#x2193;
                                    %<% } else { %>%
                                        Browse Catalog &#x2193;
                                    %<% } %>%
                                </a>
                            </li>
                            %<% }
                                if (count($menus) === 0) {
                                    %>%
                                    <li><a href="%<%= $Site->core->getCartDomain() %>%">Home</a></li>
                                    %<% if ($Site->current->site->isNotBasicSite()) { %>%
                                        %<% if (!isset($_SESSION['variables']) || (isset($_SESSION['user_info']['username']) && ($_SESSION['user_info']['username'] != ''))) { %>%
                                            <li><a rel="nofollow" href="%<%= $Site->core->getCartDomain() %>%home/dashboard.html">My
                                                    Account</a></li>
                                        %<% } %>%
                                    %<% } %>%
                                    %<% if (empty($portal->minimal)) { %>%
                                        <li class="dropdown">
                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Services
                                                <span class="caret"></span></a>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a href="%<%= $Site->core->getCartDomain() %>%services/printing-services.html">Printing
                                                        Services</a>
                                                </li>
                                                <li>
                                                    <a href="%<%= $Site->core->getCartDomain() %>%services/design-services.html">Design
                                                        Services</a>
                                                </li>
                                                <li class="divider"></li>
                                                <li>
                                                    <a href="%<%= $Site->core->getCartDomain() %>%services/mailing-services.html">Mailing
                                                        Services</a>
                                                </li>
                                                <li>
                                                    <a href="%<%= $Site->core->getCartDomain() %>%services/mailing-lists.html">Mailing
                                                        Lists</a>
                                                </li>
                                            </ul>
                                        </li>
                                        <li><a href="%<%= $Site->core->getCartDomain() %>%help/index.html">Customer
                                                Support</a></li>
                                    %<% } %>%
                                    %<%
                                }                                else {
                                    foreach ($menus as $menu) {
                                                                                if (isset($portal->minimal) && $portal->minimal === 1 && (strpos($menu['url'], 'home.html') === false && strpos($menu['url'], 'dashboard.html') === false && strpos($menu['template'], 'home.html') === false && strpos($menu['template'], 'dashboard.html') === false)) {
                                            continue;
                                        }

                                                                                if (isset($_SESSION['variables']) && ($Site->isUserNotLoggedIn())) {
                                            if (($menu['site'] === 'Customer') && ($menu['template'] === 'custom_quote.html')) {
                                                continue;
                                            }
                                            if (($menu['site'] === 'Customer') && ($menu['template'] === 'home.html')) {
                                                continue;
                                            }
                                        }

                                        $menuItems = $Site->core->listMenuItems($menu['id']);

                                        if ($menu['url'] == '') {
                                            $url = "{$Site->core->getCartDomain()}{$menu['library']}/{$menu['template']}";
                                        } else {
                                            $url = $menu['url'];
                                        }

                                        $dropdown = "";
                                        $caret = "";
                                        %>%%<%
                                        if (count($menuItems) > 0) {
                                            %>%
                                            <li class="dropdown">
                                                <a href="#" class="dropdown-toggle"
                                                   data-toggle="dropdown">%<%= $menu['label'] %>%
                                                    <span class="caret"></span></a>
                                                <ul class="dropdown-menu">
                                                    %<%
                                                        foreach ($menuItems as $menuItem) {
                                                            if ($menuItem['url'] == '') {
                                                                $url = "{$Site->core->getCartDomain()}{$menuItem['library']}/{$menuItem['template']}";
                                                            } else {
                                                                $url = $menuItem['url'];
                                                            }
                                                            %>%
                                                            <li>
                                                                <a href="%<%= $url %>%" class="menu-link"
                                                                   target="%<%= $menuItem['target'] %>%">%<%= $menuItem['label'] %>%</a>
                                                            </li>
                                                            %<%
                                                        }
                                                    %>%
                                                </ul>
                                            </li>
                                        %<% } else { %>%
                                            <li>
                                                <a href="%<%= $url %>%"
                                                   target="%<%= $menu['target'] %>%">%<%= $menu['label'] %>%</a>
                                            </li>
                                            %<%
                                        }
                                    }

                                }
                            %>%

                            %<% if ($Site->current->portal) { %>%
                                %<% if ($Site->current->portal->isNotMainOnlineDesigner()) { %>%
                                    <li><a href='#' class="unsetPortal">Exit Portal</a></li>
                                %<% } %>%
                                %<% if (($CanvasBase->designs_in_progress_count() > 0 && cartProduct::totalItemsInCart() > 0) && $Site->isUserLoggedIn()) { %>%
                                    <li class="switchCartItem">
                                        <a href="#">View %<%= $CanvasBase->designs_in_progress_count() %>% Design(s)</a>
                                    </li>
                                %<% } %>%
                            %<% } %>%
                            %<% if ($Site->theme->getThemeSetting("header_display_search") == 1) { %>%
                                <li id="header-search-tool">
                                    <form class="navbar-form search-tool-form">
                                        <div class="form-group search-tool-group">
                                            <input type="text"
                                                   class="form-control cat-product-search-input header-search productCategorySearch"
                                                   placeholder="Search Products" name="productCategorySearch"
                                                   id="productCategorySearch">
                                            <label for="productCategorySearch"><i class="fa fa-search"
                                                                                  aria-hidden="true"></i><span
                                                        class="sr-only">Search Products</span></label>
                                        </div>
                                    </form>
                                </li>
                            %<% } %>%
                        </ul>

                    </div><!-- /.navbar-collapse -->
                </div><!-- /.container-fluid -->
            </nav>

        </div>

    </div><!--HEADER END-->
    <!-- Centered Header with navigation bar -->
%<% } %>%


    <script>
        window.addEventListener('load', function () {
            // EXPANDS THE PRODUCT NAVIGATION MENU
            $(document).ready(function () {

                $(document).on('click', '.product-menu, .cart-view-product-menu', function () {
                    $("html, body").animate({scrollTop: 0}, "fast");

                    if ($("#navCatalogResult").attr('loaded') == '0') {
                        $(".nav-expanded").slideToggle('fast');

                        %<%php if ($Site->core->getDbSetting("dbVar_showCategoryGroups") === 'Yes') {
                        $catalogContentURL = 'store/product_catalog.html?ajax=true';
                    } else {
                        $catalogContentURL = 'store/product_grid.html?ajax=true';
                    } %>%

                        $("#navCatalogResult").load("%<%=$Site->core->getCartDomain() . $catalogContentURL%>%", function () {
                            $("#navCatalogResult").attr('loaded', '1');
                            // Get an array of all element heights
                            var self = $(this);
                            var elementHeights = 100;
                            var maxHeight = 100;
                            var loopCount = 0;

                            function setMaxHeight() {
                                elementHeights = $(".nav-expanded").find($('.category-group-item')).map(function () {
                                    return $(this).height();
                                }).get();

                                // Math.max takes a variable number of arguments
                                // `apply` is equivalent to passing each height as an argument
                                maxHeight = Math.max.apply(null, elementHeights);

                                loopCount++;
                                setCategoryHeights();
                            }

                            function setCategoryHeights() {
                                if (maxHeight <= 100 && loopCount < 5) {
                                    setTimeout(function () {
                                        setMaxHeight();
                                    }, 50);
                                } else {
                                    // Set each height to the max height
                                    $('.category-group-item').height(maxHeight);

                                    if (self.is(":visible")) {
                                        self.css('margin-bottom', '6px');
                                    } else {
                                        self.css('margin-bottom', '0px');
                                    }
                                }
                            }

                            setMaxHeight();
                        });
                    } else {
                        $(".nav-expanded").slideToggle('fast');
                    }

                    $(".productCategorySearch").focus();
                });

                $('.unsetPortal').click(function () {
                    swal({
                        title: "Exit Portal",
                        html: "Are you sure you would like to exit the portal?\n\n<span style='color: #7d7d7d;'>This action will be cancelled if no selection is made within <strong>30 seconds</strong>.</span>",
                        type: "question",
                        showCancelButton: true,
                        confirmButtonColor: "#5cb85c",
                        confirmButtonText: "Yes",
                        cancelButtonColor: "#d33",
                        cancelButtonText: "No",
                        timer: 30000
                    }).then(function () {
                        $.ajax({
                            url: '%<%=$Site->core->getDomain()%>%canvasBase/ajaxLogoutPortal.html',
                            success: function (response) {
                                $(".unsetPortal").hide();
                                $.growlUI('', 'You have successfully exited the portal!<br><br>You will now be redirected to the normal website.', 5000);

                                //remove get variables or else user may not be able to exit the portal
                                window.location = '%<%=$Site->core->getDomain()%>%';

                                setTimeout(function () {
                                    window.location.reload();
                                }, 3500);
                            }
                        });
                    }, function (dismiss) {
                        // dismiss can be "cancel", "overlay", "close", "timer"
                        if (dismiss === "cancel") {
                            swal.clickCancel();
                            swal("Canceled!", "The request was cancelled!", "success");
                        } else if (dismiss === "timer") {
                            swal.clickCancel();
                            swal("Canceled!", "The request was automatically cancelled!", "success");
                        }
                    });
                });
            });
        });
    </script><!-- NAV MENU EXPANDED -->

    <div class="container expanded-nav">
        <div class="nav-expanded %<%=$Site->theme->getThemeSetting("border_radius")%>% %<%=$Site->theme->getThemeSetting("accent_color")%>%"
             style="display:none;">
            <div class="content-block">
                <div class="input-group" id="productSearchTool">
                    <div id="multiple-datasets" class="search-tool-group">
                        <input id="header-search-expanded" type="text"
                               class="form-control cat-product-search-input productCategorySearch header-search"
                               placeholder="Type Here To Search Products" name="productCategorySearch">
                        <label for="header-search-expanded"><i class="fa fa-search" aria-hidden="true"></i><span
                                    class="sr-only">Search Products</span></label>
                    </div>
                </div><!-- /input-group -->
                <div class="alert alert-danger noCategories" role="alert">
                    <span class="glyphicon glyphicon-alert"></span> No products match this search
                </div>
                <div id="navCatalogResult" loaded="0">
                    <h3 style="text-align:center;"><i class="fa fa-cog fa-spin"></i> Loading... Please Wait...</h3>
                </div>

            </div>
        </div>
    </div><!-- NAV MENU EXPANDED -->

%<% if (isset($_SESSION['user_info']['fromAdminAsClient']) || isset($_SESSION['variables']['disableCMS'])) { %>%
    <div class="adminLoginMode">
        %<% if (isset($_SESSION['user_info']['fromAdminAsClient'])) { %>%
            %<% if ($Site->isUserLoggedIn()) {

                if (!isset($_SESSION['user_info']['fiveMostRecentShippingAccounts'])) {
                    $_SESSION['user_info']['fiveMostRecentShippingAccounts'] = $Site->current->user->fiveMostRecentOrdersWithShippingAccounts()->unique('shipping.shipping_account')->pluck('shipping.option.option_name', 'shipping.shipping_account')->toArray();
                }

                $tooltip = "";
                $tooltip .= 'Default Tier: ' . ucwords(ProductOptionValuePrices::getCustomerDefaultTier($Site->current->user->cID, true));

                if ($Site->current->user->defaultProfile !== null) {
                    $tooltip .= '<hr style=\'margin: 5px;\' />';
                    $tooltip .= '<br />';
                    $tooltip .= 'High Priority: ' . (($Site->current->user->defaultProfile->cHighPriority === 'Y') ? 'Yes' : 'No');
                    $tooltip .= '<br />';
                    $tooltip .= 'Free Coating: ' . (($Site->current->user->defaultProfile->cFreeCoating === 'Y') ? 'Yes' : 'No');
                    $tooltip .= '<br />';
                    $tooltip .= 'Reseller: ' . (($Site->current->user->defaultProfile->cReseller === 'Y') ? 'Yes' : 'No');
                    $tooltip .= '<br />';
                    $tooltip .= 'Tax Exempt: ' . (($Site->current->user->defaultProfile->cTaxExempt === 'Y') ? 'Yes' : 'No');
                    $tooltip .= '<br />';
                    $tooltip .= 'Free Shipping: ' . (($Site->current->user->defaultProfile->cFreeShipping === 'Y') ? 'Yes' : 'No');
                }

                if (isset($_SESSION['user_info']['fiveMostRecentShippingAccounts']) && is_array($_SESSION['user_info']['fiveMostRecentShippingAccounts']) && count($_SESSION['user_info']['fiveMostRecentShippingAccounts']) > 0) {
                    $tooltip .= '<hr style=\'margin: 5px;\' />';
                    $tooltip .= '<br />';
                    $tooltip .= 'Ship Account Number(s)#<br><br>';

                    foreach ($_SESSION['user_info']['fiveMostRecentShippingAccounts'] as $saKey => $shippingAccount) {
                        $tooltip .= $shippingAccount . ': ' . $saKey . '<br>';
                    }
                }

                %>%
                You are logged in from the admin section as Client ID %<%= $Site->current->user->cID %>% (%<%= $_SESSION['user_info']['username'] %>%)
                <a href="#" data-toggle="tooltip" data-placement="bottom" title="%<%= $tooltip %>%">[ User Info ]</a> <a
                        class="pull-right"
                        href="%<%= $Site->core->getCartDomain(false, true) %>%account/logout.html"><span
                            class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span> Click Here To Logout</a>
            %<% } else { %>%
                You are attempting to login in from the admin section as a deleted client or there was an error logging in, please try again.
            %<% } %>%

            %<% if (isset($_SESSION['variables']['disableCMS'])) { %>%
                <br>
                <br>
            %<% } %>%
        %<% } %>%

        %<% if (isset($_SESSION['variables']['disableCMS'])) { %>%
            <i>The CMS is temporarily disabled. It will resume working again
                in %<%= ($_SESSION['variables']['disableCMS'] - time()) %>% seconds (refresh page to refresh timer)</i>
        %<% } %>%
    </div>

    <script>
        $(document).ready(function () {
            $('.adminLoginMode').appendTo('body');
            var currentTopMargin = parseInt($('body').css('margin-top'));
            %<% if($Site->theme->getThemeSetting("header_float_nav") == 1 && $Site->theme->getThemeSetting("header_options") == 1){ %>%
            currentTopMargin + %<%=$Site->theme->getThemeSetting("header_height") !== null && $Site->theme->getThemeSetting("header_height") !== '' ? (int)$Site->theme->getThemeSetting("header_height") : 60%>%;
            $('#compact-header.navbar-static-top').css('top', %<%=(isset($_SESSION['user_info']['fromAdminAsClient']) ? 32 : 0)%>% + %<%=(isset($_SESSION['variables']['disableCMS']) ? 32 : 0)%>% + 'px');
            %<% } %>%

            $('body').css('margin-top', currentTopMargin + %<%=(isset($_SESSION['user_info']['fromAdminAsClient']) ? 32 : 0)%>% + %<%=(isset($_SESSION['variables']['disableCMS']) ? 32 : 0)%>% + 'px');
            $('.adminLoginMode').show();
        });
    </script>
%<% } %>%