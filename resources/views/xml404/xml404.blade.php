<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>site map xml parser</title>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('css/xml404/bootstrap/bootstrap.min.css') }}">   
        <link rel="stylesheet" href="{{ asset('css/xml404/jquery-ui.css') }}">   
        <link rel="stylesheet" type="text/css" href="{{asset('css/xml404/font-awesome/css/font-awesome.min.css')}}">
        <link rel="stylesheet" href="{{ asset('css/xml404/custom.css') }}">
    </head>
    <body>       
        <div class="smx-header">
            <h1 class="text-center smx-text"><b>SITE MAP XML PARSER</b></h1>               
        </div>
        <div class="container main-content">                              
            <div class="section">                              
                <div class="row smx-form justify-content-center mb-5">           
                    <div class="col-md-6">       
                        <form name="siteMapXmapParserForm" id="siteMapXmapParserForm">                                              
                            <div class="input-group">
                                <input placeholder="http://www.example.com/sitemap.xml" type="text" class="form-control" name="smxDoamin" id="smxDoamin">                            
                                <div class="input-group-append">                                
                                    <button class="btn smxValidate" id="smxValidate" type="submit" ><i class="fa fw fa-search"></i></button>                                                                      
                                </div>
                            </div>
                        </form>           
                    </div>
                </div>
                <div class="row smx-text" id="sitemapResult">                     
                    <div class="col-lg-6 mb-5">                            
                        <div class="card smxresult">
                            <div class="card-body">
                                <h4 class="text-center">Active Urls</h4>
                                <hr />
                                <div id="emptyActiveUrlsMsg" class="text-center text-secondary"></div>
                                <ol id="siteMapWorkingUrls" class="active-urls">                                    
                                </ol>
                                <div class="text-center" id="activeUrlLoader"><img src="{{ asset('img/xml404/loader2.gif') }}" height='50' class='m-5'></div>
                            </div>
                        </div>
                    </div>                    
                    <div class="col-lg-6">                            
                        <div class="card smxresult">
                            <div class="card-body">
                                <h4 class="text-center">Broken Urls</h4>
                                <hr />
                                <div id="emptyBrokenUrlsMsg" class="text-center text-muted"></div>
                                <ol id="siteMapBrokenUrls" class="brocken-urls">                                           
                                </ol>
                                <div class="text-center" id="brokenUrlLoader"><img src="{{ asset('img/xml404/loader2.gif') }}" height='50' class='m-5'></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-center" id="backgroundImage"></div>
                <div class="text-center" id="loader"><img src="{{ asset('img/xml404/loader2.gif') }}" height='250' class='m-5 p-4'></div>
            </div>           
        </div>                            
        <footer class="text-center smx-text p-2 mt-4">
            <b>Copyright © 2019 Logistic Infotech Pvt. Ltd. - All rights reserved.</b>
        </footer>     
        <script src="{{ asset('js/xml404/jquery-3.3.1.min.js') }}"></script>
        <script src="{{ asset('js/xml404/jquery-validate/jquery.validate.min.js') }} "></script>
        <script src="{{ asset('js/xml404/jquery-validate/additional-methods.min.js') }} "></script>
        <script src="{{ asset('js/xml404/jquery-form/jquery.form.min.js') }} "></script>    
        <script src="{{ asset('js/xml404/bootstrap/bootstrap.min.js') }}" ></script>
        <script type="text/javascript">            
           
            $(document).ready(function () { 
                var active_url_count, broken_url_count, no_of_request;

                $("#backgroundImage").html("<img src='"+"{{ asset('img/xml404/backgroundImage.png') }}"+"'>").show();
                $("#sitemapResult").hide();              
                $("#loader").hide(); 
               
                //validation               
                $("#siteMapXmapParserForm").validate({
                    rules: {
                        smxDoamin: { 
                            required: true, 
                            url: true
                        }                                    
                    },
                    messages: {
                        smxDoamin: {
                            required: "",
                            url: ""
                        }
                    }
                });                
              
                $(document).on('click', '#smxValidate', function(e){  

                    if($('#siteMapXmapParserForm').valid()) {                                              
                        e.preventDefault();                             
                        $('#smxValidate').attr("disabled", true);                        
                        $("#activeUrlLoader").show(); 
                        $("#brokenUrlLoader").show(); 
                        $("#emptyActiveUrlsMsg").hide();
                        $("#emptyBrokenUrlsMsg").hide();                      
                        $("#sitemapResult").hide();                                              
                        $("#backgroundImage").hide();                  
                        $("#loader").show();                            
                        $("#siteMapWorkingUrls").html("");
                        $("#siteMapBrokenUrls").html("");                                              
                        domain = $('#smxDoamin').val();
                        active_url_count = 0;
                        broken_url_count = 0;
                        no_of_request = 1;
                       
                        $.ajax({              
                            url : "{{ route('validateSiteMapXml') }}",
                            type : "get",                                
                            data: {
                               domain: domain,                               
                            },                          
                            success:function(data) {                                                                          
                                                                                                
                                $("#loader").hide();                                
                                no_of_request--;  

                                if( data["status"] == 200) {                                                                        
                                                                        
                                    $("#sitemapResult").show();
                                    if( $.isEmptyObject(data.responseText["siteMapXml"]) ) {                                          
                                        $('#smxValidate').attr("disabled", false);    
                                        $("#activeUrlLoader").hide();    
                                        $("#brokenUrlLoader").hide(); 

                                        if( $.isEmptyObject(data.responseText["activeUrls"]) ) { 
                                            $("#emptyActiveUrlsMsg").show().html("No Active Url found!");
                                        }
                                        else {                      
                                            $.each( data.responseText["activeUrls"], function( index, urls ) { 
                                                $("#siteMapWorkingUrls").append("<li><a target='_blank' href='"+urls+"'>"+ urls +"</a></li>");
                                                active_url_count++;
                                            });
                                        }

                                        if( $.isEmptyObject(data.responseText["brokenUrls"]) ) {
                                            $("#emptyBrokenUrlsMsg").show().html("No Broken Url found!");
                                        }
                                        else {                                    
                                            $.each( data.responseText["brokenUrls"], function( index, urls ) { 
                                                $("#siteMapBrokenUrls").append("<li><a target='_blank' href='"+urls+"'>"+ urls +"</a></li>");
                                                broken_url_count++;
                                            });
                                        }
                                    }
                                    else{

                                        if(! $.isEmptyObject(data.responseText["activeUrls"]) ) {                      
                                            $.each( data.responseText["activeUrls"], function( index, urls ) { 
                                                $("#siteMapWorkingUrls").append("<li><a target='_blank' href='"+urls+"'>"+ urls +"</a></li>");
                                                active_url_count++;
                                            });
                                        }

                                        if(! $.isEmptyObject(data.responseText["brokenUrls"]) ) {                                   
                                            $.each( data.responseText["brokenUrls"], function( index, urls ) { 
                                                $("#siteMapBrokenUrls").append("<li><a target='_blank' href='"+urls+"'>"+ urls +"</a></li>");
                                                broken_url_count++;
                                            });
                                        }

                                        $.each( data.responseText["siteMapXml"], function( index, urls ) {  
                                            no_of_request++;
                                            siteMapRecursiveXMl(urls);
                                        });
                                    }                                                                     
                                }
                                else {
                                    $("#backgroundImage").html("<img src='"+"{{ asset('img/xml404/no-result-found.png') }}"+" 'height='200' class='m-5'><br /><span class='result-not-found-text'>No Results Found</span>").show();
                                    $('#smxValidate').attr("disabled", false);
                                }

                            },               
                            error:function(data){ 
                                no_of_request--;
                                $("#backgroundImage").html("<img src='"+"{{ asset('img/xml404/no-result-found.png') }}"+"' height='200' class='m-5'><br /><span class='result-not-found-text'>No Results Found</span>").show();
                                $('#smxValidate').attr("disabled", false);
                                $("#loader").hide();
                                console.log(data);                               
                            }
                            
                        });                                                           
                    }
                });                    
                
                function siteMapRecursiveXMl(url) {
                    
                    $.ajax({              
                        url : "{{ route('validateSiteMapXml') }}",
                        type : "get",                                
                        data: {
                           domain: url,                               
                        },                          
                        success:function(data) {                                                                          
                            no_of_request--;                                            
                            if( data["status"] == 200) {                                                                        
                                if( no_of_request == 0 ) {                                      
                                    $('#smxValidate').attr("disabled", false);
                                    $("#activeUrlLoader").hide(); 
                                    $("#brokenUrlLoader").hide(); 
                                    if( active_url_count == 0 ) {
                                        $("#emptyActiveUrlsMsg").show().html("No Active Url found!");                                       
                                    }
                                    if( broken_url_count == 0 ) {
                                        $("#emptyBrokenUrlsMsg").show().html("No Broken Url found!");                                        
                                    }
                                }

                                if(! $.isEmptyObject(data.responseText["activeUrls"]) ) {                                        
                                    $.each( data.responseText["activeUrls"], function( index, urls ) { 
                                        $("#siteMapWorkingUrls").append("<li><a target='_blank' href='"+urls+"'>"+ urls +"</a></li>");
                                        active_url_count++;
                                    });
                                }

                                if( ! $.isEmptyObject(data.responseText["brokenUrls"]) ) {
                                    $.each( data.responseText["brokenUrls"], function( index, urls ) { 
                                        $("#siteMapBrokenUrls").append("<li><a target='_blank' href='"+urls+"'>"+ urls +"</a></li>");
                                        broken_url_count++;
                                    });
                                }

                                if( ! $.isEmptyObject(data.responseText["siteMapXml"]) ) { 
                                    $.each( data.responseText["siteMapXml"], function( index, urls ) {  
                                        no_of_request++;
                                        siteMapRecursiveXMl(urls);
                                    });
                                }
                            }                            
                        },               
                        error:function(data){   
                            no_of_request--;  
                            $("#backgroundImage").html("<img src='"+"{{ asset('img/xml404/no-result-found.png') }}"+"' height='200' class='m-5'><br /><span class='result-not-found-text'>No Results Found</span>").show();
                            $('#smxValidate').attr("disabled", false);
                            $("#loader").hide();                         
                            console.log(data);                               
                        }
                        
                    });  
                }

            });

        </script>
       
    </body>
</html>
