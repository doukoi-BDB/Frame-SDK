var page = require('webpage').create(); 
var args = require('system').args; 
 
var url = args[1]; 
var filename = args[2]; 
 
page.open(url, function () { 
    page.render(filename); 
    phantom.exit(); 
}); 