<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>DNS Resolver</title>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('css/dnsutility/bootstrap/bootstrap.min.css') }}">   
        <link rel="stylesheet" href="{{ asset('css/dnsutility/jquery-ui.css') }}">   
        <link rel="stylesheet" type="text/css" href="{{asset('css/dnsutility/font-awesome/css/font-awesome.min.css')}}">        
        <link rel="stylesheet" href="{{ asset('css/dnsutility/custom.css') }}">   
    </head>
    <body>
       
        <div class="dnsheader shadow-sm text-light mb-4">
            <h1 class="pt-3 pb-3 text-center"><b>DNS | UTILITY</b></h1>               
        </div>

        <div class="container text-info">                  

            <div id="dnsTypeRecord">
                <div class="row justify-content-center mb-4">           
                    <div class="col-md-8">       
                        <form name="dnsTypeResolverForm" id="dnsTypeResolverForm">                                              
                            <div class="input-group">
                                <input placeholder="www.example.com" type="text" class="form-control" name="typeRecordDomain" id="typeRecordDomain">                            
                                <div class="input-group-append">
                                    <select class="custom-select" id="type" name="type">
                                        
                                        @foreach( config('dnsutility.recordTypes') as $type )
                                            <option>{{ $type }}</option>
                                        @endforeach

                                    </select>
                                    <button class="btn dnsFetchBtn text-white" id="DNSTypeRecordFetchBtn" type="submit" ><i class="fa fw fa-search"></i></button>
                                    <button class="btn dnsDownloadBtn text-white" id="DNSTypeRecordDownloadBtn" type="button" ><i class="fa fw fa-download"></i></button>
                                  
                                </div>
                            </div>
                        </form>           
                    </div>
                </div>
                <div class="row justify-content-center mb-4" id="downloadCountryRecords"> 
                    <button class="btn dnsALlDownloadBtn text-white" id="DNSCountryRecordDownloadBtn" type="button"><i class="fa fw fa-download"></i> <b>Download All Records</b> </button>
                </div>
                <div class="row dnsRecord justify-content-center">           
                    <div class="col-lg-6">  
                        <table class="table dnsTable bg-white mb-4">
                            @foreach( config('dnsutility.nameservers') as $nameserver => $nameserverDetails )
                                <tr id="{{ $nameserver }}" class=" align-middle">
                                    <td class="align-middle" width="300px"><i class="{{ $nameserverDetails['icon'] }}"></i> {{ $nameserverDetails["name"] }}</td>
                                    <td class="dnsUtilityTypeResultData"></td>
                                    <td class="align-middle" width="40px">
                                        <div class="text-info float-right dnsUtilityTypeResult align-middle">                                        
                                            <b><i class="fa fw fa-circle-o"></i></b>                                       
                                        </div> 
                                    </td>                                    
                                </tr>
                            @endforeach
                        </table>                                            
                    </div>
                    <div class="col-lg-6">
                        <div id="typeMap" class="map"></div>                    
                    </div>
                </div>
            </div>
           
        </div>
                            
        <footer class="text-center dnsheader p-2 text-white mt-4">
            <b>Copyright © 2019 Logistic Infotech Pvt. Ltd. - All rights reserved.</b>
        </footer>     

        <script src="{{ asset('js/dnsutility/jquery-3.3.1.min.js') }}"></script>
        <script src="{{ asset('js/dnsutility/jquery-validate/jquery.validate.min.js') }} "></script>
        <script src="{{ asset('js/dnsutility/jquery-validate/additional-methods.min.js') }} "></script>
        <script src="{{ asset('js/dnsutility/jquery-form/jquery.form.min.js') }} "></script>    
        <script src="{{ asset('js/dnsutility/bootstrap/bootstrap.min.js') }}" ></script>
        <script type="text/javascript">            
                                   
            //Google Map
            var markers = [];
            var uluru = {lat: 0, lng: 3};
            var map;
            var mapDiv = "typeMap";

            function initMap() {  
                map = new google.maps.Map( 
                    document.getElementById(mapDiv), 
                    {
                        zoom: 1, 
                        center: uluru, 
                        scrollwheel: false,
                        draggable: false,
                        disableDefaultUI: true
                    }
                );  
                                                                                
            }                 
            function deleteMarkers() {
                for (var i = 0; i < markers.length; i++) {                 
                  markers[i].setMap(null);
                }
                markers = [];
            }           

            $(document).ready(function () {                                                                                      
                //variable define
                var dnsRecord = [];
                var downloadDnsData = {};
                var typeRecordDomain,type, country, countryRecordDomain;
                var nameservers =  <?php echo json_encode(config('dnsutility.nameservers')); ?>;                       
                var totRecord = 0;
                var icon;
                var marker;
                var content;            
               
                $('#DNSTypeRecordBtn').attr("disabled", true);                                
                $('#DNSTypeRecordDownloadBtn').attr("disabled", true);
                $('#DNSCountryRecordDownloadBtn').attr("disabled", true);
                $('#downloadCountryRecords').hide();        

                //validation               
                $("#dnsTypeResolverForm").validate({
                    rules: {
                        typeRecordDomain: { 
                            required: true, 
                            verify_domain: true
                        },
                        type: {
                            required: true,                             
                        }                   
                    },
                    messages: {
                        typeRecordDomain: {
                            required: "",
                            verify_domain: ""
                        },
                        type: {
                            required: "",
                        } 
                    }
                });                

                //Domain name Validation
                $.validator.addMethod("verify_domain", function(value, element) {                                  
                    var Pattern = /^(?!:\/\/)(?=.{1,255}$)((.{1,63}\.){1,127}(?![0-9]*$)[a-z0-9-]+\.?)$/i;
                    if(value.match(Pattern)){
                        return true;    
                    }
                    return false;
                }, "Please enter valid Domain name");
 
                //get dns record
                $(document).on('click', '#DNSTypeRecordFetchBtn', function(e){  
                    //init value                  
                    // check validation 
                    if($('#dnsTypeResolverForm').valid()) {                       
                        //change init value
                        e.preventDefault();                                                                                        
                        deleteMarkers();                                                                                        
                        $(".dnsUtilityTypeResultData").html(" ");                                                                            
                        $('.dnsUtilityTypeResult').html('<b><i class="fa fa-circle-o"></i></b>');
                        $('#DNSTypeRecordDownloadBtn').attr("disabled", true);
                        $('#downloadCountryRecords').hide();
                        $('#DNSTypeRecordFetchBtn').attr("disabled", true);
                        $('.dnsUtilityTypeResult').html('<div class="spinner-border  spinner-border-sm " role="status"><span class="sr-only">Loading...</span></div>');                    
                        //get value
                        typeRecordDomain = $('#typeRecordDomain').val();                                         
                        type = $('#type').val();   
                        downloadDnsData = {};                        

                        $.each( nameservers, function( nameserver, record ) {      
                            totRecord++;
                            $.ajax({              
                                url : "{{ route('fetchDNSTypeRecords') }}",
                                type : "get",                                
                                data: {
                                   domain: typeRecordDomain,
                                   type: type,
                                   nameserver: nameserver,
                                },                          
                                success:function(data) { 
                                    totRecord--;                                    
                                    if( totRecord == 0 ) {
                                        $('#DNSTypeRecordFetchBtn').attr("disabled", false);                                                                 
                                        $('#DNSTypeRecordDownloadBtn').attr("disabled", false);  
                                        $('#DNSCountryRecordDownloadBtn').attr("disabled", false);
                                        $('#downloadCountryRecords').show();
                                    }
                                    
                                    if( data["status"] == '200' ) {                                   
                                        dnsRecord = data["responseText"];                                   
                                        if( $.isEmptyObject(dnsRecord) ) {
                                            $('.dnsUtilityTypeResult').html("<span class='text-danger'><i class='fa fw fa-times'></i></span>");
                                            $(".dnsUtilityTypeResultData").html("-");                                                                            
                                        }
                                        else {        
                                            $.each( dnsRecord, function( id, record ) {                                            
                                                downloadDnsData[id] = record["dnsRecord"];                                               
                                                if( record["dnsRecord"] ) {        
                                                    $("#"+id).find(".dnsUtilityTypeResultData").html(record["dnsRecord"]);                                                                            
                                                    $("#"+id).find(".dnsUtilityTypeResult").html("<i class='fa fw fa-check text-success'></i>");                                                                            
                                                    icon = "{{ asset('img/dnsutility/tick.png') }}";
                                                    content = "<i class='"+record["icon"]+"'></i> " + "<b>"+ record["nsName"] +"</b><hr class='dashed' /><spna class='text-success'>" + record["dnsRecord"] + "</span>"
                                                } 
                                                else {
                                                    $("#"+id).find(".dnsUtilityTypeResultData").html("-");                                                                            
                                                    $("#"+id).find(".dnsUtilityTypeResult").html("<span class='text-danger'><i class='fa fw fa-times'></i></span>");                                                                            
                                                    icon = "{{ asset('img/dnsutility/cross.png') }}";
                                                    content = "<i class='"+record["icon"]+"'></i> " + "<b>"+ record["nsName"] +"</b><hr class='dashed' /><spna class='text-danger'>Timeout</spna>"
                                                }    
                                                //Map marker
                                                var marker = new google.maps.Marker({
                                                    position: new google.maps.LatLng(record["nsLat"], record["nsLng"]),
                                                    map: map,
                                                    icon: icon,                                                    
                                                }); 
                                                var infowindow = new google.maps.InfoWindow({disableAutoPan : true, maxWidth: 200});

                                                google.maps.event.addListener(marker,'mouseover', (function(marker,content,infowindow){ 
                                                    return function() {
                                                       infowindow.setContent(content);
                                                       infowindow.open(map,marker);                                                    
                                                    };
                                                })(marker,content,infowindow)); 

                                                google.maps.event.addListener(map, 'mouseout', function() {
                                                    if (infowindow) {
                                                        infowindow.close();
                                                    }
                                                });
                                                  
                                                markers.push(marker);                                                                                          
                                            });                                        
                                        }
                                    } 
                                    else{
                                        $(".dnsUtilityTypeResultData").html("-");                                                                            
                                        $('.dnsUtilityTypeResult').html("<span class='text-danger'><i class='fa fw fa-times'></i></span>");
                                        console.log(data);
                                    }   
                                             
                                },               
                                error:function(data){ 
                                    totRecord--;
                                    if( totRecord == 0 ) {
                                        $('#DNSTypeRecordFetchBtn').attr("disabled", false); 
                                    }
                                    $(".dnsUtilityTypeResultData").html("-");                                                                            
                                    $('.dnsUtilityTypeResult').html('<b><i class="fa fa-circle-o"></i></b>');                                                    
                                    console.log(data);                               
                                }
                                
                            });                       
                        });
                    }
                });
              
                //downlaod dns record
                $(document).on('click', '#DNSTypeRecordDownloadBtn', function(){                                     
                   window.location.href = "{{ url('dnsutility/downloadDNSTypeRecords') }}?type="+type+"&domain="+typeRecordDomain+"&"+jQuery.param(downloadDnsData);                      
                });

                //downlaod dns country record
                $(document).on('click', '#DNSCountryRecordDownloadBtn', function(){                                     
                    window.location.href = "{{ url('dnsutility/downloadDNSCountryRecords') }}?domain="+typeRecordDomain;                      
                });

            });

        </script>
        <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAc2zQGF5TtPDPS_yzF4vTEfh6chCyUkS0&callback=initMap" type="text/javascript"></script>
       
    </body>
</html>
