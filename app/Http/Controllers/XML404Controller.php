<?php

namespace App\Http\Controllers;

use App\Http\Requests\SiteMapXmlParserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use vipnytt\SitemapParser;
use vipnytt\SitemapParser\Exceptions\SitemapParserException;

class XML404Controller extends Controller
{
	public function index()
	{
    	return view("xml404/xml404");
	}

	public function validateSiteMapXml(SiteMapXmlParserRequest $request)
	{
		try {
		    $rules = ['url' => 'active_url'];
		    $parser = new SitemapParser();
		    $parser->parse( $request->domain);	
		    $siteMapXml = [];
		    $activeUrls = [];
		    $brokenUrls = [];
		    
		    if( ! empty($parser->getURLs()) ) {
			    foreach ($parser->getURLs() as $url => $tags) {			   			    				    				    		    		
		    		$input = ['url' => $url];
		   			if(Validator::make($input, $rules)->passes()) {
				        $activeUrls[] = $url;
		   			}				    
				    else {
				    	$brokenUrls[] = $url;
				    }
			    }
		    }		    	
		   	
		   	if( !empty($parser->getSitemaps()) ) {
		   		foreach ($parser->getSitemaps() as $url => $tags) {		   		    
		   		    $siteMapXml[] = $url;		   		     
		   		}
		   	}

		   	$urls["siteMapXml"] = $siteMapXml;		   	
		   	$urls["activeUrls"] = $activeUrls;
		    $urls["brokenUrls"] = $brokenUrls;
		    $data = array('status' => 200, 'responseText' => $urls);
            return response()->json($data);		
		} 
		catch (SitemapParserException $e) {
		    $data = array('status' => 400, 'responseText' => $e->getMessage());
            return response()->json($data);
		}
	}

}
