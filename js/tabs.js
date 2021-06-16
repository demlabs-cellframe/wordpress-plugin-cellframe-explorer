document.addEventListener('DOMContentLoaded', function(){
    // Variables
    var clickedTab = jQuery(".tabs > .active");
    var tabWrapper = jQuery(".tab__content");
    var activeTab = tabWrapper.find(".active");
    var activeTabHeight = activeTab.outerHeight();
    // Show tab on page load
    activeTab.show();
    // Set height of wrapper on page load
    tabWrapper.height(activeTabHeight);
    jQuery(".tabs > li").on("click", function() {
        // Remove class from active tab
        jQuery(".tabs > li").removeClass("active");
        // Add class active to clicked tab
        jQuery(this).addClass("active");
        // Update clickedTab variable
        clickedTab = jQuery(".tabs .active");
        // fade out active tab
        activeTab.fadeOut(250, function() {
            // Remove active class all tabs
            jQuery(".tab__content > li").removeClass("active");
            // Get index of clicked tab
            var clickedTabIndex = clickedTab.index();
            // Add class active to corresponding tab
            jQuery(".tab__content > li").eq(clickedTabIndex).addClass("active");
            // update new active tab
            activeTab = jQuery(".tab__content > .active");
            // Update variable
            activeTabHeight = activeTab.outerHeight();
            // Animate height of wrapper to new tab height
            tabWrapper.stop().delay(50).animate({
                height: activeTabHeight
            }, 500, function() {
                // Fade in active tab
                activeTab.delay(50).fadeIn(250);
            });
        });
    });
});

