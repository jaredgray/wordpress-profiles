

function initialize_[DataBinder::Name]()
{
  if(window.jquery_fileuploadui == true && window.jquery_fileuploadfp)
  {
    $(function ()
    {
        "use strict";
        $(".[DataBinder::Name]").fileupload();
        $(".[DataBinder::Name]").each(
        function ()
        {
            this.ImageType = [DataBinder::FileType];
            this.ProfileID = [DataBinder::ProfileID];
            var that = this;
            this.action = "[DataBinder::HttpHandler]?ProfileID="+ this.ProfileID + "&Type=" + this.ImageType.toString();
            $.getJSON(this.action, 
            function (result)
            {
                if (result && result.length)
                {
                    $(that).fileupload("option", "done").call(that, null, { result: result });
                }
            });
         });
      });
    }
    else
    {
        setTimeout("initialize_[DataBinder::Name]", 100);
    }
});


      function initialize_[DataBinder::Name]()
      {
        
        if(window.jquery_fileuploadui == true && window.jquery_fileuploadfp == true)
        {
          $(document).ready(function()
          {
            $(function ()
            {
                "use strict";
                $(".[DataBinder::Name]").fileupload();
                $(".[DataBinder::Name]").each(
                function ()
                {
                    this.ImageType = [DataBinder::FileType];
                    this.ProfileID = [DataBinder::ProfileID];
                    var that = this;
                    this.action = "[DataBinder::HttpHandler]?ProfileID="+ this.ProfileID + "&Type=" + this.ImageType.toString();
                    $.getJSON(this.action,
                    function (result)
                    {
                    if (result && result.length)
                    {
                        $(that).fileupload("option", "done").call(that, null, { result: result });
                    }
                    });
                });
            });
          });
        }
        else
        {
          window.setTimeout("initialize_[DataBinder::Name]()", 100);
          console.log("Failed loading File upload js  control");
        }
      };
      initialize_[DataBinder::Name]();

 
