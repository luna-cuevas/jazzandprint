%<%php
    global $Site;
%>%

<!--FOOTER START-->
<div class="container footer-container">
    <div class="footer-wrapper %<%=$Site->theme->getThemeSetting("border_radius"); %>%" id="footer-wrapper">
        <div id="footer" class="content-block">
            <div class="category-groups-footer">
                <h3 class="footer-heading">Services</h3>
                <div class="products-footer">
                    <ul>
                        <li><a class="product-menu" href="#">Product Catalog</a></li>
                        <li>
                            <a href="%<%=$Site->core->getCartDomain()%>%services/printing-services.html">Printing Services</a>
                        </li>
                        %<% if ($Site->core->getDbSetting("dbVar_requestSamplesEnabled") !== 'No') { %>%
                            <li>
                                <a href="%<%=$Site->core->getCartDomain()%>%store/request-sample.html">Request Samples</a>
                            </li>
                        %<% } %>%
                        <li><a class="product-menu" href="#">Place Order</a></li>
                        <li>
                            <a href="%<%=$Site->core->getCartDomain()%>%services/design-services.html">Design Services</a>
                        </li>
                        <li>
                            <a href="%<%=$Site->core->getCartDomain()%>%services/mailing-services.html">Mailing Services</a>
                        </li>
                        <li><a href="%<%=$Site->core->getCartDomain()%>%services/mailing-lists.html">Mailing Lists</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="category-groups-footer">
                <h3 class="footer-heading">Help</h3>
                <div class="products-footer">
                    <ul>
                        <li><a href="%<%=$Site->core->getCartDomain()%>%help/index.html">Customer Support</a></li>
                        <li><a href="%<%=$Site->core->getCartDomain()%>%help/about-us.html">About Us</a></li>
                        <li><a href="%<%=$Site->core->getCartDomain()%>%faq/categories.html">F.A.Q.</a></li>
                        <li><a href="%<%=$Site->core->getCartDomain()%>%help/glossary.html">Glossary</a></li>
                        <li><a href="%<%=$Site->core->getCartDomain()%>%help/print-specs.html">Print Specifications</a>
                        </li>
                        <li><a href="%<%=$Site->core->getCartDomain()%>%help/testimonials.html">Testimonials</a></li>
                        %<% if ($Site->core->isBasicSite() == 0) { %>%
                            <li><a href="%<%=$Site->core->getCartDomain()%>%help/tutorials.html">Tutorials</a></li>
                        %<% } %>%
                    </ul>
                </div>
            </div>
            %<% if ($Site->core->isBasicSite() == 0) { %>%
                <div id="quick-link-cart-footer" class="category-groups-footer">
                    <h3 class="footer-heading">Quick Links</h3>
                    <div class="products-footer">
                        <ul>
                            <li>
                                <a href="%<%=$Site->core->getCartDomain()%>%account/login.html">Login/Create Profile</a>
                            </li>
                            %<% if ($Site->core->getDbSetting("dbVar_requestSamplesEnabled") !== 'No') { %>%
                                <li>
                                    <a href="%<%=$Site->core->getCartDomain()%>%store/request-sample.html">Request Samples</a>
                                </li>
                            %<% } %>%
                            <li><a class="product-menu" href="#">Place Order</a></li>
                            <li>
                                <a href="%<%=$Site->core->getCartDomain()%>%orders/custom_quote.html">Custom Quote</a>
                            </li>
                        %<% if (!isset($_SESSION['variables']) || (isset($_SESSION['user_info']['username']) && ($_SESSION['user_info']['username'] != ''))) { %>%
                                <li>
                                <a href="%<%=$Site->core->getCartDomain()%>%products/view-product-prices.html">Pricing</a>
                                </li>%<% } %>%
                            %<%
                                if (isset($_SESSION['user_info']['username'])) {
                                    %>%
                                    <li>
                                        <a href="%<%=$Site->core->getCartDomain()%>%orders/view-my-orders.html">Order Status</a>
                                    </li>
                                    %<%
                                } else {
                                %>%%<% if (!isset($_SESSION['variables']) || (isset($_SESSION['user_info']['username']) && ($_SESSION['user_info']['username'] != ''))) { %>%
                                        <li><a href="%<%=$Site->core->getCartDomain()%>%home/dashboard.html">My Profile</a>
                                        </li>%<% } %>%%<%
                                }
                            %>%
                        </ul>
                    </div>
                </div>
            %<% } %>%
            <div class="category-groups-footer legal-items">
                <h3 class="footer-heading">Legal &amp; Other</h3>
                <div class="products-footer">
                    <ul>
                        %<% if ($Site->core->isBasicSite() == 0) { %>%
                            <li><a href="%<%=$Site->core->getCartDomain()%>%site_map/site-map.html">Sitemap</a></li>
                        %<% } %>%
                        <li><a href="%<%=$Site->core->getCartDomain()%>%feedback/feedback.html">Customer Feedback</a>
                        </li>
                        <li><a href="%<%=$Site->core->getCartDomain()%>%employment/careers.html">Employment</a></li>
                        <li><a href="%<%=$Site->core->getCartDomain()%>%legal/terms.html">Terms &amp; Conditions</a>
                        </li>
                    </ul>
                </div>
            </div>
            %<% if (($Site->theme->getThemeSetting("header_display_social") == 1) && (($Site->core->getDbSetting("dbVar_facebookURL") != "") || ($Site->core->getDbSetting("dbVar_googleplusURL") != "") || ($Site->core->getDbSetting("dbVar_linkedinURL") != "") || ($Site->core->getDbSetting("dbVar_twitterURL") != "") || ($Site->core->getDbSetting("dbVar_youtubeURL") != "") || ($Site->core->getDbSetting("dbVar_pintrestURL") != "") || ($Site->core->getDbSetting("dbVar_instagramURL") != ""))) { %>%
                <div class="social-icons-footer">
                    <span id="follow-us">Connect with Us:</span><br />
                    <div class="social">
                        %<%=($Site->core->getDbSetting("dbVar_facebookURL") != "") ? "<a target=\"_blank\" class=\"facebook\" rel=\"nofollow\"  title=\"Please click here to visit our facebook page\" href=\"" . $Site->core->getDbSetting("dbVar_facebookURL") . "\"></a>" : ""%>%
                        %<%=($Site->core->getDbSetting("dbVar_googleplusURL") != "") ? "<a target=\"_blank\" class=\"google\" rel=\"nofollow\"  title=\"Please click here to visit our google+ page\" href=\"" . $Site->core->getDbSetting("dbVar_googleplusURL") . "\"></a>" : ""%>%
                        %<%=($Site->core->getDbSetting("dbVar_linkedinURL") != "") ? "<a target=\"_blank\" class=\"linkedin\" rel=\"nofollow\"  title=\"Please click here to visit our linkedin page\" href=\"" . $Site->core->getDbSetting("dbVar_linkedinURL") . "\"></a>" : ""%>%
                        %<%=($Site->core->getDbSetting("dbVar_twitterURL") != "") ? "<a target=\"_blank\" class=\"twitter\"  rel=\"nofollow\"  title=\"Please click here to visit our twitter page\" href=\"" . $Site->core->getDbSetting("dbVar_twitterURL") . "\"></a>" : ""%>%
                        %<%=$Site->core->getDbSetting("dbVar_youtubeURL") != "" ? "<a target=\"_blank\" class=\"youtube\"  rel=\"nofollow\"  title=\"Please click here to visit our Youtube page\" href=\"" . $Site->core->getDbSetting("dbVar_youtubeURL") . "\"></a>" : ""%>%
                        %<%=$Site->core->getDbSetting("dbVar_instagramURL") != "" ? "<a target=\"_blank\" class=\"instagram\"  rel=\"nofollow\"  title=\"Please click here to visit our Instagram page\" href=\"" . $Site->core->getDbSetting("dbVar_instagramURL") . "\"></a>" : ""%>%
                        %<%=$Site->core->getDbSetting("dbVar_pintrestURL") != "" ? "<a target=\"_blank\" class=\"pintrest\"  rel=\"nofollow\"  title=\"Please click here to visit our Pintrest page\" href=\"" . $Site->core->getDbSetting("dbVar_pintrestURL") . "\"></a>" : ""%>%
                    </div>
                </div>
            %<% } %>%
        </div>
    </div><!--FOOTER END-->
</div>


//SUBFOOTER 

<footer class="footer">
        <div class="footer-box">
            <div class="footer-column" id='footer-first' style="text-align: left;">
                <img src="https://s3.amazonaws.com/autoprint/115/images/branding/737/logo.png" alt="">
                <p><strong>Email:</strong> info@jazzandprint.com</p>
                <p><strong>Phone:</strong> (347) 763-2420</p>
                <p><Strong>Location:</Strong> 238 Graham Ave, Brooklyn, NY 11206</p>
                <p>
                    <strong>Connect with us:</strong>
                    <a href="https://www.facebook.com/jazzandprint/"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M22.675 0h-21.35c-.732 0-1.325.593-1.325 1.325v21.351c0 .731.593 1.324 1.325 1.324h11.495v-9.294h-3.128v-3.622h3.128v-2.671c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.795.715-1.795 1.763v2.313h3.587l-.467 3.622h-3.12v9.293h6.116c.73 0 1.323-.593 1.323-1.325v-21.35c0-.732-.593-1.325-1.325-1.325z"/></svg></a>
                    <a href="https://twitter.com/jazzandprint"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-.139 9.237c.209 4.617-3.234 9.765-9.33 9.765-1.854 0-3.579-.543-5.032-1.475 1.742.205 3.48-.278 4.86-1.359-1.437-.027-2.649-.976-3.066-2.28.515.098 1.021.069 1.482-.056-1.579-.317-2.668-1.739-2.633-3.26.442.246.949.394 1.486.411-1.461-.977-1.875-2.907-1.016-4.383 1.619 1.986 4.038 3.293 6.766 3.43-.479-2.053 1.08-4.03 3.199-4.03.943 0 1.797.398 2.395 1.037.748-.147 1.451-.42 2.086-.796-.246.767-.766 1.41-1.443 1.816.664-.08 1.297-.256 1.885-.517-.439.656-.996 1.234-1.639 1.697z"/></svg></a>
                    <a href="https://www.instagram.com/jazzandprint/"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M15.233 5.488c-.843-.038-1.097-.046-3.233-.046s-2.389.008-3.232.046c-2.17.099-3.181 1.127-3.279 3.279-.039.844-.048 1.097-.048 3.233s.009 2.389.047 3.233c.099 2.148 1.106 3.18 3.279 3.279.843.038 1.097.047 3.233.047 2.137 0 2.39-.008 3.233-.046 2.17-.099 3.18-1.129 3.279-3.279.038-.844.046-1.097.046-3.233s-.008-2.389-.046-3.232c-.099-2.153-1.111-3.182-3.279-3.281zm-3.233 10.62c-2.269 0-4.108-1.839-4.108-4.108 0-2.269 1.84-4.108 4.108-4.108s4.108 1.839 4.108 4.108c0 2.269-1.839 4.108-4.108 4.108zm4.271-7.418c-.53 0-.96-.43-.96-.96s.43-.96.96-.96.96.43.96.96-.43.96-.96.96zm-1.604 3.31c0 1.473-1.194 2.667-2.667 2.667s-2.667-1.194-2.667-2.667c0-1.473 1.194-2.667 2.667-2.667s2.667 1.194 2.667 2.667zm4.333-12h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm.952 15.298c-.132 2.909-1.751 4.521-4.653 4.654-.854.039-1.126.048-3.299.048s-2.444-.009-3.298-.048c-2.908-.133-4.52-1.748-4.654-4.654-.039-.853-.048-1.125-.048-3.298 0-2.172.009-2.445.048-3.298.134-2.908 1.748-4.521 4.654-4.653.854-.04 1.125-.049 3.298-.049s2.445.009 3.299.048c2.908.133 4.523 1.751 4.653 4.653.039.854.048 1.127.048 3.299 0 2.173-.009 2.445-.048 3.298z"/></svg></a>
                </p>
            </div>
            <div class="footer-column" id="footer-middle">
                <h4>Help</h4>
                <p><a href="https://store.jazzandprint.com/help/index.html">Customer Support</a></p>
                <p><a href="https://store.jazzandprint.com/help/about-us.html">About Us</a></p>
                <p><a href="https://store.jazzandprint.com/faq/categories.html">F.A.Q.</a></p>
                <p><a href="https://search.google.com/local/writereview?placeid=ChIJ8Rq4I1ZZwokReznArF6A5Po">Reviews</a></p>
                <p><a href="https://store.jazzandprint.com/help/tutorials.html">Tutorials</a></p>
            </div>
            <div class="footer-column" id='footer-end'>
                <h4>Quick Links</h4>
                <p><a href="https://store.jazzandprint.com/account/login.html">Login/Create Profile</a></p>
                <p><a href="https://store.jazzandprint.com/store/request-sample.html">Request Samples</a></p>
                <p><a href="https://store.jazzandprint.com/account/login.html">Custom Quote</a></p>
                <p><a href="https://store.jazzandprint.com/account/login.html">My Profile</a></p>
                <p><a href="https://jazzandprint-236449.square.site/">Promotional Items</a></p>
            </div>
        </div>
        <div class="container_bottom">
            <span class="text_right">
                All Rights Reserved. Jazz &amp; Print. 2021.
                <span class="credit_cards">
                    <i class="fa fa-paypal fa-lg" aria-hidden="true"></i> 
                    <i class="fa fa-cc-mastercard fa-lg" aria-hidden="true"></i> 
                    <i class="fa fa-cc-visa fa-lg" aria-hidden="true"></i> 
                    <i class="fa fa-cc-amex fa-lg" aria-hidden="true"></i> 
                    <i class="fa fa-apple fa-lg" aria-hidden="true"></i> 
                </span>
            </span>
        </div>
    </footer>