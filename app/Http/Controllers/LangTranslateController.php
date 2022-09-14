<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use File;
use Storage;
use App\Jobs\LangTrasnlate;
use Log;
use Jose\Factory\JWKFactory;
use Jose\Factory\JWSFactory;

use App\Models\TrnslKey;
use App\Models\Jobs;
use App\User;
use Mail;
use App\Mail\SendOtp;

class LangTranslateController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public $translateFunToUse = "ibm";  // yandex, ibm, google

    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
 * Show the application <dashboard class="">                                                                                                                                                </dashboard> class=""></dashboard> class=""></dashboard> class=""></dashboard> class=""></dashboard> class=""></dashboard>
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $count = TrnslKey::where(['api_key_type' => $this->translateFunToUse, 'status' => true])->count();
        return view('langtranslate.langtranslate', ['count' => $count]);
    }

    public function getLanguages()
    {
        switch($this->translateFunToUse) {
            case "yandex":
                $languages = json_decode('{"af":"Afrikaans","am":"Amharic","ar":"Arabic","az":"Azerbaijani","ba":"Bashkir","be":"Belarusian","bg":"Bulgarian","bn":"Bengali","bs":"Bosnian","ca":"Catalan","ceb":"Cebuano","cs":"Czech","cy":"Welsh","da":"Danish","de":"German","el":"Greek","en":"English","eo":"Esperanto","es":"Spanish","et":"Estonian","eu":"Basque","fa":"Persian","fi":"Finnish","fr":"French","ga":"Irish","gd":"Scottish Gaelic","gl":"Galician","gu":"Gujarati","he":"Hebrew","hi":"Hindi","hr":"Croatian","ht":"Haitian","hu":"Hungarian","hy":"Armenian","id":"Indonesian","is":"Icelandic","it":"Italian","ja":"Japanese","jv":"Javanese","ka":"Georgian","kk":"Kazakh","km":"Khmer","kn":"Kannada","ko":"Korean","ky":"Kyrgyz","la":"Latin","lb":"Luxembourgish","lo":"Lao","lt":"Lithuanian","lv":"Latvian","mg":"Malagasy","mhr":"Mari","mi":"Maori","mk":"Macedonian","ml":"Malayalam","mn":"Mongolian","mr":"Marathi","mrj":"Hill Mari","ms":"Malay","mt":"Maltese","my":"Burmese","ne":"Nepali","nl":"Dutch","no":"Norwegian","pa":"Punjabi","pap":"Papiamento","pl":"Polish","pt":"Portuguese","ro":"Romanian","ru":"Russian","si":"Sinhalese","sk":"Slovak","sl":"Slovenian","sq":"Albanian","sr":"Serbian","su":"Sundanese","sv":"Swedish","sw":"Swahili","ta":"Tamil","te":"Telugu","tg":"Tajik","th":"Thai","tl":"Tagalog","tr":"Turkish","tt":"Tatar","udm":"Udmurt","uk":"Ukrainian","ur":"Urdu","uz":"Uzbek","vi":"Vietnamese","xh":"Xhosa","yi":"Yiddish","zh":"Chinese"}');
                break;
            case "ibm":
                $languages = json_decode('{"af":"Afrikaans","ar":"Arabic","az":"Azerbaijani","ba":"Bashkir","be":"Belarusian","bg":"Bulgarian","bn":"Bengali","bs":"Bosnian","ca":"Catalan","cs":"Czech","cv":"Chuvash","cy":"Welsh","da":"Danish","de":"German","el":"Greek","en":"English","eo":"Esperanto","es":"Spanish","et":"Estonian","eu":"Basque","fa":"Persian","fi":"Finnish","fr":"French","fr-CA":"French (Canada)","ga":"Irish","gu":"Gujarati","he":"Hebrew","hi":"Hindi","hr":"Croatian","ht":"Haitian","hu":"Hungarian","hy":"Armenian","id":"Indonesian","is":"Icelandic","it":"Italian","ja":"Japanese","ka":"Georgian","kk":"Kazakh","km":"Central Khmer","ko":"Korean","ku":"Kurdish","ky":"Kirghiz","lo":"Lao","lt":"Lithuanian","lv":"Latvian","ml":"Malayalam","mn":"Mongolian","mr":"Marathi","ms":"Malay","mt":"Maltese","my":"Burmese","nb":"Norwegian Bokmal","ne":"Nepali","nl":"Dutch","nn":"Norwegian Nynorsk","pa":"Punjabi","pa-PK":"Punjabi (Shahmukhi script, Pakistan)","pl":"Polish","ps":"Pushto","pt":"Portuguese","ro":"Romanian","ru":"Russian","si":"Sinhala","sk":"Slovakian","sl":"Slovenian","so":"Somali","sq":"Albanian","sr":"Serbian","sv":"Swedish","ta":"Tamil","te":"Telugu","th":"Thai","tl":"Tagalog","tr":"Turkish","uk":"Ukrainian","ur":"Urdu","vi":"Vietnamese","zh":"Simplified Chinese","zh-TW":"Traditional Chinese"}');
                break;
            default:
                $languages = json_decode('{"af":"Afrikaans","ar":"Arabic","az":"Azerbaijani","ba":"Bashkir","be":"Belarusian","bg":"Bulgarian","bn":"Bengali","bs":"Bosnian","ca":"Catalan","cs":"Czech","cv":"Chuvash","cy":"Welsh","da":"Danish","de":"German","el":"Greek","en":"English","eo":"Esperanto","es":"Spanish","et":"Estonian","eu":"Basque","fa":"Persian","fi":"Finnish","fr":"French","fr-CA":"French (Canada)","ga":"Irish","gu":"Gujarati","he":"Hebrew","hi":"Hindi","hr":"Croatian","ht":"Haitian","hu":"Hungarian","hy":"Armenian","id":"Indonesian","is":"Icelandic","it":"Italian","ja":"Japanese","ka":"Georgian","kk":"Kazakh","km":"Central Khmer","ko":"Korean","ku":"Kurdish","ky":"Kirghiz","lo":"Lao","lt":"Lithuanian","lv":"Latvian","ml":"Malayalam","mn":"Mongolian","mr":"Marathi","ms":"Malay","mt":"Maltese","my":"Burmese","nb":"Norwegian Bokmal","ne":"Nepali","nl":"Dutch","nn":"Norwegian Nynorsk","pa":"Punjabi","pa-PK":"Punjabi (Shahmukhi script, Pakistan)","pl":"Polish","ps":"Pushto","pt":"Portuguese","ro":"Romanian","ru":"Russian","si":"Sinhala","sk":"Slovakian","sl":"Slovenian","so":"Somali","sq":"Albanian","sr":"Serbian","sv":"Swedish","ta":"Tamil","te":"Telugu","th":"Thai","tl":"Tagalog","tr":"Turkish","uk":"Ukrainian","ur":"Urdu","vi":"Vietnamese","zh":"Simplified Chinese","zh-TW":"Traditional Chinese"}');
        }
        return response()->json([
            'langfilelink' => url('/storage/'.session('langfilelink')),
            'langfilename' => session('langfilename'),
            'languages' => $languages
        ], 200);
    }

    public function getSSLPage($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);

        // $command = "curl " . $url;
        // $output = shell_exec($command." 2>&1");
        return $output;
    }

    public function langFileUpload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'filelangjson' => 'required|file|max:100|mimetypes:application/json,text/plain',
        ], ['filelangjson.file' => 'Please select file']);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 400);
        }

        $filelangjson = $request->file('filelangjson');

        $fullName = $filelangjson->getClientOriginalName();
        $onlyName = explode('.', $fullName)[0];
        $extension = explode('.', $fullName)[1];

        if ($extension != "json") {
            return response()->json(['error' => ["Please select json file"]], 400);
        }

        $contents = File::get($filelangjson->getRealPath());

        // Check UTF BOM
        define ('UTF8_BOM' , chr(0xEF) . chr(0xBB) . chr(0xBF));
        $utfCheckString = substr($contents, 0, 3);
        if ($utfCheckString  == UTF8_BOM) {
          return response()->json(['error' => ["Not valid UTF-8 file, Detected UTF-8 BOM"]], 400);
        }
        //
        if ((!json_decode($contents)) || (gettype(json_decode($contents)) != 'object')) {
            return response()->json(['error' => ["Invalid json format"]], 400);
        }
        $data = json_decode($contents,TRUE);
        if(($contents == '') || empty($data)){
            return response()->json(['error' => ["Blank File uploaded"]], 400);
        }

        $pemFileName = str_replace(' ', '', $onlyName . "_" . now()->timestamp . "." . $extension);
        $pemFileNameWithPath = 'filelangjson/'.$pemFileName;

        $filelangjson->storeAs('filelangjson', $pemFileName);

        session(['langfilelink' => ($pemFileNameWithPath)]);
        session(['langfilename' => ($pemFileName)]);
        return response()->json([
            'langfilelink' => url('/storage/'.$pemFileNameWithPath),
            'langfilename' => $pemFileName,
        ], 200);
    }

    public function submitTranslateRequest(Request $request)
    {
        // if (!session('langfilelink')) {
        //     return response()->json(['error'=>'no json file selected'], 400);
        // }

        $validator = Validator::make($request->all(), [
           'from' => 'required|max:20',
           'to' => 'required|max:20',
           'email' => 'required|email|max:500',
           'fileName' => 'required|max:150'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }

        $user = User::where(['email' => $request->email])->first();
        if(!$user->isVerified) {
            return response()->json(["error" => "Hey Hacker, your email is not verified."],400);
        }

        $queue_position = $this->getLangTranslateJobNumberByUserEmail($request->email);
        if ($queue_position) {
            return response()->json(["info" => "Please wait until your previous file translated, <br/> <b> Queue Position is: " . $queue_position], 200);
        }

        $filelangjson = storage_path("app/public/filelangjson/".$request['fileName']);

        $fromLang = $request['from'];
        $toLang = $request['to'];
        $toEmail = $request['email'];
        $translateFunToUse = $this->translateFunToUse;
        // LangTrasnlate::dispatch($toEmail, $filelangjson, $fromLang, $toLang,$translateFunToUse);
        LangTrasnlate::dispatch($toEmail, $filelangjson, $fromLang, $toLang,$translateFunToUse);

        session(['langfilelink' => ""]);
    }
    // type
    // alreadyVerified
    // notVerified
    public function sendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()->all()], 400);
        }
        $otp = rand(1000,9999);
        $user = User::updateOrCreate(['email' => $request->email], ["otp" => $otp]);

        if($user && $user->isVerified) {
            return response()->json(["message" => 'Hey welcome back,<br/> You are already verified.', "type" => "alreadyVerified"], 200);
        } else {
            // Mail
            $mailSend = Mail::to($user->email)->queue(new SendOtp($user->email,$user->otp));
            return response()->json(["message" => 'Please check your inbox for OTP.', "type" => "notVerified"], 200);
        }
        return response()->json(["error" => "Oops! Something went wrong"], 400);
    }

    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'otp' => 'required|max:4',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()->all()], 400);
        }

        $user = User::where(['email' => $request->email,'otp' => $request->otp])->update(['otp' => null, 'isVerified' => 1]);
        if($user) {
            return response()->json(["message" => 'Your email is verified successfully'], 200);
        }
        return response()->json(["error" => "Oops! Something went wrong"], 400);
    }

    public function getLangTranslateJobNumberByUserEmail($email)
    {
        #Fetch all the failed jobs
        $jobs = Jobs::where('payload', 'LIKE', '%LangTrasnlate%')->get();
        foreach ($jobs as $key => $value) {
            $json_payload = json_decode($jobs[$key]->payload);
            $record_email = unserialize($json_payload->data->command)->toEmail;
            if(strtolower($record_email) === strtolower($email)) {
                return $key + 1; // queue_position;
            }
        }
        return null;
    }
}
