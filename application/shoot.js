
system = require('system');
var fname = system.args[1];
var post   =  system.args[2];

var page = require('webpage').create();
var url = 'http://onlinetestapi.aldzs.com/Main/action/Dashboard/Homepage/data_list_html'
if(post != "?") {
    url = url +    post
}

page.open(url, function () {

    var bb = page.render(fname);

    phantom.exit();
});
