<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;
use App\Mail\LangTrasnlateMail;
use File;
use App\Models\TrnslKey;
use App\Models\TrnslRequests;
use Carbon\Carbon;
use GuzzleHttp\Psr7\Request;
use Illuminate\Cache\RetrievesMultipleKeys;
use Illuminate\Support\Facades\Log;
// use Jose\Factory\JWKFactory;
use function GuzzleHttp\json_decode;

class LangTrasnlate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $toEmail; // to expose in jobs de-serialize made this public
    protected $filePath;
    protected $fromLang;
    protected $toLang;
    protected $startTime;
    protected $endTime;
    protected $trnslRequests;
    protected $switch = false;
    protected $adminEmail = "niravjadatiya@gmail.com";
    protected $errorMsg = "Oops!! we are facing some issues, please try again in few days, Sorry for the inconvenience.";
    protected $translateFunToUse;  // yandex, ibm, google

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($email, $filePath, $fromLang, $toLang, $translateFunToUse)
    {
        $this->toEmail = $email;
        $this->filePath = $filePath;
        $this->fromLang = $fromLang;
        $this->toLang = $toLang;
        $this->translateFunToUse = $translateFunToUse;

        //Create Translation  Request (Admin Purpose)
        $this->trnslRequests = $this->createTrnslRequests($email, $filePath, $fromLang, $toLang);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->startTime = Carbon::now();
        $this->trnslRequests->status = 'inprocess';
        $this->trnslRequests->save();

        $contents = File::get($this->filePath);
        $arrJson = json_decode($contents, true);

        $count = $this->getCountOfActiveApiKey();
        // if count is zero means all key is disabled then no mail to admin will be send
        // only user will receive $errorMsg;
        if ($count == 0) {
            Log::info('$count == 0 => ' . $count);
            $this->trnslRequests->status = 'failed';
            $this->trnslRequests->save();
           Mail::to($this->toEmail)->send(new LangTrasnlateMail("", $this->errorMsg));
           return false;
        }

        // start Recursive Function
        $arrJson = $this->recursiveTranslate($arrJson);
        $fileFullName = '';
        $pathLangTranslatedFile = '';
        $errorTxt = $this->errorMsg;

        if($arrJson != null && $arrJson != "invalidTranslation"){
            $fileFullName = basename($this->filePath);
            $errorTxt = '';
            $pathLangTranslatedFile = storage_path() . '/app/public/filelangjson/' . $this->toLang . '_' . $fileFullName;
            File::put($pathLangTranslatedFile, json_encode($arrJson, JSON_UNESCAPED_UNICODE));
            // $attachmentFileFullPath = url('').'/storage/filelangjson/translated_'.$fileFullName;
            $pathLangTranslatedFile = $this->trnslRequests->file_url . $this->trnslRequests->to_file;
            // \Log::info('path of first time => ' . $pathLangTranslatedFile);
        }

        $mailSend = null;
        try {
            Log::info('try {' . $errorTxt);
            $mailSend = Mail::to($this->toEmail)->send(new LangTrasnlateMail($pathLangTranslatedFile,$errorTxt));
            // \Log::info('$mailSend TRY => '. $mailSend);
            if ($mailSend !== 0 && $arrJson != "invalidTranslation") {
              $this->trnslRequests->status = 'processed';
            } else {
                $this->trnslRequests->status = 'failed';
            }
        } catch (\Exception $ex) {
            // \Log::info('$mailSend Catch => '. $mailSend);
            $this->trnslRequests->status = 'mailfailed';
            // \Log::error('$mailSend Catch Start Throwable $ex=> ');
            // \Log::error($ex->getMessage());
            // \Log::error('$mailSend Catch End Throwable $ex=> ');
        }

        $this->endTime = Carbon::now();
        $this->trnslRequests->time_to_process = $this->startTime->diff($this->endTime)->format('%H:%I:%S');
        $this->trnslRequests->save();
    }

    public function recursiveTranslate($arr)
    {
        foreach ($arr as $key => $value) {
            if (!$value) {
                continue;
            }

            if (gettype($value) == 'array') {
                $isRecursiveValid = $this->recursiveTranslate($value);
                if($isRecursiveValid == "invalidTranslation"){
                    return "invalidTranslation";
                }else{
                    $arr[$key] = $isRecursiveValid;
                }
            } else {
                // if (empty($value)) {
                //     $arr[$key] = $value;
                //     // Log::info('==========> $key START');
                //     // Log::info($key);
                //     // Log::info('==========> $key END');
                // } else {
                    // Log::info('==========> $key START');
                    // Log::info($key);
                    // Log::info('==========> $key END');
                $v = $this->translate($value);
                if($v == false || $v == "invalidTranslation"){
                    $arr = $v;
                    return "invalidTranslation";
                }
                $arr[$key] = $v;
                // }
            }

            if (!$arr[$key]) { // This will return incase of translate service if off
                return;
            }
        }
        return $arr;
    }

    public function translate($text){
        // Log::info('translate()'. $text . '-----'. $this->translateFunToUse);
        $from = $this->fromLang;
        $to = $this->toLang;
        if (gettype($text) == "string") {
            switch ($this->translateFunToUse) {
                case "google":
                    $translatedText = $this->googleTranslate($text, $from, $to);
                    break;
                case "yandex":
                    $translatedText = $this->yandexTranslate($text, $from, $to);
                    break;
                case "ibm":
                    $translatedText = $this->ibmTranslate($text, $from, $to);
                    break;
            }
            return $translatedText;
        } else {
            return $text;
        }

    }

    public function getCountOfActiveApiKey(){
        $count = TrnslKey::where(['api_key_type' => $this->translateFunToUse, 'status' => true])->count();
        return $count;
    }

    public function getAndIncreseApiCount(){

        $api = TrnslKey::orderBy('count')->where(['api_key_type' => $this->translateFunToUse, 'status' => true])->first();
        if(!$api){
            return false;
        }
        $api->count = $api->count + 1;
        $api->save();
        return $api;
    }

    public function ibmTranslate($text, $from, $to){
        $api = $this->getAndIncreseApiCount();
        if ($api) {
            $url_key = $api->transIbmUrlKey['url_key'];
            $authentication = $api['key'];
        }

        $url = "https://api.eu-gb.language-translator.watson.cloud.ibm.com/instances/". $url_key."/v3/translate?version=2018-05-01";
        $jsondata = json_encode(['text' => $text, 'model_id' => $from.'-'.$to]);
        $fields = array(
            'data' => $jsondata,
            'auth' => $authentication,
        );

        $result = $this->getSSLPage($url,'',$fields,'true');
        $translatedArray = json_decode($result->getData(), true);
        $isResponseValid = $this->checkResponse($translatedArray, $text, $api);
        $translated = "";

        if($isResponseValid) {
            foreach ($translatedArray["translations"] as $s) {
                $trans= $s["translation"];
                $translated .= isset($trans) && strlen($trans) ? $trans : $text;
            }
            return $translated;
        }
        return "invalidTranslation";
    }

    public function yandexTranslate($text, $from, $to)
    {
        $api = $this->getAndIncreseApiCount();
        $url = 'https://translate.yandex.net/api/v1.5/tr.json/translate?key=' . $api->key . '&lang=' . $from . '-' . $to . '&text=' . urlencode($text);

        if (gettype($text) == "string") {
            $translatedData = $this->getSSLPage($url);
        } else {
            return $text;
        }

        $translatedData = json_decode($translatedData->getData(),true);
        $isResponseValid = $this->checkResponse($translatedData, $text, $api);
        if ($isResponseValid) {
            if (empty($translatedData->text[0])) {
                // Log::info('if (empty($translatedData->text[0])) GONE');
                return $text;
            }
            $translatedData = $translatedData->text[0];
            return $translatedData;
        }
        return "";
    }

    public function googleTranslate($text, $from, $to)
    {
        $url = "https://translate.google.com/translate_a/single?client=at&dt=t&dt=ld&dt=qca&dt=rm&dt=bd&dj=1&hl=es-ES&ie=UTF-8&oe=UTF-8&inputm=2&otf=2&iid=1dd3b944-fa62-4b55-b330-74909a99969e";

        $fields = array(
            'sl' => urlencode($from),
            'tl' => urlencode($to),
            'q' => urlencode($text)
        );

        if(strlen($fields['q'])>=5000){
            // throw new \Exception("Maximum number of characters exceeded: 5000");
            // Mail::to("chintan.adatiya@gmail.com")
            Mail::to($this->adminEmail)
            ->send(new LangTrasnlateMail($this->filePath, "Maximum number of characters exceeded: 5000"));
            return $text;
        }

        // URL-ify the data for the POST
        $fields_string = "";
        foreach ($fields as $key => $value) {
            $fields_string .= $key . '=' . $value . '&';
        }
        rtrim($fields_string, '&');

        $result = $this->getSSLPage($url, $fields_string, $fields);

        $translatedArray = json_decode($result->getData(), true);
        $translated = "";

        if(!$translatedArray)
        {
            $message = 'No msg from API';
            if (isset($result->message)) {
                $message = $result->message;
            }
            $extraTextForAdmin = "Reason to fail <br>"
                . "text: $text <br>"
                . "reason: $message";
            // Log::info('extraTextForAdmin' . $extraTextForAdmin);
            // Mail::to("chintan.adatiya@gmail.com")
            Mail::to($this->adminEmail)
                ->send(new LangTrasnlateMail($this->filePath, $extraTextForAdmin));

            return "";
        }
        foreach ($translatedArray["sentences"] as $s) {
            $translated .= isset($s["trans"]) ? $s["trans"] : '';
        }
        return $translated;
    }

    public function checkResponse($data= [], $text= '', $api){
        // TODO:  what if service is under maintance or something else
        $code = '';
        Log::info('$data => ');
        Log::info($data);
        Log::info('$data <= ');
        $message = 'Error from API';
        switch ($this->translateFunToUse) {
            case "yandex":
                $code = $data['code'];
                $message = $data['message'];
                if ($code != 200) {
                    $this->sendErrorMail($api, $text, $message);
                    return false;
                }
                break;
            case "ibm":
                if (isset($data['code'])) {
                    $isDisableApiKey = false;
                    if ($data['code'] === 400) {
                        $this->errorMsg = "Sorry,Invalid Request.";
                    } else if ($data['code'] === 404) {
                        $this->errorMsg = "Sorry, we are not supporting this Language Pair Yet!";
                    } else if ($data['code'] === 413) {
                        $this->errorMsg = "Sorry , Translation string is too large";
                    } else if ($data['code'] === 429) {
                        $this->errorMsg = "We are facing too many requests at a time, please try after few hours.";
                    } else {
                        $isDisableApiKey = true;
                    }
                    $code = $data['code'];
                    $message = $data['error'];
                    $this->sendErrorMail($api, $text, $message, $isDisableApiKey);
                    return false;
                }
                break;
        }
        return true;
    }

    public function sendErrorMail($api, $text, $message, $isDisableApiKey = false){
        $translationRequestId = $this->trnslRequests->id;
        $extraTextForAdmin = $message;
        $api->status = !$isDisableApiKey;
        $api->save();
        $extraTextForAdmin = "File Translation failed, here are the details: <br>" .
                             "Conversion Text: \"$text\" <br>" .
                             "API Response: \"$message\" <br>".
                             "Translation Request Id: $translationRequestId <br>".
                             "API Key Id:". $api->id . "<br>".
                             ($api->key) . "<br>".
                             ($isDisableApiKey ? "<p>You need to re-activate api key manually.</p>" : "<p>Key Not Deactivated</p>");
        // Log::info('Api key =>' . $api . ' Deactivate.');
        // Mail::to("chintan.adatiya@gmail.com")
        Mail::to($this->adminEmail)
            ->send(new LangTrasnlateMail('', $extraTextForAdmin, 'Admin'));
    }

    public function getSSLPage($url, $fields_string = "", $fields = [], $isIBMTranslate = "")
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        if($isIBMTranslate != ''){
            $authentication = base64_encode("apikey:".$fields['auth']);
            $options = array(CURLOPT_HTTPHEADER => array(
                    "Authorization: Basic $authentication",
                    "Content-Type: application/json"
            ));
            curl_setopt_array($ch, $options);
        }

        if( $isIBMTranslate == ''){
            curl_setopt($ch, CURLOPT_POST, count($fields));
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            // curl_setopt($ch, CURLOPT_USERAGENT, 'AndroidTranslate/5.3.0.RC02.130475354-53000263 5.1 phone TRANSLATE_OPM5_TEST_1');
        }else{
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields['data']);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }
        $output = curl_exec($ch);
        // $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        // return response()->json(['response' => 'success', 'comments' => $comments]);
        // return response()->json(['status' => $statusCode, 'output' => $output]);
        return response()->json($output);
    }

    /**
     *
     * For Create Translation  Request (Admin Purpose)
     *
     * @param  $email, $filePath, $fromLang, $toLang
     * @return TrnslRequests $trnslRequests
     */
    public function createTrnslRequests($email, $filePath, $fromLang, $toLang)
    {
        $fileFullName = basename($filePath);
        $trnslRequests = TrnslRequests::updateOrCreate([
            'email' => $email,
            'from_file' => $fileFullName,
            'to_file' => $toLang . '_' . $fileFullName,
            'from_lng' => $fromLang,
            'to_lng' => $toLang,
        ]);
        return $trnslRequests;
    }
}
