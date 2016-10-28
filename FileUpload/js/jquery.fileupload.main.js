/*
* jQuery File Upload Plugin JS Example 6.7
* https://github.com/blueimp/jQuery-File-Upload
*
* Copyright 2010, Sebastian Tschan
* https://blueimp.net
*
* Licensed under the MIT license:
* http://www.opensource.org/licenses/MIT
        //this.action = "http://dev.frenchcuthair.com/wp-content/plugins/Profiles/FileUpload/Index.php?ProfileID=1";
*/

/*jslint nomen: true, unparam: true, regexp: true */
/*global $, window, document */

$(function ()
{
    'use strict';

    // Initialize the jQuery File Upload widget:
    $('.fileupload').fileupload();

    // Load existing files:
    $('.fileupload').each(function ()
    {
        this.ImageType = 1;
        this.ProfileID = 1;
        var that = this;
        var cssclass = $(this).attr('class');
        if (cssclass.indexOf('type_') !== -1)
        {
            var classes = cssclass.split(" ");
            $.each(classes, function (idx, classname)
            {
                if (classname.match("^type_"))
                {
                    that.ImageType = parseInt(classname.replace("type_", ""));
                }
                if (classname.match("^profileid_"))
                {
                    that.ProfileID = parseInt(classname.replace("profileid_", ""));
                }
            });
        }
        this.action = "http://dev.frenchcuthair.com/wp-content/plugins/Profiles/FileUpload/Core/Index.php?ProfileID="+ this.ProfileID + "&Type=" + this.ImageType.toString();
        $.getJSON(this.action, function (result)
        {
            if (result && result.length)
            {
                $(that).fileupload('option', 'done').call(that, null, { result: result });
            }
        });
    });

});
$(function ()
{
    'use strict';

    // Initialize the jQuery File Upload widget:
    $('.fileupload2').fileupload();

    // Load existing files:
    $('.fileupload2').each(function ()
    {
        this.ImageType = 1;
        this.ProfileID = 1;
        var that = this;
        var cssclass = $(this).attr('class');
        if (cssclass.indexOf('type_') !== -1)
        {
            var classes = cssclass.split(" ");
            $.each(classes, function (idx, classname)
            {
                if (classname.match("^type_"))
                {
                    that.ImageType = parseInt(classname.replace("type_", ""));
                }
                if (classname.match("^profileid_"))
                {
                    that.ProfileID = parseInt(classname.replace("profileid_", ""));
                }
            });
        }
        this.action = "http://dev.frenchcuthair.com/wp-content/plugins/Profiles/FileUpload/Core/Index.php?ProfileID=" + this.ProfileID + "&Type=" + this.ImageType.toString();
        $.getJSON(this.action, function (result)
        {
            if (result && result.length)
            {
                $(that).fileupload('option', 'done').call(that, null, { result: result });
            }
        });
    });

});
