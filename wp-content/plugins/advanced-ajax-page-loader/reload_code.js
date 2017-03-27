function AAPL_reload_code() {
//This file is generated from the admin panel - dont edit here! 
loadMedia();
}

function AAPL_click_code(thiss) {
//This file is generated from the admin panel - dont edit here! 
// highlight the current menu item
jQuery('ul.menu li').each(function() {
	jQuery(this).removeClass('current-menu-item');
});
jQuery(thiss).parents('li').addClass('current-menu-item');
}

function AAPL_data_code(dataa) {
//This file is generated from the admin panel - dont edit here! 
console.log("Data Ajax Loaded Code / AAPL_pageData: ---------------------------------------");
console.dir(AAPL_pageData);
var matches = AAPL_pageData.match(/<body class=["']([^"']*)["'].*>/),
classes = matches && matches[1];
console.log("BODY CLASSES:" + jQuery('body').attr('class'));
console.log( jQuery('body').attr('class'));
console.log("CLASSES: "+classes);
console.log("------------------------------------------------");
jQuery("body").removeClass().addClass(classes);
}