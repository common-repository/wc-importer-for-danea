//WOOCOMMERCE IMPORTER FOR DANEA | NAVIGATION MENU SCRIPT
jQuery(document).ready(function ($) {
	var $contents = $('.wcifd-admin')
	 $("h2#wcifd-admin-menu a").click(function () {
        var $this = $(this);
        $contents.hide();
        $("#" + $this.data("link")).fadeIn(200);
        $('h2#wcifd-admin-menu a.nav-tab-active').removeClass("nav-tab-active");
        $this.addClass('nav-tab-active');
    }).first().click();
});
