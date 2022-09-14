<?php

namespace App\Http\Controllers;

use App\Exports\DNSRecordExport;
use App\Http\Requests\DNSTypeRecordRequest;
use Excel;
use Illuminate\Http\Request;
use Spatie\Dns\Dns;

class DNSUtilityController extends Controller
{
	public function index()
	{	
    	return view("dnsutility/dnsutility");
	}

	public function fetchDNSTypeRecords(DNSTypeRecordRequest $request)
	{
		try{			
			$nameserver = $request->nameserver;
			$nameserverDetails = config('dnsutility.nameservers.'.$request->nameserver);
			$responseData = [];
					
			try{
				// Get DNS record			
				$responseData[$nameserver]["nsName"] = $nameserverDetails["name"];
				$responseData[$nameserver]["nsLat"] = $nameserverDetails["lat"];
				$responseData[$nameserver]["nsLng"] = $nameserverDetails["lng"];
				$responseData[$nameserver]["icon"] = $nameserverDetails["icon"];
				
				$dns = new Dns($request->domain, $nameserverDetails["ip"]);	
				$responseData[$nameserver]["dnsRecord"] = $this->getDNSRecord($request->type, $dns);			
			}
			catch (\Exception $e) {
	            $responseData[$nameserver]["dnsRecord"] = "";		            
	        }			

			$data = array('status' => 200, 'responseText' => $responseData);        
			return response()->json($data); 
		}
        catch (\Exception $e) {
            $data = array('status' => 400, 'responseText' => $e->getMessage());
            return response()->json($data);
        }
	}

	public function downloadDNSTypeRecords(Request $request)
	{	
		$data = [];
		$type = $request->type;		
		$request->request->remove('type');
		$domain = $request->domain;
		$request->request->remove('domain');

		foreach ($request->all() as $dnsName => $dnsResult) {		
			$data[$dnsName][] = $nameservers = config('dnsutility.nameservers.'.$dnsName.'.name');
			$data[$dnsName][] = str_replace("<br />", "\r\n", $dnsResult);
		}					
		$export = new DNSRecordExport([
			$data
	   	]);

		return Excel::download($export, $domain.'-'.$type.'.csv', \Maatwebsite\Excel\Excel::CSV);			
	}

	public function downloadDNSCountryRecords(Request $request)
	{

		$responseData = [];
		$domain = $request->domain;
		$country ="";
		$nameservers = config('dnsutility.nameservers');		
		
		try{		
			foreach ( $nameservers as $nameserver => $nameserverDetails ) {				
				$country = $nameserver;
				$result = $this->dnsAllRecordDownload($domain, $nameserver, $nameserverDetails);				
				if( ! empty($result) ) {
					$responseData = $result;
					break;
				}
			}
		}
        catch (\Exception $e) {                     
        }
        finally { 
			$export = new DNSRecordExport([
				$responseData
	   		]);		

			return Excel::download($export, $domain.'-'.$country.'.csv', \Maatwebsite\Excel\Excel::CSV); 
		}

	}

	public function dnsAllRecordDownload($domain, $nameserver, $nameserverDetails)
	{
		try{
			$dns = new Dns($domain, $nameserverDetails["ip"]);	
			$Recordtypes = config('dnsutility.recordTypes');
 			foreach ($Recordtypes as $type) {
				
				$responseData[$type]["type"] = $type;
				$responseData[$type]["dnsRecord"] = str_replace("<br />", "\r\n", $this->getDNSRecord($type, $dns));
 			}
			return $responseData;
		}
		catch (\Exception $e) {
            return "";		            
        }
	}

	public function getDNSRecord($type, $dns)
	{		
		$responseText = "";
		$DnsRecord = trim($dns->getRecords($type));

		//if CNAME record exist return dns library different formate data - formate dns record
		if( $type != "CNAME" ) {
			if( !empty($dns->getRecords("CNAME"))) {
				$formattedDnsRecord = "";						
				$DnsRecord =  array_filter(explode("\n", $DnsRecord));
				foreach ($DnsRecord as $record) {
					if( strpos($record, "CNAME") === false ) {
						$formattedDnsRecord .= $record."\n";							
					}										
				}
				$DnsRecord = $formattedDnsRecord;
			}
		}

		if( !empty($DnsRecord) ) {	

			switch($type) {
				case "A":
				case "AAAA":												
				case "NS":
					$formattedDnsRecord = "";
					$DnsRecord = array_filter(explode("\n", $DnsRecord));	
					foreach ($DnsRecord as $record) {
						$record = explode(" ", ltrim(trim(str_replace("\n", " ", str_replace("\t", " ", str_replace("\t\t", " ", $record)))), ","));											
						$formattedDnsRecord.= $record[4]."<br />";
					}
					$responseText = rtrim($formattedDnsRecord, "<br />");
					break;										
				case "CNAME":		
					$DnsRecord = explode(" ", ltrim(trim(str_replace("\n", " ", str_replace("\t", " ", str_replace("\t\t", " ", $DnsRecord)))), ","));
					$responseText = $DnsRecord[4];
					break;					
				case "TXT":
				case "MX":
				case "CAA":
				case "SRV":
				case "DNSKEY":
					$formattedDnsRecord = "";
					$DnsRecord = array_filter(explode("\n", $DnsRecord));
					foreach ($DnsRecord as $record) {
						$formattedDnsRecord .= ltrim(rtrim(implode( " ", array_slice(explode(" ", str_replace("\t", " ", str_replace("\t\t", " ", $record))) , 4)), '"'), '"')."<br />";		
					}
					$responseText = rtrim($formattedDnsRecord, "<br />");					
					break;											
				case "SOA":	

					$DnsRecord = explode("\n", $DnsRecord);											
					$DomainDetails = explode(" ", str_replace("\t", " ", str_replace("\t\t", " ", $DnsRecord[0])));							
					$timeRecords = [];						
					for ($i=1; $i <=5 ; $i++) { 
						$timeRecordDetails = explode(";", $DnsRecord[$i]);
						$timeRecords[] = str_replace(" ", "", str_replace("\t", "", $timeRecordDetails[0]));								
					}							
					$responseText = $DomainDetails[4]." ".$DomainDetails[5]." ".$timeRecords[0]." ".$timeRecords[1]." ".$timeRecords[2]." ".$timeRecords[3]." ".$timeRecords[4];							
					break;	

			}	
		}

		return $responseText;		
	}
}
