<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use File;
use Storage;

use Jose\Factory\JWKFactory;
use Jose\Factory\JWSFactory;
use Log;
use App\Models\PushTryRequest;

class PushTryController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pushtry.pushtry');
    }

    public function apnsFileUpload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fileapns' => 'required|file|max:10',
        ],['fileapns.file' => 'Please select file']);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()->all()], 400);
        }
        $fileapns = $request->file('fileapns');

        // $extension = $fileapns->clientExtension();

        $fullName = $fileapns->getClientOriginalName();
        $onlyName = explode('.', $fullName)[0];
        $extension = explode('.', $fullName)[1];

        if($extension != "p8" && $extension != "pem" && $extension != "p12") {
            return response()->json(['error'=>["Please select p12/pem/p8 files only"]], 400);
        }
        $pemFileNameWithPath = "";
        $pemFileName = "";
        // echo "fullName: ".$fullName. "  extension: ".$extension;
        // exit();

        $isp8 = false;
        if ($extension == "p12") {
            $pemFileName = $onlyName.".pem";
            $pemFileNameWithPath = 'fileapns/'.$pemFileName;

            $password = '';
            $results = array();
            $contents = File::get($fileapns->getRealPath());

            $worked = openssl_pkcs12_read($contents, $results, $password);

            if (empty($results)) {
              return response()->json(['error'=>["Please provide valid file"]], 400);
            }

            $pemContent = $results["cert"].$results["pkey"];

            Storage::put('fileapns/'.$pemFileName, $pemContent);

            // $fileapns->storeAs('fileapns', $onlyName.".pem");
        } else if ($extension == "pem") {
            $pemFileName = $fullName;
            $pemFileNameWithPath = 'fileapns/'.$pemFileName;

            $fileapns->storeAs('fileapns', $pemFileName);

        } else if ($extension == "p8") {
            $pemFileName = $fullName;
            $pemFileNameWithPath = 'fileapns/'.$pemFileName;

            $fileapns->storeAs('fileapns', $pemFileName);

            $isp8 = true;
        }

         // echo "extension: ".$extension;

        session(['isp8' => $isp8]);
        session(['pemfilelink' => ($pemFileNameWithPath)]);
        session(['pemfilename' => ($pemFileName)]);

        return response()->json([
            'pemfilelink' => url('/storage/'.$pemFileNameWithPath),
            'pemfilename' => $pemFileName,
            'pemfilelinkpathonly' => $pemFileNameWithPath,
            'isp8' => $isp8
        ], 200);
    }

    public function getPemFileFromSession()
    {
        if (!session('pemfilelink')) {
            return response()->json(['error'=>'no p12 or pem file selected'], 400);
        }

        return response()->json([
            'pemfilelink' => url('/storage/'.session('pemfilelink')),
            'pemfilelinkpathonly' => session('pemfilelink'),
            'pemfilename' => session('pemfilename'),
            'devicetokens' => session('devicetokens'),
            'message' => session('message'),
            'isproduction' => session('isproduction'),
            'isp8' => session('isp8'),
            'keyid' => session('keyid'),
            'teamid' => session('teamid'),
            'appid' => session('appid')
        ], 200);
    }

    public function sendApnsPush(Request $request)
    {
        Log::info('==========> sendApnsPush <==========');
        Log::info('$request');
        Log::info($request);

        $keyid = "";
        $teamid = "";
        $appid = "";

        $pemfilelink = $request->get("hiddenpemfilelink");
        $isp8 = $request->get("hiddenisp8");
        if ($isp8 == "true") {
            $validator = Validator::make($request->all(), [
                'devicetokens' => 'required',
                'message' => 'required',
                'keyid' => 'required',
                'teamid' => 'required',
                'appid' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json(['error'=>$validator->errors()->all()], 400);
            }

            $keyid = $request->get("keyid");
            $teamid = $request->get("teamid");
            $appid = $request->get("appid");

            session(['keyid' => ($keyid)]);
            session(['teamid' => ($teamid)]);
            session(['appid' => ($appid)]);

        } else {
            $validator = Validator::make($request->all(), [
                'devicetokens' => 'required',
                'message' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json(['error'=>$validator->errors()->all()], 400);
            }
        }

        $deviceTokens = $request->get("devicetokens");
        $message = $request->get("message");
        $isproduction = $request->get("isproduction");

        session(['devicetokens' => ($deviceTokens)]);
        session(['message' => ($message)]);
        session(['isproduction' => ($isproduction)]);

        $arrDeviceToken = array_map('trim', explode(',', $deviceTokens));

        $body['aps'] = array(
            'alert' => $message,
            'sound' => 'default',
        );

        $messagePush = json_encode($body);
        // echo $payload;

        // open connection
        $http2ch = curl_init();
        curl_setopt($http2ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_2_0);

        // send push
        $apple_cert = storage_path("app/public/".$pemfilelink);
        Log::info('$apple_cert : ' . $apple_cert);

        $http2_server = 'https://api.development.push.apple.com';
        if($isproduction) {
            $http2_server = 'https://api.push.apple.com';
        }
        // $app_bundle_id = 'it.tabasoft.samplepush';
        // $app_bundle_id = 'com.logistic.pushtest';

        //------- If P8 Found -----------
        $jws = "";
        if ($isp8 == "true") {
            session(['keyid' => ($keyid)]);
            session(['teamid' => ($teamid)]);
            $key_file = $apple_cert; //'/Users/chintan/Desktop/AuthKey_Q8G44BM3V5.p8';
            $secret = null; // If the key is encrypted, the secret must be set in this variable
            $private_key = JWKFactory::createFromKeyFile($key_file, $secret, [
                'kid' => $keyid,  // Q8G44BM3V5 // The Key ID obtained from your developer account
                'alg' => 'ES256',      // Not mandatory but recommended
                'use' => 'sig',        // Not mandatory but recommended
            ]);
            $payload = [
                'iss' => $teamid, // JUGDE9K6Y2
                'iat' => time(),
            ];
            $header = [
                'alg' => 'ES256',
                'kid' => $private_key->get('kid'),
            ];
            $jws = JWSFactory::createJWSToCompactJSON(
                $payload,
                $private_key,
                $header
            );
        }

        $arrResponses = [];
        for ($i=0; $i < count($arrDeviceToken); $i++) {
            $token = $arrDeviceToken[$i];

            $tokenResult = $this->sendHTTP2PushPEM($http2ch, $http2_server, $apple_cert, $appid, $messagePush, $token, $jws, $pemfilelink);
            Log::notice("sendApnsPush");
            Log::notice($tokenResult);
            if($tokenResult["status"] != 200){
              return response()->json($tokenResult, $tokenResult["status"]);
            }
            $arrResponses[] = $tokenResult;
        }

        curl_close($http2ch);
        return response()->json($arrResponses, 200);
    }

    /**
     * @param $http2ch          the curl connection
     * @param $http2_server     the Apple server url
     * @param $apple_cert       the path to the certificate
     * @param $app_bundle_id    the app bundle id
     * @param $message          the payload to send (JSON)
     * @param $token            the token of the device
     * @return mixed            the status code
     */
    function sendHTTP2PushPEM($http2ch, $http2_server, $apple_cert, $app_bundle_id, $message, $token, $jws, $pemfilelink) {

        // url (endpoint)
        $url = "{$http2_server}/3/device/{$token}";

        // certificate
        // $cert = realpath($apple_cert);
        $cert = $apple_cert;
        // echo "<br/>cert : ".$cert."<br/>";

        // headers
        $headers = array(
            //"User-Agent: My Sender",
            "apns-expiration: 0",
            "apns-priority: 10"
        );
        if($jws) {
            $headers[] = 'Authorization: bearer ' . $jws;
            $headers[] = "apns-topic: $app_bundle_id";
        }

        // other curl options
        curl_setopt_array($http2ch, array(
            CURLOPT_URL => $url,
            CURLOPT_PORT => 443,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_POST => TRUE,
            CURLOPT_POSTFIELDS => $message,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => false,
            // CURLOPT_SSLCERT => $cert,
            CURLOPT_HEADER => 1
        ));
        if (!$jws) {
            curl_setopt($http2ch, CURLOPT_SSLCERT, $cert);
        }

        // go...
        $result = curl_exec($http2ch);
        // dd($result);
        // echo "<pre>";print_r($result);
        Log::notice("<Result>");
        Log::notice($result);
        Log::notice("</Result>");

        if ($result === FALSE) {
            $status = curl_getinfo($http2ch, CURLINFO_HTTP_CODE);
            $error = curl_error($http2ch);
            if (strpos($error, 'expired') !== false) {
               $error = "Your certificate expired";
            }
            if (strpos($error, 'revoked') !== false) {
               $error = "Your certificate revoked";
            }
            if (empty($error)) {
               $error = "Oops! Something went wrong";
            }
            Log::notice($status);
            Log::notice($error);
            $response = [];
            $response["status"] = 401;
            $response["reason"] = $error;
            $response["token"] = $token;
            return $response;
        }

        // get response
        $error = curl_error($http2ch);
        if($error){
          Log::error($error);
        }
        $status = curl_getinfo($http2ch, CURLINFO_HTTP_CODE);
        Log::notice($status);
        // dd($status);

        if($status == 200 ){
          $msg = json_decode($message);
          PushTryRequest::create([
            'devicetokens' => $token,
            'message' => isset($msg->aps->alert) ? $msg->aps->alert : '',
            'keyid' => $jws ? session('keyid') : null,
            'teamid' => $jws ? session('teamid') : null,
            'appid' => $app_bundle_id,
            'isproduction' => session('isproduction') ? 'yes' : 'no',
            'file_cert' => $pemfilelink,
          ]);
        }

        list($header, $body) = explode("\r\n\r\n", $result, 2);

        $body = json_decode($body, true);

        $response = [];
        $response["status"] = $status;
        $response["reason"] = $body["reason"] ? $body["reason"] : "";
        $response["token"] = $token;
        // dd($body);
        return $response;
    }

    public function testApi()
    {
        return response()->json(["Hello", "Hi"], 200);
    }

    public function testPostJson(Request $request)
    {
        $fname = $request->input('fname');
        $lname = $request->input('lname');

        $fullName = ucwords($fname. ' '.$lname);

        return response()->json(['fullname' => $fullName], 200);
    }
}
