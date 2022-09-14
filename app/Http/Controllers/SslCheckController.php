<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\SslCertificate\SslCertificate;
use App\Models\DomainSslDetails;
use App\Models\SubscriberSslExpire;

class SslCheckController extends Controller
{
    public function index()
    {
        return view('sslchecker.sslcheck');
    }

    public function getSSLDetails(Request $request)
    {
        try {
            $serverHostName = rtrim($request->serverhost, "/");
            $veiwData = [];

            if ($serverHostName) {
                $certificate = SslCertificate::createForHostName($serverHostName);

                $certificateInput = collect([
                    'server_ip_address' => gethostbyname($serverHostName),
                    'web_site' => $serverHostName,
                    "issuer" => $certificate->getIssuer(),
                    "domain_name" => $certificate->getDomain(),
                    'signature_algorithm' => $certificate->getSignatureAlgorithm(),
                    'additional_domains' => $certificate->getAdditionalDomains(),
                    'fingerprint' => $certificate->getFingerprint(),
                    'fingerprint_sha256' => $certificate->getFingerprintSha256(),
                    'valid_from_date' => $certificate->validFromDate(),
                    'expiration_date' => $certificate->expirationDate(),
                    'is_valid' => $certificate->isValid(),
                    'is_expired' => $certificate->isExpired(),
                ]);


                $createdCertificate = DomainSslDetails::updateOrCreate(
                    $certificateInput->only('web_site', 'domain_name', 'server_ip_address')->toArray(),
                    $certificateInput->toArray()
                );

                $veiwData['certificate'] = $createdCertificate;
                $veiwData['additionalData'] = $certificate->getRawCertificateFields();
                unset($veiwData['additionalData']['extensions']);
                // return response()->json($veiwData, 200, array('Content-Type' => 'application/json;charset=utf8'), JSON_UNESCAPED_UNICODE);
                // return view('sslchecker.ssldetails', $veiwData);

                $view = view("sslchecker.ssldetails",$veiwData)->render();
                return response()->json(['html'=>$view]);
                // return response()->json($veiwData, 200);
            }
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 400);
        }
    }

    public function updateMailForGetReminder(Request $request)
    {
        $rules = [
            'email' => 'required|email',
            'ssl_id' => 'required',
        ];
        $message = [
            'email.required' => 'Please enter email address',
            'email.email' => 'Please enter valid email address.',
            'ssl_id' => 'Some data is missing, Please try again.',
        ];

        $request->validate($rules, $message);

        try {
            $inputData = [
                'email' => $request->email,
                'ssl_id' => $request->ssl_id
            ];

            $subscribe = SubscriberSslExpire::firstOrCreate($inputData, $inputData);

            $result['code'] = 200;
            $result['message'] = "You already subscribed for this host name";
            $result['wasRecentlyCreated'] = $subscribe->wasRecentlyCreated;
            if ($subscribe->wasRecentlyCreated) {
                $result['message'] = "Subscribed successfully";
            }

            $result['data'] = $subscribe;
        } catch (\Throwable $th) {
            $result['code'] = 400;
            $result['message'] = "Something went wrong. Please try again.";
        }
        return response()->json($result, $result['code']);
    }
}
